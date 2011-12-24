<?php
 
class RiderOnTheStormBotController extends BaseBotController {
    public function play() {
        parent::play();
        $info = $this->getInfo();
        var_dump($info);
        foreach ($info['robots'] as $robotId=>$robotInfo) {
            $this->exec('select '.$robotId);
            $this->exec('mv 5,5');
        }
        // TODO: setActiveAt
    }

}
