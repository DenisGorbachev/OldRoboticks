<?php

class botActions extends rkActions {
    public function prepareAutoObjectByName() {
        set_time_limit(180);
        $this->prepareAutoRealm();
        $this->prepareAutoObject('name', 'object', 'name');
    }

    public function prepareAdd() {
        $this->prepareAutoObjectByName();
    }

    public function validateAdd() {
        return $this->validateAutoObject();
    }

    public function executeAdd(sfWebRequest $request) {
        $this->realm->addBot($this->object);
        return $this->success('Added bot %bot% to realm %realm%', array(
            'bot' => (string)$this->object,
            'realm' => (string)$this->realm,
        ));
    }

}
