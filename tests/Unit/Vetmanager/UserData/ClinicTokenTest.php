<?php

declare(strict_types=1);

namespace Tests\Vetmanager\UserData;

use App\Vetmanager\UserData\ClinicToken;
use App\Vetmanager\UserData\UserRepository\UserInterface;
use PHPUnit\Framework\TestCase;

class ClinicTokenTest extends TestCase
{
    private function userWithToken(string $token): UserInterface
    {
        $user = $this->createMock(UserInterface::class);
        $user->method('getToken')
            ->willReturn($token);
        return $user;
    }
    public function testAsStringNotEmptyToken(): void
    {
        $this->assertEquals(
            "mykey",
            (
                new ClinicToken($this->userWithToken('mykey'))
            )->asString()
        );
    }

    public function testAsStringWithEmptyToken(): void
    {
        $this->expectException(\Exception::class);
        (
            new ClinicToken($this->userWithToken(''))
        )->asString();
    }
}
