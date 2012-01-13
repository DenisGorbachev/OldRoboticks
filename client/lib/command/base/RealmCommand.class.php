<?php

require_once dirname(__FILE__).'/UserInterfaceCommand.class.php';

abstract class RealmCommand extends UserInterfaceCommand {
    public $realmId;

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
        $this->setVariable('realm_id', $realmId);
    }

    public function getRealmId()
    {
        if (empty($this->realmId)) {
            $this->realmId = $this->getVariable('realm_id');
        }
        return $this->realmId;
    }

    public function getLogFilenames() {
        $logFilenames = parent::getLogFilenames();
        $realmId = $this->getRealmId();
        if ($realmId) {
            $logFilenames[] = $this->getBaseLogDirname().'/realm/'.$realmId;
        }
        return $logFilenames;
    }

    public function preExecute($options, $arguments)
    {
        parent::preExecute($options, $arguments);
        if ($this->getOption('realm_id') === null) {
            throw new RoboticksCacheException('No realm selected. '.PHP_EOL.'See a list of available realms using `rk realm:ls`, select a realm using `rk realm:select ID`. '.PHP_EOL.'Alternatively, you can select a realm for a specific command by adding `--realm-id|-m ID`.');
        }
    }

    public function request($controller, $parameters = array(), $method = 'GET', $options = array())
    {
        $parameters['realm_id'] = $this->getOption('realm_id');
        return parent::request($controller, $parameters, $method, $options);
    }

}
