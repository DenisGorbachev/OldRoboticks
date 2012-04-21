<?php

class botActions extends rkActions {
    public function prepareAutoObjectByName() {
        return $this->prepareAutoObject('name', 'object', 'name');
    }

    public function prepareAdd() {
        return $this->prepareAutoObjectByName();
    }

    public function validateAdd() {
        return $this->validateAutoObject();
    }

    public function executeAdd(sfWebRequest $request) {
        set_time_limit(0);
//        $this->bot->checkConnection();
        $this->bot->addToRealm($this->realm);
        return $this->success('Added bot %bot% to realm %realm%', array(
            'bot' => (string)$this->bot,
            'realm' => (string)$this->realm,
        ));
    }

}
