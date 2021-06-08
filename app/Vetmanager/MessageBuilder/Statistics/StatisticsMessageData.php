<?php


namespace App\Vetmanager\MessageBuilder\Statistics;


use App\Http\Helpers\Rest\UsersApi;
use App\Vetmanager\Api\AuthenticatedClientFactory;
use App\Vetmanager\UserData\UserRepository\UserInterface;
use ElegantBro\Interfaces\Arrayee;
use Illuminate\Support\Facades\DB;

class StatisticsMessageData implements Arrayee
{
    /**
     * @var UserInterface
     */
    private $user;

    /**
     * StatisticsMessageData constructor.
     * @param UserInterface $user
     */
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    private function userFirstName(): string
    {
        $clientFactory = new AuthenticatedClientFactory($this->user);
        $usersApi = new UsersApi($clientFactory->create());
        return $usersApi->byId($this->user->getVmUserId())['data']['user']['first_name'];
    }

    private function avgReviewMark()
    {
        $reviews = DB::table('review')->orderBy('id', 'desc')->take(10)->get()->toArray();
        $marks = array_column($reviews, 'mark');
        return (!empty($marks)) ? array_sum($marks) / count($marks) : "Оценок нет";
    }

    private function eventsCount()
    {
        $lastWeek = \Carbon\Carbon::today()->subDays(7);
        $events = DB::table('statistic')->where('created_at', '>=', $lastWeek)->get();
        return count($events);
    }

    private function userEventsCount()
    {
        $lastWeek = \Carbon\Carbon::today()->subDays(7);
        $events = DB::table('statistic')->where([
            ['created_at', '>=', $lastWeek],
            ['user_id', '=', $this->user->getId()]
        ])->get();
        return count($events);
    }

    public function asArray(): array
    {
        return [
            'firstName' => $this->userFirstName(),
            'avgMark' => $this->avgReviewMark(),
            'eventsCount' => $this->eventsCount(),
            'eventsForUser' => $this->userEventsCount()
        ];
    }
}