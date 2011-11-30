<?php

class robotActions extends rbActions {
	public function prepareList() {
		return $this->objects = RobotTable::getInstance()->getList($this->getUser()->getId());
	}
	
	public function validateList() {
		return $this->validateAutoStatic();
	}
	
	public function executeList(sfWebRequest $request) {
		$this->add('objects', $this->objects);
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
		$letter = $this->object->doExtract();
		return $this->success('extracted letter "'.$letter.'"');
	}

    public function prepareDrop() {
        return $this->prepareAutoObject()
            && $this->argumentUnless('letter');
    }

    public function validateDrop() {
        return $this->validateAutoObject($this->letter);
    }

    public function executeDrop(sfWebRequest $request) {
        $this->object->doDrop($this->letter);
        $this->object->save();
        return $this->success('dropped letter '.$this->letter);
    }

    public function preparePick() {
        return $this->prepareAutoObject()
            && $this->argumentUnless('letter');
    }

    public function validatePick() {
        return $this->validateAutoObject($this->letter);
    }

    public function executePick(sfWebRequest $request) {
        $this->object->doPick($this->letter);
        $this->object->save();
        return $this->success('picked letter '.$this->letter);
    }

    public function prepareAssemble() {
        return $this->prepareAutoObject()
            && $this->argumentUnless('name');
    }

    public function validateAssemble() {
        return $this->validateAutoObject($this->name);
    }

    public function executeAssemble(sfWebRequest $request) {
        $newborn = $this->object->doAssemble($this->name);
        return $this->success('assembled new robot '.$newborn);
    }

}
