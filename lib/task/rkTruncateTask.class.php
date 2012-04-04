<?php

class rkTruncateTask extends sfBaseTask {
    protected function configure() {
        $this->addArguments(array(
            new sfCommandArgument('models', sfCommandArgument::REQUIRED | sfCommandArgument::IS_ARRAY, 'Models to truncate'),
        ));

        $this->namespace = 'rk';
        $this->name = 'truncate';
        $this->briefDescription = '';

        $this->detailedDescription = '';
    }

    protected function execute($arguments = array(), $options = array()) {
        $this->databaseManager = new sfDatabaseManager($this->configuration);
        $this->databaseManager->getDatabase('doctrine')->getConnection();
        $this->connection = Doctrine_Manager::connection();
        $dbh = $this->connection->getDbh();
        foreach ($arguments['models'] as $model) {
            $dbh->query('TRUNCATE TABLE '.Doctrine_Core::getTable($model)->getTableName());
            $this->logSection($this->namespace, 'Truncated "'.$model.'" table');
        }
    }

}
