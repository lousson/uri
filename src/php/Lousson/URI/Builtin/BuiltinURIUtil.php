<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 textwidth=75: *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Copyright (c) 2011, Mathias J. Hennig                                 *
 * Copyright (c) 2012 - 2013, The Lousson Project                        *
 *                                                                       *
 * All rights reserved.                                                  *
 *                                                                       *
 * Redistribution and use in source and binary forms, with or without    *
 * modification, are permitted provided that the following conditions    *
 * are met:                                                              *
 *                                                                       *
 * 1) Redistributions of source code must retain the above copyright     *
 *    notice, this list of conditions and the following disclaimer.      *
 * 2) Redistributions in binary form must reproduce the above copyright  *
 *    notice, this list of conditions and the following disclaimer in    *
 *    the documentation and/or other materials provided with the         *
 *    distribution.                                                      *
 *                                                                       *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS   *
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT     *
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS     *
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE        *
 * COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT,            *
 * INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES    *
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR    *
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)    *
 * HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT,   *
 * STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)         *
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED   *
 * OF THE POSSIBILITY OF SUCH DAMAGE.                                    *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

/**
 *  Lousson\URI\Builtin\BuiltinURIUtil class definition
 *
 *  @package    org.lousson.uri
 *  @copyright  (c) 2012 - 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\URI\Builtin;

/** Dependencies: */
use Lousson\URI\AnyURI;
use Lousson\URI\Error\InvalidURIError;

/**
 *  Utilities for common URI operations
 *
 *  The BuiltinURIUtil class is a container for utility methods that ease
 *  common URI operations, especially those that are required to implement
 *  the interfaces in the Lousson\URI namespace.
 *
 *  Note that this class will, most likely, get converted into a Trait in
 *  the future!
 *
 *  @since      lousson/Lousson_URI-0.1.0
 *  @package    org.lousson.uri
 */
final class BuiltinURIUtil
{
    /**
     *  Obtain a utility instance
     *
     *  The getInstance() method will return an instance of the utility
     *  class and is used instead of the "new" keyword.
     *
     *  @return \Lousson\URI\Builtin\BuiltinURIUtil
     *          An URI utility instance is returned on success
     */
    public static function getInstance()
    {
        return new self;
    }

    /**
     *  Parse URI parts
     *
     *  The parseURI() method is used to split the given $uri URI
     *  representation into it's parts - according to the generic syntax
     *  specified in RFC 3986. The results are returned as an associative
     *  array that may include any of the following keys:
     *
     *- AnyURI::PART_SCHEME (required)
     *- AnyURI::PART_AUTHORITY
     *- AnyURI::PART_USERINFO
     *- AnyURI::PART_USERNAME
     *- AnyURI::PART_USERAUTH
     *- AnyURI::PART_HOST
     *- AnyURI::PART_PORT
     *- AnyURI::PART_PATH
     *- AnyURI::PART_QUERY
     *- AnyURI::PART_FRAGMENT
     *
     *  @param  string      $uri    The URI to parse
     *
     *  @return array
     *          The URI parts are returned on success
     *
     *  @throws \Lousson\URI\Error\InvalidURIError
     *          Raised in case the URI is malformed or not prefixed with
     *          an URI scheme (including the colon)
     */
    public static function parseURI($uri)
    {
        $setup = ini_set("track_errors", true);
        $php_errormsg = null;
        $lexical = (string) $uri;
        $parts = @parse_url($lexical);
        $errormsg = $php_errormsg;
        ini_set("track_errors", $setup);

        if (!$parts) {
            $message = "Malformed URI: \"{$lexical}\" - {$errormsg}";
            throw new InvalidURIError($message);
        }

        if (!isset($parts["scheme"])) {
            $message = "Missing or invalid URI scheme: \"{$lexical}\"";
            throw new InvalidURIError($message);
        }

        self::extendAuthority($parts);
        return $parts;
    }

    /**
     *  Parse URI scheme mnemonics
     *
     *  The parseURIScheme() method parses the URI scheme mnemonic in the
     *  given $scheme string (which may also be a URI).
     *  Furthermore, it ensures the well-formedness of the scheme, raising
     *  an exception in case it is invalid or absent.
     *
     *  @param  string      $scheme    The URI scheme to parse
     *
     *  @return string
     *          The URI scheme mnemonic is returned on success
     *
     *  @throws \Lousson\URI\Error\InvalidURIError
     *          Raised in case
     */
    public static function parseURIScheme($scheme)
    {
        if (preg_match("/^([a-z][a-z0-9+\\-.]*)(:|\$)/i", $scheme, $res)) {
            $result = strtolower($res[1]);
            return $result;
        }

        $message = "Missing or invalid URI scheme: \"{$scheme}\"";
        throw new InvalidURIError($message);
    }

    /**
     *  Extend "authority" data
     *
     *  The extendAuthority() method is used internally to extend the
     *  given $parts array by the AnyURI::PART_AUTHORITY data, if any.
     *
     *  @param  array       $parts      The array to extend
     */
    private static function extendAuthority(array &$parts)
    {
        static $patterns = array(
            /* 00 */ "%1\$s",
            /* 01 */ "%1\$s:%3\$d",
            /* 10 */ "%2\$s@%1\$s",
            /* 11 */ "%2\$s@%1\$s:%3\$d",
        );

        if (isset($parts[AnyURI::PART_HOST])) {

            self::extendUserInfo($parts);

            $index = isset($parts[AnyURI::PART_USERINFO]);
            $index <<= 1;
            $index |= isset($parts[AnyURI::PART_PORT]);

            $parts[AnyURI::PART_AUTHORITY] = sprintf(
                $patterns[$index],
                $parts[AnyURI::PART_HOST],
                @$parts[AnyURI::PART_USERINFO],
                @$parts[AnyURI::PART_PORT]
            );
        }
    }

    /**
     *  Extend "userinfo" data
     *
     *  The extendUserInfo() method is used internally to extend the given
     *  $parts array by the AnyURI::PART_USERINFO data, if any.
     *
     *  @param  array       $parts      The array to extend
     */
    private static function extendUserInfo(array &$parts)
    {
        static $patterns = array(
            /* 00 */ "%s",
            /* 01 */ "%s:%s",
        );

        if (isset($parts[AnyURI::PART_USERNAME])) {
            $index = isset($parts[AnyURI::PART_USERAUTH]);
            $parts[AnyURI::PART_USERINFO] = sprintf(
                $patterns[$index],
                $parts[AnyURI::PART_USERNAME],
                @$parts[AnyURI::PART_USERAUTH]
            );
        }
    }
}

