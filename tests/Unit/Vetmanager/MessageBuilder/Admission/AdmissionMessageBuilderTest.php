<?php


namespace Tests\Unit\Vetmanager\MessageBuilder\Admission;


use App\Exceptions\VmEmptyAdmissionsException;
use App\Vetmanager\MessageBuilder\Admission\AdmissionMessageBuilder;
use Tests\TestCase;

class AdmissionMessageBuilderTest extends TestCase
{
    public function testBuildMessage()
    {
        $admissions = $this->admissions();
        $messageBuilder = new AdmissionMessageBuilder($admissions);
        $this->assertEquals(
            "2021-03-29 11:54:01\nКлиент: Тест Тестов\nКличка питомца: Тестик\nТип: Тестовый вид\nПорода: Тестовая порода\n\n",
            $messageBuilder->buildMessage()
        );
    }

    public function testBuildMessageWithEmptyTimesheet()
    {
        $admissions = [];
        $messageBuilder = new AdmissionMessageBuilder($admissions);
        $this->expectException(VmEmptyAdmissionsException::class);
        $messageBuilder->buildMessage();
    }


    private function admissions()
    {
        return [
            [
                'admission_date' => '2021-03-29 11:54:01',
                'client' => [
                    'first_name' => 'Тестов',
                    'last_name' => 'Тест',
                ],
                'pet' => [
                    'alias' => 'Тестик',
                    'pet_type_data' => [
                        'title' => 'Тестовый вид'
                    ],
                    'breed_data' => [
                        'title' => 'Тестовая порода'
                    ]
                ]
            ]
        ];
    }
}