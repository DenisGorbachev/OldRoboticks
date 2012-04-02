<?php

require_once dirname(__FILE__) . '/FunWanderCommand.class.php';
require_once dirname(__FILE__) . '/../ReportCommand.class.php';

class FunSearchCommand extends FunWanderCommand {
    public $defaultFields = array(
        'robots' => 'Status',
        'letters' => 'Letter',
        'drops' => 'Drops',
    );

	public function getParserConfig() {
		return array_merge(parent::getParserConfig(), array(
			'description' => 'Search for given letters'
		));
	}

	public function getOptionConfigs() {
		return array_merge(parent::getOptionConfigs(), array(
			'in' => array(
				'short_name' => '-i',
				'long_name' => '--in',
				'description' => 'The report to search in. Possible values are: robots, letters, drops',
				'action' => 'StoreString',
                'default' => 'letters'
			),
            'field' => array(
                'short_name' => '-f',
                'long_name' => '--field',
                'description' => 'The field in report to search in. Possible values are various names of report columns.',
                'action' => 'StoreString'
            )
		));
	}
	
	public function getArgumentConfigs() {
        $argumentConfigs = parent::getArgumentConfigs();
        unset($argumentConfigs['command']);
        return array_merge($argumentConfigs, array(
			'regex' => array(
				'description' => 'A regular expression to look for the match (example: A|B|C)'
			)
		));
	}

    public function checkpoint() {
        $command = new ReportCommand($this->getConfig());
        $command->setOptions(array(
            'for' => $this->getOption('in')
        ));
        $result = $command->run();
        if ($result['success']) {
            
        }
        return $result;
    }


}
