<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Superbalist\Appboy\Messages\AppleMessageBuilder;

class AppleMessageBuilderTest extends TestCase
{
    public function testSetBadgeCount()
    {
        $builder = new AppleMessageBuilder();
        $return = $builder->setBadgeCount(3);
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('badge', $params);
        $this->assertEquals(3, $params['badge']);
    }

    public function testSetAlert()
    {
        $builder = new AppleMessageBuilder();
        $return = $builder->setAlert('Hello World!');
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('alert', $params);
        $this->assertEquals('Hello World!', $params['alert']);
    }

    public function testSetSound()
    {
        $builder = new AppleMessageBuilder();
        $params = $builder->build();
        $this->assertArrayHasKey('sound', $params);
        $this->assertEquals('default', $params['sound']);

        $return = $builder->setSound('custom_sound');
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('sound', $params);
        $this->assertEquals('custom_sound', $params['sound']);
    }

    public function testWithExtraAttributes()
    {
        $builder = new AppleMessageBuilder();
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
        $builder = new AppleMessageBuilder();
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
        $builder = new AppleMessageBuilder();
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

    public function testSetCategory()
    {
        $builder = new AppleMessageBuilder();
        $return = $builder->setCategory('shipping_notification');
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('category', $params);
        $this->assertEquals('shipping_notification', $params['category']);
    }

    public function testExpiresAt()
    {
        $builder = new AppleMessageBuilder();
        $return = $builder->expiresAt(new \DateTime('2017-05-29 10:00:00', new \DateTimeZone('Africa/Johannesburg')));
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('expiry', $params);
        $this->assertEquals('2017-05-29T10:00:00+02:00', $params['expiry']);
    }

    public function testSetUri()
    {
        $builder = new AppleMessageBuilder();
        $return = $builder->setUri('http://superbalist.com');
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('custom_uri', $params);
        $this->assertEquals('http://superbalist.com', $params['custom_uri']);
    }

    public function testSetMessageVariation()
    {
        $builder = new AppleMessageBuilder();
        $return = $builder->setMessageVariation('group_a');
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('message_variation_id', $params);
        $this->assertEquals('group_a', $params['message_variation_id']);
    }

    public function testSetAsset()
    {
        $builder = new AppleMessageBuilder();
        $return = $builder->setAsset('file://image.jpg', 'jpg');
        $this->assertSame($builder, $return);
        $params = $builder->build();
        $this->assertArrayHasKey('asset_url', $params);
        $this->assertArrayHasKey('asset_file_type', $params);
        $this->assertEquals('file://image.jpg', $params['asset_url']);
        $this->assertEquals('jpg', $params['asset_file_type']);
    }

    public function testBuild()
    {
        $builder = new AppleMessageBuilder();
        $params = $builder->setBadgeCount(3)
            ->setAlert('Hello World!')
            ->setSound('custom_sound')
            ->withExtraAttributes(['is_test' => true])
            ->setCategory('shipping_notification')
            ->expiresAt(new \DateTime('2017-05-29 10:00:00', new \DateTimeZone('Africa/Johannesburg')))
            ->setUri('http://superbalist.com')
            ->setMessageVariation('group_a')
            ->setAsset('file://image.jpg', 'jpg')
            ->build();

        $expected = [
            'badge' => 3,
            'alert' => 'Hello World!',
            'sound' => 'custom_sound',
            'extra' => [
                'is_test' => true,
            ],
            'category' => 'shipping_notification',
            'expiry' => '2017-05-29T10:00:00+02:00',
            'custom_uri' => 'http://superbalist.com',
            'message_variation_id' => 'group_a',
            'asset_url' => 'file://image.jpg',
            'asset_file_type' => 'jpg',
        ];
        $this->assertEquals($expected, $params);
    }
}
