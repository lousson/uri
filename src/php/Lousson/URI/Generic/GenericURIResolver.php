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
 *  Lousson\URI\Generic\GenericURIResolver class definition
 *
 *  @package    org.lousson.uri
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\URI\Generic;

/** Dependencies: */
use Lousson\URI\AnyURI;
use Lousson\URI\AnyURIFactory;
use Lousson\URI\AbstractURIResolver;
use Lousson\URI\Generic\GenericURIFactory;
use Closure;

/**
 *  A generic URI resolver implementation
 *
 *  The GenericURIResolver class is an implementation of the AnyURIResolver
 *  interface that is based on a Closure callback mechanism and, therefore,
 *  suitable for implementing any resolver algorithm.
 *
 *  @since      lousson/uri-0.1.1
 *  @package    org.lousson.uri
 */
class GenericURIResolver extends AbstractURIResolver
{
    /**
     *  Create an URI resolver instance
     *
     *  The constructor is used to create instances of the generic URI
     *  resolver, using the provided $callback to actually perform the
     *  operation behind the resolveURI() method.
     *
     *  @param  \Lousson\URI\AnyURIFactory  $factory    The URI factory
     *  @param  \Closure                    $callback   The URI callback
     */
    public function __construct(
         AnyURIFactory $factory, Closure $callback)
    {
        $this->factory = $factory;
        $this->callback = $callback;
    }

    /**
     *  Obtain an URI factory instance
     *
     *  The getURIFactory() method is used to obtain the URI factory
     *  that is associated with the URI resolver.
     *
     *  @return \Lousson\URI\AnyURIFactory
     */
    public function getURIFactory()
    {
        return $this->factory;
    }

    /**
     *  Resolve an URI instance
     *
     *  The resolveURI() method analyzes the given $uri instance, returning
     *  a list of zero or more items, each of whose is an URI instance
     *  itself, representing a distinct resolved form or $uri.
     *  In case there resolve does not implement a process to resolve URIs
     *  of the particuar type, it returns an empty array.
     *
     *  @param  \Lousson\URI\AnyURI     $uri    The URI instance to resolve
     *
     *  @return array
     *          A list of URI instances is returned on success
     *
     *  @throws \Lousson\URI\AnyURIException
     *          Raised in case the $lexical URI is malformed or invalid
     *          in general, or in if an internal error is encountered
     */
    public function resolveURI(AnyURI $uri)
    {
        $callback = $this->callback;
        $uriList = $callback($uri);
        return $uriList;
    }

    /**
     *  The URI factory
     *
     *  @var \Lousson\URI\AnyURIFactory
     */
    private $factory;

    /**
     *  The resolver callback
     *
     *  @var \Closure
     */
    private $callback;
}

