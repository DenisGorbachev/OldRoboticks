<?php

/**
 * Robot
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    robotics
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Robot extends BaseRobot {
    public function __toString() {
        return sprintf($this->getTable()->getToStringFormat(), $this->id, $this->getStatus());
    }

    public function __toStatusString() {
        return sprintf($this->getTable()->getToStringFormat(), $this->id, $this->getStatus());
    }

    public function __toEnemyStatusString() {
        return sprintf($this->getTable()->getToEnemyStringFormat(), $this->id);
    }

    public function getName() {
        return (string)$this->getEffectiveWord();
    }

    public function getStatusArray() {
        return str_split($this->getStatus());
    }

    public function setStatus($status) {
        if (sfContext::hasInstance()) {
            $statusWordName = preg_replace('/_/u', '', $status);
            $effectiveWord = WordTable::getInstance()->findOneBy('name', $statusWordName);
            if ($effectiveWord) {
                $this->setEffectiveWord($effectiveWord);
                $this->setEffectiveWordId($effectiveWord->getId());
            } else {
                $this->setEffectiveWord(null);
                $this->setEffectiveWordId(null);
            }
        }
        return $this->_set('status', $status);
    }

    public function hasLetter($letter) {
        return $this->getTable()->hasDenotative($this->getStatus(), $letter);
    }

    public function hasDenotative($denotative) {
        return $this->getTable()->hasDenotative($this->getName(), $denotative);
    }

    public function hasFunction($meaning) {
        return $this->getTable()->hasFunction($this->getName(), $meaning);
    }

    public function getFunctions() {
        return $this->getTable()->getFunctionsForName($this->getName());
    }

    public function getEffectiveStatus() {
        return preg_replace('/_/u', '', $this->getStatus());
    }

    public function hasCargo($letter) {
        return mb_strpos($this->getCargo(), $letter) !== false;
    }

    public function hasFreeCargoSpace() {
        return mb_strlen($this->getCargo()) < $this->getTotalCargoSpace();
    }

    public function getTotalCargoSpace() {
        return $this->getTable()->getFunctionCount($this->getName(), 'transport');
    }

    public function canFire($letter) {
        return $this->getTable()->canFire($this->getName(), $letter);
    }

    public function isDisabled() {
        return !$this->getEffectiveWordId();
    }

    public function isInactive() {
        return (bool)$this->getInactiveTimeLeft();
    }

    public function getInactiveTimeLeft() {
        return max(0, $this->getActiveAt() - time());
    }

    public function hasLetterPinchedOut($letter) {
        return in_array($letter, array_diff_assoc($this->getWord()->getNameArray(), $this->getStatusArray()));
    }

    public function calculateSpeed() {
        return $this->isDisabled()? 0 : max(0, sfConfig::get('app_speed_limit') - sfConfig::get('app_speed_increment')*mb_strlen($this->getName()));
    }

    public function getFireableRange() {
        return $this->isDisabled()? 0 : max(0, sfConfig::get('app_fire_range_increment')*mb_strlen($this->getName()) - sfConfig::get('app_fire_range_limit'));
    }

    public function getScanBorders() {
        $base = $this->Sector;
        return array(
            'blX' => $base->x - sfConfig::get('app_scan_size'),
            'blY' => $base->y - sfConfig::get('app_scan_size'),
            'trX' => $base->x + sfConfig::get('app_scan_size'),
            'trY' => $base->y + sfConfig::get('app_scan_size'),
        );
    }

    public function hasInFireableRange(Sector $target) {
        return SectorTable::getInstance()->isInRange($this->getSector(), $target, $this->getFireableRange());
    }

    public function doAction($action /* ... */) {
        $arguments = func_get_args();
        array_shift($arguments); // removing $action
        $connection = $this->getTable()->getConnection();
        $connection->beginTransaction();
        try {
            $result = call_user_func_array(array($this, 'do'.$action.'Action'), $arguments);
            if ($result !== false) {
                $this->setActiveAt(time() + $this->getRealm()->getController()->getRobotInactivityInterval($this->getUserId(), $this->getRealmId()));
                $this->save();
            }
        } catch (Exception $e) {
            $connection->rollback();
            throw $e;
        }
        $connection->commit();
        return $result;
    }

    public function doMoveAction($targetSector) {
        $sectorTable = SectorTable::getInstance();
        $currentSector = $this->getSector();
        list($effectiveX, $effectiveY) = $sectorTable->getEffectiveCoordinates($currentSector->getX(), $currentSector->getY(), $targetSector->getX(), $targetSector->getY(), $this->getSpeed());
        $effectiveTargetSector = $sectorTable->findOneByXAndY($effectiveX, $effectiveY);
        $this->setSector($effectiveTargetSector);
        return $effectiveTargetSector->getId() == $currentSector->getId()? false : $effectiveTargetSector;
    }

    public function doExtractAction() {
        $sector = $this->getSector();
        $sector->addToDrops($sector->getLetter());
        $sector->save();
        return $sector->getLetter();
    }

    public function doDropAction($letter) {
        $sector = $this->getSector();
        $sector->addToDrops($letter);
        $this->setCargo(preg_replace('/'.preg_quote($letter, '/').'/u', '', $this->getCargo(), 1));
    }

    public function doPickAction($letter) {
        $sector = $this->getSector();
        $sector->removeFromDrops($letter);
        $this->setCargo($this->getCargo().$letter);
    }

    public function doAssembleAction($name) {
        $sector = $this->getSector();
        $sector->removeFromDrops($name);
        $sector->save();
        $robot = new Robot();
        $robot->setRealm($this->getRealm());
        $robot->setStatus($name);
        $robot->setUser($this->getUser());
        $robot->setSector($sector);
        $robot->save();
        return $robot;
    }

    public function doDisassembleAction(Robot $target) {
        $dispatcher = sfContext::getInstance()->getEventDispatcher();
        $dispatcher->notify(new sfEvent($this, 'robot.pre_do_disassemble_action', array(
            'target' => $target,
        )));
        $target->delete();
        $dispatcher->notify(new sfEvent($this, 'robot.post_do_disassemble_action', array(
            'target' => $target,
        )));
    }

    public function doFireAction(Sector $sector, $letter) {
        $dispatcher = sfContext::getInstance()->getEventDispatcher();
        $dispatcher->notify(new sfEvent($this, 'robot.pre_do_fire_action', array(
            'target' => $sector,
            'letter' => $letter
        )));
        $statistics = array('hit' => array(), 'destroyed' => array());
        foreach ($sector->getRobots() as $robot) {
            $robot->setStatus(preg_replace('/'.preg_quote($letter, '/').'/u', '_', $robot->getStatus()));
            if (preg_match('/[^_]/u', $robot->getStatus())) {
                $robot->save();
                $statistics['hit'][] = $robot;
                $dispatcher->notify(new sfEvent($this, 'robot.hit', array(
                    'target' => $robot,
                    'letter' => $letter,
                )));
            } else {
                $robot->delete();
                $statistics['destroyed'][] = $robot;
                $dispatcher->notify(new sfEvent($this, 'robot.destroyed', array(
                    'target' => $robot,
                    'letter' => $letter,
                )));
            }
        }
        $dispatcher->notify(new sfEvent($this, 'robot.post_do_fire_action', array(
            'target' => $sector,
            'letter' => $letter,
            'statistics' => $statistics
        )));
        return $statistics;
    }

    public function doRepairAction(Robot $target, $letter) {
        $dispatcher = sfContext::getInstance()->getEventDispatcher();
        $dispatcher->notify(new sfEvent($this, 'robot.pre_do_repair_action', array(
            'target' => $target,
            'letter' => $letter
        )));
        $statusArray = $target->getStatusArray();
        foreach (array_keys($target->getWord()->getNameArray(), $letter) as $key) {
            if ($statusArray[$key] == '_') {
                $sector = $this->getSector();
                $sector->removeFromDrops($letter);
                $sector->save();
                $statusArray[$key] = $letter;
                $target->setStatus(implode('',  $statusArray));
                $target->save();
                $dispatcher->notify(new sfEvent($this, 'robot.post_do_repair_action', array(
                    'target' => $target,
                    'letter' => $letter,
                )));
                return $target;
            }
        }
        throw new sfException('Can\'t repair letter "'.$letter.'" in robot '.$target.', something is wrong with validation');
    }

    public function doNoopAction() {
        // An action that is executed on rsInsanityException, leading to loss of turn
    }

    public function preInsert($event) {
        if (!$this->getWordId() && !$this->getWord()->getId()) {
            $word = WordTable::getInstance()->findOneBy('name', $this->getStatus());
            $this->setWord($word);
            $this->setEffectiveWord($word);
        }
        $this->speed = $this->calculateSpeed(); // preSave is invoked before preInsert
        parent::preInsert($event);
    }

    public function preSave($event) {
        $this->speed = $this->calculateSpeed();
        parent::preSave($event);
    }

    public function preDelete($event)
    {
        $sector = $this->getSector();
        $sector->addToDrops($this->getEffectiveStatus().$this->getCargo());
        $sector->save();
        parent::preDelete($event);
    }

}
