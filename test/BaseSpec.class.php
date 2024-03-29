<?php

require_once __DIR__.'/../lib/symfony/lib/yaml/sfYaml.php';

class BaseSpec extends PHPUnit_Extensions_Story_TestCase {
    public $realms = array();
    public $robots = array();

    protected $world = array(
        'output' => '',
        'results' => array(),
        'lastResult' => ''
    );

    public function __construct() {
        parent::__construct();
        $this->realms[] = null;
        $realmFixtures = sfYaml::load($this->getServerFixturesDir().'/02-Realm.yml');
        foreach ($realmFixtures['Realm'] as $name=>$properties) {
            $this->realms[] = $name;
        }
        $this->robots[] = null;
        $robotFixtures = sfYaml::load($this->getServerFixturesDir().'/05-Robot.yml');
        foreach ($robotFixtures['Robot'] as $name=>$properties) {
            $this->robots[] = strtolower($name);
        }
    }

    public function setUp() {
        parent::setUp();
        $clientHomeDir = $this->getClientHomeDir();
        if (file_exists($clientHomeDir)) {
            `rm -r $clientHomeDir`;
        }
        putenv('RK_REALM_ID=');
        putenv('RK_ROBOT_ID=');
        $this->setDebug(true);
        return $this;
    }

    public function tearDown() {
        $this->setDebug(false);
        parent::tearDown();
    }

    public function addRealm($name) {
        $this->realms[] = $name;
        return $this;
    }

    public function addRobot($name) {
        $this->robots[] = $name;
        return $this;
    }

    public function exec($command) {
        $cmd = 'rk '.$command;
        echo PHP_EOL.$cmd.PHP_EOL;
        $result = `$cmd`;
        echo $result.PHP_EOL;
        $this->world['output'] .= $result;
        $this->world['results'][] = $result;
        $this->world['lastResult'] = $result;
        return $result;
    }

    public function getClientDir() {
        return __DIR__.'/../client';
    }

    public function getClientHomeDir() {
        return getenv('HOME').'/.roboticks';
    }

    public function getClientCacheDir() {
        return $this->getClientHomeDir().'/roboticks/cache';
    }

    public function getClientLogDir() {
        return $this->getClientHomeDir().'/roboticks/log';
    }

    public function getServerFixturesDir() {
        return __DIR__.'/../data/fixtures';
    }

    public function getServerCacheDir() {
        return __DIR__.'/../cache';
    }

    public function createDirIfNotExists($dirname) {
        if (!file_exists($dirname)) {
            mkdir($dirname, 0755, true);
        }
    }

    public function getRobotId($name) {
        return array_search($name, $this->robots, true);
    }

    public function getRealmId($name) {
        return array_search($name, $this->realms, true);
    }

    public function runGiven(&$world, $action, $arguments) {
        return call_user_func_array(array($this, 'given'.$action), $arguments);
    }

    public function givenGenesis() {
        $dump = __DIR__.'/dump.sql';
        $databases = sfYaml::load(__DIR__.'/../config/databases.yml');
        $param = $databases['all']['doctrine']['param'];
        $dsn = $param['dsn'];
        $username = $param['username'];
        $password = $param['password'];
        preg_match('/dbname=(.*)/', $dsn, $match);
        $dbname = $match[1];
        $cmd = "mysql -u $username ".($password? "-p$password" : "")." $dbname < $dump";
        `$cmd`;
        $this->exec('host roboticks');
    }

    public function givenUser($login, $password = 'asdf') {
        $login = strtolower($login);
        return $this->exec('login -p '.$password.' '.$login);
    }

    public function givenRealm($name) {
        return $this->givenRealmId($this->getRealmId($name));
    }

    public function givenRealmId($realmId) {
        return $this->exec('realm:select '.$realmId);
    }

    public function givenRobot($name) {
        return $this->givenRobotId($this->getRobotId($name));
    }

    public function givenRobotId($robotId) {
        return $this->exec('select '.$robotId);
    }

    public function runWhen(&$world, $action, $arguments) {
        return call_user_func_array(array($this, 'when'.$action), $arguments);
    }

    public function whenWait($seconds) {
        sleep($seconds);
        return $this;
    }

    public function whenExec($retinue) {
        return $this->exec($retinue);
    }

    public function whenSelectRobotByName($name) {
        $this->exec('report --for robots');
        preg_match('/^(\d+)\s.*'.$name.'/Uum', $this->world['lastResult'], $matches);
        $this->exec('select '.$matches[1]);
        return $this;
    }

    public function runThen(&$world, $action, $arguments) {
        $this->assertNotContains('Server error', implode(PHP_EOL, $this->world['results']), 'Output contains server errors.');
        $this->assertNotContains('Call Stack', implode(PHP_EOL, $this->world['results']), 'Output contains PHP errors.');
        return call_user_func_array(array($this, 'then'.$action), $arguments);
    }

    public function thenSuccess() {
        return $this->assertStringStartsWith('Success:', $this->world['lastResult']);
    }

    public function thenNotice() {
        return $this->assertStringStartsWith('Notice:', $this->world['lastResult']);
    }

    public function thenPleaseWait() {
           return $this->assertStringStartsWith('Please wait:', $this->world['lastResult']);
       }

    public function thenFailure() {
        return $this->assertStringStartsWith('Failure:', $this->world['lastResult']);
    }

    public function thenInvalidArgumentsFailure() {
        return $this->assertContains('>> Invalid argument', $this->world['lastResult']);
    }

    public function thenContains($needle, $message = '', $ignoreCase = false) {
        return $this->assertContains($needle, $this->world['lastResult'], $message, $ignoreCase);
    }

    public function thenNotContains($needle, $message = '', $ignoreCase = false) {
        return $this->assertNotContains($needle, $this->world['lastResult'], $message, $ignoreCase);
    }

    public function thenLogContains($file, $needle, $message = '', $canonicalize = false, $ignoreCase = false) {
        return $this->assertContains($needle, file_get_contents($this->getClientLogDir().$file), $message, $ignoreCase);
    }

    public function thenMatches($pattern, $message = '', $ignoreCase = false) {
        return $this->assertRegExp($pattern, $this->world['lastResult'], $message, $ignoreCase);
    }

    public function thenNotMatches($pattern, $message = '', $ignoreCase = false) {
        return $this->assertNotRegExp($pattern, $this->world['lastResult'], $message, $ignoreCase);
    }

    public function getServerDebugFilename() {
        return $this->getServerCacheDir().'/debug';
    }

    public function setDebug($debug) {
        $debugFilename = $this->getServerDebugFilename();
        $this->createDirIfNotExists(dirname($debugFilename));
        return $debug? touch($debugFilename) : unlink($debugFilename);
    }

    public function getDebug() {
        return file_exists($this->getServerDebugFilename());
    }

    public function unlinkIfExists($filename) {
        if (file_exists($filename)) {
            unlink($filename);
        }
    }

}
