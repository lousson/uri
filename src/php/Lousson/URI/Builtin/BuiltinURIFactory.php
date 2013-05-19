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
 *  Lousson\URI\Builtin\BuiltinURIFactory class definition
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
use Lousson\URI\AnyURI;
use Lousson\URI\Generic\GenericURIResolver;
use Lousson\URI\Generic\GenericURIScheme;
use Lousson\URI\Generic\GenericURI;

/**
 *  A factory for builtin URI modules
 *
 *  The BuiltinURIFactory class is the default implementation of the
 *  AnyURIFacory interface. It has been introduced to provide a builtin
 *  URI resolver, as well as various URI scheme information items.
 *
 *  @since      lousson/uri-0.1.1
 *  @package    org.lousson.uri
 *  @link       http://www.iana.org/assignments/uri-schemes.html
 */
class BuiltinURIFactory implements AnyURIFactory
{
    /**
     *  Obtain an URI instance
     *
     *  The getURI() method returns an instance of the AnyURI interface,
     *  representing the URI provided in it's $lexical form.
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
     *  The getURIScheme() method returns an AnyURIScheme instance,
     *  representing the URI scheme associated with the given $name.
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
        $mnemonic = strtolower($name);
        $abbreviation = null;
        $english = null;

        if (isset($this->schemes[$mnemonic])) {
            list($abbreviation, $english) = $this->schemes[$mnemonic];
        }

        $scheme = GenericURIScheme::create(
            $mnemonic, $abbreviation, $english
        );

        return $scheme;
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
        $callback = function(AnyURI $uri) {
            $scheme = $uri->getPart(AnyURI::PART_SCHEME);
            $result = strcasecmp("urn", $scheme)? array($uri): array();
            return $result;
        };

        $resolver = new GenericURIResolver($this, $callback);
        return $resolver;
    }

    /**
     *  A register for builtin URI scheme information
     *
     *  @var array
     */
    private $schemes = array(
        "aaa" => array("AAA", "Diameter Protocol"),
        "aaas" => array("AAAS", "Diameter Protocol With Secure Transport"),
        "acap" => array("ACAP", "Application Configuration Access Protocol"),
        "cap" => array("CAP", "Calendar Access Protocol"),
        "cid" => array("CID", "Content Identifier"),
        "crid" => array("CRID", "TV-Anytime Content Reference Identifier"),
        "dict" => array("DICT", "Dictionary Service Protocol"),
        "dns" => array("DNS", "Domain Name System"),
        "file" => array("FILE", "Host-Specific File Names"),
        "ftp" => array("FTP", "File Transfer Protocol"),
        "geo" => array("GEO", "Geographic Locations"),
        "gopher" => array("GOPHER", "The Gopher Protocol"),
        "h323" => array("H.323", "H.323"),
        "http" => array("HTTP", "Hypertext Transfer Protocol"),
        "https" => array("HTTPS", "Hypertext Transfer Protocol Secure"),
        "iax" => array("IAX", "Inter-Asterisk eXchange Version 2"),
        "icap" => array("ICAP", "Internet Content Adaptation Protocol"),
        "im" => array("IM", "Instant Messaging"),
        "imap" => array("IMAP", "Internet Message Access Protocol"),
        "info" => array("INFO", "Information Assets With Identifiers In Public Namespaces"),
        "ipp" => array("IPP", "Internet Printing Protocol"),
        "iris" => array("IRIS", "Internet Registry Information Service"),
        "jabber" => array("JABBER", "Jabber"),
        "ldap" => array("LDAP", "Lightweight Directory Access Protocol"),
        "mailto" => array("MAILTO", "Electronic Mail Address"),
        "mid" => array("MID", "Message Identifier"),
        "msrp" => array("mSRP", "Message Session Relay Protocol"),
        "msrps" => array("mSRPS", "Message Session Relay Protocol Secure"),
        "mtqp" => array("MTQP", "Message Tracking Query Protocol"),
        "mupdate" => array("MUPDATE", "Mailbox Update (MUPDATE) Protocol"),
        "news" => array("NEWS", "USENET News"),
        "nfs" => array("NFS", "Network File System Protocol"),
        "nntp" => array("NNTP", "USENET News using NNTP Access"),
        "opaquelocktoken" => array("OPAQUELOCKTOKEN", "Opaquelocktoken"),
        "pop" => array("POP", "Post Office Protocol V3"),
        "pres" => array("PRES", "Presence"),
        "rtsp" => array("RTSP", "Real Time Streaming Protocol"),
        "shttp" => array("SHTTP", "Secure Hypertext Transfer Protocol"),
        "sieve" => array("SIEVE", "ManageSieve Protocol"),
        "sip" => array("SIP", "Session Initiation Protocol"),
        "sips" => array("SIPS", "Secure Session Initiation Protocol"),
        "sms" => array("SMS", "Short Message Service"),
        "snmp" => array("SNMP", "Simple Network Management Protocol"),
        "telnet" => array("TELNET", "Reference To Interactive Sessions"),
        "tftp" => array("TFTP", "Trivial File Transfer Protocol"),
        "tip" => array("TIP", "Transaction Internet Protocol"),
        "urn" => array("URN", "Uniform Resource Names"),
        "vemmi" => array("VEMMI", "Versatile Multimedia Interface"),
        "ws" => array("wS", "WebSocket Connections"),
        "wss" => array("wSS", "Encrypted WebSocket Connections"),
        "xmpp" => array("XMPP", "Extensible Messaging And Presence Protocol"),
        "z39.50r" => array("Z39.50R", "Z39.50 Retrieval"),
        "z39.50s" => array("Z39.50S", "Z39.50 Session"),
    );
}

