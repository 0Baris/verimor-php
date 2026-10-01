<?php

declare(strict_types=1);

$captureFile = getenv('VERIMOR_CAPTURE_FILE');
if (!is_string($captureFile) || $captureFile === '') {
    http_response_code(500);
    exit;
}
$host = $_SERVER['HTTP_HOST'] ?? '';
if (strpos($host, '127.0.0.1') !== 0 && strpos($host, 'localhost') !== 0) {
    http_response_code(400);
    exit;
}
$uri = (string) ($_SERVER['REQUEST_URI'] ?? '/');
$headers = function_exists('getallheaders') ? getallheaders() : [];
$record = [
    'method' => (string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'),
    'path' => (string) parse_url($uri, PHP_URL_PATH),
    'query' => $_GET,
    'headers' => $headers,
    'body' => (string) file_get_contents('php://input'),
];
file_put_contents($captureFile, json_encode($record) . "\n", FILE_APPEND | LOCK_EX);

$controlFile = getenv('VERIMOR_CONTROL_FILE');
$control = null;
if (is_string($controlFile) && is_file($controlFile)) {
    $decoded = json_decode((string) file_get_contents($controlFile), true);
    if (is_array($decoded) && isset($decoded['status'])) {
        $control = $decoded;
    }
}
if (is_array($control)) {
    $delay = (int) ($control['delayMilliseconds'] ?? 0);
    if ($delay > 0) {
        usleep($delay * 1000);
    }
    http_response_code((int) $control['status']);
    if (isset($control['contentType']) && is_string($control['contentType'])) {
        header('Content-Type: ' . $control['contentType']);
    }
    echo (string) ($control['body'] ?? '');
    return;
}

header('Content-Type: application/json');
$path = $record['path'];
if (strpos($path, '/v1/messages/') === 0) {
    http_response_code(202);
    echo '{"id":"00000000-0000-4000-8000-000000000001","status":"accepted"}';
} elseif ($path === '/health') {
    echo '{"status":"ok"}';
} else {
    echo '[]';
}
