<?php

require_once dirname(__FILE__).'/Command.class.php';

abstract class ServerCommand extends Command {
    public $user_id = null;

    public function run() {
        $filename = $this->getCookieJar();
        if (file_exists($filename)) {
            $contents = file_get_contents($filename);
            preg_match('/user_id\t(.*)/u', $contents, $matches);
            if (isset($matches[1])) {
                $this->setUserId($matches[1]);
            }
        }

        return parent::run();
    }

	public function request($controller, $parameters = array(), $method = 'GET', $options = array()) {
        $host = $this->getHost();
        if (!$host) {
            throw new RoboticksConnectionException('host not set. You can set it using `rk host` command.');
        }
		$method = strtoupper($method);
		$uri = 'http://'.$host.($this->getConfig()->isDebug()? '/dev.php' : '').'/'.$controller;
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
			if (($error = $json['error'])) {
				$debug = $error['debug'];
				$message .= ': '.$debug['name'].'. '.$debug['message'].PHP_EOL.PHP_EOL.implode(PHP_EOL, $debug['traces']);
			}
			throw new RoboticksHttpServerException($message, '10'.$code);
		}
		if (is_null($json)) {
			$message = 'Unknown server error ['.$code.']';
            $message .= PHP_EOL.$response.PHP_EOL.'>> End of server error'.PHP_EOL;
			throw new RoboticksUnknownServerException($message);
		}
		curl_close($ch);
		return $json;
	}

    public function getHost() {
        return $this->getConfig()->getHost();
    }

    public function getCurlOptions() {
		return array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_MAXREDIRS => 5,
			CURLOPT_CONNECTTIMEOUT => 15,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_COOKIEFILE => $this->getCookieJar(),
			CURLOPT_COOKIEJAR => $this->getCookieJar(),
			CURLOPT_USERAGENT => 'Robotics client v'.VERSION
		);
	}

    public function getCookieJar() {
        return $this->getConfig()->getCacheDirname() . '/cookie.jar';
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
