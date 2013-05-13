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
 *  Definition of the \Lousson\URI\AnyURI interface
 *
 *  @package    org.lousson.uri
 *  @copyright  (c) 2011, Mathias J. Hennig
 *  @copyright  (c) 2012 - 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\URI;

/**
 *  An interface for URIs
 *
 *  The \Lousson\URI\AnyURI interface declares an API for classes
 *  representing URIs.
 *
 *  @since      lousson/uri-0.1.0
 *  @package    org.lousson.uri
 *  @link       http://en.wikipedia.org/wiki/Uniform_resource_identifier
 *  @link       http://tools.ietf.org/html/rfc3986
 */
interface AnyURI
{
    /**
     *  The key identifying an URI's scheme part
     *
     *  @link   http://tools.ietf.org/html/rfc3986#section-3.1
     *  @var    string
     */
    const PART_SCHEME = "scheme";

    /**
     *  The key identifying an URI's authority part
     *
     *  @link   http://tools.ietf.org/html/rfc3986#section-3.2
     *  @var    string
     */
    const PART_AUTHORITY = "authority";

    /**
     *  The key identifying an URI's user information part
     *
     *  @link   http://tools.ietf.org/html/rfc3986#section-3.2.1
     *  @var    string
     */
    const PART_USERINFO = "userinfo";

    /**
     *  The key identifying an URI's user name part
     *
     *  @link   http://php.net/manual/function.parse-url.php
     *  @var    string
     */
    const PART_USERNAME = "user";

    /**
     *  The key identifying an URI's authentication part
     *
     *  @link   http://php.net/manual/function.parse-url.php
     *  @var    string
     */
    const PART_USERAUTH = "pass";

    /**
     *  The key identifying an URI's host part
     *
     *  @link   http://tools.ietf.org/html/rfc3986#section-3.2.2
     *  @var    string
     */
    const PART_HOST = "host";

    /**
     *  The key identifying an URI's port part
     *
     *  @link   http://tools.ietf.org/html/rfc3986#section-3.2.3
     *  @var    string
     */
    const PART_PORT = "port";

    /**
     *  The key identifying an URI's path part
     *
     *  @link   http://tools.ietf.org/html/rfc3986#section-3.3
     *  @var    string
     */
    const PART_PATH = "path";

    /**
     *  The key identifying an URI's query part
     *
     *  @link   http://tools.ietf.org/html/rfc3986#section-3.4
     *  @var    string
     */
    const PART_QUERY = "query";

    /**
     *  The key identifying an URI's fragment part
     *
     *  @link   http://tools.ietf.org/html/rfc3986#section-3.5
     *  @var    string
     */
    const PART_FRAGMENT = "fragment";

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
     *  @throws \InvalidArgumentException
     *          Raised in case the given $name is not one of the token
     *          defined by the \Lousson\URI\AnyURI::PART_* constants
     *
     *  @link   http://tools.ietf.org/html/rfc3986#section-3
     *  @link   http://php.net/manual/function.parse-url.php
     */
    public function getPart($name);

    /**
     *  Obtain the URI's lexical representation
     *
     *  The getLexical() method returns the lexical representation of the
     *  URI instance.
     *
     *  @return string
     *          The URI's lexical representation is returned on success
     */
    public function getLexical();

    /**
     *  Obtain the URI's lexical representation
     *
     *  The "magic" method __toString() is an alias for the getLexical()
     *  method. It returns the lexical representation of the URI instance.
     *
     *  @return string
     *          The URI's lexical representation is returned on success
     */
    public function __toString();
}

