<?php

namespace Superbalist\Appboy;

use DateTime;

class ScheduledNotificationBuilder extends NotificationBuilder
{
    /**
     * @var DateTime
     */
    protected $sendDate;

    /**
     * Create a new ScheduledNotificationBuilder.
     *
     * **Example**
     *
     * ```php
     * $builder = new ScheduledNotificationBuilder();
     * $params = $builder->toUsers([1, 2])
     *     ->setCampaign('my_campaign')
     *     ->ignoreFrequencyCapping()
     *     ->setSubscriptionState('opted_in')
     *     ->withMessage(['alert' => 'Hello World!'])
     *     ->sendsAt(new \DateTime('2017-05-29 10:00:00', new \DateTimeZone('Africa/Johannesburg')))
     *     ->build();
     * ```
     */
    public function __construct()
    {

    }

    /**
     * Set the send date/time.
     *
     * @return ScheduledNotificationBuilder
     */
    public function sendsAt(DateTime $date)
    {
        $this->sendDate = $date;
        return $this;
    }

    /**
     * Build the notification payload.
     *
     * @return array
     */
    public function build()
    {
        $params = parent::build();

        if ($this->sendDate) {
            $params['schedule'] = [
                'time' => $this->sendDate->format('c'),
            ];
        }

        return $params;
    }
}