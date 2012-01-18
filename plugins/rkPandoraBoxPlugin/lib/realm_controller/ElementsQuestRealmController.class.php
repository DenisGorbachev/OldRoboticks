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
        $count = UserRealmTable::getInstance()->countByRealmId($realm->getId());
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

    public function getWinningConditions()
    {
        return array_merge(array(
            array(
                'text' => 'To build 5 robots for each element: FIRE, WATER, EARTH, AIR (20 robots in total)',
            )
        ), parent::getWinningConditions());
    }

    public function isWinner(User $user)
    {
        $clearVictory = true;
        $robotTable = RobotTable::getInstance();
        foreach ($this->elements as $element=>$positions) {
            $elementCount = $robotTable->getActiveOwnedInRealmQuery($user->getId(), $this->getRealm()->getId())
                ->innerJoin('r.EffectiveWord ew')
                ->andWhere('ew.name = ?', $element)
            ->count();
            $clearVictory = $clearVictory && ($elementCount >= 5);
        }
        return $clearVictory || parent::isWinner($user);
    }

}
