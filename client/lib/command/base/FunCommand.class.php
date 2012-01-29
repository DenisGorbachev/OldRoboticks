<?php

require_once dirname(__FILE__).'/RobotCommand.class.php';

abstract class FunCommand extends RobotCommand {
	public function getOptionConfigs() {
		return array_merge(parent::getOptionConfigs(), array(
			'steps' => array(
				'short_name' => '-s',
				'long_name' => '--steps',
				'description' => 'Advance the function this number of steps',
				'action' => 'StoreInt',
                'default' => null
			)
		));
	}

    public function execute($options, $arguments) {
           while (is_null($options['steps']) || $options['steps']--) {
               $result = $this->step($options, $arguments);
               if ($result === false) {
                   break;
               }
           }
   	}

    abstract public function step($options, $arguments);

}
