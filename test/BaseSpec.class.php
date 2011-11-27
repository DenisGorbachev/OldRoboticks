<?php

require_once __DIR__.'/../lib/symfony/lib/yaml/sfYaml.php';

class BaseSpec extends PHPUnit_Extensions_Story_TestCase {
	public $robots = array(null, 'tea', 'grunt');
	protected $world = array(
		'results' => array(),
		'lastResult' => ''
	);
	
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
	
	public function getRobotId($name) {
		return array_search($name, $this->robots, true);
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
		file_put_contents($this->getClientDir().'/cache/cookie.jar', '');
	}
	
	public function givenUser($login, $password = 'asdf') {
		$login = strtolower($login);
		return $this->exec('login '.$login.' '.$password);
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

	public function whenExec($retinue) {
		return $this->exec($retinue);
	}

    public function whenSelectRobotByName($retinue) {
		$this->exec('report --for robots');
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
    
}
