<?php

declare(strict_types=1);

namespace AgeChecker\Service;

use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use GuzzleHttp\Exception\GuzzleException;

class AgeCheckerClient
{
    private Client $client;
    private LoggerInterface $logger;

    private string $baseUrl = 'https://api.agechecker.net';
    public function __construct(LoggerInterface $logger)
    {
        $this->client = new Client(['base_uri' => $this->baseUrl]);
        $this->logger = $logger;
    }

    public function request(string $uuid)
    {
        try {
            $endpoint = '/v1/status/';

            $response = $this->client->request('GET', "{$this->baseUrl}{$endpoint}{$uuid}");
            return json_decode($response->getBody()->getContents());
        } catch (GuzzleException $e) {
            $this->logger->error(dump($e));
        }
    }
}