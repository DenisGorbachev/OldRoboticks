<?php

require_once dirname(__FILE__).'/UserInterfaceCommand.class.php';

abstract class RobotCommand extends UserInterfaceCommand {
    public $robotId;

    public function __construct() {
        $robotIdCacheFilename = CACHEDIR.'/robotId';
        if (file_exists($robotIdCacheFilename)) {
            $this->setRobotId((int)file_get_contents($robotIdCacheFilename));
        }
        parent::__construct();
    }

    public function getOptionConfigs() {
        return array(
            'robot_id' => array(
                'short_name' => '-r',
                'long_name' => '--robot-id',
                'description' => 'ID of controlled robot (example: 10)',
                'action' => 'StoreInt',
                'default' => $this->getRobotId()
            )
        );
    }

    public function setRobotId($robotId)
    {
        $this->robotId = $robotId;
    }

    public function getRobotId()
    {
        return $this->robotId;
    }

    public function preExecute($options, $arguments)
    {
        parent::preExecute($options, $arguments);
        if (!$this->getOption('robot_id')) {
            throw new RoboticksCacheException('No robot selected. '.PHP_EOL.'See a list of available robots using `rk ls`, select a robot using `rk select ID`. '.PHP_EOL.'Alternatively, you can select a robot for a specific command by adding `--robot-id|-r ID`.');
        }
    }

}
