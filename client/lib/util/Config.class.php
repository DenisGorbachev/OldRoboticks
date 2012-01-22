<?php

require_once dirname(__FILE__) . '/../yaml/sfYaml.php';

class Config {
    public $home;
    public $host;

    public function __construct() {
        $this->setHost($this->readHost());
    }

    public function setHost($host) {
        if (empty($host)) {
            return;
        }
        $this->host = $host;
        file_put_contents($this->getHostFilename(), $host);
        mkdir_if_not_exists($this->getCacheDirname());
        mkdir_if_not_exists($this->getLogDirname());
    }

    public function getHost() {
        return $this->host;
    }

    public function readHost() {
        $hostFilename = $this->getHostFilename();
        return file_exists($hostFilename)? file_get_contents($hostFilename) : '';
    }

    public function getHostFilename() {
        return $this->getHomeDirname() . '/host';
    }

    public function setVariable($name, $value) {
        file_put_contents($this->getVariableFilename($name), $value);
    }

    public function getVariable($name, $default = null) {
        $value = getenv('RK_' . strtoupper($name));
        if ($value) {
            return $value;
        }
        $filename = $this->getVariableFilename($name);
        if (file_exists($filename)) {
            $value = file_get_contents($filename);
            if ($value) {
                return $value;
            }
        }
        return $default;
    }

    public function getVariableFilename($name) {
        return $this->getCacheDirname() . '/' . $name;
    }

    public function getLibDirname() {
        return BASEDIR.'/lib';
    }

    public function getHomeDirname() {
        $home = getenv('HOME');
        return $home? $home.'/.roboticks' : BASEDIR.'/data';
    }

    public function getCacheDirname() {
        return $this->getHomeDirname() . '/' . $this->getHost() . '/cache';
    }

    public function getLogDirname() {
        return $this->getHomeDirname() . '/' . $this->getHost() . '/log';
    }

    public function isDebug() {
        return file_exists($this->getHomeDirname().'/debug');
    }

}
