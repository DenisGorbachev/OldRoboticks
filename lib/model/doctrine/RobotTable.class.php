<?php


class RobotTable extends Doctrine_Table {
	public $language;

    /**
     * @static
     * @return RobotTable
     */
    public static function getInstance() {
        return Doctrine_Core::getTable('Robot');
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
    
    public function getFunctionDenotative($meaning) {
    	$array = $this->getFunctions();
    	return $array[$meaning];
    }

    public function getFunctionMeaning($denotative) {
    	return array_search($denotative, $this->getFunctions());
    }
    
    public function getOwnedQuery($userId) {
    	return $this->createQuery('r')
    		->where('r.user_id = ?', $userId);
    }
    
    public function getListQuery($userId) {
    	return $this->getOwnedQuery($userId)
    		->select('r.*, s.*')
    		->leftJoin('r.Sector s');
    }
    
    public function getList($userId) {
    	return $this->getListQuery($userId)->execute();
    }
    
}
