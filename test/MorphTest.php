<?php

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Tsumari\Gooapi\Morph;

class MorphTest extends TestCase
{
    public function setUp(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();
    }

    public function testRead()
    {
        $mockGooAppId = $_ENV['GOO_APP_ID'] ?? '';
        $sentence = 'これはテスト用の文字列です。';

        $result = Morph::read($mockGooAppId, $sentence);
        $expected = [
            [["これ"], ["は"], ["てすと"], ["よう"], ["の"], ["もじれつ"], ["です"], [""]],
        ];
        $this->assertEquals($expected, $result);
    }

    public function testInvalidAppId()
    {
        $mockGooAppId = '';
        $sentence = 'これはテスト用の文字列です。';

        $result = Morph::read($mockGooAppId, $sentence);
        $expected = [
            [['これはテスト用の文字列です。']],
        ];
        $this->assertEquals($expected, $result);
    }
}
