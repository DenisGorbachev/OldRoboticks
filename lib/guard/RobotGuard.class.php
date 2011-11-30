<?php

class RobotGuard extends BaseGuard {
	public static function canList() {
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

    public function canExtract() {
        $this->checkIsOwner();
        $this->checkHasFunction('extract');
        $this->checkSectorHasLetter();
        return true;
    }

    public function canAssemble($name) {
        $this->checkIsOwner();
        $this->checkHasFunction('assemble');
        $this->checkIsWord($name);
        $this->checkSectorHasDrops(str_split($name));
        return true;
    }

    public function canDrop($letter) {
        $this->checkIsOwner();
        $this->checkHasFunction('transport');
        $this->checkIsLetter($letter);
        $this->checkHasCargo($letter);
        return true;
    }

    public function canPick($letter) {
        $this->checkIsOwner();
        $this->checkHasFunction('transport');
        $this->checkIsLetter($letter);
        $this->checkSectorHasDrops(array($letter));
        $this->checkHasFreeCargoSpace();
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

    public function checkHasFunction($meaning) {
        if (!$this->object->hasFunction($meaning)) {
			throw new tfSanityException('Robot %robot% can\'t %function%', array(
				'robot' => (string)$this->object,
                'function' => $meaning
			));
		}
		return true;
    }

    public function checkSectorHasLetter() {
        if (!$this->object->getSector()->getLetter()) {
			throw new tfSanityException('Sector %sector% has no letters', array(
				'sector' => (string)$this->object->getSector()
			));
		}
		return true;
    }

    public function checkIsLetter($letter) {
        if (!WordTable::getInstance()->isLetter($letter)) {
			throw new tfSanityException('%letter% is not a letter', array(
				'letter' => $letter,
			));
		}
		return true;
    }

    public function checkIsWord($name) {
        if (!WordTable::getInstance()->findOneBy('name', $name)) {
			throw new tfSanityException('%name% is not a word', array(
				'name' => $name,
			));
		}
		return true;
    }

    public function checkSectorHasDrops(array $drops) {
        $diff = array_diff($drops, $this->object->getSector()->getDropsArray());
        if ($diff) {
			throw new tfSanityException('Sector %sector% has no %diff% drops', array(
				'sector' => (string)$this->object->getSector(),
                'diff' => implode(', ', $diff),
			));
		}
		return true;
    }

    public function checkHasCargo($letter) {
        if (!$this->getObject()->hasCargo($letter)) {
			throw new tfSanityException('Letter %letter% is not present in cargo', array(
				'letter' => $letter,
			));
		}
		return true;
    }

    public function checkHasFreeCargoSpace() {
        if (!$this->getObject()->hasFreeCargoSpace()) {
            $totalCargoSpace = $this->getObject()->getTotalCargoSpace();
            throw new tfSanityException('Robot %robot% can\'t carry more than %limit% letter%ending%', array(
				'robot' => (string)$this->getObject(),
                'limit' => $totalCargoSpace,
                'ending' => $totalCargoSpace == 1? '' : 's',
			));
		}
		return true;
    }


}
