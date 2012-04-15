<?php
 
class TutorialRealmController extends GenericRealmController {
    public function createRobots($userId) {
        $realm = $this->getRealm();
        $count = UserRealmTable::getInstance()->countByRealmId($realm->getId());
        $robots = array();
        $sectorTable = SectorTable::getInstance();
        switch ($count) {
            case 1:

                break;
            case 2:
                $sector = $sectorTable->findOneByXAndY();
                $robot->setSector($sector);
                break;
        }
        foreach ($robots as $robot) {

        }
        return $robots;
    }

}
