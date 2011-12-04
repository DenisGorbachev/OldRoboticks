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

    public function getLetters() {
        return $this->language['letters'];
    }

    public function getVowels() {
    	return $this->language['vowels'];
    }

    public function getConsonants() {
    	return array_diff($this->getLetters(), $this->getVowels());
    }

    public function getFunctions() {
    	return $this->language['functions'];
    }

    public function isLetter($candidate) {
        return in_array($candidate, $this->getLetters());
    }

    public function hasLetter($word, $letter) {
        return mb_strpos($word, $letter) !== false;
    }

    public function getPreviousLetter($letter) {
        $letters = $this->getLetters();
        $letterIndex = array_search($letter, $letters);
        $previousLetterIndex = $letterIndex - 1;
        if ($previousLetterIndex < 0) {
            $previousLetterIndex = count($letters) - 1;
        }
        $previousLetter = $letters[$previousLetterIndex];
        return $previousLetter;
    }

}
