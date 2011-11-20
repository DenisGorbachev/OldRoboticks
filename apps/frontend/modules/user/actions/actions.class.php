<?php

class userActions extends rbActions {
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
		return $this->message('You should authenticate using the "login" command of your client.');
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
	
	public function prepareRequest() {
		$this->argumentUnless('item');
	}

	public function validateRequest() {
		return $this->failureUnless(method_exists($this, 'executeRequest'.$this->item), 'You can\'t request an item "'.$this->item.'"');
	}
	
	public function executeRequest(sfWebRequest $request) {
		$this->forward($this->getModuleName(), 'request'.ucfirst($this->item));
	}

	public function validateRequestRobot() {
		return $this->failureUnless(!RobotTable::getInstance()->hasPlayableRobot($this->getUser()->getId()), 'You can\'t request another robot, because you already have at least one.');
	}
	
	public function executeRequestRobot(sfWebRequest $request) {
		$this->object = new Robot();
		$this->object->User = $this->getUser()->getUser();
		$this->object->Sector = SectorTable::getInstance()->getRandomSector();
		$this->object->save();
		return $this->message('A new robot '.$this->object.' created at '.$this->object->Sector);
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
		return $this->message($this->getPartial('profile', array(
			'user' => $this->getUser()->getUser()
		)));
	}

}