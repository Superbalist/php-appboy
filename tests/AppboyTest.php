<?php

namespace Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Mockery;
use PHPUnit\Framework\TestCase;
use Superbalist\Appboy\Appboy;

class AppboyTest extends TestCase
{
    public function testSetGetUri()
    {
        $guzzleClient = Mockery::mock(Client::class);
        $appboy = new Appboy($guzzleClient, 'ABC123');
        $this->assertEquals('https://api.appboy.com', $appboy->getUri());
        $appboy->setUri('http://127.0.0.1');
        $this->assertEquals('http://127.0.0.1', $appboy->getUri());

        $appboy = new Appboy($guzzleClient, 'ABC123', 'http://192.168.0.1');
        $this->assertEquals('http://192.168.0.1', $appboy->getUri());
    }

    public function testSetGetAppGroupId()
    {
        $guzzleClient = Mockery::mock(Client::class);
        $appboy = new Appboy($guzzleClient, 'ABC123');
        $this->assertEquals('ABC123', $appboy->getAppGroupId());
        $appboy->setAppGroupId('9998');
        $this->assertEquals('9998', $appboy->getAppGroupId());
    }

    public function testGetClient()
    {
        $guzzleClient = Mockery::mock(Client::class);
        $appboy = new Appboy($guzzleClient, 'ABC123');
        $this->assertSame($guzzleClient, $appboy->getClient());
    }

    public function testMakeBaseUri()
    {
        $guzzleClient = Mockery::mock(Client::class);
        $appboy = new Appboy($guzzleClient, 'ABC123');
        $this->assertEquals('https://api.appboy.com/messages/send', $appboy->makeBaseUri('/messages/send'));
        $this->assertEquals('https://api.appboy.com/messages/send', $appboy->makeBaseUri('messages/send'));
        $this->assertEquals('https://api.appboy.com/messages/send', $appboy->makeBaseUri('messages/send/'));
    }

    public function testPost()
    {
        $request = new Request(
            'POST',
            'https://api.appboy.com/messages/send',
            [
                'Content-Type' => 'application/json',
            ],
            json_encode(['lorem' => 'ipsum'])
        );

        $guzzleClient = Mockery::mock(Client::class);
        $appboy = Mockery::mock('\Superbalist\Appboy\Appboy[createRequest,sendRequest]', [$guzzleClient, 'ABC123']);
        $appboy->shouldAllowMockingProtectedMethods();
        $appboy->shouldReceive('createRequest')
            ->withArgs([
                'POST',
                'messages/send',
                json_encode(['lorem' => 'ipsum']),
                ['Content-Type' => 'application/json'],
            ])
            ->once()
            ->andReturn($request);
        $appboy->shouldReceive('sendRequest')
            ->with($request)
            ->once();

        $appboy->post('messages/send', ['lorem' => 'ipsum']);
    }

    public function testSendMessage()
    {
        $guzzleClient = Mockery::mock(Client::class);
        $appboy = Mockery::mock('\Superbalist\Appboy\Appboy[post]', [$guzzleClient, 'ABC123']);
        $appboy->shouldReceive('post')
            ->withArgs([
                'messages/send',
                [
                    'app_group_id' => 'ABC123',
                    'blah' => 'bleh',
                ],
            ])
            ->once();
        $appboy->sendMessage(['blah' => 'bleh']);
    }

    public function testScheduleMessage()
    {
        $guzzleClient = Mockery::mock(Client::class);
        $appboy = Mockery::mock('\Superbalist\Appboy\Appboy[post]', [$guzzleClient, 'ABC123']);
        $appboy->shouldReceive('post')
            ->withArgs([
                'messages/schedule/create',
                [
                    'app_group_id' => 'ABC123',
                    'blah' => 'bleh',
                ],
            ])
            ->once();
        $appboy->scheduleMessage(['blah' => 'bleh']);
    }
}
