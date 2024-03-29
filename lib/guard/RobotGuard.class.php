<?php

/**
 * @method Robot getObject()
 */
class RobotGuard extends BaseGuard {
    public static function canList() {
        return true;
    }

    public function canMove($sector) {
        $this->checkIsOwner();
        $this->checkIsEnabled();
        $this->checkIsActive();
        $this->checkIsMobile();
        $this->checkSectorHasEnoughSpace($sector);
        return true;
    }

    public function canScan() {
        $this->checkIsOwner();
        $this->checkIsEnabled();
        return true;
    }

    public function canExtract() {
        $this->checkIsOwner();
        $this->checkIsEnabled();
        $this->checkIsActive();
        $this->checkHasFunction('extract');
        $this->checkSectorHasLetter();
        return true;
    }

    public function canAssemble($name) {
        $this->checkIsOwner();
        $this->checkIsEnabled();
        $this->checkIsActive();
        $this->checkHasFunction('assemble');
        $this->checkIsWord($name);
        $this->checkCurrentSectorHasDrops(str_split($name));
        $this->checkCurrentSectorHasEnoughSpace();
        return true;
    }

    public function canDisassemble(Robot $target) {
        $this->checkIsOwner();
        $this->checkIsEnabled();
        $this->checkIsActive();
        $this->checkHasFunction('disassemble');
        $this->checkIsNotHimself($target);
        $this->checkTargetIsInSameSector($target);
        try {
            $this->checkIsOwnerOfTarget($target);
        } catch (rsSanityException $e) {
            $this->checkTargetIsDisabled($target);
        }
        return true;
    }

    public function canDrop($letter) {
        $this->checkIsOwner();
        $this->checkIsEnabled();
        $this->checkIsActive();
        $this->checkHasFunction('transport');
        $this->checkIsLetter($letter);
        $this->checkHasCargo($letter);
        return true;
    }

    public function canPick($letter) {
        $this->checkIsOwner();
        $this->checkIsEnabled();
        $this->checkIsActive();
        $this->checkHasFunction('transport');
        $this->checkIsLetter($letter);
        $this->checkCurrentSectorHasDrops(array($letter));
        $this->checkHasFreeCargoSpace();
        return true;
    }

    public function canFire(Sector $sector, $letter) {
        $this->checkIsOwner();
        $this->checkIsEnabled();
        $this->checkIsActive();
        $this->checkIsLetter($letter);
        $this->checkIsFireableLetter($letter);
        $this->checkSectorIsInRange($sector);
        return true;
    }

    public function canRepair(Robot $target, $letter) {
        $this->checkIsOwner();
        $this->checkIsEnabled();
        $this->checkIsActive();
        $this->checkHasFunction('repair');
        $this->checkIsLetter($letter);
        $this->checkTargetIsInSameSector($target);
        $this->checkTargetHasLetterInWord($target, $letter);
        $this->checkTargetHasLetterPinchedOut($target, $letter);
        $this->checkCurrentSectorHasDrops(array($letter));
        return true;
    }

    public function checkIsOwner() {
        if (!$this->isOwner()) {
            throw new rsSanityException('robot %robot% is not owned by you.', array(
                'robot' => (string)$this->object
            ));
        }
        return true;
    }

    public function checkIsOwnerOfTarget($target) {
        if ($target->getUserId() != $this->getUser()->getId()) {
            throw new rsSanityException('target robot %robot% is not owned by you.', array(
                'robot' => $target->__toEnemyStatusString()
            ));
        }
        return true;
    }

    public function checkIsEnabled() {
        if ($this->getObject()->isDisabled()) {
            throw new rsSanityException('robot %robot% is disabled (its status is not a word)', array(
                'robot' => (string)$this->getObject(),
            ));
        }
        return true;
    }

    public function checkIsActive() {
        if ($this->getObject()->isInactive()) {
            throw new rsInactivityException('robot %robot% can\'t act for %amount% more seconds', array(
                'robot' => (string)$this->getObject(),
                'amount' => (string)$this->getObject()->getInactiveTimeLeft(),
            ));
        }
        return true;
    }
    
    public function checkIsMobile() {
        if (!$this->getObject()->getSpeed()) {
            throw new rsSanityException('robot %robot% is immobile.', array(
                'robot' => (string)$this->object
            ));
        }
        return true;
    }

    public function checkSectorHasEnoughSpace($sector) {
        if (!SectorTable::getInstance()->hasEnoughSpace($sector)) {
            throw new rsSanityException('sector %sector% doesn\'t have enough space (max %max% robots in sector)', array(
                'sector' => (string)$sector,
                'max' => sfConfig::get('app_space_limit'),
            ));
        }
        return true;
    }

