<?php

declare(strict_types=1);

use BarisCemant\Verimor\Sms\Config\SmsConfig;
use BarisCemant\Verimor\Sms\SmsClient;
use BarisCemant\Verimor\SwitchApi\Config\SwitchConfig;
use BarisCemant\Verimor\SwitchApi\Dto\OriginateRequest;
use BarisCemant\Verimor\SwitchApi\SwitchClient;
use BarisCemant\Verimor\WhatsApp\Config\WhatsAppConfig;
use BarisCemant\Verimor\WhatsApp\Dto\SendOtpRequest;
use BarisCemant\Verimor\WhatsApp\WhatsAppClient;

require __DIR__ . '/vendor/autoload.php';

$sdkFile = (string) (new ReflectionClass(SmsClient::class))->getFileName();
if (strpos(realpath($sdkFile) ?: '', realpath(__DIR__ . '/vendor') ?: 'missing') !== 0) {
    throw new RuntimeException('SDK was not loaded from the installed artifact');
}

$socket = stream_socket_server('tcp://127.0.0.1:0', $code, $message);
if ($socket === false) {
    throw new RuntimeException($message, $code);
}
$address = (string) stream_socket_get_name($socket, false);
fclose($socket);
$port = (int) substr((string) strrchr($address, ':'), 1);
$capture = tempnam(sys_get_temp_dir(), 'verimor-consumer-capture-');
$router = tempnam(sys_get_temp_dir(), 'verimor-consumer-router-');
if ($capture === false || $router === false) {
    throw new RuntimeException('Cannot create localhost fixtures');
}

$routerCode = <<<'PHP'
<?php
$headers = function_exists('getallheaders') ? getallheaders() : [];
file_put_contents(getenv('VERIMOR_CAPTURE_FILE'), json_encode([
    'method' => $_SERVER['REQUEST_METHOD'],
    'path' => parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),
    'query' => $_GET,
    'headers' => array_change_key_case($headers, CASE_LOWER),
    'body' => file_get_contents('php://input'),
]) . "\n", FILE_APPEND | LOCK_EX);
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ($path === '/v1/messages/otp') {
    http_response_code(202);
    header('Content-Type: application/json');
    echo '{"id":"00000000-0000-4000-8000-000000000001","status":"accepted"}';
} else {
    header('Content-Type: text/plain');
    echo $path === '/originate' ? 'call-42' : 'campaign-42';
}
PHP;
file_put_contents($router, $routerCode);

$process = proc_open(
    [PHP_BINARY, '-S', '127.0.0.1:' . $port, $router],
    [0 => ['file', '/dev/null', 'r'], 1 => ['file', '/dev/null', 'a'], 2 => ['file', '/dev/null', 'a']],
    $pipes,
    null,
    ['VERIMOR_CAPTURE_FILE' => $capture]
);
if (!is_resource($process)) {
    throw new RuntimeException('Cannot start localhost server');
}

$assert = static function (bool $condition, string $message): void {
    if (!$condition) {
        throw new RuntimeException($message);
    }
};

try {
    $deadline = microtime(true) + 3.0;
    do {
        $connection = @stream_socket_client('tcp://127.0.0.1:' . $port, $code, $message, 0.05);
        if (is_resource($connection)) {
            fclose($connection);
            break;
        }
        usleep(10000);
    } while (microtime(true) < $deadline);

    $baseUrl = 'http://127.0.0.1:' . $port;
    $sms = new SmsClient(new SmsConfig('consumer-user', 'consumer-pass', 'VERIMOR', $baseUrl));
    $smsResult = $sms->send([
        'messages' => [['msg' => 'Merhaba', 'dest' => '905000000000']],
    ]);
    $assert($smsResult === 'campaign-42', 'SMS response failed');
    $switch = new SwitchClient(new SwitchConfig('switch-key', $baseUrl));
    $assert($switch->originate(new OriginateRequest('905000000000', '101')) === 'call-42', 'Switch response failed');
    $whatsapp = new WhatsAppClient(new WhatsAppConfig('whatsapp-key', $baseUrl));
    $otp = $whatsapp->sendOtp(SendOtpRequest::fromArray(['templateName' => 'otp', 'to' => '905000000000']));
    $assert($otp->getStatus() === 'accepted', 'WhatsApp response failed');

    $requests = array_map(static function (string $line): array {
        return json_decode($line, true);
    }, file($capture, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: []);
    $assert(count($requests) === 3, 'Expected three product requests');
    $smsBody = json_decode($requests[0]['body'], true);
    $assert($smsBody['username'] === 'consumer-user' && $smsBody['password'] === 'consumer-pass', 'SMS auth failed');
    $assert($smsBody['source_addr'] === 'VERIMOR', 'SMS sender default failed');
    $assert($requests[1]['query']['key'] === 'switch-key', 'Switch auth failed');
    $assert($requests[2]['headers']['x-api-key'] === 'whatsapp-key', 'WhatsApp auth failed');
    fwrite(STDOUT, "Installed archive consumer passed for SMS, Switch and WhatsApp.\n");
} finally {
    proc_terminate($process);
    proc_close($process);
    @unlink($capture);
    @unlink($router);
}
