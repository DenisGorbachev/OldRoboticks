<?php
 
class RiderOnTheStormBotController extends BaseBotController {
    public $sectorPercents = array(
        array(0.2,0.2),
        array(0.2,0.8),
        array(0.8,0.8),
        array(0.8,0.2),
    );

    public function play() {
        foreach ($this->info['robots'] as $robotInfo) {
            $this->exec('select '.$robotInfo['id']);
            $nextSector = $this->info['plans'][$robotInfo['id']]['nextSector'];
            if ($this->getSquaredDistanceBetweenRobotAndSectorPercent($robotInfo, $this->sectorPercents[$nextSector]) == 0) {
                $nextSector++;
            }
            if ($nextSector >= count($this->sectorPercents)) {
                $nextSector = 0;
            }
            $result = $this->exec('mv '.$this->getSectorPercentX($this->sectorPercents[$nextSector]).','.$this->getSectorPercentY($this->sectorPercents[$nextSector]));
            $this->info['plans'][$robotInfo['id']]['nextSector'] = $nextSector;
        }
    }

    public function refresh() {
        parent::refresh();
        foreach ($this->info['robots'] as $robotInfo) {
            if (isset($this->info['plans'][$robotInfo['id']])) {
                continue;
            }
            $minDistance = SectorTable::getInstance()->getSquaredDistanceBetweenCoordinates(0, 0, $this->info['realm']['width'], $this->info['realm']['height']);
            $nearestSector = 0;
            for ($i = 0; $i < count($this->sectorPercents); $i++) {
                $distance = $this->getSquaredDistanceBetweenRobotAndSectorPercent($robotInfo, $this->sectorPercents[$i]);
                if ($distance < $minDistance) {
                    $nearestSector = $i;
                    $minDistance = $distance;
                }
            }
            $this->info['plans'][$robotInfo['id']] = array(
                'nextSector' => $nearestSector
            );
        }
    }

    public function getSquaredDistanceBetweenRobotAndSectorPercent($robotInfo, $sectorPercent) {
        return SectorTable::getInstance()->getSquaredDistanceBetweenCoordinates($robotInfo['Sector']['x'], $robotInfo['Sector']['y'], $this->getSectorPercentX($sectorPercent), $this->getSectorPercentY($sectorPercent));
    }

    public function getSectorPercentX($sectorPercent) {
        return round($this->info['realm']['width'] * $sectorPercent[0]);
    }

    public function getSectorPercentY($sectorPercent) {
        return round($this->info['realm']['height'] * $sectorPercent[1]);
    }

}
