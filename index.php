<?php

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use GoCardlessPro\Webhook as GoCardlessWebhook;

require __DIR__ . '/vendor/autoload.php';

require_once 'WebhookSender.php';

$request = Request::createFromGlobals();
$payload = $request->getContent();
$signature = $request->headers->get('Webhook-Signature');

if (empty($payload)) {
    return new JsonResponse('No body provided', Response::HTTP_BAD_REQUEST);
}

if (!$signature) {
    $response = new JsonResponse(['error' => 'invalid webhook supplied'], 401);
    $response->send();
    return;
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$webhook_secret = $_ENV['WEBHOOK_SECRET'];

if (!$webhook_secret) {
    $response = new JsonResponse(['error' => 'webhook not configured'], 500);
    $response->send();
    return;
}

$valid_signature = GoCardlessWebhook::isSignatureValid(
    $payload,
    $signature,
    $webhook_secret
);

if (!$valid_signature) {
    $response = new JsonResponse(['error' => 'Invalid Token'], 498);
    $response->send();
    return;
}

$sender = new WebhookSender();

$options = [
    'body' => $request->getContent(),
    'headers' => [
        'Webhook-Signature' => $signature
    ]
];

$receiver_urls = explode("\n", file_get_contents('.receivers'));

foreach ($receiver_urls as $receiver) {
    $sender->sendTo($receiver, $options);
}

$response = new JsonResponse('', 204);
$response->send();
