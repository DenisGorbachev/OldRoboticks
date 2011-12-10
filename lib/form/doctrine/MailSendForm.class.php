<?php

class MailSendForm extends MailForm
{
  public function configure()
  {
      parent::configure();
  }

    public function getSuccessText() {
        return 'sent mail %mail% to %recipient%';
    ;}

    public function getSuccessArguments() {
        return array(
            'mail' => (string)$this->getObject(),
            'recipient' => (string)$this->getObject()->getRecipient()
        );
    }

}
