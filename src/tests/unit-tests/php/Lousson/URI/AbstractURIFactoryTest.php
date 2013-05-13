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
 *  Definition of the \Lousson\URI\AbstractURIFactoryTest class
 *
 *  @package    org.lousson.uri
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\URI;

/** Dependencies: */
use PHPUnit_Framework_TestCase;

/**
 *  An abstract test case for URI factories
 *
 *  @since      lousson/uri-0.1.0
 *  @package    org.lousson.uri
 *  @link       http://www.phpunit.de/manual/current/en/
 */
abstract class AbstractURIFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     *  Obtain the URI factory to test
     *
     *  The getURIFactory() method returns the URI factory instance that
     *  is used in the tests.
     *
     *  @return \Lousson\URI\AnyURIFactory
     */
    abstract public function getURIFactory();

    /**
     *  Obtain a URI instance
     *
     *  The getURI() method returns an instance of the URI interface that
     *  is obtained from the factory associated with the test case.
     *  It will also ensure the URI being an instance of the URI interface.
     *
     *  @param  string      $lexical    The URI's lexical representation
     *
     *  @throws \InvalidArgumentException
     *          Raised in case the given $lexical value is invalid
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case an assertion has failed
     */
    final public function getURI($lexical)
    {
        $factory = $this->getRealURIFactory();
        $uri = $factory->getURI($lexical);
        $this->assertInstanceOf(
            "Lousson\\URI\\AnyURI", $uri, sprintf(
            "The %s::getURI() method must return an intance of ".
            "the AnyURI interface", get_class($factory)
        ));

        return $uri;
    }

    /**
     *  Obtain a URI scheme instance
     *
     *  The getURI() method returns an instance of the URI interface that
     *  is obtained from the factory associated with the test case.
     *  It will also ensure the URI scheme being an instance of the URI
     *  scheme interface.
     *
     *  @param  string      $name       The URI scheme's name
     *
     *  @throws \InvalidArgumentException
     *          Raised in case the given $name is invalid
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case an assertion has failed
     */
    final public function getURIScheme($name)
    {
        $factory = $this->getRealURIFactory();
        $scheme = $factory->getURIScheme($name);
        $this->assertInstanceOf(
            "Lousson\\URI\\AnyURIScheme", $scheme, sprintf(
            "The %s::getURIScheme() method must return an intance of ".
            "the AnyURIScheme interface", get_class($factory)
        ));

        return $scheme;
    }

    /**
     *  Obtain a verified URI factory
     *
     *  The getRealURIFactory() method is used internally to obtain an
     *  instance of the URI factory interface. It verfies that the return
     *  value of getURIFactory() is an instance of the interface before
     *  passing it back to the caller.
     *
     *  @return \Lousson\URI\AnyURIFactory
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case an assertion has failed
     */
    private function getRealURIFactory()
    {
        $factory = $this->getURIFactory();
        $this->assertInstanceOf(
            "Lousson\\URI\\AnyURIFactory", $factory, sprintf(
            "The %s::getURIFactory() method must return an intance of ".
            "the AnyURIFactory interface", get_class($this)
        ));

        return $factory;
    }
}

