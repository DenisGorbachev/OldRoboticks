<?php

class tfUser extends sfBasicSecurityUser {
	protected $user = null;
	protected $permissions = array();

	const ID_NAMESPACE = 'symfony/user/tfUser/id';

	public function initialize(sfEventDispatcher $dispatcher, sfStorage $storage, $options = array()) {
		parent::initialize($dispatcher, $storage, $options);

		if (!$this->isAuthenticated()) {
			$remember_cookie_name = sfConfig::get('app_remember_cookie_name', 'rmbr_key');
			if (
				($remember_key = sfContext::getInstance()->getRequest()->getCookie($remember_cookie_name, null)) &&
				($user = Doctrine::getTable(sfConfig::get('app_auth_table', 'User'))->findOneBy('remember_key', $remember_key))
			) {
				$this->login($user, true, $remember_key);
			} else {
				$this->logout();
			}
		} else {
			if (!$this->getUser()) {
				$this->logout();
				return;
			}
			
//			foreach($this->getUser()->getUserGroup()->getData() as $userGroup) {
//				$this->permissions = array_merge($this->permissions, $userGroup->getGroup()->getPermissions());
//			}
		}
	}

	public function login($user, $remember = true, $remember_key = null) {
		$this->storage->write(self::ID_NAMESPACE, $user->getId());
	    $this->setAuthenticated(true);
		$this->clearCredentials();
//		$this->addCredentials($user->getAllPermissions());
		$this->user = $user;
		$this->user->last_login = $this->user->current_login;
		$this->user->current_login = date('Y-m-d H:i:s'); // Updating
		if($remember) {
			$remember_key = $remember_key ? $remember_key : md5($this->generateRandomKey());
			$this->user->remember_key = $remember_key;
			$remember_cookie_name = sfConfig::get('app_user_remember_cookie_name', 'rmbr_key');
			sfContext::getInstance()->getResponse()->setCookie($remember_cookie_name, $remember_key, time() + sfConfig::get('app_user_remember_cookie_expire', 365*24*3600*5));
		}
		$this->user->save();
	}

	protected function generateRandomKey($len = 50) {
		$string = '';
		$pool	 = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		for ($i = 1; $i <= $len; $i++) {
			$string .= substr($pool, rand(0, 61), 1);
		}
		return $string;
	}

	public function logout() {
		$this->user = null;
		$this->clearCredentials();
		$this->storage->remove(self::ID_NAMESPACE);
		$this->setAuthenticated(false);
		$remember_cookie_name = sfConfig::get('app_remember_cookie_name', 'rmbr_key');
		sfContext::getInstance()->getResponse()->setCookie($remember_cookie_name, '', null);
	}

	public function getUser() {
		if (!$this->user) {
			if (!$this->isAuthenticated()) {
				return null;
			}
			
			$id = $this->storage->read(self::ID_NAMESPACE);
			if ($id) {
				$this->user = Doctrine::getTable(sfConfig::get('app_auth_table', 'User'))->find($id);
				if (!$this->user) {
					$this->logout();
					throw new sfException('The user does not exist anymore in the database.');
				}
			}
		}
		return $this->user;
	}

	public function __call($name, $arguments) {
		$dbUser = $this->getUser();
		if ($dbUser) {
			if (is_callable(array($dbUser, $name))) {
				return call_user_func_array(array($dbUser, $name), $arguments);
			}
		}
	}

	public function __toString() {
	  return $this->getUser()->__toString();
	}

}

?>
