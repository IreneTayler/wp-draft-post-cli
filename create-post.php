#!/usr/bin/env php
<?php
/**
 * CLI script to create a draft post on a WordPress site via REST API.
 * Usage: php create-post.php <site_url> <username> <application_password>
 */

require __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;

if ($argc < 4) {
    echo "Usage: php {$argv[0]} <site_url> <username> <password>\n";
    exit(1);
}

$site = rtrim($argv[1], '/');
$username = $argv[2];
$password = $argv[3];

$client = new Client([
    'base_uri' => $site,
    'timeout' => 10,
    'headers' => ['Accept' => 'application/json'],
]);

$auth = [$username, $password];

$data = [
    'title' => 'Test draft post',
    'content' => 'Hello from REST API',
    'status' => 'draft',
];

try {
    $response = $client->post('/wp-json/wp/v2/posts', [
        'auth' => $auth,
        'json' => $data,
    ]);

    $body = json_decode((string) $response->getBody(), true);

    if (isset($body['id'])) {
        echo "Draft post created successfully. ID: {$body['id']}\n";
        exit(0);
    }

    echo "Unexpected response from server.\n";
    exit(1);

} catch (ConnectException $e) {
    echo "Connection error: {$e->getMessage()}\n";
    exit(1);
} catch (RequestException $e) {
    if ($e->hasResponse()) {
        $status = $e->getResponse()->getStatusCode();
        $message = $e->getResponse()->getReasonPhrase();
        echo "HTTP {$status}: {$message}\n";
    } else {
        echo "Request error: {$e->getMessage()}\n";
    }
    exit(1);
} catch (\Exception $e) {
    echo "Error: {$e->getMessage()}\n";
    exit(1);
}