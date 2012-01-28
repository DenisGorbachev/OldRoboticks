<?php

require_once dirname(__FILE__) . '/PasswordCommand.class.php';

abstract class PasswordOptionalCommand extends PasswordCommand {
    public function getOptionConfigs() {
        return array_merge(parent::getOptionConfigs(), array(
            'no_password' => array(
                'short_name' => '-n',
                'long_name' => '--no-password',
                'description' => 'Allow empty password',
                'action' => 'StoreTrue',
                'default' => false
            ),
        ));
    }

    public function promptPasswordIfEmpty() {
        if (!$this->getOption('no_password')) {
            parent::promptPasswordIfEmpty();
        }
    }

}
