<?php

require_once dirname(__FILE__).'/../base/UserInterfaceCommand.class.php';

class RealmSelectCommand extends UserInterfaceCommand {
    public function getParserConfig() {
        return array(
            'description' => 'Select a realm'
        ) + parent::getParserConfig();
    }

    public function getArgumentConfigs() {
        return array(
            'realm_id' => array(
                'description' => 'ID of selected realm (example: 6)'
            )
        );
    }

    public function execute($options, $arguments) {
        $realmId = (int)$arguments['realm_id'];
        if ((string)$realmId != $arguments['realm_id']) {
            throw new RoboticksArgumentException('Invalid argument "realm_id": expected integer, but got "'.$arguments['realm_id'].'"');
        }
        $this->setVariable('realm_id', $realmId);
        $this->success('selected realm #'.$realmId);
    }

}
