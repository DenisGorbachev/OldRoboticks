<?php

/**
 * User
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    robotics
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class User extends BaseUser {
    public $salt = '343b02c254cc227f0bd461cd6d95cdc6';

    public function __toString() {
        return '#'.$this->id.' "'.$this->username.'"';
    }

    public function setPassword($password) {
        $this->_set('password', md5($password.$this->salt));
    }

    public function checkPassword($password) {
      return $this->password == md5($password.$this->salt);
    }

}
