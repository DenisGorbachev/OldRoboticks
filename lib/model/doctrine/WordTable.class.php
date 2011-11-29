<?php


class WordTable extends Doctrine_Table
{

    /**
     * @static
     * @return WordTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Word');
    }

    public function construct() {
    	$this->language = sfYaml::load(sfConfig::get('sf_config_dir').'/language.yml');
    }

    public function getFunctions() {
    	return $this->language['functions'];
    }

    public function getVowels() {
    	return $this->language['vowels'];
    }

    public function getConsonants() {
    	return $this->language['consonants'];
    }

    public function getLetters() {
        return array_merge($this->getVowels(), $this->getConsonants());
    }

    public function isLetter($candidate) {
        return in_array($candidate, $this->getLetters());
    }
}
