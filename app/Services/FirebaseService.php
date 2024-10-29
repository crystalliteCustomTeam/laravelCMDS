<?php

namespace App\Services;

use GuzzleHttp\Client;

class FirebaseService
{
    protected $client;
    protected $databaseURL;

    public function __construct()
    {
        $this->client = new Client();
        $this->databaseURL = env('FIREBASE_DATABASE_URL');
    }

    public function getData($path)
    {
        $url = $this->databaseURL . '/' . $path . '.json';
        $response = $this->client->get($url);
        return json_decode($response->getBody(), true);
    }

    public function setData($path, $data)
    {
        $url = $this->databaseURL . '/' . $path . '.json';
        $response = $this->client->put($url, [
            'json' => $data,
        ]);
        return json_decode($response->getBody(), true);
    }

    // Add more methods for delete, update, etc. as needed
}
