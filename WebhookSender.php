<?php

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class WebhookSender
{
    /**
     * @param string $url
     * @param array $options
     * @throws TransportExceptionInterface
     */
    public function sendTo(string $url, array $options): void
    {
        $client = HttpClient::create();

        $client->request(
            'POST',
            $url,
            $options,
        );
    }
}