<?php

class robotActions extends rbActions {
	public function prepareList() {
        $this->restrictByRealmId();
		return ($this->objects = RobotTable::getInstance()->getList($this->getUser()->getId()));
	}

    public function validateList() {
		return $this->validateAutoStatic();
	}
	
	public function executeList(sfWebRequest $request) {
		$this->add('objects', $this->objects);
		return $this->success('got own robots list');
	}
	
	public function prepareMove() {
        $this->restrictByRealmId();
        $this->prepareAutoObject();
        $this->argumentUnless('x');
        $this->argumentUnless('y');
        $this->argument('relative', false);
        if ($this->relative) {
            $sector = $this->object->getSector();
            $this->x += $sector->getX();
            $this->y += $sector->getY();
        }
	;}
	
	public function validateMove() {
		return $this->validateAutoObject($this->x, $this->y);
	}
	
	public function executeMove(sfWebRequest $request) {
        $this->object->doAction('Move', $this->x, $this->y);
        return $this->success('moved robot '.$this->object.' at '.$this->object->getSector());
	}

	public function prepareScan() {
        $this->restrictByRealmId();
		$this->prepareAutoObject();
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
        $this->restrictByRealmId();
		$this->prepareAutoObject();
	}

	public function validateExtract() {
		return $this->validateAutoObject();
    }

	public function executeExtract(sfWebRequest $request) {
		$letter = $this->object->doAction('Extract');
		return $this->success('extracted letter "'.$letter.'"');
	}

    public function prepareDrop() {
        $this->restrictByRealmId();
        $this->prepareAutoObject();
        $this->argumentUnless('letter');
    }

    public function validateDrop() {
        return $this->validateAutoObject($this->letter);
    }

    public function executeDrop(sfWebRequest $request) {
        $this->object->doAction('Drop', $this->letter);
        return $this->success('dropped letter '.$this->letter);
    }

    public function preparePick() {
        $this->restrictByRealmId();
        $this->prepareAutoObject();
        $this->argumentUnless('letter');
    }

    public function validatePick() {
        return $this->validateAutoObject($this->letter);
    }

    public function executePick(sfWebRequest $request) {
        $this->object->doAction('Pick', $this->letter);
        return $this->success('picked letter '.$this->letter);
    }

    public function prepareAssemble() {
        $this->restrictByRealmId();
        $this->prepareAutoObject();
        $this->argumentUnless('name');
    }

    public function validateAssemble() {
        return $this->validateAutoObject($this->name);
    }

    public function executeAssemble(sfWebRequest $request) {
        $newborn = $this->object->doAction('Assemble', $this->name);
        return $this->success('assembled new robot '.$newborn);
    }

    public function prepareDisassemble() {
        $this->restrictByRealmId();
        $this->prepareAutoObject();
		$this->prepareAutoObject('target_id', 'target')
    ;}

    public function validateDisassemble() {
        return $this->validateAutoObject($this->target);
    }

    public function executeDisassemble(sfWebRequest $request) {
        $this->object->doAction('Disassemble', $this->target);
        return $this->success('disassembled robot '.$this->target);
    }

    public function prepareFire() {
        $this->restrictByRealmId();
        $this->prepareAutoObject();
		$this->prepareAutoObject('target_id', 'target');
        $this->argumentUnless('letter');
    }

    public function validateFire() {
        return $this->validateAutoObject($this->target, $this->letter);
    }

    public function executeFire(sfWebRequest $request) {
        $this->object->doAction('Fire', $this->target, $this->letter);
        return $this->success('fired at robot '.$this->target->__toStatusString());
    }

    public function prepareRepair() {
        $this->restrictByRealmId();
        $this->prepareAutoObject();
		$this->prepareAutoObject('target_id', 'target');
        $this->argumentUnless('letter');
    }

    public function validateRepair() {
        return $this->validateAutoObject($this->target, $this->letter);
    }

    public function executeRepair(sfWebRequest $request) {
        $this->object->doAction('Repair', $this->target, $this->letter);
        return $this->success('repaired letter "'.$this->letter.'" in robot '.$this->target->__toStatusString());
    }

    public function validateFailed(rsException $e) {
        if ($e instanceof rsInsanityException) {
            $this->object->doAction('Noop');
        }
        return parent::validateFailed($e);
    }

}
