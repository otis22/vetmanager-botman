<?php

declare(strict_types=1);

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Support\Facades\DB;

final class ReviewConversation extends VetmanagerConversation
{
    public function askMark()
    {
        foreach (range(1,10) as $value) {
            $buttons[] = Button::create($value)->value($value);
        }
        $question = Question::create('Поставьте оценку от 1 до 10. Где 10 - отличный бот, буду рекомендовать')
            ->callbackId('select_rate')
            ->addButtons($buttons);

        $this->ask($question, function (Answer $answer) {
            $mark = $answer->getText();
            if (in_array($mark, range(1,10))) {
                $this->getBot()->userStorage()->save(['mark' => $mark]);
                $this->askFeature();
            } else {
                $this->say("Ошибка ввода");
                $this->askMark();
            }
        });
    }

    public function askFeature()
    {
        $this->ask("Какую одну функцию нужно добавить боту?", function (Answer $answer) {
            $feature = $answer->getText();
            DB::table('review')->insert([
                'user_id' => $this->getBot()->getUser()->getId(),
                'channel' => $this->getBot()->getDriver()->getName(),
                'mark' => intval($this->getBot()->userStorage()->get('mark')),
                'the_best_feature' => $feature,
                'created_at' => date("Y-m-d H:i:s"),
            ]);
            $this->say("Благодарим за отзыв!");
            $this->endConversation();
        });
    }

    public function run()
    {
        $this->askMark();
    }
}