    public function checkCurrentSectorHasEnoughSpace() {
        return $this->checkSectorHasEnoughSpace($this->getObject()->getSector());
    }

    public function checkHasFunction($meaning) {
        if (!$this->getObject()->hasFunction($meaning)) {
            throw new rsSanityException('robot %robot% can\'t %function%', array(
                'robot' => (string)$this->object,
                'function' => $meaning
            ));
        }
        return true;
    }

    public function checkSectorHasLetter() {
        if (!$this->getObject()->getSector()->getLetter()) {
            throw new rsSanityException('sector %sector% has no letters', array(
                'sector' => (string)$this->getObject()->getSector()
            ));
        }
        return true;
    }

    public function checkIsLetter($letter) {
        if (!WordTable::getInstance()->isLetter($letter)) {
            throw new rsSanityException('%letter% is not a letter', array(
                'letter' => $letter,
            ));
        }
        return true;
    }

    public function checkIsWord($name) {
        if (!WordTable::getInstance()->findOneBy('name', $name)) {
            throw new rsSanityException('%name% is not a word', array(
                'name' => $name,
            ));
        }
        return true;
    }

    public function checkCurrentSectorHasDrops(array $drops) {
        $diff = array_diff($drops, $this->getObject()->getSector()->getDropsArray());
        if ($diff) {
            throw new rsSanityException('Sector %sector% has no %diff% drops', array(
                'sector' => (string)$this->getObject()->getSector(),
                'diff' => implode(', ', $diff),
            ));
        }
        return true;
    }

    public function checkHasCargo($letter) {
        if (!$this->getObject()->hasCargo($letter)) {
            throw new rsSanityException('Letter %letter% is not present in cargo', array(
                'letter' => $letter,
            ));
        }
        return true;
    }

    public function checkHasFreeCargoSpace() {
        if (!$this->getObject()->hasFreeCargoSpace()) {
            $totalCargoSpace = $this->getObject()->getTotalCargoSpace();
            throw new rsSanityException('robot %robot% can\'t carry more than %limit% letter%ending%', array(
                'robot' => (string)$this->getObject(),
                'limit' => $totalCargoSpace,
                'ending' => $totalCargoSpace == 1? '' : 's',
            ));
        }
        return true;
    }

    public function checkIsFireableLetter($letter) {
        if (!$this->getObject()->canFire($letter)) {
            throw new rsInsanityException('can\'t fire at letter %letter%', array(
                'letter' => $letter,
            ));
        }
        return true;
    }

    public function checkSectorIsInRange(Sector $sector) {
        if (!$this->getObject()->hasInFireableRange($sector)) {
            throw new rsInsanityException('sector %sector% is not in fireable range', array(
                'sector' => (string)$sector,
            ));
        }
        return true;
    }

    public function checkTargetHasLetterInWord(Robot $target, $letter) {
        if (!$target->getWord()->hasLetter($letter)) {
            throw new rsSanityException('robot %robot% doesn\'t have letter %letter% in its base word', array(
                'robot' => $target->__toEnemyStatusString(),
                'letter' => $letter,
            ));
        }
        return true;
    }

    public function checkTargetHasLetterPinchedOut(Robot $target, $letter) {
        if (!$target->hasLetterPinchedOut($letter)) {
            throw new rsSanityException('robot %robot% doesn\'t have a pinched out letter %letter%', array(
                'robot' => $target->__toEnemyStatusString(),
                'letter' => $letter,
            ));
        }
        return true;
    }

    public function checkIsNotHimself(Robot $target) {
        if ($this->getObject()->getId() == $target->getId()) {
            throw new rsSanityException('robot %robot% can\'t do this to himself', array(
                'robot' => (string)$this->getObject(),
            ));
        }
        return true;
    }

    public function checkTargetIsInSameSector(Robot $target) {
        if ($this->getObject()->getSectorId() != $target->getSectorId()) {
            throw new rsSanityException('robot %robot% is not in the same sector', array(
                'robot' => $target->__toEnemyStatusString(),
            ));
        }
        return true;
    }

    public function checkTargetIsDisabled(Robot $target) {
        if (!$target->isDisabled()) {
            throw new rsSanityException('robot %robot% is not disabled (its status is a word)', array(
                'robot' => $target->__toEnemyStatusString(),
            ));
        }
        return true;
    }

}
