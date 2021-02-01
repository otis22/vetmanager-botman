<?php

declare(strict_types=1);

namespace Tests\Vetmanager\UserData;

use App\Vetmanager\UserData\ClinicUrl;
use BotMan\BotMan\BotMan;
use PHPUnit\Framework\TestCase;

class ClinicUrlTest extends TestCase
{
    private function botmanWithUserStorageWillReturnValue(string $value): BotMan
    {
        $bot = $this->createMock(BotMan::class);
        $bot->method('userStorage')
            ->willReturn(
                new class($value) {
                    private $val;
                    public function __construct($value)
                    {
                        $this->val = $value;
                    }
                    public function get(string $key)
                    {
                        return $this->val;
                    }
                }
            );
        return $bot;
    }
    public function testAsStringNotEmptyToken(): void
    {
        $this->assertEquals(
            "https://mydomain.vetmanager.ru",
            (
                new ClinicUrl(
                    $this->botmanWithUserStorageWillReturnValue('mydomain'),
                    function ($domain) {return "https://{$domain}.vetmanager.ru";}
                )
            )->asString()
        );
    }

    public function testAsStringWithEmptyToken(): void
    {
        $this->expectException(\Exception::class);
        (
            new ClinicUrl(
                $this->botmanWithUserStorageWillReturnValue(''),
                function ($domain) {return "https://{$domain}.vetmanager.ru";}
            )
        )->asString();
    }
}
