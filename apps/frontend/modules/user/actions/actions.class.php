<?php

class userActions extends rkActions {
	public function prepareCreate() {
		return parent::prepareAutoCreateForm();
	}
	
	public function validateCreate() {
		return true;
	}
	
	public function executeCreate(sfWebRequest $request) {
		return $this->executeAutoAjaxForm();
	}
	
	public function validateLoginMessage() {
		return true;
	}
	
	public function executeLoginMessage(sfWebRequest $request) {
		return $this->failure('You should authenticate using the "login" command of your client.');
	}

	public function prepareLogin() {
		$this->object = UserTable::getInstance()->findOneByUsername($this->getRequestParameter('username'));
		$this->failureUnless($this->object, 'user not found');
		$this->argumentUnless('password');
		return true;
	;}
	
	public function validateLogin() {
		$this->failureUnless($this->object->checkPassword($this->password), 'wrong password');
		return true;
	}
	
	public function executeLogin() {
		$this->getUser()->login($this->object);
		return $this->success('authenticated as player %username%.', array(
			'username' => (string)$this->object
		));
	}
	
	public function prepareShow() {
		return $this->prepareAutoObject();
	}

	public function validateShow() {
		return true;
	}
	
	public function executeShow(sfWebRequest $request) {
		return $this->message($this->getPartial('user', array(
			'user' => $this->object
		)));
	}

	public function validateProfile() {
		return true;
	}
	
	public function executeProfile(sfWebRequest $request) {
		return $this->userInfo($this->getUser()->getUser());
	}

    public function userInfo(User $user) {
        return $this->success('User %user% is nice.', array(
            'user' => (string)$user
        ));
    }

}
