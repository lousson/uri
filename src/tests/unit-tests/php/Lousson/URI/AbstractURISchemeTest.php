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
 *  Lousson\URI\AbstractURISchemeTest class definition
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
use Lousson\URI\AnyURIScheme;
use Lousson\URI\Builtin\BuiltinURIUtil;

/**
 *  An abstract test case for URI schemes
 *
 *  @since      lousson/Lousson_URI-0.1.0
 *  @package    org.lousson.uri
 *  @link       http://www.phpunit.de/manual/current/en/
 */
abstract class AbstractURISchemeTest extends AbstractURIFactoryTest
{
    /**
     *  Obtain well-formed URI schemes
     *
     *  The getWellformedURISchemes() method returns an array of multiple
     *  items, each of whose is a string representing a well-formed scheme
     *  mnemonic.
     *
     *  @return array
     */
    public function getWellformedURISchemes()
    {
        return array("urn", "http", "https", "mailto", "example",);
    }

    /**
     *  Obtain malformed URI schemes
     *
     *  The getMalformedURISchemes() method returns an array of one or
     *  more items, each of whose is a string representing a malformed
     *  scheme mnemonic.
     *
     *  @return array
     */
    public function getMalformedURISchemes()
    {
        return array("",);
    }

    /**
     *  Provide valid URI scheme names
     *
     *  The provideValidURISchemeNames() method is a data provider for
     *  test-methods expecting one parameter; a valid URI scheme mnemonic.
     *
     *  @return array
     */
    public function provideValidURISchemeNames()
    {
        $schemeList = $this->getWellformedURISchemes();
        $parameters = array();

        foreach ($schemeList as $scheme) {
            $parameters[] = array($scheme);
        }

        return $parameters;
    }

    /**
     *  Provide invalid URI scheme names
     *
     *  The provideInvalidURISchemeNames() method is a data provider for
     *  test-methods expecting one parameter; an invalid URI scheme name.
     *
     *  @return array
     */
    public function provideInvalidURISchemeNames()
    {
        $schemeList = $this->getMalformedURISchemes();
        $parameters = array();

        foreach ($schemeList as $scheme) {
            $parameters[] = array($scheme);
        }

        return $parameters;
    }

    /**
     *  Provide URI scheme names an expectations
     *
     *  The provideNameExpectations() method is a data provider for
     *  test-methods expecting three parameters;
     *
     *- A valid URI scheme mnemonic or abbreviation
     *- One of the Lousson\URI\AnyURIScheme::NAME_* constants
     *- The value the scheme's getName() method must return
     *
     *  @return array
     */
    public function provideNameExpectations()
    {
        $schemeList = $this->getWellformedURISchemes();
        $parameters = array();

        static $transformerMap = array(
            AnyURIScheme::NAME_TYPE_MNEMONIC => "strtolower",
            AnyURIScheme::NAME_TYPE_ABBREVIATION => "strtoupper",
        );

        foreach ($schemeList as $scheme) {
            foreach ($transformerMap as $type => $transformer) {
                $expected = $transformer($scheme);
                $parameters[] = array($scheme, $type, $expected);
            }
        }

        return $parameters;
    }

    /**
     *  Test whether name validation works
     *
     *  The testValidation() method is used to verify whether the name
     *  validation works. That is, if the invalid $name provided is not
     *  accepted but an \Lousson\URI\AnyURIException is raised.
     *
     *  @param  string      $name       The input URI scheme name
     *
     *  @dataProvider       provideInvalidURISchemeNames
     *  @expectedException  Lousson\URI\AnyURIException
     *  @test
     *
     *  @throws \Lousson\URI\AnyURIException
     *          Raised in case the test is successful
     */
    public function testValidation($name)
    {
        $this->getURIScheme($name);
    }

