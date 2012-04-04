<?php

class tfGuardedRecord extends sfDoctrineRecord {
    protected $guard;

    public function construct() {
        parent::construct();
        $class = get_class($this).'Guard';
        if (class_exists($class)) {
            $this->guard = new $class($this);
        }
    }

    public function getGuard() {
        return $this->guard;
    }

}
