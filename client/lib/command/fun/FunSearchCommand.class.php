<?php

require_once dirname(__FILE__) . '/../base/FunCommand.class.php';

class FunSearchCommand extends FunCommand {
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
		return array_merge(parent::getArgumentConfigs(), array(
			'letters' => array(
				'description' => 'A string of letters to search for (example: ABC)'
			)
		));
	}

    public function step($options, $arguments) {
        if (empty($this->cache['base'])) {

        }
        return $this->stepReport();
    }

    public function stepReport() {
        $command = new ReportCommand($this->getConfig());
        $command->setOptions(array(
            'for' => $this->getOption('in')
        )
        );
        $result = $command->run();
        return $result;
    }


}
