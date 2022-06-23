<?php

declare(strict_types=1);

namespace VCR\Tests\Integration\HttpClient;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;
use org\bovigo\vfs\vfsStream;
use VCR\VCR;

final class HttpClientTest extends TestCase
{
    public static function setupBeforeClass(): void
    {
        VCR::configure()->setCassettePath('tests/fixtures');
        VCR::configure()->enableLibraryHooks(['curl']);
    }

    public function testCallDirectlyMagic(): void
    {
        VCR::turnOn();
        VCR::insertCassette('unittest_httpclient_test');

        $client = HttpClient::create();
        $response = $client->request('GET', 'http://example.com', [
            'headers' => ['User-Agent' => 'Unit-Test'],
        ]);

        $this->assertEquals('This is a httpclient test dummy.', $response->getContent(), 'HttpClient call was not intercepted.');
        VCR::eject();
        VCR::turnOff();
    }
}
