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
        $this->log('executing', $command.PHP_EOL);
        $result = `$cmd`;
        $this->log('result', $result);
        return $result;
    }

    public function split($infoString) {
        $result = array();
        foreach (explode("\n", $infoString) as $infoStringRow) {
            $splinters = explode('  ', $infoStringRow);
            array_walk($splinters, 'trim');
            $result[] = $splinters;
        }
        return $result;
    }

    public function log($type, $string) {
        file_put_contents($this->homeDirname.'/log', date('Y-m-d H:i:s').' | '.$type.' | '.$string, FILE_APPEND);
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
        return array(
            'robots' => $this->getInfoArray('ls')
        );
    }

    public function getInfoArray($command) {
        $splinters = $this->split($this->exec($command));
        var_dump($splinters);
        $numberedInfoarrays = array_slice($splinters, 2, count($splinters));
        $names = array_shift($numberedInfoarrays);
        $result = array();
        foreach ($numberedInfoarrays as $numberedInfoarray) {
            $infoarray = array();
            foreach ($names as $i=>$name) {
                $infoarray[$name] = $numberedInfoarray[$i];
            }
            $result[$numberedInfoarray[0]] = $infoarray;
        }
        return $result;
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
