<?php
namespace Nickolaspz\GraphQL;

use GuzzleHttp\Client as GuzzleClient;
use Nickolaspz\GrahpQL\Query;

class CRM
{
    private function authenticate()
    {
        try {
            $client = new GuzzleClient();
            $headers = ['Content-Type' => 'application/json'];

            $response = $client->request('POST', env('CRM_URL') . '/login', [
                'headers' => $headers,
                'body' => \json_encode([
                    "username" => env('CRM_USER'),
                    "password" => env('CRM_PASS')
                ])
            ]);

            if ($response->getStatusCode() == 200) {
                return json_decode($response->getBody())->token;
            }
        } catch(\Exception $e) {
            error_log('Nickolaspz\\GraphQL\\CRM:: ' . $e);
        }

        return false;
    }

    public function exec($payload, $variables = []) {
        $token = $this->authenticate();

        if ($token) {
            try {
                $client = new GuzzleClient();
                $headers = [
                    'Content-Type' => 'application/json',
                    'Authorization' => sprintf('Bearer %s', $token)
                ];
        
                # Escape double quotes + remove new lines and extra spaces
                $payload = addslashes(trim(preg_replace('/\s\s+/', ' ', $payload)));
        
                $response = $client->request('POST', env('CRM_URL') . '/graphql', [
                    'headers' => $headers,
                    'body' => sprintf('{ "query": "%s" ,"variables": %s }', $payload, json_encode($variables))
                ]);
        
                if ($response->getStatusCode() == 200) {
                    return json_decode($response->getBody());
                }
            } catch (\Exception $e) {
                error_log('Nickolaspz\\GraphQL\\CRM:: ' . $e);
            }
        } else {
            error_log('Nickolaspz\\GraphQL\\CRM:: ' . 'Erorr getting Bearer token from CRM');
        }

        return false;
    }

    public function query()
    {
        return new Query();
    }
}