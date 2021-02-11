<?php

declare(strict_types=1);

namespace Tests\Vetmanager\UserData;

use App\Vetmanager\UserData\ClinicUrl;
use App\Vetmanager\UserData\UserRepository\UserRepository;
use PHPUnit\Framework\TestCase;

class ClinicUrlTest extends TestCase
{
    private function userWithDomain(string $domainName): UserRepository
    {
        $user = $this->createMock(UserRepository::class);
        $user->method('getDomain')
            ->willReturn($domainName);
        return $user;
    }
    public function testAsStringNotEmptyToken(): void
    {
        $this->assertEquals(
            "https://mydomain.vetmanager.ru",
            (
                new ClinicUrl(
                    function ($domain): string {
                        return "https://{$domain}.vetmanager.ru";
                    },
                    $this->userWithDomain('mydomain'),
                )
            )->asString()
        );
    }

    public function testAsStringWithEmptyToken(): void
    {
        $this->expectException(\Exception::class);
        (
            new ClinicUrl(
                function ($domain) {return "https://{$domain}.vetmanager.ru";},
                $this->userWithDomain('')
            )
        )->asString();
    }
}
