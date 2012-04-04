<?php

/**
 * @method Robot getObject()
 */
class RobotGuard extends BaseGuard {
    public static function canList() {
        return true;
    }

    public function canMove($x, $y) {
        $this->checkIsOwner();
        $this->checkIsEnabled();
        $this->checkIsActive();
        $this->checkIsMobile();
        $this->checkSectorExists($x, $y);
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
        $this->checkSectorHasDrops(str_split($name));
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
        $this->checkSectorHasDrops(array($letter));
        $this->checkHasFreeCargoSpace();
        return true;
    }

    public function canFire(Robot $target, $letter) {
        $this->checkIsOwner();
        $this->checkIsEnabled();
        $this->checkIsActive();
        $this->checkIsLetter($letter);
        $this->checkIsFireableLetter($letter);
        $this->checkTargetIsInRange($target);
        $this->checkTargetHasLetter($target, $letter);
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
        $this->checkSectorHasDrops(array($letter));
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

    public function checkSectorExists($x, $y) {
        if (!SectorTable::getInstance()->findOneByXAndY($x, $y)) {
            throw new rsSanityException('sector with coordinates "%x%,%y%" doesn\'t exist', array(
                'x' => $x,
                'y' => $y,
            ));
        }
        return true;
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

    public function checkSectorHasDrops(array $drops) {
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

    public function checkTargetIsInRange(Robot $target) {
        if (!$this->getObject()->hasInFireableRange($target)) {
            throw new rsInsanityException('robot %robot% is not in fireable range', array(
                'robot' => $target->__toEnemyStatusString(),
            ));
        }
        return true;
    }

    public function checkTargetHasLetter(Robot $target, $letter) {
        if (!$target->hasLetter($letter)) {
            throw new rsInsanityException('robot %robot% doesn\'t have letter %letter%', array(
                'robot' => $target->__toEnemyStatusString(),
                'letter' => $letter,
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
