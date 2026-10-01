<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$tag = $argv[1] ?? getenv('GITHUB_REF_NAME');
if (!is_string($tag) || !preg_match('/^v(0|[1-9][0-9]*)\.(0|[1-9][0-9]*)\.(0|[1-9][0-9]*)$/D', $tag, $matches)) {
    fwrite(STDERR, "Expected an exact stable tag such as v0.1.0.\n");
    exit(1);
}

$version = trim((string) file_get_contents($root . '/VERSION'));
$tagVersion = $matches[1] . '.' . $matches[2] . '.' . $matches[3];
if ($tagVersion !== $version) {
    fwrite(STDERR, sprintf("Tag %s does not match VERSION %s.\n", $tag, $version));
    exit(1);
}

$composer = json_decode((string) file_get_contents($root . '/composer.json'), true);
if (!is_array($composer) || array_key_exists('version', $composer)) {
    fwrite(STDERR, "composer.json must not contain a version field.\n");
    exit(1);
}

fwrite(STDOUT, sprintf("Release tag %s matches VERSION.\n", $tag));
