<?php

class rsLaunchBotTask extends sfBaseTask {
	protected function configure() {
		$this->addArguments(array(
			new sfCommandArgument('bot_id', sfCommandArgument::REQUIRED, 'ID of bot to launch'),
		));
		
		$this->addOptions(array(
			new sfCommandOption('recover-info', 'r', sfCommandOption::PARAMETER_NONE, 'Drop the existing info, start anew'),
//			new sfCommandOption('probability', 'p', sfCommandOption::PARAMETER_REQUIRED, 'Probability of a letter (float, from 0 to 1)', 0.02),
//			new sfCommandOption('print-only', 'o', sfCommandOption::PARAMETER_NONE, 'Only print, do not insert into database'),
		));

		$this->namespace = 'rs';
		$this->name = 'launch-bot';
		$this->briefDescription = '';

		$this->detailedDescription = '';
	}

	protected function execute($arguments = array(), $options = array()) {
        $this->databaseManager = new sfDatabaseManager($this->configuration);
        $this->databaseManager->getDatabase('doctrine')->getConnection();
        $this->connection = Doctrine_Manager::connection();

        $bot = BotTable::getInstance()->find($arguments['bot_id']);
        $controller = $bot->getController();
        if ($options['recover-info']) {
            $info = $controller->recoverInfo();
            $bot->setInfo($info);
        }
        $controller->play();
        $bot->save();
	}

}
