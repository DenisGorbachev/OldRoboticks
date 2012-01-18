<?php

class realmActions extends rkActions {
	public function prepareCreate() {
        $this->prepareAutoCreateForm();
        $this->pushFormParameters($this->form, array(
            'owner_id' => $this->getUser()->getUser()->getId()
        ));
        $this->appendFormParameters($this->form, $this->form->getDefaults());
	}

    public function validateCreate() {
		return $this->validateAutoStatic();
	}
	
	public function executeCreate(sfWebRequest $request) {
        set_time_limit(0);
        return $this->executeAutoAjaxForm();
	}

    public function prepareEdit() {
        $this->prepareAutoEditForm();
        $this->appendFormParameters($this->form, $this->form->getDefaults());
    }

    public function validateEdit() {
        return $this->validateAutoObject();
    }

    public function executeEdit(sfWebRequest $request) {
        return $this->executeAutoAjaxForm();
    }

    public function prepareShow() {
        $this->prepareAutoObject();
    }

    public function validateShow() {
        return $this->validateAutoObject();
    }

    public function executeShow(sfWebRequest $request) {
        return $this->success('realm '.$this->object->getToStringFormat().' [%width%x%height%] is '.($this->object->getPassword()? 'free to join' : 'password-protected').' and has %sectors_count% sectors, %users_count% users, %active_robots_count% active robots, %robots_count% total robots. The winning conditions are: %winning_conditions%',
            array_merge($this->object->toArray(), array(
                'sectors_count' => $this->object->getSectorsCount(),
                'users_count' => $this->object->getUsersCount(),
                'active_robots_count' => $this->object->getActiveRobotsCount(),
                'robots_count' => $this->object->getRobotsCount(),
                'winning_conditions' => $this->object->getController()->getWinningConditions(),
            ))
        );
    }

    public function prepareJoin() {
        $this->prepareAutoObject();
        $this->argument('password', '');
    }

    public function validateJoin() {
        return $this->validateAutoObject($this->password);
    }

    public function executeJoin(sfWebRequest $request) {
        $dbUser = $this->getUser()->getUser();
        if (!$this->object->isMember($dbUser)) {
            $this->object->getController()->addUser($dbUser);
        }
        return $this->success('joined realm %realm%.', array(
            'realm' => (string)$this->object
        ));
    }

    public function prepareAutoObject($parameter = 'id', $varname = 'object') {
        $result = parent::prepareAutoObject($parameter, $varname);
        sfConfig::set('app_realm_id', $this->object->getId());
        return $result;
    }

    public function prepareWin() {
        $this->prepareAutoObject();
    }

    public function validateWin() {
        return $this->validateAutoObject();
    }

    public function executeWin(sfWebRequest $request) {
        $this->object->doWin($this->getUser()->getUser()->getId());
        return $this->success('claimed victory over realm %realm%.', array(
            'realm' => (string)$this->object
        ));
    }

}
