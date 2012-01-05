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

}
