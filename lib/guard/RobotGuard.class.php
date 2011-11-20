<?php

class RobotGuard extends BaseGuard {
	public static function canList() {
		return true;
	}
	
    public function checkIsOwner() {
	    if (!$this->isOwner()) {
			throw new tfSanityException('Robot %robot% is not owned by you.', array(
				'robot' => (string)$this->object
			));
		}
		return true;
    }
	
    public function checkIsMobile() {
    	if (!$this->object->speed) {
			throw new tfSanityException('Robot %robot% is immobile.', array(
				'robot' => (string)$this->object
			));
		}
		return true;
    }
	
	public function canMove() {
		$this->checkIsOwner();
		$this->checkIsMobile();
		return true;
	}

	public function canScan() {
		$this->checkIsOwner();
		return true;
	}
	
}
