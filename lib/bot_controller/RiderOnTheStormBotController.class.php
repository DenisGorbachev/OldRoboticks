<?php
 
class RiderOnTheStormBotController extends BaseBotController {
    public function play() {
        parent::play();
        $info = $this->getInfo();
        foreach ($info['robots'] as $robotInfo) {
            $this->exec('select '.$robotInfo['id']);
            $result = $this->exec('mv 5,5');
            var_dump($result);
        }
        // TODO: setActiveAt
    }

}
