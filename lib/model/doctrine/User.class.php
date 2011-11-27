<?php

/**
 * User
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    robotics
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class User extends BaseUser {
	public $salt = '343b02c254cc227f0bd461cd6d95cdc6';
	
	public function __toString() {
		return '#'.$this->id.' "'.$this->username.'"';
	}
	
	public function setPassword($password) {
		$this->_set('password', md5($password.$this->salt));
	}

	public function checkPassword($password) {
      return $this->password == md5($password.$this->salt);
	}

	public function countRobots() {
		return RobotTable::getInstance()->createQuery('r')
			->where('r.user_id = ?', $this->id)
			->count();
	}

    public function preInsert($event) {
        if (sfContext::hasInstance()) {
            $this->createRobot();
        }
        parent::preInsert($event);
    }

    public function restart() {
        RobotTable::getInstance()->deleteOwnedRobots($this->getId());
        $this->createRobot();
    }

    public function createRobot() {
        $robot = new Robot();
		$robot->setSector(SectorTable::getInstance()->getRandomSector());
		$this->Robots[] = $robot;
    }

}
