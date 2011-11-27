<?php

require_once dirname(__FILE__).'/Command.class.php';

abstract class ServerCommand extends Command {
    public $user_id = null;

    public function run() {
        $options = $this->getCurlOptions();
        $filename = $options[CURLOPT_COOKIEFILE];
        $contents = file_get_contents($filename);
        preg_match('/user_id\t(.*)/u', $contents, $matches);
        if (isset($matches[1])) {
            $this->setUserId($matches[1]);
        }

        return parent::run();
    }

	public function rawRequest($controller, $parameters = array(), $method = 'GET', $options = array()) {
		$method = strtoupper($method);
		$host = Config::get('generic/server/host');
		if (empty($host)) {
			throw new RoboticksConnectionException('No host defined in generic.yml');
		}
		$uri = 'http://'.$host.(Config::get('generic/debug')? '/dev.php' : '').'/'.$controller;
		if ($method == 'GET') {
			$uri .= '?'.http_build_query($parameters);
		}
		if ($method == 'POST') {
			$options[CURLOPT_POST] = true;
			$options[CURLOPT_POSTFIELDS] = $parameters;
		}
		$ch = curl_init($uri);
		curl_setopt_array($ch, $options + $this->getCurlOptions());
		$response = curl_exec($ch);
		$json = json_decode($response, true);
		if (($error = curl_error($ch))) {
			throw new RoboticksConnectionException('Curl error: '.$error);
		}
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if ($code != 200) {
			$message = 'Server error ['.$code.']';
			if (Config::get('generic/debug') && ($error = $json['error'])) {
				$debug = $error['debug'];
				$message .= ': '.$debug['name'].'. '.$debug['message'].PHP_EOL.PHP_EOL.implode(PHP_EOL, $debug['traces']);
			}
			throw new RoboticksHttpServerException($message, '10'.$code);
		}
		if (is_null($json)) {
			$message = 'Unknown server error ['.$code.']';
			if (Config::get('generic/debug')) {
				$message .= PHP_EOL.$response.PHP_EOL.'>> End of server error'.PHP_EOL;
			}
			throw new RoboticksUnknownServerException($message);
		}
		curl_close($ch);
		return $json;
	}

    public function getCurlOptions() {
		return array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_MAXREDIRS => 5,
			CURLOPT_CONNECTTIMEOUT => 15,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_COOKIEFILE => CACHEDIR.'/cookie.jar',
			CURLOPT_COOKIEJAR => CACHEDIR.'/cookie.jar',
			CURLOPT_USERAGENT => 'Robotics client v'.VERSION
		);
	}
	
	public function request($controller, $parameters = array(), $method = 'GET', $options = array()) {
		$response =  $this->rawRequest($controller, $parameters, $method, $options);
		$message = __($response['message']);
		if ($response['success']) {
			echoln('Success: '.$message);
			return $response;
		} else {
			echoln('Failure: '.$message);
			if (!empty($response['globalErrors'])) {
				foreach ($response['globalErrors'] as $error) {
					echoln('  - '.__($error));
				}
			}
			if (!empty($response['errors'])) {
				foreach ($response['errors'] as $field=>$error) {
					echoln('  - '.__(array('text' => ucfirst($field), 'arguments' => array())).': '.__($error));
				}
			}
			return false;
		}
	}
	
	public function get($controller, $parameters = array(), $options = array()) {
		return $this->request($controller, $parameters, 'GET', $options);
	}

	public function post($controller, $parameters = array(), $options = array()) {
		return $this->request($controller, $parameters, 'POST', $options);
	}
	
	public function postForm($formName, $controller, $parameters = array(), $options = array()) {
		return $this->post($controller, $this->reform($formName, $parameters), $options);
	}
	
	public function reform($formName, $parameters) {
		$reformed = array();
		foreach ($parameters as $parameter=>$value) {
			$reformed[$formName.'['.$parameter.']'] = $value;
		}
		return $reformed;
	}

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

}
