<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Superbalist\Appboy\NotificationBuilder;

class NotificationBuilderTest extends TestCase
{
    public function testToUsers()
    {
        $builder = new NotificationBuilder();
        $return = $builder->toUsers([1, 2]);
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('external_user_ids', $params);
        $this->assertEquals([1, 2], $params['external_user_ids']);
    }

    public function testToSegment()
    {
        $builder = new NotificationBuilder();
        $return = $builder->toSegment('abc123');
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('segment_id', $params);
        $this->assertEquals('abc123', $params['segment_id']);
    }

    public function testSetCampaign()
    {
        $builder = new NotificationBuilder();
        $return = $builder->setCampaign('my_campaign');
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('campaign_id', $params);
        $this->assertEquals('my_campaign', $params['campaign_id']);
    }

    public function testIgnoreFrequencyCapping()
    {
        $builder = new NotificationBuilder();
        $params = $builder->build();
        $this->assertArrayHasKey('override_frequency_capping', $params);
        $this->assertFalse($params['override_frequency_capping']);

        $return = $builder->ignoreFrequencyCapping();
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('override_frequency_capping', $params);
        $this->assertTrue($params['override_frequency_capping']);
    }

    public function testRespectFrequencyCapping()
    {
        $builder = new NotificationBuilder();
        $builder->ignoreFrequencyCapping();
        $return = $builder->respectFrequencyCapping();
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('override_frequency_capping', $params);
        $this->assertFalse($params['override_frequency_capping']);
    }

    public function testSetSubscriptionState()
    {
        $builder = new NotificationBuilder();
        $params = $builder->build();
        $this->assertArrayHasKey('recipient_subscription_state', $params);
        $this->assertEquals('subscribed', $params['recipient_subscription_state']);

        $return = $builder->setSubscriptionState('all');
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('recipient_subscription_state', $params);
        $this->assertEquals('all', $params['recipient_subscription_state']);
    }

    public function testWithMessages()
    {
        $builder = new NotificationBuilder();
        $return = $builder->withMessages([
            ['alert' => 'Message 1'],
            ['alert' => 'Message 2'],
        ]);
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('messages', $params);
        $this->assertEquals([['alert' => 'Message 1'], ['alert' => 'Message 2']], $params['messages']);

        $builder->withMessages([['alert' => 'Message 3']]);
        $params = $builder->build();
        $this->assertEquals([['alert' => 'Message 3']], $params['messages']);
    }

    public function testWithMessage()
    {
        $builder = new NotificationBuilder();
        $return = $builder->withMessage(['alert' => 'Message 1']);
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('messages', $params);
        $this->assertEquals([['alert' => 'Message 1']], $params['messages']);

        $builder->withMessage(['alert' => 'Message 2']);
        $params = $builder->build();
        $this->assertEquals([['alert' => 'Message 2']], $params['messages']);
    }

    public function testAddMessage()
    {
        $builder = new NotificationBuilder();
        $return = $builder->addMessage(['alert' => 'Message 1']);
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('messages', $params);
        $this->assertEquals([['alert' => 'Message 1']], $params['messages']);

        $builder->withMessage(['alert' => 'Message 2']);
        $params = $builder->build();
        $this->assertEquals([['alert' => 'Message 2']], $params['messages']);

        $builder->addMessage(['alert' => 'Message 3']);
        $builder->addMessage(['alert' => 'Message 4']);
        $params = $builder->build();
        $this->assertEquals(
            [
                ['alert' => 'Message 2'],
                ['alert' => 'Message 3'],
                ['alert' => 'Message 4'],
            ],
            $params['messages']
        );
    }

    public function testBuild()
    {
        $builder = new NotificationBuilder();
        $params = $builder->toUsers([1, 2])
            ->setCampaign('my_campaign')
            ->ignoreFrequencyCapping()
            ->setSubscriptionState('opted_in')
            ->withMessage(['alert' => 'Hello World!'])
            ->build();

        $expected = [
            'external_user_ids' => [1, 2],
            'campaign_id' => 'my_campaign',
            'override_frequency_capping' => true,
            'recipient_subscription_state' => 'opted_in',
            'messages' => [
                ['alert' => 'Hello World!']
            ],
        ];
        $this->assertEquals($expected, $params);

    }
}
