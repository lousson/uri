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
 *  Lousson\URI\Generic\GenericURIFactory class definition
 *
 *  @package    org.lousson.uri
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\URI\Generic;

/** Dependencies: */
use Lousson\URI\AnyURIResolver;
use Lousson\URI\Builtin\BuiltinURIFactory;

/**
 *  A generic URI factory
 *
 *  The GenericURIFactory class is a more generic implementation of the
 *  URI factory than the builtin one. It allows the URI resolver instance
 *  returned by getURIResolver() to be a custom one. Other than that, it
 *  is merely the same as the BuiltinURIFactory.
 *
 *  @since      lousson/Lousson_URI-0.1.1
 *  @package    org.lousson.uri
 */
class GenericURIFactory extends BuiltinURIFactory
{
    /**
     *  Create a factory instance
     *
     *  The constructor requires the caller to provide an URI resolver
     *  instance that is to be returned in getURIResolver().
     *
     *  @param  AnyURIResolver      $resolver   The URI resolver instance
     */
    public function __construct(AnyURIResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     *  Obtain an URI resolver instance
     *
     *  The getURIResolver() method returns an AnyURIResolver instance,
     *  used to transform URIs into a list of resource locations.
     *
     *  @return \Lousson\URI\AnyURIResolver
     *          An URI resolver instance is returned on success
     */
    public function getURIResolver()
    {
        return $this->resolver;
    }

    /**
     *  The URI resolver instance
     *
     *  @var \Lousson\URI\AnyURIResolver
     */
    private $resolver;
}

