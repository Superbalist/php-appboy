<?php

namespace Superbalist\Appboy\Messages;

use DateTime;

class AppleMessageBuilder
{
    /**
     * @var int
     */
    protected $badge;

    /**
     * @var string
     */
    protected $alert;

    /**
     * @var string
     */
    protected $sound = 'default';

    /**
     * @var array
     */
    protected $extra = [];

    /**
     * @var string
     */
    protected $category;

    /**
     * @var DateTime
     */
    protected $expiryDate;

    /**
     * @var string
     */
    protected $uri;

    /**
     * @var string
     */
    protected $messageVariationId;

    /**
     * @var string
     */
    protected $assetUrl;

    /**
     * @var string
     */
    protected $assetFileType;

    /**
     * Create a new AppleMessageBuilder.
     *
     * **Example**
     *
     * ```php
     * $builder = new AppleMessageBuilder();
     * $params = $builder->setBadgeCount(3)
     *     ->setAlert('Hello World!')
     *     ->setSound('custom_sound')
     *     ->withExtraAttributes(['is_test' => true])
     *     ->setCategory('shipping_notification')
     *     ->expiresAt(new \DateTime('2017-05-29 10:00:00', new \DateTimeZone('Africa/Johannesburg')))
     *     ->setUri('http://superbalist.com')
     *     ->setMessageVariation('group_a')
     *     ->setAsset('file://image.jpg', 'jpg')
     *     ->build();
     * ```
     */
    public function __construct()
    {
        //
    }

    /**
     * Set the badge count to display after the message is received.
     *
     * @param int $count
     * @return AppleMessageBuilder
     */
    public function setBadgeCount($count)
    {
        $this->badge = $count;
        return $this;
    }

    /**
     * Set the message content.
     *
     * @param string $alert
     * @return AppleMessageBuilder
     */
    public function setAlert($alert)
    {
        $this->alert = $alert;
        return $this;
    }

    /**
     * Set the location of a custom sound to play.
     *
     * The 'default' sound is used as a default.
     *
     * @param string $sound
     * @return AppleMessageBuilder
     */
    public function setSound($sound)
    {
        $this->sound = $sound;
        return $this;
    }

    /**
     * Set additional key => value attributes to include with the message.
     *
     * @param array $attributes
     * @return AppleMessageBuilder
     */
    public function withExtraAttributes(array $attributes)
    {
        $this->extra = $attributes;
        return $this;
    }

    /**
     * Add an additional attribute to include with the message.
     *
     * @param string $key
     * @param mixed $value
     * @return AppleMessageBuilder
     */
    public function addExtraAttribute($key, $value)
    {
        $this->extra[$key] = $value;
        return $this;
    }

    /**
     * Add additional attributes to include with the message.
     *
     * @param array $attributes
     * @return AppleMessageBuilder
     */
    public function addExtraAttributes(array $attributes)
    {
        $this->extra = array_merge($this->extra, $attributes);
        return $this;
    }

    /**
     * Set the category.
     *
     * @param string $category
     * @return AppleMessageBuilder
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * Set a date/time at which the message will expire.
     *
     * @param DateTime $date
     * @return AppleMessageBuilder
     */
    public function expiresAt(DateTime $date)
    {
        $this->expiryDate = $date;
        return $this;
    }

    /**
     * Set a uri / deep-link click through.
     *
     * @param string $uri
     * @return AppleMessageBuilder
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
        return $this;
    }

    /**
     * Set the message variation under which this send will be tracked under.
     *
     * @param string $messageVariationId
     * @return AppleMessageBuilder
     */
    public function setMessageVariation($messageVariationId)
    {
        $this->messageVariationId = $messageVariationId;
        return $this;
    }

    /**
     * Set a content url for rich notifications.
     *
     * This is only available on devices running iOS 10 or higher.
     *
     * @param string $url
     * @param string $fileType
     * @return AppleMessageBuilder
     */
    public function setAsset($url, $fileType)
    {
        $this->assetUrl = $url;
        $this->assetFileType = $fileType;
        return $this;
    }

    /**
     * Build the message payload.
     *
     * @return array
     */
    public function build()
    {
        $params = [
            'badge' => $this->badge,
            'alert' => $this->alert,
            'sound' => $this->sound,
            'extra' => $this->extra,
            'category' => $this->category,
            'expiry' => $this->expiryDate ? $this->expiryDate->format('c') : null,
            'custom_uri' => $this->uri,
            'message_variation_id' => $this->messageVariationId,
            'asset_url' => $this->assetUrl,
            'asset_file_type' => $this->assetFileType,
        ];

        return array_filter($params, function ($param) {
            return $param !== null;
        });
    }
}
