<?php

require_once dirname(__FILE__).'/../BaseUserInterfaceCommand.class.php';

class HelpCommand extends BaseUserInterfaceCommand {
	public function getParserConfig() {
		return array(
			'description' => 'Output info about command'
		) + parent::getParserConfig();
	}

	public function getArgumentConfigs() {
		return array(
			'command' => array(
				'description' => 'Output description for this command'
			)
		);
	}
	
	public function execute($options, $arguments) {
		global $argv;
		$cmdClass = ucfirst($arguments['command']).'Command';
		$file = LIBDIR.'/command/depot/'.$cmdClass.'.class.php';
		if (!file_exists($file)) {
			throw new RoboticksException('No such command: "'.$arguments['command'].'"');
		}
		
		require_once $file;
		$cmd = new $cmdClass();
		$optionsSynopsys = array();
		$argumentsSynopsys = array();
		$optionsDescription = array();
		$argumentsDescription = array(); 
		foreach ($cmd->getOptionConfigs() as $longName=>$array) {
			list($shortConfig, $longConfig, $description) = $array;
			$shortName = preg_replace('/:/u', '', $shortConfig, -1, $shortCount);
			$arg = '';
			if ($shortCount) {
				$arg = '='.$longName;
				if ($shortCount == 2) {
					$arg = '['.$arg.']';
				}
			}
			$optionsSynopsys[] = '[--'.$longName.$arg.' | -'.$shortName.$arg.']';
			$optionsDescription[] = '   --'.$longName.str_repeat(' ', 14-strlen($longName)).$description;
		}
		foreach ($cmd->getArgumentConfigs() as $argument=>$array) {
			list($description) = $array;
			$argumentsSynopsys[] = $argument;
			$argumentsDescription[] = '   '.$argument.str_repeat(' ', 14-strlen($argument)).$description;
		}
		echoln($arguments['command'].' - '.$cmd->getShortDescription().PHP_EOL);
		echoln('usage: '.$argv[0].' '.$arguments['command'].($optionsSynopsys? ' '.implode(' ', $optionsSynopsys) : '').($argumentsSynopsys? ' '.implode(' ', $argumentsSynopsys) : '').PHP_EOL);
		if (($description = $this->getLongDescription())) {
			echoln($description.PHP_EOL);
		}
		if ($argumentsDescription) {
			echoln(implode(PHP_EOL, $argumentsDescription));
		}
		if ($optionsDescription) {
			echoln(implode(PHP_EOL, $optionsDescription));
		}
		echoln();
	}
	
}
