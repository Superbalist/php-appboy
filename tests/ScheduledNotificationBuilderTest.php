<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Superbalist\Appboy\ScheduledNotificationBuilder;

class ScheduledNotificationBuilderTest extends TestCase
{
    public function testToUsers()
    {
        $builder = new ScheduledNotificationBuilder();
        $return = $builder->toUsers([1, 2]);
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('external_user_ids', $params);
        $this->assertEquals([1, 2], $params['external_user_ids']);

        $builder->toUsers(2);
        $params = $builder->build();
        $this->assertArrayHasKey('external_user_ids', $params);
        $this->assertEquals([2], $params['external_user_ids']);
    }

    public function testToUser()
    {
        $builder = new ScheduledNotificationBuilder();
        $return = $builder->toUser(2);
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('external_user_ids', $params);
        $this->assertEquals([2], $params['external_user_ids']);
    }

    public function testToSegment()
    {
        $builder = new ScheduledNotificationBuilder();
        $return = $builder->toSegment('abc123');
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('segment_id', $params);
        $this->assertEquals('abc123', $params['segment_id']);
    }

    public function testSetCampaign()
    {
        $builder = new ScheduledNotificationBuilder();
        $return = $builder->setCampaign('my_campaign');
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('campaign_id', $params);
        $this->assertEquals('my_campaign', $params['campaign_id']);
    }

    public function testIgnoreFrequencyCapping()
    {
        $builder = new ScheduledNotificationBuilder();
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
        $builder = new ScheduledNotificationBuilder();
        $builder->ignoreFrequencyCapping();
        $return = $builder->respectFrequencyCapping();
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('override_frequency_capping', $params);
        $this->assertFalse($params['override_frequency_capping']);
    }

    public function testSetSubscriptionState()
    {
        $builder = new ScheduledNotificationBuilder();
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
        $builder = new ScheduledNotificationBuilder();
        $return = $builder->withMessages([
            'apple_push' => ['alert' => 'Message 1'],
            'android_push' => ['alert' => 'Message 2'],
        ]);
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('messages', $params);
        $expected = [
            'apple_push' => ['alert' => 'Message 1'],
            'android_push' => ['alert' => 'Message 2'],
        ];
        $this->assertEquals($expected, $params['messages']);

        $builder->withMessages(['apple_push' => ['alert' => 'Message 3']]);
        $params = $builder->build();
        $this->assertEquals(['apple_push' => ['alert' => 'Message 3']], $params['messages']);
    }

    public function testWithMessage()
    {
        $builder = new ScheduledNotificationBuilder();
        $return = $builder->withMessage('apple_push', ['alert' => 'Message 1']);
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('messages', $params);
        $this->assertEquals(['apple_push' => ['alert' => 'Message 1']], $params['messages']);

        $builder->withMessage('android_push', ['alert' => 'Message 2']);
        $params = $builder->build();
        $this->assertEquals(['android_push' => ['alert' => 'Message 2']], $params['messages']);
    }

    public function testAddMessage()
    {
        $builder = new ScheduledNotificationBuilder();
        $return = $builder->addMessage('apple_push', ['alert' => 'Message 1']);
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('messages', $params);
        $this->assertEquals(['apple_push' => ['alert' => 'Message 1']], $params['messages']);

        $builder->withMessage('android_push', ['alert' => 'Message 2']);
        $params = $builder->build();
        $this->assertEquals(['android_push' => ['alert' => 'Message 2']], $params['messages']);

        $builder->addMessage('apple_push', ['alert' => 'Message 3']);
        $builder->addMessage('kindle_push', ['alert' => 'Message 4']);
        $params = $builder->build();
        $this->assertEquals(
            [
                'android_push' => ['alert' => 'Message 2'],
                'apple_push' => ['alert' => 'Message 3'],
                'kindle_push' => ['alert' => 'Message 4'],
            ],
            $params['messages']
        );
    }

    public function testSendsAt()
    {
        $builder = new ScheduledNotificationBuilder();
        $return = $builder->sendsAt(new \DateTime('2017-05-29 10:00:00', new \DateTimeZone('Africa/Johannesburg')));
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('schedule', $params);
        $this->assertEquals(['time' => '2017-05-29T10:00:00+02:00'], $params['schedule']);
    }

    public function testBuild()
    {
        $builder = new ScheduledNotificationBuilder();
        $params = $builder->toUsers([1, 2])
            ->setCampaign('my_campaign')
            ->ignoreFrequencyCapping()
            ->setSubscriptionState('opted_in')
            ->withMessage('apple_push', ['alert' => 'Hello World!'])
            ->build();

        $expected = [
            'external_user_ids' => [1, 2],
            'campaign_id' => 'my_campaign',
            'override_frequency_capping' => true,
            'recipient_subscription_state' => 'opted_in',
            'messages' => [
                'apple_push' => ['alert' => 'Hello World!']
            ],
        ];
        $this->assertEquals($expected, $params);

    }
}
