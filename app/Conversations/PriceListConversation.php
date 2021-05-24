<?php


namespace App\Conversations;


class PriceListConversation extends VetmanagerConversation
{
    public function givePriceListLink()
    {
        $userId = $this->user()->getVmUserId();
        $domainName = $this->user()->getDomain();
        $md5 = md5($domainName . ":" . $userId);
        $this->say("https://vetmanager-botman.herokuapp.com/price/" . $md5);
        $this->endConversation();
    }

    public function run()
    {
        $this->givePriceListLink();
    }
}