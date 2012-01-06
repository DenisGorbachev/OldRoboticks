<?php
 
abstract class BaseBotController {
    public $bot;
    public $clientFilename;
    public $homeDirname;

    public function __construct(Bot $bot, $clientFilename, $homeDirname) {
        $this->bot = $bot;
        $this->clientFilename = $clientFilename;
        $this->homeDirname = $homeDirname;
        if (!file_exists($this->homeDirname)) {
            mkdir($this->homeDirname, 0755, true);
        }
    }

    public function exec($command) {
        $cmd = 'export HOME='.escapeshellarg($this->homeDirname).'; export RK_OUTPUT_FORMAT='.escapeshellarg('json').'; '.$this->clientFilename.' '.$command;
        $this->log('executing', $command);
        $result = `$cmd`;
        $this->log('result', $result);
        return $result;
    }

    public function log($type, $string) {
        file_put_contents($this->homeDirname.'/log', date('Y-m-d H:i:s').' | '.$type.' | '.trim($string).PHP_EOL, FILE_APPEND);
    }

    public function play() {
        $this->exec('login '.$this->getBot()->getUsername().' '.$this->getBot()->getPassword());
        $this->exec('realm:select '.$this->getBot()->getRealmId());
    }

    public function getInfo() {
        $info = $this->getBot()->getInfo();
        if (empty($info)) {
            $info = $this->recoverInfo();
            $this->getBot()->setInfo($info);
        }
        return $info;
    }

    public function recoverInfo() {
        $ls = $this->getInfoArray('ls');
        return array(
            'robots' => $ls['objects']
        );
    }

    public function getInfoArray($command) {
        $infoArrays = $this->getInfoArrays($command);
        return $infoArrays[0];
    }

    public function getInfoArrays($command) {
        return json_decode($this->exec($command), true);
    }

    public function setBot($bot)
    {
        $this->bot = $bot;
    }

    public function getBot()
    {
        return $this->bot;
    }

    public function setClientFilename($clientFilename)
    {
        $this->clientFilename = $clientFilename;
    }

    public function getClientFilename()
    {
        return $this->clientFilename;
    }

    public function setHomeDirname($homeDirname)
    {
        $this->homeDirname = $homeDirname;
    }

    public function getHomeDirname()
    {
        return $this->homeDirname;
    }

}
