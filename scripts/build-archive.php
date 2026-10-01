<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$outputDirectory = $argv[1] ?? $root . '/build/dist';
if ($outputDirectory[0] !== '/') {
    $outputDirectory = $root . '/' . $outputDirectory;
}
if (!is_dir($outputDirectory) && !mkdir($outputDirectory, 0775, true) && !is_dir($outputDirectory)) {
    throw new RuntimeException('Cannot create archive directory');
}

$version = trim((string) file_get_contents($root . '/VERSION'));
if (!preg_match('/^(0|[1-9][0-9]*)\.(0|[1-9][0-9]*)\.(0|[1-9][0-9]*)$/D', $version)) {
    throw new RuntimeException('VERSION must contain a stable semantic version');
}

$command = 'cd ' . escapeshellarg($root)
    . ' && COMPOSER_ROOT_VERSION=' . escapeshellarg($version)
    . ' composer archive --format=zip --dir=' . escapeshellarg($outputDirectory)
    . ' --file=' . escapeshellarg('bariscemant-verimor-' . $version) . ' 2>&1';
exec($command, $output, $status);
if ($status !== 0) {
    throw new RuntimeException("Composer archive failed:\n" . implode("\n", $output));
}

$archive = $outputDirectory . '/bariscemant-verimor-' . $version . '.zip';
$phar = new \PharData($archive);
$composer = json_decode((string) $phar['composer.json']->getContent(), true);
if (!is_array($composer)) {
    throw new RuntimeException('Archive composer.json is invalid');
}
$composer['version'] = $version;
$phar['composer.json'] = json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";

fwrite(STDOUT, $archive . "\n");
