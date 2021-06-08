<?php


namespace App\Vetmanager\MessageBuilder\Statistics;


use App\Http\Helpers\Rest\UsersApi;
use App\Vetmanager\Api\AuthenticatedClientFactory;
use App\Vetmanager\UserData\UserRepository\UserInterface;
use Illuminate\Support\Facades\DB;

class StatisticsMessageData
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

    public function asArray(): array
    {
        $clientFactory = new AuthenticatedClientFactory($this->user);
        $usersApi = new UsersApi($clientFactory->create());
        $firstName = $usersApi->byId($this->user->getVmUserId())['data']['user']['first_name'];
        $reviews = DB::table('review')->orderBy('id', 'desc')->take(10)->get()->toArray();
        $marks = array_column($reviews, 'mark');
        $avgReviewMark = (!empty($marks)) ? array_sum($marks) / count($marks) : "Оценок нет";
        $lastWeek = \Carbon\Carbon::today()->subDays(7);
        $events = DB::table('statistic')->where('created_at', '>=', $lastWeek)->get();
        $eventsForUser = $events->where('user_id', '=', $this->user->getId());
        return [
            'firstName' => $firstName,
            'avgMark' => $avgReviewMark,
            'eventsCount' => count($events->toArray()),
            'eventsForUser' => count($eventsForUser->toArray())
        ];
    }
}