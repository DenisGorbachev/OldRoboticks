<?php

class botActions extends rkActions {
    public function prepareAdd() {
        $this->prepareAutoAddForm();
        $this->pushFormParameters($this->form, array(
            'realm_id' => $this->realm_id
        ));
        $this->appendFormParameters($this->form, $this->form->getDefaults());
    }

    public function validateAdd() {
        return $this->validateAutoStatic();
    }

    public function executeAdd(sfWebRequest $request) {
        set_time_limit(0);
        return $this->executeAutoAjaxForm();
    }

}
