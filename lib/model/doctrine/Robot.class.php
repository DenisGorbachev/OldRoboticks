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
		return $this->id.' ("'.$this->getCanonicalName().'")';
	}
	
	public function getCanonicalName() {
		return mb_strtolower($this->name);
	}
	
	public function toListItem() {
		return array(
			'name' => $this->getCanonicalName(),
			'sector' => $this->Sector->__toString(),
			'functions' => implode($this->getFunctions())
		) + $this->toArray(false);
	}
	
	public function getFunctions() {
		$functions = array();
		foreach ($this->getTable()->getFunctions() as $meaning=>$denotative) {
			if (mb_strpos($this->name, $denotative)) {
				$functions[$meaning] = $denotative;
			}
		}
		return $functions;
	}

	public function calculateSpeed() {
		preg_match_all('/'.implode('|', $this->getTable()->getVowels()).'/u', $this->name, $matches, PREG_SET_ORDER);
		return max(0, 3*count($matches) - mb_strlen($this->name));
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
	
	public function preSave($event) {
		$this->speed = $this->calculateSpeed();
	}
	
}
