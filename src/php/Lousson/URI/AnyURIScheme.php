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
 *  Lousson\URI\AnyURIScheme interface declaration
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
 *  An interface for URI schemes
 *
 *  The AnyURIScheme interface declares an API for classes representing
 *  URI schemes.
 *
 *  @since      lousson/uri-0.1.0
 *  @package    org.lousson.uri
 */
interface AnyURIScheme
{
    /**
     *  The id of the scheme name type "mnemonic"
     *
     *  @see    Lousson\URI\AnyURIScheme::getName()
     *  @var    int
     */
    const NAME_TYPE_MNEMONIC = 0x000;

    /**
     *  The id of the scheme name type "abbreviation"
     *
     *  @see    Lousson\URI\AnyURIScheme::getName()
     *  @var    int
     */
    const NAME_TYPE_ABBREVIATION = 0x001;

    /**
     *  The id of the scheme name type "english"
     *
     *  @see    Lousson\URI\AnyURIScheme::getName()
     *  @var    int
     */
    const NAME_TYPE_ENGLISH = 0x002;

    /**
     *  Obtain the scheme's name
     *
     *  The getName() method will return the name of the URI scheme the
     *  object represents. Depending on the requested $type, this is one
     *  of the following phenotypes:
     *
     *- Lousson\URI\AnyURIScheme::NAME_TYPE_MNEMONIC
     *  Returns the scheme's mnemonic that is used as prefix for URIs
     *  (exluding the colon), e.g. "urn" or "http"
     *
     *- Lousson\URI\AnyURIScheme::NAME_TYPE_ABBREVIATION
     *  Returns the official abbreviation, if any. Otherwise just returns
     *  an upper-case representation of NAME_TYPE_MNEMONIC, e.g. "HTTP"
     *
     *- Lousson\URI\AnyURIScheme::NAME_TYPE_ENGLISH
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
    public function getName($type = self::NAME_TYPE_MNEMONIC);

    /**
     *  An alias for getName()
     *
     *  The "magic" method __toString() is an alias or shortcut for an
     *  invocation of getName() with AnyURIScheme::NAME_TYPE_MNEMONIC.
     *
     *  @return string
     *          The name of the URI scheme is returned on success
     */
    public function __toString();
}

