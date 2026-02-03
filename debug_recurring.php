<?php
// Debug script to test recurring transaction creation
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__.'/config/bootstrap.php';

use App\Kernel;
use Symfony\Component\HttpFoundation\Request;

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$kernel->boot();

// Simulate a POST request
$request = Request::create(
    '/api/v1/recurring-transaction/create',
    'POST',
    [],
    [],
    [],
    ['CONTENT_TYPE' => 'application/json'],
    json_encode([
        'id' => '65e3178c-0fb6-48c0-bc69-3286f05f9999',
        'type' => 'expense',
        'amount' => '123.45',
        'accountId' => '5808c601-b33f-420f-bf5b-07cda6abe8d3',
        'interval' => 'monthly',
        'startDate' => '2026-02-03 00:00:00',
        'description' => 'Gemini Debug Test',
    ])
);

try {
    $response = $kernel->handle($request);
    echo "Response status: " . $response->getStatusCode() . "\n";
    echo "Response body: " . $response->getContent() . "\n";
} catch (\Throwable $e) {
    echo "Exception: " . get_class($e) . "\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
