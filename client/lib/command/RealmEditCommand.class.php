<?php

require_once dirname(__FILE__).'/base/RealmCommand.class.php';

class RealmEditCommand extends RealmCommand {
	public function getParserConfig() {
		return array(
			'description' => 'Edit properties of a selected realm'
		) + parent::getParserConfig();
	}

	public function getArgumentConfigs() {
		return array_merge(parent::getArgumentConfigs(), array(
			'property_value_pair' => array(
				'description' => 'A pair in format "property=value", without spaces (is multiple)',
                'multiple' => true,
			)
		));
	}

	public function execute($options, $arguments) {
        $parameters = array();
        foreach ($arguments['property_value_pair'] as $pair) {
            $splinters = explode('=', $pair);
            $parameters[$splinters[0]] = $splinters[1];
        }
        return $this->postForm('realm', 'realm/edit?id='.$options['realm_id'], $parameters);
	}

}
