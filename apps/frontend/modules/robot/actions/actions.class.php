<?php

class robotActions extends rkActions {
    public function prepare() {
        $this->prepareAutoRealm();
        return parent::prepare();
    }


    public function prepareList() {
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
        $result = $this->object->doAction('Move', $this->x, $this->y);
        if ($result === false) {
			return $this->notice('robot %robot% is already at %sector%', array(
				'robot' => (string)$this->object,
				'sector' => (string)$this->object->getSector(),
			));
		}
        return $this->success('moved robot %robot% at sector %sector%', array(
            'robot' => (string)$this->object,
            'sector' => (string)$this->object->getSector()
        ));
	}

	public function prepareScan() {
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
        $this->prepareAutoObject();
		$this->prepareAutoObject('target_id', 'target');
        $this->argumentUnless('letter');
    }

    public function validateFire() {
        return $this->validateAutoObject($this->target, $this->letter);
    }

    public function executeFire(sfWebRequest $request) {
        $returnedTarget = $this->object->doAction('Fire', $this->target, $this->letter);
        return $this->success('fired at robot '.$this->target->__toStatusString().($returnedTarget? '' : ' and destroyed it'));
    }

    public function prepareRepair() {
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

    public function respond($success, $type, $text, array $arguments = array()) {
        if (!empty($this->object)) {
            $this->add('active_at', $this->object->getActiveAt());
        }
        return parent::respond($success, $type, $text, $arguments);
    }


}
