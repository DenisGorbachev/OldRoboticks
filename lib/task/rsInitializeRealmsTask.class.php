<?php

class rsInitializeRealmsTask extends sfBaseTask {
    protected function configure() {
//        $this->addArguments(array(
//            new sfCommandArgument('size', sfCommandArgument::REQUIRED, 'The width and height of the map'),
//        ));
//
//        $this->addOptions(array(
//            new sfCommandOption('default', 'd', sfCommandOption::PARAMETER_REQUIRED, 'Default letter', ''),
//            new sfCommandOption('probability', 'p', sfCommandOption::PARAMETER_REQUIRED, 'Probability of a letter (float, from 0 to 1)', 0.02),
//            new sfCommandOption('print-only', 'o', sfCommandOption::PARAMETER_NONE, 'Only print, do not insert into database'),
//        ));

        $this->namespace = 'rs';
        $this->name = 'initialize-realms';
        $this->briefDescription = '';

        $this->detailedDescription = '';
    }

    protected function execute($arguments = array(), $options = array()) {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase('doctrine')->getConnection();

        $uninitializedRealms = RealmTable::getInstance()->getUninitializedRealms();
        foreach ($uninitializedRealms as $realm) {
            $connection->beginTransaction();
            try {
                $realm->getController()->initialize();
                $realm->setInitialized(true);
                $realm->save();
            } catch (Exception $e) {
                $connection->rollback();
                throw $e;
            }
            $connection->commit();
        }
        return 0;
    }

}
