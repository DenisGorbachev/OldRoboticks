<?php

require_once dirname(__FILE__).'/../base/RealmCommand.class.php';

class RealmShowCommand extends RealmCommand {
    public function getParserConfig() {
        return array(
            'description' => 'Show info about selected realm'
        ) + parent::getParserConfig();
    }

    public function execute($options, $arguments) {
        return $this->get('realm/show', array(
            'id' => $options['realm_id']
        ));
    }

}
