<?php

require_once __DIR__.'/BaseSpec.class.php';

abstract class BotBaseSpec extends BaseSpec {
    public $bots = array();
    public $selectedBotId;
    
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

    public function selectBot($bot) {
        $this->selectedBotId = $this->getBotId($bot);
        if (empty($this->selectedBotId)) {
            throw new Exception('No such bot: "'.$bot.'"');
        }
    }

    public function getBotsColloquialHomeDir() {
        return __DIR__.'/../cache/bots';
    }

    public function getClientHomeDir() {
        return $this->getBotsColloquialHomeDir().'/'.$this->getSelectedBotId().'/.roboticks';
    }

    public function whenMyNightComesDown($times = 1) {
        return $this->whenNightComesDown($this->getSelectedBotId(), $times);
    }

    public function whenNightComesDown($botId, $times = 1) {
        $cmd = __DIR__.'/../symfony rk:launch-bot '.$botId;
        while ($times--) {
            shell_exec($cmd);
        }
        return $this;
    }

    public function setSelectedBotId($selectedBotId) {
        $this->selectedBotId = $selectedBotId;
    }

    public function getSelectedBotId() {
        return $this->selectedBotId;
    }

}
