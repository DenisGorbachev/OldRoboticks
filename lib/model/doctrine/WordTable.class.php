<?php


class WordTable extends Doctrine_Table
{
    public $language = array();

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

    public function getRandomLetter() {
        return $this->language['letters'][array_rand($this->language['letters'])];
    }

}
