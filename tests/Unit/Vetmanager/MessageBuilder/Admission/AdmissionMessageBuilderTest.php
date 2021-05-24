<?php


namespace Tests\Unit\Vetmanager\MessageBuilder\Admission;


use App\Exceptions\VmEmptyAdmissionsException;
use App\Vetmanager\MessageBuilder\Admission\AdmissionMessageBuilder;
use Tests\TestCase;

class AdmissionMessageBuilderTest extends TestCase
{
    public function testBuildMessage()
    {
        $admission = $this->admission();
        $messageBuilder = new AdmissionMessageBuilder($admission);
        $this->assertEquals(
            "2021-03-29 11:54:01\nКлиент: Тест Тестов\nТелефон: 0956666666\nКличка питомца: Тестик\nТип: Тестовый вид\nПорода: Тестовая порода\n\n",
            $messageBuilder->buildMessage()->getText()
        );
    }

    private function admission()
    {
        return [
                'admission_date' => '2021-03-29 11:54:01',
                'client' => [
                    'id' => 66,
                    'first_name' => 'Тестов',
                    'last_name' => 'Тест',
                    'cell_phone' => '0956666666'
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
            ];
    }
}