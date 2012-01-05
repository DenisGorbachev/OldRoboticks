<?php
 
class ElementsQuestRealmController extends GenericRealmController {
    public $elements = array(
        'WATER' => array(0.2,0.2),
        'FIRE' => array(0.2,0.8),
        'EARTH' => array(0.8,0.8),
        'AIR' => array(0.8,0.2),
    );

    public function createRobot($userId) {
        $realm = $this->getRealm();
        $count = UserRealmTable::getInstance()->countByUserIdAndRealmId($userId, $realm->getId());
        $positionNumber = $count % 4 - 1;
        $positions = array_values($this->elements);
        $position = $positions[$positionNumber];
        $robot = parent::createRobot($userId);
        $sector = SectorTable::getInstance()->findOneByXAndY(ceil($realm->getWidth()*$position[0]), ceil($realm->getHeight()*$position[1]));
        $robot->setSector($sector);
        return $robot;
    }

    public function setElements($elements) {
        $this->elements = $elements;
    }

    public function getElements() {
        return $this->elements;
    }

}
