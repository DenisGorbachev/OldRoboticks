<?php

require_once dirname(__FILE__).'/../BaseUserInterfaceCommand.class.php';

class LsCommand extends BaseUserInterfaceCommand {
	public $columns = array(
		'id' => array(
			'name' => 'ID',
			'length' => 5
		),
		'sector' => array(
			'name' => 'Sector',
			'length' => 11
		),
		'cargo' => array(
			'name' => 'Cargo',
			'length' => 11
		),
		'functions' => array(
			'name' => 'Funcs',
			'length' => 11
		),
		'name' => array(
			'name' => 'Name'
		),
	);
	
	public function getParserConfig() {
		return array(
			'description' => 'List robots'
		) + parent::getParserConfig();
	}

	public function execute($options, $arguments) {
		if (($response = $this->get('robot/list'))) {
			foreach ($this->columns as $key=>$column) {
				$this->renderColumn($key, $column['name']);
			}
			$this->endRow();
			foreach ($response['objects'] as $object) {
				foreach ($this->columns as $key=>$column) {
					$this->renderColumn($key, $object[$key]);
				}
				$this->endRow();
			}
		}
	}
	
	public function renderColumn($key, $value) {
		if (isset($this->columns[$key]['length'])) {
			$value = str_pad($value, $this->columns[$key]['length']);
		}
		echo $value.' ';
	}
	
	public function endRow() {
		echo PHP_EOL;
	}
	
}
