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
 *  Lousson\URI\Builtin\BuiltinURIResolverTest class definition
 *
 *  @package    org.lousson.uri
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\URI\Builtin;

/** Dependencies: */
use Lousson\URI\AbstractURIResolverTest;
use Lousson\URI\Builtin\BuiltinURIFactory;

/**
 *  A test case for the builtin URI resolver
 *
 *  @since      lousson/Lousson_URI-0.1.1
 *  @package    org.lousson.uri
 *  @link       http://www.phpunit.de/manual/current/en/
 */
class BuiltinURIResolverTest extends AbstractURIResolverTest
{
    /**
     *  Obtain the URI factory to test
     *
     *  The getURIFactory() method returns the URI factory instance that
     *  is used in the tests.
     *
     *  @return \Lousson\URI\AnyURIFactory
     */
    public function getURIFactory()
    {
        $factory = new BuiltinURIFactory();
        return $factory;
    }

    /**
     *  Provide test parameters for testResolveURIMap()
     *
     *  The provideMapParameters() method returns an array of multiple
     *  items, each of whose is a set of valid parameters for the
     *  testResolveURIMap() method.
     *
     *  @return array
     *          A list of test parameters is returned on success
     */
    public function provideMapParameters()
    {
        $map = array(
            "/test/" => "TEST",
            "/foo:(baz)/" => "foo:bar:\$1"
        );

        $p[] = array($map, "urn:foo:bar", array("urn:foo:bar"));
        $p[] = array($map, "urn:lousson:test", array(
            "urn:lousson:TEST",
            "urn:lousson:test"
        ));

        $p[] = array($map, "urn:foo:bazz", array(
            "urn:foo:bar:bazz",
            "urn:foo:bazz"
        ));

        $p[] = array(array(), "urn:foo:bar", array("urn:foo:bar"));

        return $p;
    }

    /**
     *  Test the pattern mechanism
     *
     *  The testResolveURIMap() method is a smoke test for the pattern
     *  mechanism that has been introduced in Lousson_URI-1.2.0.
     *
     *  @param  array               $map            The URI pattern map
     *  @param  string              $uri            The URI to resolve
     *  @param  array               $expected       The expected results
     *
     *  @dataProvider       provideMapParameters
     *  @test
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case an assertion has failed
     *
     *  @throws \Exception
     *          Raised in case of an implementation error
     */
    public function testResolveURIMap(array $map, $uri, array $expected)
    {
        $resolver = $this->getURIResolver();
        $resolver->setPatterns($map);

        $uriList = $resolver->resolve($uri);

        foreach ($uriList as $item) {
            $this->assertInstanceOf("Lousson\\URI\\AnyURI", $item);
        }

        $expected = array_map("strval", $expected);
        $resolved = array_map("strval", $uriList);

        $this->assertEquals($expected, $resolved);
    }
}

