<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 textwidth=75: *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Copyright (c) 2013, The Lousson Project                               *
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
 *  Lousson\URI\AbstractURIResolver class definition
 *
 *  @package    org.lousson.uri
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\URI;

/** Dependencies: */
use Lousson\URI\AnyURIResolver;

/**
 *  An abstract URI resolver implementation
 *
 *  The AbstractURIResolver class implements the API defined by the
 *  Lousson\URI\AnyURIResolver interface as far as possible, without
 *  assuming too many implementation details.
 *
 *  @since      lousson/Lousson_URI-0.1.1
 *  @package    org.lousson.uri
 */
abstract class AbstractURIResolver implements AnyURIResolver
{
    /**
     *  Obtain an URI factory instance
     *
     *  The getURIFactory() method is used to obtain the URI factory
     *  that is associated with the URI resolver.
     *
     *  @return \Lousson\URI\AnyURIFactory
     */
    abstract public function getURIFactory();

    /**
     *  Resolve an URI string
     *
     *  The resolve() method analyzes the given $lexical URI and returns a
     *  list of zero or more items, each of whose is an URI string itself,
     *  representing a distinct resolved form of the $uri.
     *  In case there resolve does not implement a process to resolve URIs
     *  of the particuar type, it returns an empty array.
     *
     *  @param  string      $lexical    The URI string to resovle
     *
     *  @return array
     *          A list of URI strings is returned on success
     *
     *  @throws \Lousson\URI\AnyURIException
     *          Raised in case the $lexical URI is malformed or invalid
     *          in general, or in if an internal error is encountered
     */
    final public function resolve($lexical)
    {
        $factory = $this->getURIFactory();
        $uri = $factory->getURI($lexical);
        $uriList = $this->resolveURI($uri);
        return $uriList;
    }
}

