<?php

class RealmGuard extends BaseGuard {
	public static function canCreate() {
        return true;
    }

    public function canEdit() {
        $this->checkIsOwner();
        return true;
    }

    public function canShow() {
        return true;
    }

    public function canJoin($password) {
        $this->checkPassword($password);
        return true;
    }

    public function canWin() {
        $this->checkIsMember();
        $this->checkIsWinner();
        return true;
    }

    public function checkIsOwner() {
	    if (!$this->isOwner('owner_id')) {
			throw new rsSanityException('Realm %realm% is not owned by you.', array(
				'realm' => (string)$this->object
			));
		}
		return true;
    }

    public function checkIsMember() {
	    if (!$this->getObject()->isMember($this->getUser())) {
			throw new rsSanityException('You are not a member of realm %realm%.', array(
				'realm' => (string)$this->object
			));
		}
		return true;
    }

    public function checkIsWinner() {
	    if (!$this->getObject()->getController()->isWinner($this->getUser()->getUser())) {
			throw new rsSanityException('You haven\'t met winning conditions for realm %realm%: %winning_conditions%.', array(
				'realm' => (string)$this->object,
                'winning_conditions' => $this->getObject()->getController()->getWinningConditions(),
			));
		}
		return true;
    }

    public function checkPassword($password) {
	    if (!$this->getObject()->checkPassword($password)) {
			throw new rsSanityException('Wrong password for realm %realm%.', array(
				'realm' => (string)$this->object
			));
		}
		return true;
    }

}
