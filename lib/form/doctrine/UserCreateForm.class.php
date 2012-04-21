<?php

class UserCreateForm extends UserForm {
    public function configure() {
        parent::configure();

        $this->useFields(array('username', 'password'));
    }

    public function getSuccessText() {
        return
            'registered user %user%.'
    ;}

    public function getSuccessArguments() {
        return array(
            'user' => (string)$this->object,
        );
    }

}
