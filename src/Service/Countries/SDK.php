<?php

namespace App\Service\Countries;

use GuzzleHttp\Client;

class SDK
{
    const HOST = 'https://restcountries.eu/';

    private $client;

    public function __construct($host = null)
    {
        $this->client = new Client([
            'base_uri' => $host ?? self::HOST,
        ]);
    }

    public function countries()
    {
        $response = $this->client->get('/rest/v2/all?fields=name;alpha3Code;region');

        return json_decode($response->getBody()->getContents(), true);
    }
}
