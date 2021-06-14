<?php


namespace App\Vetmanager\MessageBuilder\Statistics;


use App\Http\Helpers\Rest\MedCardsApi;
use App\Http\Helpers\Rest\UsersApi;
use App\Vetmanager\Api\AuthenticatedClientFactory;
use App\Vetmanager\UserData\UserRepository\UserInterface;
use ElegantBro\Interfaces\Arrayee;
use Illuminate\Support\Facades\DB;

class StatisticsMedCardsMessageData implements Arrayee
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

    private function lastWeekMedCardsCount(): int
    {
        $clientFactory = new AuthenticatedClientFactory($this->user);
        $medCardsApi = new MedCardsApi($clientFactory->create());
        return count($medCardsApi->lastWeekMedCards($this->user->getVmUserId()));
    }

    private function preLastWeekMedCardsCount(): int
    {
        $clientFactory = new AuthenticatedClientFactory($this->user);
        $medCardsApi = new MedCardsApi($clientFactory->create());
        $monday = strtotime("last monday");
        $preLastWeekMonday = (date('W', $monday) == date('W') ? $monday-7*86400 : $monday)-7*86400;
        $preLastWeekSunday = strtotime(date("Y-m-d",$preLastWeekMonday)." +6 days");
        return count(
            $medCardsApi->medCardsByDateRangeAndUserId(
                $preLastWeekMonday,
                $preLastWeekSunday,
                $this->user->getVmUserId()
            )
        );
    }


    private function lastWeekMedCardsPercentageDiff(): int
    {
        $lastWeekCount = $this->lastWeekMedCardsCount();
        $preLastWeekCount = $this->preLastWeekMedCardsCount();
        return $lastWeekCount/$preLastWeekCount*100;
    }


    public function asArray(): array
    {
        return [
            'firstName' => $this->userFirstName(),
            'medicalCardsCount' => $this->lastWeekMedCardsCount(),
            'percentageDiff' => $this->lastWeekMedCardsPercentageDiff()
        ];
    }
}