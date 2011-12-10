<?php

class rbActions extends tfExtendedActions {
	public function prepareFailed(rsException $e) {
		return $this->somethingFailed($e);
	}
	
	public function validateFailed(rsException $e) {
		return $this->somethingFailed($e);
	}
	
	public function somethingFailed(rsException $e) {
		return $this->failure($e->getText(), $e->getArguments());
	}

    public function restrictByRealmId() {
        $this->argumentUnless('realm_id');
        $this->failureUnless(UserRealmTable::getInstance()->findOneByRealmIdAndUserId($this->realm_id, $this->getUser()->getUser()->getId()), 'You don\'t have access to realm %realm%', array(
            'realm' => (string)RealmTable::getInstance()->find($this->realm_id)
        ));
        sfConfig::set('app_realm_id', $this->realm_id);
    }

}
