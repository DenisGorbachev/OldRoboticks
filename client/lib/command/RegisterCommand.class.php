<?php

require_once dirname(__FILE__).'/base/PasswordCommand.class.php';

class RegisterCommand extends PasswordCommand {
    public function getParserConfig() {
        return array(
            'description' => 'Register new user'
        ) + parent::getParserConfig();
    }

    public function getArgumentConfigs() {
        return array(
            'username' => array(
                'description' => 'New user name'
            ),
        );
    }

    public function execute($options, $arguments) {
        return $this->postForm('user', 'user/create', array(
            'username' => $arguments['username'],
            'password' => $options['password'],
        ));
    }

}
