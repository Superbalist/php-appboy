<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Superbalist\Appboy\Messages\AndroidMessageBuilder;

class AndroidMessageBuilderTest extends TestCase
{
    public function testSetAlert()
    {
        $builder = new AndroidMessageBuilder();
        $return = $builder->setAlert('Hello World!');
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('alert', $params);
        $this->assertEquals('Hello World!', $params['alert']);
    }

    public function testSetTitle()
    {
        $builder = new AndroidMessageBuilder();
        $return = $builder->setTitle('Message Title');
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('title', $params);
        $this->assertEquals('Message Title', $params['title']);
    }

    public function testWithExtraAttributes()
    {
        $builder = new AndroidMessageBuilder();
        $return = $builder->withExtraAttributes(['is_test' => true, 'show_banner' => false]);
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('extra', $params);
        $this->assertEquals(['is_test' => true, 'show_banner' => false], $params['extra']);

        $builder->withExtraAttributes(['version' => 'abc']);
        $params = $builder->build();
        $this->assertArrayHasKey('extra', $params);
        $this->assertEquals(['version' => 'abc'], $params['extra']);
    }

    public function testAddExtraAttribute()
    {
        $builder = new AndroidMessageBuilder();
        $return = $builder->addExtraAttribute('is_test', true);
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('extra', $params);
        $this->assertEquals(['is_test' => true], $params['extra']);

        $builder->withExtraAttributes(['show_banner' => false]);
        $params = $builder->build();
        $this->assertEquals(['show_banner' => false], $params['extra']);

        $builder->addExtraAttribute('is_test', true);
        $builder->addExtraAttribute('version', 'abc');
        $params = $builder->build();
        $this->assertEquals(
            [
                'show_banner' => false,
                'is_test' => true,
                'version' => 'abc',
            ],
            $params['extra']
        );
    }

    public function testAddExtraAttributes()
    {
        $builder = new AndroidMessageBuilder();
        $return = $builder->addExtraAttributes(['is_test' => true, 'show_banner' => false]);
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('extra', $params);
        $this->assertEquals(['is_test' => true, 'show_banner' => false], $params['extra']);

        $builder->withExtraAttributes(['version' => 'abc']);
        $params = $builder->build();
        $this->assertEquals(['version' => 'abc'], $params['extra']);

        $builder->addExtraAttributes(['is_test' => true]);
        $builder->addExtraAttributes(['show_banner' => false]);
        $params = $builder->build();
        $this->assertEquals(
            [
                'version' => 'abc',
                'is_test' => true,
                'show_banner' => false,
            ],
            $params['extra']
        );
    }

    public function testSetMessageVariation()
    {
        $builder = new AndroidMessageBuilder();
        $return = $builder->setMessageVariation('group_a');
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('message_variation_id', $params);
        $this->assertEquals('group_a', $params['message_variation_id']);
    }

    public function testSetPriority()
    {
        $builder = new AndroidMessageBuilder();
        $params = $builder->build();
        $this->assertArrayHasKey('priority', $params);
        $this->assertEquals(0, $params['priority']);

        $return = $builder->setPriority(2);
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('priority', $params);
        $this->assertEquals(2, $params['priority']);
    }

    public function testSetCollapseKey()
    {
        $builder = new AndroidMessageBuilder();
        $return = $builder->setCollapseKey('shipment_1234');
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('collapse_key', $params);
        $this->assertEquals('shipment_1234', $params['collapse_key']);
    }

    public function testSetSound()
    {
        $builder = new AndroidMessageBuilder();
        $params = $builder->build();
        $this->assertArrayHasKey('sound', $params);
        $this->assertEquals('default', $params['sound']);

        $return = $builder->setSound('custom_sound');
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('sound', $params);
        $this->assertEquals('custom_sound', $params['sound']);
    }

    public function testSetUri()
    {
        $builder = new AndroidMessageBuilder();
        $return = $builder->setUri('http://superbalist.com');
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('custom_uri', $params);
        $this->assertEquals('http://superbalist.com', $params['custom_uri']);
    }

    public function testSetSummaryText()
    {
        $builder = new AndroidMessageBuilder();
        $return = $builder->setSummaryText('This is a summary line');
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('summary_text', $params);
        $this->assertEquals('This is a summary line', $params['summary_text']);
    }

    public function testSetTimeToLive()
    {
        $builder = new AndroidMessageBuilder();
        $return = $builder->setTimeToLive(60);
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('time_to_live', $params);
        $this->assertEquals(60, $params['time_to_live']);
    }

    public function testExpiresAt()
    {
        $builder = new AndroidMessageBuilder();
        $return = $builder->expiresAt(new \DateTime('+1 minute'));
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('time_to_live', $params);
        $this->assertEquals(60, $params['time_to_live']);
    }

    public function testSetNotificationId()
    {
        $builder = new AndroidMessageBuilder();
        $return = $builder->setNotificationId(18456);
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('notification_id', $params);
        $this->assertEquals(18456, $params['notification_id']);
    }

    public function testSetPushIconImageUrl()
    {
        $builder = new AndroidMessageBuilder();
        $return = $builder->setPushIconImageUrl('http://link/to/asset.jpg');
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('push_icon_image_url', $params);
        $this->assertEquals('http://link/to/asset.jpg', $params['push_icon_image_url']);
    }

    public function testSetAccentColour()
    {
        $builder = new AndroidMessageBuilder();
        $return = $builder->setAccentColour(16777215);
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('accent_color', $params);
        $this->assertEquals(16777215, $params['accent_color']);
    }

    public function testBuild()
    {
        $builder = new AndroidMessageBuilder();
        $params = $builder->setAlert('Hello World!')
            ->setTitle('Message Title')
            ->withExtraAttributes(['is_test' => true])
            ->setMessageVariation('group_a')
            ->setPriority(2)
            ->setCollapseKey('shipment_1234')
            ->setSound('custom_sound')
            ->setUri('http://superbalist.com')
            ->setSummaryText('This is a summary line')
            ->setTimeToLive(60)
            ->setNotificationId(18456)
            ->setPushIconImageUrl('http://link/to/asset.jpg')
            ->setAccentColour(16777215)
            ->build();

        $expected = [
            'alert' => 'Hello World!',
            'title' => 'Message Title',
            'extra' => [
                'is_test' => true,
            ],
            'message_variation_id' => 'group_a',
            'priority' => 2,
            'collapse_key' => 'shipment_1234',
            'sound' => 'custom_sound',
            'custom_uri' => 'http://superbalist.com',
            'summary_text' => 'This is a summary line',
            'time_to_live' => 60,
            'notification_id' => 18456,
            'push_icon_image_url' => 'http://link/to/asset.jpg',
            'accent_color' => 16777215,
        ];
        $this->assertEquals($expected, $params);
    }
}