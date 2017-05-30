<?php

namespace Superbalist\Appboy;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

class Appboy {
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $uri;

    /**
     * @var string
     */
    protected $appGroupId;

    /**
     * Create a new Appboy client.
     *
     * **Example**
     *
     * ```php
     * $client = new \GuzzleHttp\Client();
     * $appboy = new Appboy($client, 'ABC123');
     * ```
     *
     * @param Client $client
     * @param string $appGroupId
     * @param string $uri
     */
    public function __construct(Client $client, $appGroupId, $uri = 'https://api.appboy.com')
    {
        $this->client = $client;
        $this->appGroupId = $appGroupId;
        $this->uri = $uri;
    }

    /**
     * Set the uri.
     *
     * @param string $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    /**
     * Return the uri.
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Set the App Group Identifier.
     *
     * The App Group Identifier can be found in the developer console settings.
     *
     * @param string $appGroupId
     * @see https://www.appboy.com/documentation/REST_API/#app-group-identifier-explanation
     */
    public function setAppGroupId($appGroupId)
    {
        $this->appGroupId = $appGroupId;
    }

    /**
     * Return the App Group Identifier.
     *
     * @return string
     */
    public function getAppGroupId()
    {
        return $this->appGroupId;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Return the full uri to an API end-point.
     *
     * @param string $endpoint
     * @return string
     */
    public function makeBaseUri($endpoint)
    {
        return rtrim($this->uri, '/') . '/' . trim($endpoint, '/');
    }

    /**
     * Create an HTTP request object.
     *
     * @param string $method
     * @param string $endpoint
     * @param mixed $body
     * @param array $headers
     * @return Request
     */
    protected function createRequest($method, $endpoint, $body = null, array $headers = [])
    {
        $uri = $this->makeBaseUri($endpoint);
        return new Request($method, $uri, $headers, $body);
    }

    /**
     * Make an HTTP POST request.
     *
     * @param string $endpoint
     * @param array $data
     * @return array
     */
    public function post($endpoint, array $data = [])
    {
        $body = json_encode($data);
        $request = $this->createRequest('POST', $endpoint, $body, ['Content-Type' => 'application/json']);
        return $this->sendRequest($request);
    }

    /**
     * Send an HTTP request.
     *
     * @param RequestInterface $request
     * @throws \Exception
     * @return array
     */
    protected function sendRequest(RequestInterface $request)
    {
        $response = $this->client->send($request);
        return json_decode($response->getBody(), true);
    }

    /**
     * Send a message.
     *
     * **Example**
     *
     * ```php
     * $appboy->sendMessage(
     *     (new NotificationBuilder())
     *         ->toUsers([1, 2])
     *         ->setCampaign('my_campaign')
     *         ->ignoreFrequencyCapping()
     *         ->setSubscriptionState('opted_in')
     *         ->withMessage(
     *             'apple_push',
     *             (new AppleMessageBuilder())
     *                 ->setAlert('Hello World!')
     *                 ->setSound('custom_sound')
     *                 ->withExtraAttributes(['is_test' => true])
     *                 ->setCategory('shipping_notification')
     *                 ->expiresAt(new \DateTime('2017-05-29 10:00:00', new \DateTimeZone('Africa/Johannesburg')))
     *                 ->setUri('http://superbalist.com')
     *                 ->setMessageVariation('group_a')
     *                 ->setAsset('file://image.jpg', 'jpg')
     *                 ->build()
     *         )
     *         ->build()
     * );
     * ```
     *
     * @param array $params
     * @return array
     */
    public function sendMessage(array $params)
    {
        $params['app_group_id'] = $this->appGroupId;
        return $this->post('messages/send', $params);
    }

    /**
     * Schedule a message.
     *
     * **Example**
     *
     * ```php
     * $appboy->scheduleMessage(
     *     (new ScheduledNotificationBuilder())
     *         ->toUsers([1, 2])
     *         ->setCampaign('my_campaign')
     *         ->ignoreFrequencyCapping()
     *         ->setSubscriptionState('opted_in')
     *         ->withMessage(
     *             'apple_push',
     *             (new AppleMessageBuilder())
     *                 ->setAlert('Hello World!')
     *                 ->setSound('custom_sound')
     *                 ->withExtraAttributes(['is_test' => true])
     *                 ->setCategory('shipping_notification')
     *                 ->expiresAt(new \DateTime('2017-05-29 10:00:00', new \DateTimeZone('Africa/Johannesburg')))
     *                 ->setUri('http://superbalist.com')
     *                 ->setMessageVariation('group_a')
     *                 ->setAsset('file://image.jpg', 'jpg')
     *                 ->build()
     *         )
     *         ->sendsAt(new \DateTime('2017-05-29 10:00:00', new \DateTimeZone('Africa/Johannesburg')))
     *         ->build()
     * );
     * ```
     *
     * @param array $params
     * @return array
     */
    public function scheduleMessage(array $params)
    {
        $params['app_group_id'] = $this->appGroupId;
        return $this->post('messages/schedule/create', $params);
    }
}