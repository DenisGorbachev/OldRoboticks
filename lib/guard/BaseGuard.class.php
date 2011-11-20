<?php

abstract class BaseGuard {
	public $object;
	public static $user;

	public function __construct($object) {
		$this->object = $object;
	}

	protected function getObject() {
		return $this->object;
	}
	
	public function callForUser($user, $method, $arguments) {
		self::$user = $user;
		$result = call_user_func_array(array($this, $method), $arguments);
		self::$user = null;
		return $result;
	}
	
	protected static function getUser() {
		if (empty(self::$user)) {
			if (sfContext::hasInstance()) {
				self::$user = sfContext::getInstance()->getUser();
			} else {
				throw new sfException('sfContext has no instance.');
			}
		}
		return self::$user;
	}

	public static function hasCredential($credential) {
		return self::getUser()->hasCredential($credential);
	}

    public function isOwner($field = 'user_id') {
        return $this->object->{$field} == $this->getUser()->getId();
    }

}