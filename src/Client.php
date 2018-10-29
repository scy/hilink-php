<?php

namespace scy\HiLink;

use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use Psr\Http\Message\StreamInterface;

class Client
{
    protected $guzzle;
    protected $cookieJar;
    protected $sesInfo = '';

    public function __construct()
    {
        $this->cookieJar = new CookieJar();
        $this->guzzle = new \GuzzleHttp\Client([
            'base_uri' => 'http://192.168.8.1',
            'cookies' => $this->cookieJar,
        ]);
    }

    public function login(): bool
    {
        $response = $this->guzzle->get('/api/webserver/SesTokInfo');
        $xml = $this->parseXml($response->getBody());
        $sesInfo = preg_replace('/^SessionID=/', '', (string)$xml->SesInfo);
        if (!strlen($sesInfo)) {
            return false;
        }

        $cookie = new SetCookie();
        $cookie->setName('SessionID');
        $cookie->setDomain('192.168.8.1');
        $cookie->setValue($sesInfo);
        $this->cookieJar->setCookie($cookie);

        return true;
    }

    public function getStatus(): Status
    {
        $response = $this->guzzle->get('/api/monitoring/status');
        return new Status($response);
    }

    protected function parseXml(StreamInterface $body): \SimpleXMLElement
    {
        return new \SimpleXMLElement($body->getContents());
    }
}

