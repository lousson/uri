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
 *  Lousson\URI\AbstractURITest class definition
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
use Lousson\URI\AnyURI;
use Lousson\URI\Builtin\BuiltinURIUtil;

/**
 *  An abstract test case for URIs
 *
 *  @since      lousson/Lousson_URI-0.1.0
 *  @package    org.lousson.uri
 *  @link       http://www.phpunit.de/manual/current/en/
 */
abstract class AbstractURITest extends AbstractURIFactoryTest
{
    /**
     *  Obtain well-formed URIs
     *
     *  The getWellformedURIs() method returns an array of multiple items,
     *  each of whose is a string representing a well-formed URI.
     *
     *  @return array
     */
    public function getWellformedURIs()
    {
        return array(
            "scheme://userinfo@example.com:1234/path?query#fragment",
            "http://example.com/",
            "urn:lousson:test:example",
            "file:///dev/urandom",
        );
    }

    /**
     *  Obtain malformed URIs
     *
     *  The getMalformedURIs() method returns an array of multiple items,
     *  each of whose is a string representing a malformed URI.
     *
     *  @return array
     */
    public function getMalformedURIs()
    {
        return array(
            "",
            "http://example.com:foo/",
            "an-arbitrary-string",
        );
    }

    /**
     *  Provide valid URIs
     *
     *  The provideValidURIs() method is a data provider for test-methods
     *  expecting one parameter; a valid URI string.
     *
     *  @return array
     */
    public function provideValidURIs()
    {
        $uriList = $this->getWellformedURIs();
        $parameters = array();

        foreach ($uriList as $uri) {
            $parameters[] = array($uri);
        }

        return $parameters;
    }

    /**
     *  Provide invalid URIs
     *
     *  The provideURIArguments() method is a data provider for test-methods
     *  expecting one parameter; an invalid URI string.
     *
     *  @return array
     */
    public function provideURIArguments()
    {
        $uriList = $this->getMalformedURIs();
        $parameters = array();

        foreach ($uriList as $uri) {
            $parameters[] = array($uri);
        }

        return $parameters;
    }

    /**
     *  Provide URIs and part expectations
     *
     *  The providePartExpectations() method is a data provider for
     *  test-methods expecting two parameters; a valid URI string and a
     *  map of parts expected from the getParts() method.
     *
     *  @return array
     */
    public function providePartExpectations()
    {
        $uriList = $this->getWellformedURIs();
        $util = BuiltinURIUtil::getInstance();
        $parameters = array();

        foreach ($uriList as $uri) {
            $parts = $util->parseURI($uri);
            $parameters[] = array($uri, $parts);
        }

        return $parameters;
    }

    /**
     *  Provide URIs and lexical expectations
     *
     *  The provideLexicalExpectations() method is a data provider for
     *  test-methods expecing two parameters; a valid URI string and the
     *  lexical representation that should get returned by both, the
     *  getLexical() and the __toString() methods.
     *
     *  @return array
     */
    public function provideLexicalExpectations()
    {
        $uriList = $this->getWellformedURIs();
        $parameters = array();

        foreach ($uriList as $uri) {
            $parameters[] = array($uri, $uri);
        }

        return $parameters;
    }

    /**
     *  Test whether URI validation works
     *
     *  The testValidation() method is used to verify whether the URI
     *  validation works as expected. That is, if the invalid/malformed
     *  $lexical URI representation causes an \Lousson\URI\AnyURIException
     *  to get raised.
     *
     *  @param  string      $lexical    The URIs lexical representation
     *
     *  @dataProvider       provideURIArguments
     *  @expectedException  Lousson\URI\AnyURIException
     *  @test
     *
     *  @throws \Lousson\URI\AnyURIException
     *          Raised in case the test is successful
     */
    public function testValidation($lexical)
    {
        $this->getURI($lexical);
    }

    /**
     *  Test whether getPart() works as expected
     *
     *  The testGetPart() method checks whether the URI's getPart() mehthod
     *  returns all the $expected values after the URI instance has been
     *  created using the provided $lexical value.
     *
     *  @param  string      $lexical    The URI string
     *  @param  array       $expected   The expected parts map
     *
     *  @dataProvider       providePartExpectations
     *  @test
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case an assertion, and therefore the entire test
     *          case, has failed
     */
    final public function testGetPart($lexical, array $expected)
    {
        $uri = $this->getURI($lexical);
        $uriClass = get_class($uri);
        $parts = (object) $expected;
        $partNames = array_keys($expected);

        foreach ($partNames as $name) {
            $value = $uri->getPart($name);
            $this->assertAttributeEquals(
                $value, $name, $parts, sprintf(
                "The values returned by the %s::getPart() method must ".
                "equal the expected values", $uriClass
            ));
        }

        foreach (self::$possibleURIParts as $name) {
            $value = $uri->getPart($name);
            $this->assertThat(
                $value, $this->logicalOr(
                    $this->isType("scalar"), $this->isNull()
                ), sprintf(
                    "The values returned by the %s::getPart() method ".
                    "must be either scalar or NULL", $uriClass
                )
            );
        }
    }

    /**
     *  Test whether getLexical() and __toString() are ok
     *
     *  The testGetLexical() method is used to confirm that the URI's
     *  getLexical() and __toString() methods return the $expected string
     *  value after the instance has been created using the given $lexical
     *  URI representation.
     *
     *  @param  string      $lexical    The input URI string
     *  @param  string      $expected   The expected URI string
     *
     *  @dataProvider       provideLexicalExpectations
     *  @test
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case an assertion, and therefore the entire test
     *          case, has failed
     */
    final public function testGetLexical($lexical, $expected)
    {
        $uri = $this->getURI($lexical);
        $uriClass = get_class($uri);

        $this->assertEquals(
            $expected, $uri->getLexical(), sprintf(
            "The %s::getLexical() method must return the expected ".
            "value", $uriClass
        ));

        $this->assertEquals(
            $expected, (string) $uri, sprintf(
            "The %s::__toString() method must return the expected ".
            "value", $uriClass
        ));

        $this->assertEquals(
            $uri->getLexical(), (string) $uri, sprintf(
            "The %1\$s::getLexical() and %1\$s::__toString() methods ".
            "must both return the same value", $uriClass
        ));
    }

    /**
     *  A list of all known URI parts
     *
     *  @var array
     */
    private static $possibleURIParts = array(
        AnyURI::PART_SCHEME,
        AnyURI::PART_AUTHORITY,
        AnyURI::PART_USERINFO,
        AnyURI::PART_USERNAME,
        AnyURI::PART_USERAUTH,
        AnyURI::PART_HOST,
        AnyURI::PART_PORT,
        AnyURI::PART_PATH,
        AnyURI::PART_QUERY,
        AnyURI::PART_FRAGMENT,
    );
}

