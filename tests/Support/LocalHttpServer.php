<?php

declare(strict_types=1);

namespace BarisCemant\Verimor\Tests\Support;

use RuntimeException;

final class LocalHttpServer
{
    /** @var resource|null */ private $process;
    /** @var string */ private $captureFile;
    /** @var string */ private $controlFile;
    /** @var int */ private $port;

    public function __construct()
    {
        $socket = stream_socket_server('tcp://127.0.0.1:0', $errorCode, $errorMessage);
        if ($socket === false) {
            throw new RuntimeException('Cannot allocate localhost port: ' . $errorMessage, $errorCode);
        }
        $address = stream_socket_get_name($socket, false);
        fclose($socket);
        if (!is_string($address)) {
            throw new RuntimeException('Cannot determine localhost port');
        }
        $separator = strrchr($address, ':');
        if ($separator === false) {
            throw new RuntimeException('Cannot parse localhost port');
        }
        $this->port = (int) substr($separator, 1);
        $capture = tempnam(sys_get_temp_dir(), 'verimor-php-capture-');
        if ($capture === false) {
            throw new RuntimeException('Cannot create capture file');
        }
        $this->captureFile = $capture;
        $control = tempnam(sys_get_temp_dir(), 'verimor-php-control-');
        if ($control === false) {
            throw new RuntimeException('Cannot create control file');
        }
        $this->controlFile = $control;
        $router = __DIR__ . '/server.php';
        $command = [PHP_BINARY, '-S', '127.0.0.1:' . $this->port, $router];
        $descriptors = [
            0 => ['file', '/dev/null', 'r'],
            1 => ['file', '/dev/null', 'a'],
            2 => ['file', '/dev/null', 'a'],
        ];
        $process = proc_open($command, $descriptors, $pipes, null, [
            'VERIMOR_CAPTURE_FILE' => $this->captureFile,
            'VERIMOR_CONTROL_FILE' => $this->controlFile,
        ]);
        if (!is_resource($process)) {
            throw new RuntimeException('Cannot start localhost server');
        }
        $this->process = $process;
        $deadline = microtime(true) + 3.0;
        do {
            $connection = @stream_socket_client('tcp://127.0.0.1:' . $this->port, $code, $message, 0.05);
            if (is_resource($connection)) {
                fclose($connection);
                return;
            }
            usleep(10000);
        } while (microtime(true) < $deadline);
        $this->stop();
        throw new RuntimeException('Localhost server did not start');
    }

    public function url(): string
    {
        return 'http://127.0.0.1:' . $this->port;
    }

    public function clear(): void
    {
        file_put_contents($this->captureFile, '');
    }

    public function respond(
        int $status,
        string $body,
        ?string $contentType,
        int $delayMilliseconds = 0
    ): void {
        file_put_contents($this->controlFile, (string) json_encode([
            'status' => $status,
            'body' => $body,
            'contentType' => $contentType,
            'delayMilliseconds' => $delayMilliseconds,
        ]), LOCK_EX);
    }

    /** @return CapturedRequest[] */
    public function captured(): array
    {
        $lines = file($this->captureFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
        return array_map(static function (string $line): CapturedRequest {
            $value = json_decode($line, true);
            if (!is_array($value)) {
                throw new RuntimeException('Invalid captured request');
            }
            return CapturedRequest::fromArray($value);
        }, $lines);
    }

    public function stop(): void
    {
        if (is_resource($this->process)) {
            proc_terminate($this->process);
            proc_close($this->process);
            $this->process = null;
        }
        if (is_file($this->captureFile)) {
            unlink($this->captureFile);
        }
        if (is_file($this->controlFile)) {
            unlink($this->controlFile);
        }
    }

    public function __destruct()
    {
        $this->stop();
    }
}
