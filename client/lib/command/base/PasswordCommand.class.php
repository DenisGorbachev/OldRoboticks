<?php

require_once dirname(__FILE__) . '/UserInterfaceCommand.class.php';

abstract class PasswordCommand extends UserInterfaceCommand {
    public function getOptionConfigs() {
        return array_merge(parent::getOptionConfigs(), array(
            'password' => array(
                'short_name' => '-p',
                'long_name' => '--password',
                'description' => 'A secret phrase used for authentication',
                'action' => 'StoreString',
                'default' => ''
            )
        ));
    }

    public function preExecute($options, $arguments) {
        parent::preExecute($options, $arguments);
        $this->promptPasswordIfEmpty();
    }

    public function promptPasswordIfEmpty() {
        $this->setOption('password', $this->promptSilent($this->getOption('password')));
    }


}
