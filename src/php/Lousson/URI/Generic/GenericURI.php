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
 *  Lousson\URI\Generic\GenericURI class definition
 *
 *  @package    org.lousson.uri
 *  @copyright  (c) 2011, Mathias J. Hennig
 *  @copyright  (c) 2012 - 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\URI\Generic;

/** Dependencies: */
use Lousson\URI\AnyURI;;
use Lousson\URI\Builtin\BuiltinURIUtil;

/**
 *  A generic URI implementation
 *
 *  The GenericURI class is an implementation of the AnyURI interface.
 *  It can represent any well-formed URI, although it doesn't consider
 *  scheme-specific constraints, if any.
 *
 *  @since      lousson/Lousson_URI-0.1.0
 *  @package    org.lousson.uri
 */
class GenericURI implements AnyURI
{
    /**
     *  Create a generic URI instance
     *
     *  The create() method is used to create an instance of the GenericURI
     *  class that represents the $lexical URI provided.
     *
     *  @param  string      $lexical    The URI's lexical representation
     *
     *  @return \Lousson\URI\Generic\GenericURI
     *          A generic URI instance is returned on success
     *
     *  @throws \Lousson\URI\AnyURIException
     *          Raised in case the lexical representation is malformed
     */
    public static function create($lexical)
    {
        $util = BuiltinURIUtil::getInstance();
        $parts = $util->parseURI($lexical);
        $uri = new static($lexical, $parts);
        return $uri;
    }

    /**
     *  Obtain an URI part's value
     *
     *  The getPart() method returns the string value associated with the
     *  URI's $name requested. NULL is returned in case the $name is not
     *  associated with a value at all.
     *
     *  @param  string      $name       The name of the part to retrieve
     *
     *  @return string
     *          The value associated with the requested URI part is
     *          returned on success
     *
     *  @throws \Lousson\URI\AnyURIException
     *          Raised in case the given $name is not one of the token
     *          defined by the Lousson\URI\AnyURI::PART_* constants
     *
     *  @link   http://tools.ietf.org/html/rfc3986#section-3
     *  @link   http://php.net/manual/function.parse-url.php
     */
    final public function getPart($name)
    {
        $value = isset($this->parts[$name])? $this->parts[$name]: null;
        return $value;
    }

    /**
     *  Obtain the URI's lexical representation
     *
     *  The getLexical() method returns the lexical representation of the
     *  URI instance.
     *
     *  @return string
     *          The URI's lexical representation is returned on success
     */
    final public function getLexical()
    {
        return $this->lexical;
    }

    /**
     *  Obtain the URI's lexical representation
     *
     *  The "magic" method __toString() is an alias for the getLexical()
     *  method. It returns the lexical representation of the URI instance.
     *
     *  @return string
     *          The URI's lexical representation is returned on success
     */
    final public function __toString()
    {
        return $this->lexical;
    }

    /**
     *  Constructor
     *
     *  The constructor has been declared private and is accessed from
     *  within getInstance() only. It transfers the given URI's $lexical
     *  representation and it's $parts to the instance's members.
     *
     *  @param  string      $lexical    The URI's lexical representation
     *  @param  array       $parts      The URI's parts
     */
    final private function __construct($lexical, array $parts)
    {
        $this->lexical = (string) $lexical;
        $this->parts = $parts;
    }

    /**
     *  The URI's lexical representation
     *
     *  @var string
     */
    private $lexical;

    /**
     *  The parsed parts of the URI
     *
     *  @var array
     */
    private $parts;
}

