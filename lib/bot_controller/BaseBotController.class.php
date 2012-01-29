<?php
 
abstract class BaseBotController {
    public $bot;
    public $info = array();
    public $activeAt;
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

    public function execAndReturnEverything($command) {
        $cmd = 'export HOME='.escapeshellarg($this->homeDirname).'; export RK_OUTPUT_FORMAT='.escapeshellarg('json').'; '.$this->clientFilename.' '.$command;
        $resultsInJson = `$cmd`;
        $results = json_decode($resultsInJson, true);
        foreach ($results as $result) {
            if (!empty($result['active_at'])) {
                if ($result['active_at'] < $this->activeAt || is_null($this->activeAt)) {
                    $this->activeAt = $result['active_at'];
                }
            }
        }
        return $results;
    }

    public function exec($command) {
        return array_pop($this->execAndReturnEverything($command));
    }

    public function connect() {
        $this->exec('host roboticks');
        $this->exec('login -p '.$this->getBot()->getPassword().' '.$this->getBot()->getUsername());
        $this->exec('realm:select '.$this->getBot()->getRealmId());
    }

    abstract public function play();

    public function refresh() {
        if (empty($this->info['realm'])) {
            $realmShow = $this->exec('realm:show');
            $this->info['realm'] = $realmShow['message']['arguments'];
        }
        if (empty($this->info['plans'])) {
            $this->info['plans'] = array();
        }
        $ls = $this->exec('ls');
        $this->info['robots'] = $ls['objects'];
    }

    public function setBot($bot)
    {
        $this->bot = $bot;
    }

    public function getBot()
    {
        return $this->bot;
    }

    public function setInfo($info)
    {
        $this->info = $info;
    }

    public function getInfo()
    {
        return $this->info;
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

    public function setActiveAt($activeAt) {
        $this->activeAt = $activeAt;
    }

    public function getActiveAt() {
        return $this->activeAt;
    }

}
