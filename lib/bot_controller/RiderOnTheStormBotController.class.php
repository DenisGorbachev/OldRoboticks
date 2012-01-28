<?php
 
class RiderOnTheStormBotController extends BaseBotController {
    public $sectorPercents = array(
        array(0.2,0.2),
        array(0.2,0.8),
        array(0.8,0.8),
        array(0.8,0.2),
    );

    public function play() {
        parent::play();
        $info = $this->getInfo();
        $realmWidth = $info['realm']['width'];
        $realmHeight = $info['realm']['height'];
        foreach ($info['robots'] as &$robotInfo) {
            $this->exec('select '.$robotInfo['id']);
            if (!isset($robotInfo['nextSectorIndex'])) {
                for ($i = 0; $i < count($this->sectorPercents); $i++) {
                    $targetX = round($realmWidth*$this->sectorPercents[$i][0]);
                    $targetY = round($realmHeight*$this->sectorPercents[$i][1]);
                    var_dump($targetX);
                    var_dump($targetY);
                    if ($robotInfo['Sector']['x'] == $targetX && $robotInfo['Sector']['y'] == $targetY) {
                        break;
                    }
                }
                $robotInfo['nextSectorIndex'] = $i;
            }
            $robotInfo['nextSectorIndex']++;
            if ($robotInfo['nextSectorIndex'] >= count($this->sectorPercents)) {
                $robotInfo['nextSectorIndex'] = 0;
            }
            $result = $this->exec('mv '.round($realmWidth*$this->sectorPercents[$robotInfo['nextSectorIndex']][0]).','.round($realmHeight*$this->sectorPercents[$robotInfo['nextSectorIndex']][1]));
            var_dump($result);
            var_dump($robotInfo['nextSectorIndex']);
        }
        $this->getBot()->setInfo($info);
        // TODO: setActiveAt
    }

}