    /**
     *  Test whether getName() plays by the rules
     *
     *  The testGetNameReturnValue() method is used to verify whether the
     *  return value of the scheme's getName() method is always compliant
     *  to the interface specification - including, but not limited to:
     *
     *- Verifying that a string is returned
     *- Verifying that the string contains only valid characters
     *- Verifying that the default is used when $type is omited
     *
     *  @param  string      $name       The input URI scheme name
     *
     *  @dataProvider       provideValidURISchemeNames
     *  @test
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case an assertion, and therefore the entire test
     *          case, has failed
     */
    final public function testGetNameReturnValue($name)
    {
        static $constantList = array(
            "Lousson\\URI\\AnyURIScheme::NAME_TYPE_MNEMONIC",
            "Lousson\\URI\\AnyURIScheme::NAME_TYPE_ABBREVIATION",
            "Lousson\\URI\\AnyURIScheme::NAME_TYPE_ENGLISH",
        );

        $scheme = $this->getURIScheme($name);
        $schemeClass = get_class($scheme);

        foreach ($constantList as $constant) {

            $value = $scheme->getName(constant($constant));

            $this->assertInternalType(
                "string", $value, sprintf(
                "Invocations of %s::getName(%s) must always return ".
                "string values", $schemeClass, $constant
            ));

            $this->assertRegExp(
                "/^[^\\s](.*[^\\s])*\$/", $value, sprintf(
                "Names returned by %s::getName(%s) must begin and end ".
                "with non-whitespace characters", $schemeClass, $constant
            ));
        }

        $this->assertRegExp(
            "/[a-z0-9]+/", $scheme->getName(), sprintf(
            "The scheme name returned by %s::getName() must consist of ".
            "lowercase latin characters and digits only", $schemeClass
        ));

        $this->assertEquals(
            $scheme->getName(AnyURIScheme::NAME_TYPE_MNEMONIC),
            $scheme->getName(), sprintf(
            "The %1\$s::getName(AnyURIScheme::NAME_TYPE_MNEMONIC) and ".
            "%1\$s::getName() method invocations must both return the ".
            "same value", $schemeClass
        ));

        $this->assertEquals(
            $scheme->getName(AnyURIScheme::NAME_TYPE_MNEMONIC),
            (string) $scheme, sprintf(
            "The %1\$s::getName(AnyURIScheme::NAME_TYPE_MNEMONIC) and ".
            "%1\$s::__toString() method invocations must both return ".
            "the same value", $schemeClass
        ));
    }

    /**
     *  Test whether getName() matches the expectations
     *
     *  The testGetNameExpectations() method is used to confirm that the
     *  scheme's getName() method returns the $expected value when invoked
     *  with the given $type after being created using the provided $name.
     *
     *  @param  string      $name       The input URI scheme name
     *  @param  string      $type       The type of the name to compare
     *  @param  string      $expected   The expected URI scheme name
     *
     *  @dataProvider       provideNameExpectations
     *  @test
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case an assertion, and therefore the entire test
     *          case, has failed
     */
    final public function testGetNameExpectations($name, $type, $expected)
    {
        $scheme = $this->getURIScheme($name);
        $schemeClass = get_class($scheme);
        $value = $scheme->getName($type);

        $this->assertEquals(
            $expected, $value, sprintf(
            "The %s::getName() method must return the expected ".
            "value", $schemeClass
        ));
    }

    /**
     *  Test whether __toString() behaves as expected
     *
     *  The testToStringReturnValue() method checks whether the return
     *  value of the scheme's __toString() method equals the return value
     *  of the getName() method when invoked without any parameters.
     *
     *  @param  string      $name       The input URI scheme name
     *
     *  @dataProvider       provideValidURISchemeNames
     *  @test
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case an assertion, and therefore the entire test
     *          case, has failed
     */
    final public function testToStringReturnValue($name)
    {
        $scheme = $this->getURIScheme($name);
        $schemeClass = get_class($scheme);

        $this->assertInternalType(
            "string", $scheme->__toString(), sprintf(
            "The %s::__toString() method must return a string value",
            $schemeClass
        ));

        $this->assertEquals(
            (string) $scheme, $scheme->getName(),sprintf(
            "The %1\$s::__toString() and %1\$s::getName() method ".
            "invocations must both return the same value", $schemeClass
        ));
    }
}

