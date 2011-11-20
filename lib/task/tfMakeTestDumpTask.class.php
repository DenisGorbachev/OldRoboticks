<?php

global $dispatcher;
$dispatcher->connect('command.post_command', 'tfMakeTestDumpPostCommandListener');

function tfMakeTestDumpPostCommandListener(sfEvent $event) {
	global $application, $dispatcher;
	
	$command = $event->getSubject();
	if ($command->getNamespace() == 'doctrine') {
		if ($command->getName() == 'build') {
			$task = new tfMakeTestDumpTask($dispatcher, $command->getFormatter());
			$task->setCommandApplication($application);
			$task->run();
		}
	}
}

class tfMakeTestDumpTask extends sfDoctrineBaseTask {
	protected function configure() {
		$this->namespace = 'tf';
		$this->name = 'make-test-dump';
		
		$this->addArguments(array(
	      new sfCommandArgument('database', sfCommandArgument::OPTIONAL | sfCommandArgument::IS_ARRAY, 'A specific database'),
	    ));
	}

	protected function execute($arguments = array(), $options = array()) {
		$databaseManager = new sfDatabaseManager($this->configuration);
	    $databases = $this->getDoctrineDatabases($databaseManager, count($arguments['database']) ? $arguments['database'] : null);
		
	    foreach ($databases as $database) {
	    	extract($database->getParameterHolder()->getAll());
	    	foreach (array('host', 'dbname') as $spliter) {
		    	preg_match('/'.$spliter.'=([^;]+)/', $dsn, $matches);
		    	list($full, $$spliter) = $matches;
	    	}
	    	$filename = sfConfig::get('sf_test_dir').'/dump.sql';
	    	$passwordOption = $password? "-p$password" : '';
	    	$command = "mysqldump -h $host -u $username $passwordOption $dbname > $filename";
	    	$this->logSection('dump', 'Executing '.$command);
	    	`$command`;
	    }
	}
}
