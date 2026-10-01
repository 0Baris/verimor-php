<?php

declare(strict_types=1);

namespace BarisCemant\Verimor\Tests\Contract;

use PHPUnit\Framework\TestCase;

final class ReleaseMetadataTest extends TestCase
{
    /** @dataProvider invalidTagProvider */
    public function testVersionGuardRejectsInvalidTags(string $tag): void
    {
        [$status] = $this->runGuard($tag);
        self::assertNotSame(0, $status, $tag . ' must be rejected');
    }

    public function testVersionGuardAcceptsMatchingStableTag(): void
    {
        [$status, $output] = $this->runGuard('v0.1.0');
        self::assertSame(0, $status, $output);
    }

    public function testComposerDoesNotEmbedAReleaseVersion(): void
    {
        $composer = json_decode((string) file_get_contents(dirname(__DIR__, 2) . '/composer.json'), true);
        self::assertIsArray($composer);
        self::assertArrayNotHasKey('version', $composer);
    }

    /** @return array<string, array{string}> */
    public function invalidTagProvider(): array
    {
        return [
            'missing v' => ['0.1.0'],
            'missing patch' => ['v0.1'],
            'prerelease' => ['v0.1.0-rc.1'],
            'leading zero major' => ['v00.1.0'],
            'leading zero minor' => ['v0.01.0'],
            'leading zero patch' => ['v0.1.00'],
            'mismatch' => ['v0.1.1'],
        ];
    }

    /** @return array{int, string} */
    private function runGuard(string $tag): array
    {
        $root = dirname(__DIR__, 2);
        $command = escapeshellarg(PHP_BINARY)
            . ' ' . escapeshellarg($root . '/scripts/check-version.php')
            . ' ' . escapeshellarg($tag) . ' 2>&1';
        exec($command, $lines, $status);
        return [$status, implode("\n", $lines)];
    }
}
