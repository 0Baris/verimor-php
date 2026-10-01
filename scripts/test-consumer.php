<?php

declare(strict_types=1);

if (!isset($argv[1])) {
    fwrite(STDERR, "Usage: php scripts/test-consumer.php build/dist/package.zip\n");
    exit(2);
}

$archive = realpath($argv[1]);
if ($archive === false || substr($archive, -4) !== '.zip') {
    fwrite(STDERR, "A Composer ZIP archive is required.\n");
    exit(2);
}

$temporary = sys_get_temp_dir() . '/verimor-php-consumer-' . bin2hex(random_bytes(8));
if (!mkdir($temporary, 0700, true) && !is_dir($temporary)) {
    throw new RuntimeException('Cannot create consumer directory');
}

$remove = static function (string $path) use (&$remove): void {
    if (!is_dir($path)) {
        if (file_exists($path)) {
            unlink($path);
        }
        return;
    }
    foreach (scandir($path) ?: [] as $name) {
        if ($name !== '.' && $name !== '..') {
            $remove($path . '/' . $name);
        }
    }
    rmdir($path);
};

try {
    $template = (string) file_get_contents(dirname(__DIR__) . '/tests/Consumer/composer.json');
    $artifactDirectory = str_replace('\\', '/', dirname($archive));
    file_put_contents(
        $temporary . '/composer.json',
        str_replace('__ARCHIVE_DIR__', $artifactDirectory, $template)
    );
    copy(dirname(__DIR__) . '/tests/Consumer/consumer.php', $temporary . '/consumer.php');

    $composer = getenv('COMPOSER_BINARY') ?: 'composer';
    $install = 'cd ' . escapeshellarg($temporary)
        . ' && COMPOSER_CACHE_DIR=' . escapeshellarg($temporary . '/cache')
        . ' ' . escapeshellcmd($composer)
        . ' install --no-interaction --prefer-dist --no-dev 2>&1';
    exec($install, $installOutput, $installStatus);
    if ($installStatus !== 0) {
        throw new RuntimeException("Consumer install failed:\n" . implode("\n", $installOutput));
    }

    $run = 'cd ' . escapeshellarg($temporary)
        . ' && ' . escapeshellarg(PHP_BINARY) . ' consumer.php 2>&1';
    exec($run, $consumerOutput, $consumerStatus);
    if ($consumerStatus !== 0) {
        throw new RuntimeException("Consumer run failed:\n" . implode("\n", $consumerOutput));
    }
    fwrite(STDOUT, implode("\n", $consumerOutput) . "\n");
} finally {
    $remove($temporary);
}
