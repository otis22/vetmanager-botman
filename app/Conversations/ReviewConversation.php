<?php

declare(strict_types=1);

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use Illuminate\Support\Facades\DB;

final class ReviewConversation extends Conversation
{
    public function askMark()
    {
        $this->ask("Поставьте оценку от 1 до 10. Где 10 - отличный бот, буду рекомендовать", function (Answer $answer) {
            $mark = $answer->getText();
            if ((1 <= $mark) && ($mark <= 10)) {
                $this->getBot()->userStorage()->save(compact('mark'));
                $this->askFeature();
            } else {
                $this->say("Ошибка ввода");
                $this->askReview();
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
                'mark' => $this->getBot()->userStorage()->get('mark'),
                'the_best_feature' => $feature,
                'created_at' => date("Y-m-d H:i:s"),
            ]);
            $this->say("Благодарим за отзыв!");
        });
    }

    public function run()
    {
        $this->askMark();
    }
}
