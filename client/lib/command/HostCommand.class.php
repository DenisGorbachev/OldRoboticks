<?php

require_once dirname(__FILE__).'/base/UserInterfaceCommand.class.php';

class HostCommand extends UserInterfaceCommand {
    public function getParserConfig() {
        return array(
            'description' => 'Select a host'
        ) + parent::getParserConfig();
    }

    public function getArgumentConfigs() {
        return array(
            'host' => array(
                'description' => 'Name of selected host (example: roboticks.faster-than-wind.ru)'
            )
        );
    }

    public function execute($options, $arguments) {
        $host = $arguments['host'];
        $this->getConfig()->setHost($host);
        $this->success('selected host '.$host);
        return true;
    }

}
