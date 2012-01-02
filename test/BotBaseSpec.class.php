<?php

require_once __DIR__.'/BaseSpec.class.php';

abstract class BotBaseSpec extends BaseSpec {
    public $bots = array();
    
    public function __construct() {
        $botFixturesYaml = sfYaml::load(__DIR__.'/../data/fixtures/06-Bot.yml');
        $bots = $botFixturesYaml['Bot'];
        $this->bots = array_keys($bots);
        array_unshift($this->bots, null);
        array_push($this->bots, 'JustCreated');
        parent::__construct();
    }

    public function getBotId($bot) {
        return array_search($bot, $this->bots);
    }

    public function whenNightComesDown($botId, $times = 1) {
        $cmd = __DIR__.'/../symfony rs:launch-bot '.$botId;
        while ($times--) {
            shell_exec($cmd);
        }
        return $this;
    }
    
}
