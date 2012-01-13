<?php

require_once dirname(__FILE__).'/base/UserInterfaceCommand.class.php';

class StatusCommand extends UserInterfaceCommand {
	public function getParserConfig() {
		return array(
			'description' => 'Display various client parameters'
		) + parent::getParserConfig();
	}

	public function execute($options, $arguments) {
        $this->setOutputFormat('none');
        $response = $this->get('user/profile');
        $this->initOutputFormat();
        $this->table(array(
            array('Parameter', 'Value'),
            array('Host', $this->getHost()),
            array('Auth?', $response['success']? 'yes' : 'no'),
            array('Realm ID', $this->getVariable('realm_id', 'undefined')),
            array('Robot ID', $this->getVariable('robot_id', 'undefined')),
        ));
	}

}
