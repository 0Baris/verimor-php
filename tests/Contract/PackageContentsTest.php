<?php

declare(strict_types=1);

namespace BarisCemant\Verimor\Tests\Contract;

use PharData;
use PHPUnit\Framework\TestCase;
use RecursiveIteratorIterator;

final class PackageContentsTest extends TestCase
{
    public function testComposerArchiveContainsOnlyConsumerFiles(): void
    {
        $root = dirname(__DIR__, 2);
        $directory = sys_get_temp_dir() . '/verimor-php-archive-' . bin2hex(random_bytes(6));
        mkdir($directory, 0700, true);
        $command = escapeshellarg(PHP_BINARY)
            . ' ' . escapeshellarg($root . '/scripts/build-archive.php')
            . ' ' . escapeshellarg($directory) . ' 2>&1';
        exec($command, $output, $status);
        self::assertSame(0, $status, implode("\n", $output));
        $archives = glob($directory . '/*.zip') ?: [];
        self::assertCount(1, $archives);

        $files = $this->archiveFiles($archives[0]);
        self::assertContains('composer.json', $files);
        self::assertContains('LICENSE', $files);
        self::assertContains('README.md', $files);
        self::assertContains('README.en.md', $files);
        self::assertContains('contracts/operations.json', $files);
        self::assertNotEmpty(array_filter($files, static function (string $file): bool {
            return strpos($file, 'src/Sms/') === 0;
        }));

        $privateRepository = 'verimor-sdk-' . 'generator';
        foreach ($files as $file) {
            self::assertFalse((bool) preg_match(
                '#^(tests|\.github|vendor|spec|build|docker)/'
                    . '|^composer\.lock$|^scripts/generate|'
                    . preg_quote($privateRepository, '#') . '|\.ya?ml$#i',
                $file
            ), 'Forbidden archive entry: ' . $file);
        }
    }

    /** @return string[] */
    private function archiveFiles(string $archive): array
    {
        $files = [];
        $iterator = new RecursiveIteratorIterator(new PharData($archive));
        foreach ($iterator as $entry) {
            if ($entry->isFile()) {
                $files[] = str_replace('phar://' . $archive . '/', '', $entry->getPathname());
            }
        }
        sort($files);
        return $files;
    }
}
