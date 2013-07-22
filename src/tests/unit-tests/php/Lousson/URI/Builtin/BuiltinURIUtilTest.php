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
 *  Lousson\URI\Builtin\BuiltinURIUtilTest class definition
 *
 *  @package    org.lousson.uri
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\URI\Builtin;

/** Dependencies: */
use Lousson\URI\AnyURI;
use Lousson\URI\Builtin\BuiltinURIUtil;
use PHPUnit_Framework_TestCase;

/**
 *  A test case for the BuiltinURIUtil class
 *
 *  @since      lousson/Lousson_URI-0.1.0
 *  @package    org.lousson.uri
 *  @link       http://www.phpunit.de/manual/current/en/
 */
class BuiltinURIUtilTest extends PHPUnit_Framework_TestCase
{
    /**
     *  Obtain an util instance
     *
     *  The getURIUtil() method returns the instance of the URI util
     *  that is to be tested.
     *
     *  @return \Lousson\URI\Builtin\BuiltinURIUtil
     */
    public function getURIUtil()
    {
        $util = BuiltinURIUtil::getInstance();
        return $util;
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
        return array(
            array(""),
            array("http://example.com:alpha/"),
            array("a-plain-string"),
        );
    }

    /**
     *  Provide URIs and part expectations
     *
     *  The provideURIExpectations() method is a data provider for
     *  test-methods expecting two parameters; a valid URI string and a
     *  map of parts expected when it is parsed.
     *
     *  @return array
     */
    public function provideURIExpectations()
    {
        $p[] = array("mailto:nobofy@example.com", array());
        $p[] = array("urn:lousson:test", array());
        $p[] = array("http://foo:bar@example.com:80/", array(
            AnyURI::PART_USERINFO => "foo:bar",
            AnyURI::PART_AUTHORITY => "foo:bar@example.com:80",
        ));

        $p[] = array("http://foobar@example.com/", array(
            AnyURI::PART_USERINFO => "foobar",
            AnyURI::PART_AUTHORITY => "foobar@example.com",
        ));

        $p[] = array("http://foobar@example.com:80/", array(
            AnyURI::PART_USERINFO => "foobar",
            AnyURI::PART_AUTHORITY => "foobar@example.com:80",
        ));

        $p[] = array("http://example.com:80/", array(
            AnyURI::PART_AUTHORITY => "example.com:80",
        ));

        $p[] = array("http://example.com/", array(
            AnyURI::PART_AUTHORITY => "example.com",
        ));

        foreach ($p as &$i) {
            $i[1] = array_merge($i[1], parse_url($i[0]));
        }

        return $p;
    }

    /**
     *  Test whether parseURI() validates it's input
     *
     *  The testParseURIValidation() method is used to verify whether the
     *  URI validation works as expected. That is, if the invalid/malformed
     *  $lexical URI representation causes an \Lousson\URI\AnyURIException
     *  to get raised.
     *
     *  @param  string      $lexical    The URI string
     *
     *  @dataProvider       provideURIArguments
     *  @expectedException  Lousson\URI\AnyURIException
     *  @test
     *
     *  @throws \Lousson\URI\AnyURIException
     *          Raised in case the test is successful
     */
    public function testParseURIValidation($lexical)
    {
        $util = $this->getURIUtil();
        $parts = $util->parseURI($lexical);
    }

    /**
     *  Test whether parseURI() works as expected
     *
     *  The testParseURIExpectations() method checks whether the util's
     *  parseURI() mehthod returns all the $expected values when invoked
     *  with the given $lexical parameter.
     *
     *  @param  string      $lexical    The URI string
     *  @param  array       $expected   The expected parts map
     *
     *  @dataProvider       provideURIExpectations
     *  @test
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case an assertion, and therefore the entire test
     *          case, has failed
     */
    public function testParseURIExpectations($lexical, array $expected)
    {
        $util = $this->getURIUtil();
        $utilClass = get_class($util);
        $parts = $util->parseURI($lexical);

        $this->assertContainsOnly(
            "scalar", $parts, true, sprintf(
            "The map returned by the %s::parseURI() method must contain ".
            "scalar values only", $utilClass
        ));

        $this->assertEquals(
            $expected, $parts, sprintf(
            "The map returned by the %s::parseURI() method must contain ".
            "all the expected items", $utilClass
        ));
    }
}

