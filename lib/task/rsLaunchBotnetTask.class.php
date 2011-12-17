<?php

class rsLaunchBotnetTask extends sfBaseTask {
	protected function configure() {
		$this->addArguments(array(
//			new sfCommandArgument('size', sfCommandArgument::REQUIRED, 'The width and height of the map'),
		));
		
		$this->addOptions(array(
			new sfCommandOption('daemonize', 'd', sfCommandOption::PARAMETER_NONE, 'Run in daemonized mode'),
//			new sfCommandOption('probability', 'p', sfCommandOption::PARAMETER_REQUIRED, 'Probability of a letter (float, from 0 to 1)', 0.02),
//			new sfCommandOption('print-only', 'o', sfCommandOption::PARAMETER_NONE, 'Only print, do not insert into database'),
		));

		$this->namespace = 'rs';
		$this->name = 'launch-botnet';
		$this->briefDescription = '';

		$this->detailedDescription = '';
	}

	protected function execute($arguments = array(), $options = array()) {
        $this->databaseManager = new sfDatabaseManager($this->configuration);
        $this->databaseManager->getDatabase('doctrine')->getConnection();
        $this->connection = Doctrine_Manager::connection();

        gc_enable();
        do {
            $bots = BotTable::getInstance()->getActiveBotsOnDemand();
            foreach ($bots as $bot) {
                $bin = $_SERVER['argv'][0];
                $command = $bin.' rs:launch-bot '.$bot['id'].' >> '.sfConfig::get('sf_log_dir').'/bot_'.$bot['id'].'.log';
                $this->logSection($this->name, 'Launching bot #'.$bot['id']);
                $at_command = 'echo '.escapeshellarg($command);
                `$at_command | at now`;
            }
            $this->connection->clear();
            gc_collect_cycles();
        }
        while (!empty($options['daemonize']) && sleep(1) !== null);
	}

}
