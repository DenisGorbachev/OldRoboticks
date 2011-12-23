<?php

require_once __DIR__.'/../lib/symfony/lib/yaml/sfYaml.php';

class BaseSpec extends PHPUnit_Extensions_Story_TestCase {
	public $robots = array(null, 'tea', 'tear', 'dirk', 'grunt', 'teeter', 'pear', 'sedative', 'seaside', 'stake', '', '', 'drake', 'fuel', '', '', '', 'plush', 'mouse', 'cart', 'finger1', 'finger2', 'finger3', 'finger4', 'finger5', 'finger6', 'finger7', 'finger8', 'finger9', 'finger10', 'finger11', 'finger12', 'finger13', 'finger14', 'finger15', 'finger16', 'finger17', 'finger18', 'finger19', 'finger20', 'finger21', 'finger22', 'finger23', 'finger24', 'finger25', 'finger26', 'finger27', 'finger28', 'finger29', 'finger30', 'finger31', 'finger32', 'finger33', 'finger34', 'finger35', 'meat', 'justregistered');
    public $realms = array(null, 'Universe', 'Etherworld', 'Afterlife');
    
	protected $world = array(
		'results' => array(),
		'lastResult' => ''
	);

    public function setUp() {
        parent::setUp();
        $this->setDebug(true);
        $this->unlinkIfExists($this->getClientCacheDir().'/cookie.jar');
        $this->unlinkIfExists($this->getClientCacheDir().'/realmId');
        $this->unlinkIfExists($this->getClientCacheDir().'/robotId');
    }

    public function tearDown() {
        $this->setDebug(false);
        parent::tearDown();
    }

	public function exec($command) {
		$cmd = 'rk '.$command;
		echo PHP_EOL.$cmd.PHP_EOL;
		$result = `$cmd`;
		echo $result.PHP_EOL;
		$this->world['results'][$command] = $result;
		$this->world['lastResult'] = $result;
		return $result;
	}

	public function getClientDir() {
		return __DIR__.'/../client';
	}

    public function getClientCacheDir() {
        $cachedir = getenv('HOME').'/.roboticks';
        if (empty($cachedir)) {
            mkdir($cachedir, 0755, true); // In case the tests are run before any interaction with client
        }
        return $cachedir;
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
	}
	
	public function givenUser($login, $password = 'asdf') {
		$login = strtolower($login);
		return $this->exec('login '.$login.' '.$password);
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

    public function testDummy() {
        $this->assertTrue(true);
    }

    public function getClientDebugFilename() {
        return $this->getClientCacheDir().'/debug';
    }

    public function setDebug($debug) {
        $debugFilename = $this->getClientDebugFilename();
        return $debug? touch($debugFilename) : unlink($debugFilename);
    }

    public function getDebug() {
        return file_exists($this->getClientDebugFilename());
    }

    public function unlinkIfExists($filename) {
        if (file_exists($filename)) {
            unlink($filename);
        }
    }

}
