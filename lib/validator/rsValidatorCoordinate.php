<?php

class rsValidatorCoordinate extends sfValidatorInteger {
	protected function configure($options = array(), $messages = array()) {
		parent::configure($options, $messages);
		$this->addRequiredOption('field');
	}
	
	protected function doClean($value) {
		list($min, $max) = array_values(SectorTable::getInstance()->getMinMax($this->getOption('field')));
		$this->setOption('min', $min);
		$this->setOption('max', $max);
		return parent::doClean($value);
	}
}