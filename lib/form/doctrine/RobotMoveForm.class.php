<?php

class RobotMoveForm extends RobotForm {
	public function configure() {
		$this->useFields(array());
		
		$this->validatorSchema['x'] = new sfValidatorInteger();
		$this->validatorSchema['y'] = new sfValidatorInteger();
		$this->validatorSchema['relative'] = new sfValidatorBoolean();

		$this->validatorSchema->setPostValidator(new rsPostValidatorSector(array(
			'current' => $this->object->Sector
		)));
		parent::configure();
	}
	
	public function doUpdateObject($values) {
		parent::doUpdateObject($values);
		extract($values);
		$sector = $this->object->Sector;
		list($x, $y) = SectorTable::getInstance()->getEffectiveCoordinates($sector->x, $sector->y, $x, $y, $this->object->speed);
		$this->object->Sector = SectorTable::getInstance()->findOneByXAndY($x, $y);
	}
	
	public function getSuccessText() {
		return 'moved robot '.$this->getObject().' at %sector%';
	}

	public function getSuccessArguments() {
		return array(
			'sector' => (string)$this->object->Sector
		);
	}
	
}
