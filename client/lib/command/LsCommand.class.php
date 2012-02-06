<?php

require_once dirname(__FILE__).'/base/RealmCommand.class.php';

class LsCommand extends RealmCommand {
	public function getParserConfig() {
		return array(
			'description' => 'List robots in a selected realm'
		) + parent::getParserConfig();
	}

	public function execute($options, $arguments) {
        $response = $this->get('robot/list', array(
            'realm_id' => $options['realm_id']
        ));
		if ($response['success']) {
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
        return $response;
	}
	

}
