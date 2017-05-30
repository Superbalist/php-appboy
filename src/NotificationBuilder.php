<?php

namespace Superbalist\Appboy;

class NotificationBuilder
{
    /**
     * @var array
     */
    protected $externalUserIds = [];

    /**
     * @var string
     */
    protected $segmentId;

    /**
     * @var string
     */
    protected $campaignId;

    /**
     * @var bool
     */
    protected $overrideFrequencyCapping = false;

    /**
     * @var string
     */
    protected $recipientSubscriptionState = 'subscribed';

    /**
     * @var array
     */
    protected $messages = [];

    /**
     * Create a new NotificationBuilder.
     *
     * **Example**
     *
     * ```php
     * $builder = new NotificationBuilder();
     * $params = $builder->toUsers([1, 2])
     *     ->setCampaign('my_campaign')
     *     ->ignoreFrequencyCapping()
     *     ->setSubscriptionState('opted_in')
     *     ->withMessage(['alert' => 'Hello World!'])
     *     ->build();
     * ```
     */
    public function __construct()
    {
        //
    }

    /**
     * Set the external user ids to send to.
     *
     * @param array|mixed $ids
     *
     * @return NotificationBuilder
     *
     * @see https://www.appboy.com/documentation/REST_API/#external-user-id
     */
    public function toUsers($ids)
    {
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $this->externalUserIds = $ids;
        return $this;
    }

    /**
     * Set the external user id to send to.
     *
     * @param mixed $id
     *
     * @return NotificationBuilder
     *
     * @see https://www.appboy.com/documentation/REST_API/#external-user-id
     */
    public function toUser($id)
    {
        return $this->toUsers($id);
    }

    /**
     * Set the segment to send to.
     *
     * @param string $segmentId
     *
     * @return NotificationBuilder
     *
     * @see https://www.appboy.com/documentation/REST_API/#segment-identifier
     */
    public function toSegment($segmentId)
    {
        $this->segmentId = $segmentId;
        return $this;
    }

    /**
     * Set the campaign identifier.
     *
     * @param string $campaignId
     *
     * @return NotificationBuilder
     *
     * @see https://www.appboy.com/documentation/REST_API/#campaign-identifier
     */
    public function setCampaign($campaignId)
    {
        $this->campaignId = $campaignId;
        return $this;
    }

    /**
     * Ignore the frequency capping for the campaign send.
     *
     * This will set the override_frequency_capping param to true, default is false.
     *
     * @return NotificationBuilder
     */
    public function ignoreFrequencyCapping()
    {
        $this->overrideFrequencyCapping = true;
        return $this;
    }

    /**
     * Respect the frequency capping for the campaign send.
     *
     * This will set the override_frequency_capping param to false, which is the default.
     *
     * @return NotificationBuilder
     */
    public function respectFrequencyCapping()
    {
        $this->overrideFrequencyCapping = false;
        return $this;
    }

    /**
     * Set the required subscription state of the audience.
     *
     * The state can be one of:
     * 1. opted_in - only users who have opted in
     * 2. subscribed - only users who have subscribed or are opted in
     * 3. all - all users, including unsubscribed users
     *
     * The default state is 'subscribed' only.
     *
     * @param string $state (opted_int|subscribed|all)
     *
     * @return NotificationBuilder
     */
    public function setSubscriptionState($state)
    {
        $this->recipientSubscriptionState = $state;
        return $this;
    }

    /**
     * Set the message push objects.
     *
     * @param array $messages
     *
     * @return NotificationBuilder
     */
    public function withMessages(array $messages)
    {
        $this->messages = $messages;
        return $this;
    }

    /**
     * Set the message object.
     *
     * @param string $type (apple_push|android_push|...)
     * @param array $message
     *
     * @return NotificationBuilder
     */
    public function withMessage($type, array $message)
    {
        $this->messages = [
            $type => $message,
        ];
        return $this;
    }

    /**
     * Add a message object.
     *
     * @param string $type (apple_push|android_push|...)
     * @param array $message
     *
     * @return NotificationBuilder
     */
    public function addMessage($type, array $message)
    {
        $this->messages[$type] = $message;
        return $this;
    }

    /**
     * Build the notification payload.
     *
     * @return array
     */
    public function build()
    {
        $params = [
            'external_user_ids' => $this->externalUserIds,
            'segment_id' => $this->segmentId,
            'campaign_id' => $this->campaignId,
            'override_frequency_capping' => $this->overrideFrequencyCapping,
            'recipient_subscription_state' => $this->recipientSubscriptionState,
            'messages' => $this->messages,
        ];

        return array_filter($params, function ($param) {
            return $param !== null;
        });
    }
}
