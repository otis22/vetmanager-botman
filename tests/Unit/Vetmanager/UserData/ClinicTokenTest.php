<?php

declare(strict_types=1);

namespace Tests\Vetmanager\UserData;

use App\Vetmanager\UserData\ClinicToken;
use BotMan\BotMan\BotMan;
use PHPUnit\Framework\TestCase;

class ClinicTokenTest extends TestCase
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
            "mykey",
            (
                new ClinicToken($this->botmanWithUserStorageWillReturnValue('mykey'))
            )->asString()
        );
    }

    public function testAsStringWithEmptyToken(): void
    {
        $this->expectException(\Exception::class);
        (
            new ClinicToken($this->botmanWithUserStorageWillReturnValue(''))
        )->asString();
    }
}
