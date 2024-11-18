<?php

namespace App\Services;

use GuzzleHttp\Client;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Http;
use Firebase\JWT\JWT;

class FirebaseService
{
    protected $client;
    protected $databaseURL;

    protected $serviceAccountPath;

    protected $firebaseProjectId;
    public function __construct()
    {
        $this->client = new Client();
        $this->databaseURL = env('FIREBASE_DATABASE_URL');
        $this->serviceAccountPath = storage_path('app/google-service-account.json');
        $this->firebaseProjectId = config('app.FIREBASE_PROJECT_ID');
    }

    public function getAccessToken()
    {
        $serviceAccountPath = storage_path('app/google-service-account.json');

        if (!file_exists($serviceAccountPath)) {
            throw new \Exception("Service account JSON file not found at: {$serviceAccountPath}");
        }

        $serviceAccount = json_decode(file_get_contents($serviceAccountPath), true);

        $now = time();
        $jwtPayload = [
            "iss" => $serviceAccount["client_email"],
            "scope" => "https://www.googleapis.com/auth/firebase.messaging",
            "aud" => $serviceAccount["token_uri"],
            "iat" => $now,
            "exp" => $now + 3600,
        ];

        $jwt = JWT::encode($jwtPayload, $serviceAccount["private_key"], "RS256");

        $client = new Client();
        $response = $client->post($serviceAccount["token_uri"], [
            'form_params' => [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ],
            'timeout' => 15,
            'verify' => false,
        ]);

        $responseBody = json_decode($response->getBody(), true);

        if (isset($responseBody['access_token'])) {
            return $responseBody['access_token'];
        }

        throw new \Exception('Failed to fetch access token');
    }

    public function getData($path)
    {
        $url = $this->databaseURL . '/' . $path . '.json';
        $response = $this->client->get($url);
        return json_decode($response->getBody(), true);
    }

    /*public function setData($path, $data)
    {
        $url = $this->databaseURL . '/' . $path . '.json';
        $response = $this->client->put($url, [
            'json' => $data,
        ]);
        return json_decode($response->getBody(), true);
    }*/

    public function setData($data, $User='')
    {
        $url = "https://fcm.googleapis.com/v1/projects/{$this->firebaseProjectId}/messages:send";
        $accessToken = $this->getAccessToken();

        $data = [
            "message" => [
                "token" => $User->fcm_token,
                "notification" => [
                    "title" => $data['title'],
                    "body" => $data['MESSAGE'],
                ],
            ],
        ];

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }

        curl_close($ch);

        return json_decode($response, true);
    }

}
