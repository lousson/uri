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
 *  Definition of the \Lousson\URI\Generic\GenericURIFactory class
 *
 *  @package    org.lousson.uri
 *  @copyright  (c) 2012 - 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\URI\Generic;

/** Dependencies: */
use Lousson\URI\AbstractURIFactory;
use Lousson\URI\Generic\GenericURI;
use Lousson\URI\Generic\GenericURIScheme;

/**
 *  A factory for generic URI modules
 *
 *  The \Lousson\URI\Generic\GenericURIFactory class is an implementation
 *  of the \Lousson\URI\AnyURIFactory interface that is entirely generic.
 *  This means, one can use it to create a basic instance of any URI
 *  module, although there won't be any special rules for e.g. validating
 *  scheme-specific constraints. It's primary purpose is serving as a base
 *  when writing custom implementations, where it provides fallbacks for
 *  cases not implemented in a special manner.
 *
 *  @since      lousson/uri-0.1.0
 *  @package    org.lousson.uri
 */
class GenericURIFactory extends AbstractURIFactory
{
    /**
     *  Obtain an URI instance
     *
     *  The getURI() method returns an instance of the \Lousson\URI\AnyURI
     *  interface representing the URI provided in it's $lexical form.
     *
     *  @param  string      $lexical    The URI's lexical representation
     *
     *  @return \Lousson\URI\AnyURI
     *          An URI instance is returned on success
     *
     *  @throws \InvalidArgumentException
     *          Raised in case the $lexical URI is malformed
     */
    public function getURI($lexical)
    {
        $uri = GenericURI::create($lexical);
        return $uri;
    }

    /**
     *  Obtain an URI scheme instance
     *
     *  The getURIScheme() method returns a \Lousson\URI\AnyURIScheme
     *  instance representing the URI scheme associated with the given
     *  $name.
     *
     *  @param  string      $name       The name of the scheme
     *
     *  @return \Lousson\URI\AnyURIScheme
     *          An URI scheme instance is returned on success
     *
     *  @throws \InvalidArgumentException
     *          Raised in case the URI scheme $name is malformed
     */
    public function getURIScheme($name)
    {
        $scheme = GenericURIScheme::create($name);
        return $scheme;
    }
}

