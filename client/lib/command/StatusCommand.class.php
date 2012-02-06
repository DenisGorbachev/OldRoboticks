<?php

require_once dirname(__FILE__).'/base/UserInterfaceCommand.class.php';

class StatusCommand extends UserInterfaceCommand {
	public function getParserConfig() {
		return array(
			'description' => 'Display various client parameters'
		) + parent::getParserConfig();
	}

	public function execute($options, $arguments) {
        $maxlengths = array(10, 9);
        $this->tableFixedColumnWidth(array(
            array('Parameter', 'Value'),
            array('Robot ID', $this->getVariable('robot_id', 'undefined')),
            array('Realm ID', $this->getVariable('realm_id', 'undefined')),
            array('Server', $this->getHost()),
        ), false, $maxlengths);
        $this->setOutputFormat('none');
        $response = $this->get('user/profile');
        $this->initOutputFormat();
        $this->tableFixedColumnWidth(array(
            array('Logged in?', $response['success']? 'yes' : 'no'),
        ), false, $maxlengths);
        return true;
	}

}
