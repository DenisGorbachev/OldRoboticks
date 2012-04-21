<?php
 
class TutorialRealmController extends GenericRealmController {
    public function createRobots(User $user) {
        $userId = $user->getId();
        $realm = $this->getRealm();
        $count = UserRealmTable::getInstance()->countByRealmId($realm->getId());
        $robots = array();
        $sectorTable = SectorTable::getInstance();
        switch ($count) {
            case 1:
                $robots[] = $this->generateRobotByIds('TOASTER', $userId, $sectorTable->findOneByXAndY(8, 8)->getId());
                $robots[] = $this->generateRobotByIds('ZEBRA', $userId, $sectorTable->findOneByXAndY(4, 5)->getId());
                $robots[] = $this->generateRobotByIds('TE_', $userId, $sectorTable->findOneByXAndY(14, 14)->getId(), WordTable::getInstance()->findOneBy('name', 'TEA')->getId());
                break;
            case 2:
                $robots[] = $this->generateRobotByIds('PEAR', $userId, $sectorTable->findOneByXAndY(9, 9)->getId());
                for ($i = 0; $i < 3; $i++) {
                    $robots[] = $this->generateRobotByIds('CUCUMBER', $userId, $sectorTable->findOneByXAndY(7, 5)->getId());
                }
                $robots[] = $this->generateRobotByIds('ORANGE', $userId, $sectorTable->findOneByXAndY(6, 8)->getId());
                $robots[] = $this->generateRobotByIds('FEIJOA', $userId, $sectorTable->findOneByXAndY(2, 2)->getId());
                $robots[] = $this->generateRobotByIds('POTATO', $userId, $sectorTable->findOneByXAndY(15, 13)->getId());
                $robots[] = $this->generateRobotByIds('ZUCCHINI', $userId, $sectorTable->findOneByXAndY(7, 18)->getId());
                $robots[] = $this->generateRobotByIds('LIME', $userId, $sectorTable->findOneByXAndY(1, 19)->getId());
                $robots[] = $this->generateRobotByIds('PLUM', $userId, $sectorTable->findOneByXAndY(1, 19)->getId());
                $robots[] = $this->generateRobotByIds('CHERRY', $userId, $sectorTable->findOneByXAndY(1, 19)->getId());
                $robots[] = $this->generateRobotByIds('AVOCADO', $userId, $sectorTable->findOneByXAndY(2, 19)->getId());
                $robots[] = $this->generateRobotByIds('GOOSEBERRY', $userId, $sectorTable->findOneByXAndY(2, 19)->getId());
                $robots[] = $this->generateRobotByIds('POMEGRANATE', $userId, $sectorTable->findOneByXAndY(2, 19)->getId());
                $robots[] = $this->generateRobotByIds('LEMON', $userId, $sectorTable->findOneByXAndY(3, 19)->getId());
                $robots[] = $this->generateRobotByIds('BANANA', $userId, $sectorTable->findOneByXAndY(3, 19)->getId());
                $robots[] = $this->generateRobotByIds('APPLE', $userId, $sectorTable->findOneByXAndY(3, 19)->getId());
                break;
        }
        return $robots;
    }

}
