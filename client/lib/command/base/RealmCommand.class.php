<?php

require_once dirname(__FILE__).'/UserInterfaceCommand.class.php';

abstract class RealmCommand extends UserInterfaceCommand {
    public $realmId;

    public function __construct() {
        $realmIdCacheFilename = CACHEDIR.'/realmId';
        if (file_exists($realmIdCacheFilename)) {
            $this->setRealmId((int)file_get_contents($realmIdCacheFilename));
        }
        parent::__construct();
    }

    public function getOptionConfigs() {
        return array(
            'realm_id' => array(
                'short_name' => '-m',
                'long_name' => '--realm-id',
                'description' => 'ID of playable realm (example: 6)',
                'action' => 'StoreInt',
                'default' => $this->getRealmId()
            )
        );
    }

    public function setRealmId($realmId)
    {
        $this->realmId = $realmId;
    }

    public function getRealmId()
    {
        return $this->realmId;
    }

    public function preExecute($options, $arguments)
    {
        parent::preExecute($options, $arguments);
        if (!$this->getOption('realm_id')) {
            throw new RoboticksCacheException('No realm selected. '.PHP_EOL.'See a list of available realms using `rk realm:ls`, select a realm using `rk realm:select ID`. '.PHP_EOL.'Alternatively, you can select a realm for a specific command by adding `--realm-id|-m ID`.');
        }
    }

}
