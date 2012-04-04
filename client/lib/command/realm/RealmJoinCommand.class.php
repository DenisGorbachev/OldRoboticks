<?php

require_once dirname(__FILE__).'/../base/PasswordOptionalCommand.class.php';

class RealmJoinCommand extends PasswordOptionalCommand {
    public function getParserConfig() {
        return array(
            'description' => 'Join selected realm'
        ) + parent::getParserConfig();
    }

    public function getArgumentConfigs() {
        return array_merge(parent::getArgumentConfigs(), array(
            'realm_id' => array(
                'description' => 'ID of realm to join',
            ),
        ));
    }

    public function execute($options, $arguments) {
        return $this->get('realm/join', array(
            'id' => $arguments['realm_id'],
            'password' => $options['password']
        ));
    }

}
