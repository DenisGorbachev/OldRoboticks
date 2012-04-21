<?php

require_once dirname(__FILE__).'/../base/RealmCommand.class.php';

class BotAddCommand extends RealmCommand {
    public function getParserConfig() {
        return array(
            'description' => 'Add a bot to the realm'
        ) + parent::getParserConfig();
    }

    public function getOptionConfigs() {
        return array_merge(parent::getOptionConfigs(), array(
            
        ));
    }

    public function getArgumentConfigs() {
        return array_merge(parent::getArgumentConfigs(), array(
            'name' => array(
                'description' => 'Controller class of a bot (defines its behavior). List available controller classes using `rk bot:ls-cc`',
            )
        ));
    }

    public function execute($options, $arguments) {
        $this->wait('adding a bot. This may take a minute or two...');
        $this->postForm('bot', 'bot/create', array(
            'controller_class' => $options['controller_class'],
        ), array(
            CURLOPT_TIMEOUT => 180
        ));
    }

}
