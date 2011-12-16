<?php

class mailActions extends rbActions {
	public function prepareSend() {
        $this->prepareAutoCreateForm();
        $this->pushFormParameters($this->form, array(
            'sender_id' => $this->getUser()->getUser()->getId()
        ));
	}

    public function validateSend() {
		return $this->validateAutoStatic();
	}
	
	public function executeSend(sfWebRequest $request) {
        return $this->executeAutoAjaxForm();
	}

    public function prepareReceive() {
        $this->argument('realm_id');
	}

    public function validateReceive() {
		return $this->validateAutoStatic();
	}

	public function executeReceive(sfWebRequest $request) {
        $this->object = MailTable::getInstance()->getNextUnreadMail($this->getUser()->getUser()->getId(), $this->realm_id);
        if ($this->object) {
            $this->object->setIsRead(true);
            $this->object->save();
            return $this->success('received mail #%id%.'.PHP_EOL.PHP_EOL.'From: %sender%'.PHP_EOL.'Subject: %type% %subject%'.PHP_EOL.($this->object->getText()? 'Text: %text%'.PHP_EOL : ''), array(
                'id' => (string)$this->object->getId(),
                'sender' => (string)$this->object->getSender(),
                'type' => $this->object->getType()? '['.$this->object->getType().']' : '',
                'subject' => (string)$this->object->getSubject(),
                'text' => (string)$this->object->getText(),
            ));
        } else {
            return $this->success('no unread messages');
        }
	}

}
