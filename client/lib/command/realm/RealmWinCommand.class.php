<?php

require_once dirname(__FILE__).'/../base/RealmCommand.class.php';

class RealmWinCommand extends RealmCommand {
	public function getParserConfig() {
		return array(
			'description' => 'State yourself as a winner'
		) + parent::getParserConfig();
	}

	public function execute($options, $arguments) {
        return $this->get('realm/win', array(
            'id' => $options['realm_id']
        ));
	}

}
