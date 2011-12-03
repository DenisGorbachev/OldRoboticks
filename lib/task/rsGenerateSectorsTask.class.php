<?php

class rsGenerateSectorsTask extends sfBaseTask {
	protected function configure() {
		$this->addArguments(array(
			new sfCommandArgument('size', sfCommandArgument::REQUIRED, 'The width and height of the map'),
		));
		
		$this->addOptions(array(
			new sfCommandOption('default', 'd', sfCommandOption::PARAMETER_REQUIRED, 'Default letter', ''),
			new sfCommandOption('probability', 'p', sfCommandOption::PARAMETER_REQUIRED, 'Probability of a letter (float, from 0 to 1)', 0.02),
			new sfCommandOption('print-only', 'o', sfCommandOption::PARAMETER_NONE, 'Only print, do not insert into database'),
		));

		$this->namespace = 'rs';
		$this->name = 'generate-sectors';
		$this->briefDescription = '';

		$this->detailedDescription = '';
	}

	protected function execute($arguments = array(), $options = array()) {
        $this->arguments = $arguments;
        $this->options = $options;
		$language = sfYaml::load(sfConfig::get('sf_config_dir').'/language.yml');
        $defaultLetter = $options['default'];
        $letters = $language['letters'];
        if (($defaultIndex = array_search($defaultLetter, $letters))) {
            unset($letters[$defaultIndex]);
        }
		$this->databaseManager = new sfDatabaseManager($this->configuration);
			$this->databaseManager->getDatabase('doctrine')->getConnection();
		$this->connection = Doctrine_Manager::connection();

		$dbh = $this->connection->getDbh();
		$dbh->query('TRUNCATE TABLE '.SectorTable::getInstance()->getTableName());
		$this->map = array();
		$this->generateMap($letters);
		if ($this->options['print-only']) {
			$this->printMap();
		} else {
			$this->insertMap();
		}
		return 0;
	}

	public function generateMap($letters) {
		for ($y = 0; $y < $this->arguments['size']; $y++) {
			for ($x = 0; $x < $this->arguments['size']; $x++) {
				$this->map[$x][$y] = (mt_rand(0, 100)/100 < (float)$this->options['probability'])? $this->generateLetter($letters) : $this->options['default'];
			}
		}
	}
		
	public function generateLetter(array $letters) {
		return $this->rand($letters);
	}

	public function rand(array $array) {
		return $array[array_rand($array)];
	}
	
	public function printMap() {
		foreach ($this->map as $x => $row) {
			foreach ($row as $y => $letter) {
				echo $letter.' ';
			}
			echo PHP_EOL;
		}
	}
	
	public function insertMap() {
        $i = 0;
        $this->connection->beginTransaction();
		foreach ($this->map as $x => $row) {
			foreach ($row as $y => $letter) {
				$s = new Sector();
				$s->x = $x;
				$s->y = $y;
				$s->letter = $letter;
				$s->save();
				$s->free(true);
                $i++;
                if ($i % 100 == 0) {
                    $this->logSection($this->namespace, '['.$x.','.$y.'] Loaded '.$i.' sectors');
                }
			}
		}
        $this->connection->commit();
	}
	
}
