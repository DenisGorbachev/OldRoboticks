<?php

class RealmGuard extends BaseGuard {
	public static function canCreate() {
        return true;
    }

    public function canEdit() {
        $this->checkIsOwner();
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

}
