<?php

class realmActions extends rbActions {
	public function prepareCreate() {
        $this->prepareAutoCreateForm();
        $this->pushFormParameters($this->form, array(
            'owner_id' => $this->getUser()->getUser()->getId()
        ));
	}

    public function validateCreate() {
		return $this->validateAutoStatic();
	}
	
	public function executeCreate(sfWebRequest $request) {
        set_time_limit(0);
        return $this->executeAutoAjaxForm();
	}

}
