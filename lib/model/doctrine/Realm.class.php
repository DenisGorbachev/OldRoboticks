<?php

/**
 * Realm
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    robotics
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Realm extends BaseRealm
{
    public $salt = '301fd763d41594cacdedb18b53e265ee';

    public function __toString() {
        return '#'.$this->id.' "'.$this->name.'" ['.$this->type.']';
    }

    public function setPassword($password) {
        $this->_set('password', md5($password.$this->salt));
    }

    public function checkPassword($password) {
      return $this->password == md5($password.$this->salt);
    }

}