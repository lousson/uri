<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 textwidth=75: *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
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
 *  Lousson\URI\Generic\GenericURIScheme class definition
 *
 *  @package    org.lousson.uri
 *  @copyright  (c) 2012 - 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\URI\Generic;

/** Dependencies: */
use Lousson\URI\AbstractURIScheme;
use Lousson\URI\Builtin\BuiltinURIUtil;

/**
 *  A generic URI scheme implementation
 *
 *  The GenericURIScheme class is an implementation of the AnyURIScheme
 *  interface. It can represent any scheme with a well-formed mnemonic,
 *  although the instance won't be aware of constraints associated with
 *  the scheme specification, if any.
 *
 *  @since      lousson/uri-0.1.0
 *  @package    org.lousson.uri
 */
class GenericURIScheme extends AbstractURIScheme
{
    /**
     *  A filter for valid name type bitmasks
     *
     *  @var string
     */
    const NAME_TYPE_FILTER = 0x03;

    /**
     *  Obtain a scheme intstance
     *
     *  The getInstance() method is used to obtain a generic URI scheme
     *  instance for the given $scheme mnemonic, which must be a string
     *  starting with a latin character, followed by any number of latin
     *  characters, digits, plus signs (+), minus signs (-) and/or dots.
     *
     *  @param  string      $scheme         The mnemonic name
     *  @param  string      $abbreviation   The abbreviated name
     *  @param  string      $name           The english name
     *
     *  @return \Lousson\URI\Generic\GenericURIScheme
     *          An URI scheme instance is returned on success
     *
     *  @throws \InvalidArgumentException
     *          Raised in case the given $scheme does not fulfill the
     *          aforementioned constraints
     */
    public static function create(
        $scheme, $abbreviation = null, $name = null)
    {
        $util = BuiltinURIUtil::getInstance();
        $mnemonic = $util->parseURIScheme($scheme);

        if (null === $abbreviation) {
            $abbreviation = strtoupper($mnemonic);
        }

        if (null === $name) {
            $name = $abbreviation;
        }

        $scheme = new static($mnemonic, $abbreviation, $name);
        return $scheme;
    }

    /**
     *  Obtain the scheme's name
     *
     *  The getName() method will return the name of the URI scheme the
     *  object represents. Depending on the requested $type, this is one
     *  of the following phenotypes:
     *
     *- AnyURIScheme::NAME_TYPE_MNEMONIC
     *  Returns the scheme's mnemonic that is used as prefix for URIs
     *  (exluding the colon), e.g. "urn" or "http"
     *
     *- AnyURIScheme::NAME_TYPE_ABBREVIATION
     *  Returns the official abbreviation, if any. Otherwise just returns
     *  an upper-case representation of NAME_TYPE_MNEMONIC, e.g. "HTTP"
     *
     *- AnyURIScheme::NAME_TYPE_ENGLISH
     *  Returns the scheme's full English name. In case of HTTP, for
     *  example, this would be "Hypertext Transfer Protocol"
     *
     *  In case the requested $type is none of the ones above, getName()
     *  will behave as if AnyURIScheme::NAME_TYPE_ABBREVIATION would have
     *  been requested.
     *
     *  @param  int         $type       The type of the name requested
     *
     *  @return string
     *          The name of the URI scheme is returned on success
     */
    final public function getName($type = self::NAME_TYPE_MNEMONIC)
    {
        static $properties = array(
            "mnemonic", "abbreviation", "name"
        );

        $index = self::NAME_TYPE_FILTER & (int) $type;
        $property = $properties[$index];
        $name = $this->{$property};

        return $name;
    }

    /**
     *  Constructor
     *
     *  The constructor has been declared private and is invoked from
     *  within the create() method exclusively. It transfers the scheme
     *  names to the instance's members.
     *
     *  @param  string      $mnemonic       The mnemonic name
     *  @param  string      $abbreviation   The abbreviated name
     *  @param  string      $name           The english name
     */
    final private function __construct($mnemonic, $abbreviation, $name)
    {
        $this->mnemonic = (string) $mnemonic;
        $this->abbreviation = (string) $abbreviation;
        $this->name = (string) $name;
    }

    /**
     *  The scheme's mnemonic name
     *
     *  @var string
     */
    private $mnemonic;

    /**
     *  The scheme's abbreviated name
     *
     *  @var string
     */
    private $abbreviation;

    /**
     *  The scheme's English name
     *
     *  @var string
     */
    private $name;
}

