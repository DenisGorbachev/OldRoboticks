<?php

class robotActions extends rbActions {
	public function prepareList() {
		return $this->objects = RobotTable::getInstance()->getList($this->getUser()->getId());
	}
	
	public function validateList() {
		return $this->validateAutoStatic();
	}
	
	public function executeList(sfWebRequest $request) {
		$list = array();
		foreach ($this->objects as $object) {
			$list[] = $object->toListItem();
		}
		$this->add('objects', $list);
		return $this->success('got own robots list');
	}
	
	public function prepareMove() {
		return $this->prepareAutoEditForm();
	}
	
	public function validateMove() {
		return $this->validateAutoObject();
	}
	
	public function executeMove(sfWebRequest $request) {
		return $this->executeAutoAjaxForm();
	}

	public function prepareScan() {
		return $this->prepareAutoObject();
	}
	
	public function validateScan() {
		return $this->validateAutoObject();
	}
	
	public function executeScan(sfWebRequest $request) {
		$borders = $this->object->getScanBorders();
		$this->add('borders', $borders);
		$arguments = $borders + array(
			'userId' => $this->getUser()->getId()
		);
		$this->add('results', call_user_func_array(array(SectorTable::getInstance(), 'getScanQueryResults'), $arguments));
		return $this->success('scanned surroundings from '.$this->object->getSector());
	}

	public function prepareExtract() {
		return $this->prepareAutoObject();
	}

	public function validateExtract() {
		return $this->validateAutoObject();
    }

	public function executeExtract(sfWebRequest $request) {
		$this->object->doExtract();
        $this->object->save();
		return $this->success('extracted letter "'.$this->object->getSector()->getLetter().'"');
	}

}
