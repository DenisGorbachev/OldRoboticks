<?php

require_once dirname(__FILE__).'/base/UserInterfaceCommand.class.php';

class LsCommand extends UserInterfaceCommand {
	public function getParserConfig() {
		return array(
			'description' => 'List robots'
		) + parent::getParserConfig();
	}

	public function execute($options, $arguments) {
		if (($response = $this->get('robot/list'))) {
            $info = array(array('ID', 'Sector', 'Cargo', 'Funcs', 'Status', 'Name', 'Speed'));
			foreach ($response['objects'] as $robot) {
				$info[] = array(
                    $robot['id'],
                    $this->coords($robot['Sector']),
                    $robot['cargo'],
                    $robot['functions'],
                    $robot['status'],
                    $robot['Word']['name'],
                    $robot['speed'],
                );
			}
            $this->table($info);
		}
	}
	

}
