<?php

namespace Superbalist\Appboy\Messages;

use DateTime;

class AndroidMessageBuilder
{
    /**
     * @var string
     */
    protected $alert;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var array
     */
    protected $extra = [];

    /**
     * @var string
     */
    protected $messageVariationId;

    /**
     * @var int
     */
    protected $priority = 0;

    /**
     * @var string
     */
    protected $collapseKey;

    /**
     * @var string
     */
    protected $sound = 'default';

    /**
     * @var string
     */
    protected $uri;

    /**
     * @var string
     */
    protected $summaryText;

    /**
     * @var int
     */
    protected $timeToLive;

    /**
     * @var int
     */
    protected $notificationId;

    /**
     * @var string
     */
    protected $pushIconImageUrl;

    /**
     * @var int
     */
    protected $accentColour;

    /**
     * Create a new AndroidMessageBuilder.
     *
     * **Example**
     *
     * ```php
     * $builder = new AndroidMessageBuilder();
     * $params = $builder->setAlert('Hello World!')
     *     ->setTitle('Message Title')
     *     ->withExtraAttributes(['is_test' => true])
     *     ->setMessageVariation('group_a')
     *     ->setPriority(2)
     *     ->setCollapseKey('shipment_1234')
     *     ->setSound('custom_sound')
     *     ->setUri('http://superbalist.com')
     *     ->setSummaryText('This is a summary line')
     *     ->setTimeToLive(60)
     *     ->setNotificationId(18456)
     *     ->setPushIconImageUrl('http://link/to/asset.jpg')
     *     ->setAccentColour(16777215)
     *     ->build();
     * ```
     */
    public function __construct()
    {
        //
    }

    /**
     * Set the message content.
     *
     * @param string $alert
     * @return AndroidMessageBuilder
     */
    public function setAlert($alert)
    {
        $this->alert = $alert;
        return $this;
    }

    /**
     * Set the message title.
     *
     * @param string $title
     * @return AndroidMessageBuilder
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Set additional key => value attributes to include with the message.
     *
     * @param array $attributes
     * @return AndroidMessageBuilder
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
     * @return AndroidMessageBuilder
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
     * @return AndroidMessageBuilder
     */
    public function addExtraAttributes(array $attributes)
    {
        $this->extra = array_merge($this->extra, $attributes);
        return $this;
    }

    /**
     * Set the message variation under which this send will be tracked under.
     *
     * @param string $messageVariationId
     * @return AndroidMessageBuilder
     */
    public function setMessageVariation($messageVariationId)
    {
        $this->messageVariationId = $messageVariationId;
        return $this;
    }

    /**
     * Set the message priority.
     *
     * @param int $priority
     * @return AndroidMessageBuilder
     * @see https://www.appboy.com/documentation/Android/#advanced-use-cases
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
        return $this;
    }

    /**
     * Set the collapse key.
     *
     * @param string $collapseKey
     * @return AndroidMessageBuilder
     */
    public function setCollapseKey($collapseKey)
    {
        $this->collapseKey = $collapseKey;
        return $this;
    }

    /**
     * Set the location of a custom sound to play.
     *
     * The 'default' sound is used as a default.
     *
     * @param string $sound
     * @return AndroidMessageBuilder
     */
    public function setSound($sound)
    {
        $this->sound = $sound;
        return $this;
    }

    /**
     * Set a uri / deep-link click through.
     *
     * @param string $uri
     * @return AndroidMessageBuilder
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
        return $this;
    }


    /**
     * Set the summary text.
     *
     * @param string $summaryText
     * @return AndroidMessageBuilder
     */
    public function setSummaryText($summaryText)
    {
        $this->summaryText = $summaryText;
        return $this;
    }

    /**
     * Set the time to live (TTL).
     *
     * The ttl value is a number in seconds before which the message will expire.
     *
     * @param int $timeToLive
     * @return AndroidMessageBuilder
     * @see https://developers.google.com/cloud-messaging/concept-options
     */
    public function setTimeToLive($timeToLive)
    {
        $this->timeToLive = $timeToLive;
        return $this;
    }

    /**
     * Set the time to live (TTL) based on a date/time.
     *
     * @param DateTime $date
     * @return AndroidMessageBuilder
     * @see https://developers.google.com/cloud-messaging/concept-options
     */
    public function expiresAt(DateTime $date)
    {
        $this->timeToLive = $date->getTimestamp() - time();
        return $this;
    }

    /**
     * Set the notification id.
     *
     * @param int $notificationId
     * @return AndroidMessageBuilder
     */
    public function setNotificationId($notificationId)
    {
        $this->notificationId = $notificationId;
        return $this;
    }

    /**
     * Set the push icon image url.
     *
     * @param string $url
     * @return AndroidMessageBuilder
     */
    public function setPushIconImageUrl($url)
    {
        $this->pushIconImageUrl = $url;
        return $this;
    }

    /**
     * Set the accent colour to display in the push bar.
     *
     * @param int $colour
     * @return AndroidMessageBuilder
     */
    public function setAccentColour($colour)
    {
        $this->accentColour = $colour;
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
            'alert' => $this->alert,
            'title' => $this->title,
            'extra' => $this->extra,
            'message_variation_id' => $this->messageVariationId,
            'priority' => $this->priority,
            'collapse_key' => $this->collapseKey,
            'sound' => $this->sound,
            'custom_uri' => $this->uri,
            'summary_text' => $this->summaryText,
            'time_to_live' => $this->timeToLive,
            'notification_id' => $this->notificationId,
            'push_icon_image_url' => $this->pushIconImageUrl,
            'accent_color' => $this->accentColour,
        ];

        return array_filter($params, function ($param) {
            return $param !== null;
        });
    }
}
