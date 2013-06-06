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
 *  Lousson\URI\AbstractURIResolverTest class definition
 *
 *  @package    org.lousson.uri
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\URI;

/** Dependencies: */
use Lousson\URI\AbstractURIFactoryTest;
use Lousson\URI\AnyURIResolver;
use Lousson\URI\Builtin\BuiltinURIUtil;
use Lousson\URI\Generic\GenericURI;

/**
 *  An abstract test case for URI schemes
 *
 *  @since      lousson/Lousson_URI-0.1.1
 *  @package    org.lousson.uri
 *  @link       http://www.phpunit.de/manual/current/en/
 */
abstract class AbstractURIResolverTest extends AbstractURIFactoryTest
{
    /**
     *  Obtain URIs to resolve and expected results
     *
     *  The provideResolveTestParameters() method is a data provider that
     *  returns an array of zero or more items, each of whose is an array
     *  of two: An URI string and an array of expected URIs the string is
     *  to be resolved to.
     *
     *  @throws \Exception
     *          Raised in case the factory or the resolver the test is
     *          operating on is not compliant with the requirements
     */
    public function provideResolveTestParameters()
    {
        $urlList = array(
            "http://example.com/",
            "file:///tmp/example.file",
        );

        foreach ($urlList as $url) {
            $parameters[] = array($url, array($url));
        }

        $parameters[] = array("urn:lousson:example", array());
        return $parameters;
    }

    /**
     *  Obtain URIs to resolve and expected results
     *
     *  The provideResolveURITestParameters() method behaves exactly like
     *  provideResolveTestParameters(), except that the first item of each
     *  array in the returned list is an instance of the AnyURI interface,
     *  rather than a plain URI string.
     *
     *  @return array
     *
     *  @throws \Exception
     *          Raised in case the factory or the resolver the test is
     *          operating on is not compliant with the requirements
     */
    public function provideResolveURITestParameters()
    {
        $parameters = $this->provideResolveTestParameters();

        foreach ($parameters as &$p) {
            $uri = $this->getURI($p[0]);
            $p[0] = $uri;
        }

        return $parameters;
    }

    /**
     *  Test the resolve() method
     *
     *  The testResolveURI() method is a test for the AnyURI's resolve()
     *  method. It verifies that the resolver returns AnyURI instances that
     *  represent all of the $expected URIs (an array of URI strings), at
     *  least, when invoked with the given $lexical URI.
     *
     *  @param  string      $lexical    The URI string to resolve
     *  @param  array       $expected   The resolved URIs expected
     *
     *  @dataProvider       provideResolveTestParameters
     *  @test
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case an assertion, and therefore the entire test
     *          case, has failed
     *
     *  @throws \Exception
     *          Raised in case the factory or the resolver the test is
     *          operating on is not compliant with the requirements
     */
    public function testResolve($lexical, array $expected)
    {
        $resolver = $this->getURIResolver();
        $uriList = $resolver->resolve($lexical);
        $this->performResolveResultTests(
            $uriList, $expected, $resolver, "resolve"
        );
    }

    /**
     *  Test the resolveURI() method
     *
     *  The testResolveURI() method is a test for the AnyURI's resolveURI()
     *  method. It verifies that the resolver returns AnyURI instances that
     *  represent all of the $expected URIs (an array of URI strings), at
     *  least, when invoked with the given $uri.
     *
     *  @param  \Lousson\URI\AnyURI $lexical    The URI object to resolve
     *  @param  array               $expected   The resolved URIs expected
     *
     *  @dataProvider       provideResolveURITestParameters
     *  @test
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case an assertion, and therefore the entire test
     *          case, has failed
     *
     *  @throws \Exception
     *          Raised in case the factory or the resolver the test is
     *          operating on is not compliant with the requirements
     */
    public function testResolveURI(AnyURI $uri, array $expected)
    {
        $resolver = $this->getURIResolver();
        $uriList = $resolver->resolveURI($uri);
        $this->performResolveResultTests(
            $uriList, $expected, $resolver, "resolveURI"
        );
    }

    /**
     *  Perform assertions on a resolver result set
     *
     *  The performResolveResultTests() method is used to perform a bunch
     *  of tests on the result set of a resolver $object's $method.
     *
     *  @param  mixed                       $resolved   The resolved set
     *  @param  array                       $expected   The expected set
     *  @param  \Lousson\URI\AnyURIResolver $object     The tested object
     *  @param  string                      $method     The tested method
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case any of the performed assertions has failed
     *
     *  @throws \Exception
     *          Raised in case the factory or the resolver the test is
     *          operating on is not compliant with the requirements
     */
    protected function performResolveResultTests(
        $resolved, array $expected, AnyURIResolver $object, $method)
    {
        $class = get_class($object);
        $interface = "Lousson\\URI\\AnyURI";
        $message = sprintf(
            "The %s::%s() method must return an array of zero or more ".
            "items, each of whose must be an instance of the %s interface",
            $class, $method, $interface
        );

        $this->assertInternalType("array", $resolved, $message);
        $this->assertContainsOnly($interface, $resolved, false, $message);

        $lexicalList = array_map("strval", $resolved);
        $filteredList = array_intersect($lexicalList, $expected);

        sort($expected);
        sort($filteredList);

        $this->assertEquals(
            $expected, $filteredList, sprintf(
            "The array returned by the %s::%s() method must contain all ".
            "the expected URIs", $class, $method
        ));
    }
}

