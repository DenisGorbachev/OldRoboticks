<?php

require_once dirname(__FILE__).'/RealmCommand.class.php';

abstract class RobotCommand extends RealmCommand {
    public $robotId;

    public function getOptionConfigs() {
        return parent::getOptionConfigs() + array(
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
        $this->setVariable('robotId', $robotId);
    }

    public function getRobotId()
    {
        if (empty($this->robotId)) {
            $this->robotId = $this->getVariable('robotId');
        }
        return $this->robotId;
    }

    public function preExecute($options, $arguments)
    {
        parent::preExecute($options, $arguments);
        if ($this->getOption('robot_id') === null) {
            throw new RoboticksCacheException('No robot selected. '.PHP_EOL.'See a list of available robots using `rk ls`, select a robot using `rk select ID`. '.PHP_EOL.'Alternatively, you can select a robot for a specific command by adding `--robot-id|-r ID`.');
        }
    }

    public function request($controller, $parameters = array(), $method = 'GET', $options = array())
    {
        $parameters['id'] = $this->getOption('robot_id');
        return parent::request($controller, $parameters, $method, $options);
    }
}
