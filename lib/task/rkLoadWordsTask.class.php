<?php

class rkLoadWordsTask extends sfBaseTask {
	protected function configure() {
		parent::configure();
		
		$this->addOptions(array(
			new sfCommandOption('file', 'f', sfCommandOption::PARAMETER_REQUIRED, 'File to load', sfConfig::get('sf_data_dir').'/words'),
		));

		$this->namespace = 'rk';
		$this->name = 'load-words';
		$this->briefDescription = '';
		$this->detailedDescription = '';
	}

	protected function execute($arguments = array(), $options = array()) {
        $this->databaseManager = new sfDatabaseManager($this->configuration);
        $this->databaseManager->getDatabase('doctrine')->getConnection();
        $connection = Doctrine_Manager::connection();
        $table = WordTable::getInstance();

        $this->logSection($this->namespace, 'Loading words from "'.$options['file'].'"...');
        $connection->getDbh()->query('TRUNCATE TABLE '.$table->getTableName());
        $connection->beginTransaction();
        $i = 0;
        $fp = fopen($options['file'], 'r');
		while ($name = mb_strtoupper(str_replace("\n", '', fgets($fp)))) {
            $word = new Word();
            $word->setName($name);
            $word->save();
            $word->free(true);
            $i++;
            if ($i % 100 == 0) {
                $this->logSection($this->namespace, '['.$name.'] Loaded '.$i.' words');
            }
        }
        $connection->commit();
	}
	
}
