<?php

require_once dirname(__FILE__).'/base/UserInterfaceCommand.class.php';
require_once dirname(__FILE__).'/realm/RealmCreateCommand.class.php';

class TutorialCommand extends UserInterfaceCommand {
	public function getParserConfig() {
		return array(
			'description' => 'Begin a tutorial, learn how to play'
		) + parent::getParserConfig();
	}

	public function execute($options, $arguments) {
        $realmCreateCommand = new RealmCreateCommand($this->getConfig());
        $realmCreateCommand->setOptions(array(
            'controller_class' => 'MapAndMoveTutorialRealmController',
            'password' => md5(time()),
        ));
        $realmCreateCommand->setArguments(array(
            'name' => 'Map-and-move tutorial for user #'.$this->getUserId(),
        ));
        return $realmCreateCommand->run();
	}

}
