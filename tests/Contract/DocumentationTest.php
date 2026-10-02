<?php

declare(strict_types=1);

namespace BarisCemant\Verimor\Tests\Contract;

use PHPUnit\Framework\TestCase;

final class DocumentationTest extends TestCase
{
    /** @dataProvider languageProvider */
    public function testEachLanguageDocumentsTheCompletePublicContract(string $language): void
    {
        $root = dirname(__DIR__, 2);
        $readme = (string) file_get_contents($root . ($language === 'tr' ? '/README.md' : '/README.en.md'));
        $files = glob($root . '/docs/' . $language . '/*.md') ?: [];
        $documentation = $readme;
        foreach ($files as $file) {
            $documentation .= "\n" . file_get_contents($file);
        }
        $needles = [
            'SMS', 'Switch', 'WhatsApp', 'send', 'balance', 'statusById', 'statusByCustomId',
            'originate', 'sendOtp', 'sendUtility', 'source_addr', '30', 'raw()',
            'VerimorApiException', '429',
        ];
        foreach ($needles as $needle) {
            self::assertStringContainsString($needle, $documentation, $language . ': ' . $needle);
        }
        if ($language === 'tr') {
            self::assertStringContainsString('otomatik retry yapmaz', $documentation);
            self::assertStringContainsString('yinelenen gönderim', $documentation);
            self::assertStringContainsString('resmî değildir', $documentation);
            self::assertStringContainsString('canlı Verimor servisine karşı henüz doğrulanmamıştır', $documentation);
        } else {
            self::assertStringContainsString('does not retry automatically', $documentation);
            self::assertStringContainsString('duplicate delivery', $documentation);
            self::assertStringContainsString('unofficial', $documentation);
            self::assertStringContainsString(
                'has not yet been validated against the live Verimor service',
                $documentation
            );
        }
    }

    public function testLanguageTreesAndOperationTablesMatch(): void
    {
        $root = dirname(__DIR__, 2);
        $turkish = array_map('basename', glob($root . '/docs/tr/*.md') ?: []);
        $english = array_map('basename', glob($root . '/docs/en/*.md') ?: []);
        sort($turkish);
        sort($english);
        self::assertSame($turkish, $english);
        self::assertCount(9, $turkish);

        foreach (['tr', 'en'] as $language) {
            $operations = (string) file_get_contents($root . '/docs/' . $language . '/operations.md');
            preg_match_all('/^\| (sms|switch|whatsapp) \|/m', $operations, $matches);
            self::assertCount(72, $matches[0], $language . ' operation rows');
        }
    }

    public function testExamplesCoverEveryConvenienceMethodAndUseEnvironmentCredentials(): void
    {
        $root = dirname(__DIR__, 2);
        $examples = glob($root . '/examples/*/*.php') ?: [];
        self::assertCount(6, $examples);
        $source = '';
        foreach ($examples as $example) {
            $content = (string) file_get_contents($example);
            self::assertStringContainsString('getenv(', $content, $example);
            self::assertStringNotContainsString('Generated\\', $content, $example);
            $source .= "\n" . $content;
        }
        foreach (['send(', 'balance(', 'statusById(', 'originate(', 'sendOtp(', 'sendUtility('] as $call) {
            self::assertStringContainsString($call, $source);
        }
    }

    /** @return array<string, array{string}> */
    public function languageProvider(): array
    {
        return ['Turkish' => ['tr'], 'English' => ['en']];
    }
}
