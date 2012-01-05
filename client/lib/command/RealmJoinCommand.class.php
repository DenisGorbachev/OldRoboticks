<?php

require_once dirname(__FILE__).'/base/RealmCommand.class.php';

class RealmJoinCommand extends RealmCommand {
	public function getParserConfig() {
		return array(
			'description' => 'Join selected realm'
		) + parent::getParserConfig();
	}

    public function getArgumentConfigs() {
        return array_merge(parent::getArgumentConfigs(), array(
            'password' => array(
                'description' => 'Realm password (optional)',
                'optional' => true,
            )
        ));
    }

	public function execute($options, $arguments) {
        return $this->get('realm/join', array(
            'id' => $options['realm_id'],
            'password' => $arguments['password']
        ));
	}

}
