<?php
 
abstract class BaseBotController {
    public $bot;
    public $clientFilename;
    public $homeDirname;

    public function __construct(Bot $bot, $clientFilename, $homeDirname) {
        $this->bot = $bot;
        $this->clientFilename = $clientFilename;
        $this->homeDirname = $homeDirname;
    }

    public function exec($command) {
        $cmd = 'HOME='.escapeshellarg($this->homeDirname).'; '.$this->clientFilename.' '.$command;
        $this->log('executing', $cmd);
        $result = `$cmd`;
        $this->log('result', $result);
        return $result;
    }

    public function log($type, $string) {
        file_put_contents($this->homeDirname.'/log', date('Y-m-d H:i:s').' | '.$type.' | '.$string.PHP_EOL, FILE_APPEND);
    }

    public function play() {
        $this->exec('login '.$this->bot->getUsername().' '.$this->bot->getPassword());
        $info = $this->bot->getInfo();
        if (empty($info)) {
            // TODO: implement recovery
        }
        foreach ($info as $realmId => $realmInfo) {
            $this->exec('realm:select '.$realmId);
            $this->playInRealm($realmId, $realmInfo);
        }
    }

    abstract public function playInRealm($realmId, $realmInfo);

}
