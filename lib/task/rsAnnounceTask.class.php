<?php

class rsAnnounceTask extends sfBaseTask {
	protected function configure() {
		$this->addArguments(array(
			new sfCommandArgument('dir', sfCommandArgument::OPTIONAL, 'The directory in which to search for WordNet .txt files', sfConfig::get('sf_data_dir')),
		));
		
		$this->namespace = 'rs';
		$this->name = 'announce';
		$this->briefDescription = '';

		$this->detailedDescription = '';
	}

	protected function execute($arguments = array(), $options = array()) {
		$this->arguments = $arguments;
		$this->options = $options;
		$this->insert($this->extract());
	}

	public function insert($nouns) {
		$this->databaseManager = new sfDatabaseManager($this->configuration);
			$this->databaseManager->getDatabase('doctrine')->getConnection();
		$this->connection = Doctrine_Manager::connection();
		$this->dbh = $this->connection->getDbh();
		$this->dbh->query('TRUNCATE TABLE '.WordTable::getInstance()->getTableName());
		
		$this->connection->beginInternalTransaction();
		foreach ($nouns as $noun) {
			$object = new Word();
			$object->name = mb_strtoupper($noun);
			$object->save();
			$object->free(true);
		}
		$this->connection->commit();
		
		$this->logSection($this->name, 'Inserted '.WordTable::getInstance()->count().' words');
	}
	
	public function extract() {
		$nouns = array();
		foreach (sfFinder::type('file')->in($this->arguments['dir']) as $filename) {
			$nouns = array_merge($nouns, $this->parse($filename));
		}
		return array_unique($nouns);
	}
	
	public function parse($filename) {
		$nouns = array();
		$f = fopen($filename, 'r');
		while ($line = fgets($f)) {
			if ($line[0] == 'n') {
				$nouns[] = $this->parseLine($line);
			}
		}
		return $nouns;
	}
	
	public function parseLine($line) {
		if (preg_match('/\[(\w+)/u', $line, $matches) && !empty($matches[1])) {
			return $matches[1];
		}
	}
	
}
