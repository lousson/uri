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
 *  Lousson\URI\Builtin\BuiltinURIResolver class definition
 *
 *  @package    org.lousson.uri
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\URI\Builtin;

/** Dependencies: */
use Lousson\URI\AnyURIFactory;
use Lousson\URI\AnyURIResolver;
use Lousson\URI\AnyURI;
use Lousson\URI\Builtin\BuiltinURIFactory;

/**
 *  The default URI resolver implementation
 *
 *  The BuiltinURIResolver class is a basic implementation of the
 *  AnyURIResolver interface. By default, it always resolves to the exact
 *  same URI as invoked with, but one can provide an associative map of
 *  regular expressions to aggregate new URIs.
 *
 *  @since      lousson/Lousson_URI-0.1.1
 *  @package    org.lousson.uri
 */
class BuiltinURIResolver implements AnyURIResolver
{
    /**
     *  Create an URI resolver instance
     *
     *  The constructor is used to create instances of the generic URI
     *  resolver, using the provided $factory to convert lexical URIs to
     *  AnyURI instances.
     *
     *  @param  AnyURIFactory       $factory    The URI factory instance
     */
    public function __construct(AnyURIFactory $factory = null)
    {
        if (null === $factory) {
            $factory = new BuiltinURIFactory();
        }

        $this->factory = $factory;
    }

    /**
     *  Obtain an URI factory instance
     *
     *  The getURIFactory() method is used to obtain the URI factory
     *  that is associated with the URI resolver.
     *
     *  @return \Lousson\URI\AnyURIFactory
     *          An URI factory instance is returned on success
     */
    final public function getURIFactory()
    {
        return $this->factory;
    }

    /**
     *  Update the patterns associated
     *
     *  The setPatterns() method is used to assign a map of patterns to the
     *  URI resolver, where each key is a regular expression and each value
     *  is the associated replacement (see preg_replace()).
     *
     *  @param  array               $map            The map of URI patterns
     */
    public function setPatterns(array $map)
    {
        $this->patterns = array_map("strval", $map);
        $this->patterns = array_filter($this->patterns, "is_string");
    }

    /**
     *  Obtain the patterns associated
     *
     *  The getPatterns() method is used to obtain the map of patterns
     *  associated with the URI resolver, if any.
     *
     *  @return array
     *          A map of URI patterns is returned on success
     */
    public function getPatterns()
    {
        return $this->patterns;
    }

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
        $lexical = (string) $uri;
        $factory = $this->getURIFactory();
        $uriList = array();

        foreach ($this->getPatterns() as $regex => $replacement) {
            if (preg_match($regex, $lexical)) {
                $prototype = preg_replace($regex, $replacement, $lexical);
                $uriList[] = $factory->getURI($prototype);
            }
        }

        $uriList[] = $uri;
        return $uriList;
    }

    /**
     *  The URI factory instance
     *
     *  @var \Lousson\URI\AnyURIFactory
     */
    private $factory;

    /**
     *  The URI pattern map
     *
     *  @var array
     */
    private $patterns = array();
}

