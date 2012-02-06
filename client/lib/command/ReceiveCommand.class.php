<?php

require_once dirname(__FILE__).'/base/MailCommand.class.php';

class ReceiveCommand extends MailCommand {
	public function getParserConfig() {
		return array_merge(parent::getParserConfig(), array(
			'description' => 'Receive own mail'
		));
	}

	public function execute($options, $arguments) {
        if ($options['realm']) {
            $arguments['realm_id'] = $options['realm_id'];
        }
    	return $this->get('mail/receive', $arguments);
	}
	
}
