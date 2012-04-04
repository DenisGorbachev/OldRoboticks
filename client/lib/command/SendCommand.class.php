<?php

require_once dirname(__FILE__).'/base/MailCommand.class.php';

class SendCommand extends MailCommand {
    public function getParserConfig() {
        return array_merge(parent::getParserConfig(), array(
            'description' => 'Send mail to other user'
        ));
    }

    public function getArgumentConfigs() {
        return array_merge(parent::getArgumentConfigs(), array(
            'recipient_id' => array(
                'description' => 'ID of user receiving the mail'
            ),
            'subject' => array(
                'description' => 'Mail subject'
            ),
            'text' => array(
                'description' => 'Mail text'
            ),
        ));
    }

    public function execute($options, $arguments) {
        if ($options['realm']) {
            $arguments['realm_id'] = $options['realm_id'];
        }
        return $this->postForm('mail', 'mail/send', $arguments);
    }

}
