<?php


namespace App\Conversations;


use App\Http\Helpers\Rest\ClinicsApi;
use App\Vetmanager\Api\AuthenticatedClientFactory;
use App\Vetmanager\UserData\UserRepository\UserRepository;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class PriceListConversation extends VetmanagerConversation
{
    public function givePriceListLink($clinicId)
    {
        $userId = $this->user()->getVmUserId();
        $domainName = $this->user()->getDomain();
        $md5 = md5($domainName . $userId);
        $this->say("https://vetmanager-botman.herokuapp.com/price/" . $md5 . "/" . $clinicId . "/");
        $this->endConversation();
    }

    private function askClinicId()
    {
        $user = UserRepository::getById($this->user()->getId());
        $clientFactory = new AuthenticatedClientFactory($user);
        $clinics = (new ClinicsApi($clientFactory->create()))->all()['data']['clinics'];
        if (count($clinics) == 1) {
            $this->givePriceListLink($clinics[0]['id']);
        } else {
            foreach ($clinics as $clinic) {
                $text = $clinic['title'];
                $buttons[] = Button::create($text)->value($clinic['id']);
            }
            $question = Question::create('Выберите клинику')
                ->callbackId('select_clinic')
                ->addButtons($buttons);

            $this->ask($question, function (Answer $answer) {
                $clinicId = $answer->getText();
                try {
                    if (!is_numeric($clinicId)) {
                        throw new \Exception("Ошибка. Проверьте введенные данные!");
                    }
                    return $this->givePriceListLink($clinicId);
                } catch (\Exception $e) {
                    $this->say($e->getMessage());
                }
            });

        }
    }


    public function run()
    {
        $this->askClinicId();
    }
}