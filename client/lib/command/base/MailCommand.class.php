<?php

require_once dirname(__FILE__).'/RealmCommand.class.php';

abstract class MailCommand extends RealmCommand {
    public function getOptionConfigs() {
        return parent::getOptionConfigs() + array(
            'realm' => array(
                'short_name' => '-r',
                'long_name' => '--realm',
                'description' => 'Toggle realm level (use this for mail dealing with local realm issues)',
                'action' => 'StoreTrue',
                'default' => false
            )
        );
    }

    public function preExecute($options, $arguments)
    {
        if (!$options['realm']) {
            $this->setOption('realm_id', false); // Yeah, that's a hack
        }
        parent::preExecute($options, $arguments);
    }

}
