<?php

require_once dirname(__FILE__).'/base/UserInterfaceCommand.class.php';

class RealmCreateCommand extends UserInterfaceCommand {
	public function getParserConfig() {
		return array(
			'description' => 'Create a new realm'
		) + parent::getParserConfig();
	}

    public function getOptionConfigs() {
        return array_merge(parent::getOptionConfigs(), array(
            'controller_class' => array(
                'short_name' => '-c',
                'long_name' => '--controller-class',
                'description' => 'Controller class of new realm (defines the map layout and win conditions). List available controller classes using `rk realm:ls-cc`',
                'action' => 'StoreString',
                'default' => 'DeathmatchRealmController'
            ),
            'width' => array(
                'short_name' => '-w',
                'long_name' => '--width',
                'description' => 'Width of new realm. Can be any integer from 10 to 1000',
                'action' => 'StoreInt',
                'default' => 50
            ),
            'height' => array(
                'short_name' => '-h',
                'long_name' => '--height',
                'description' => 'Height of new realm. Can be any integer from 10 to 1000',
                'action' => 'StoreInt',
                'default' => 50
            )
        ));
    }

	public function getArgumentConfigs() {
		return array(
            'name' => array(
                'description' => 'New realm name (must be unique on the server)'
            ),
            'password' => array(
                'description' => 'New realm password'
            )
        );
	}
	
	public function execute($options, $arguments) {
        $this->echoln('Please wait, this may take a minute or two...');
        $this->postForm('realm', 'realm/create', array(
            'name' => $arguments['name'],
            'password' => $arguments['password'],
            'controller_class' => $options['controller_class'],
            'width' => $options['width'],
            'height' => $options['height'],
        ), array(
            CURLOPT_TIMEOUT => 180
        ));
	}

}
