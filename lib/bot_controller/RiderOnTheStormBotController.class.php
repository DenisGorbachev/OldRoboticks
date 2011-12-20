<?php
 
class RiderOnTheStormBotController extends BaseBotController {
    public function playInRealm($realmId, $realmInfo)
    {
        if (empty($realmInfo['robots'])) {
            // TODO: implement recovery
        }
        foreach ($realmInfo['robots'] as $robotId=>$robotInfo) {
            $this->exec('select '.$robotId);
            $this->exec('mv 5,5');
        }
        // TODO: setActiveAt
    }

}
