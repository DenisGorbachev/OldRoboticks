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

    public function hasDenotative($name, $denotative) {
        return mb_strpos($name, $denotative);
    }

    public function hasFunction($name, $meaning) {
        return $this->hasDenotative($name, $this->getFunctionDenotative($meaning));
    }

    public function getFunctionsForName($name) {
        $functions = array();
        foreach ($this->getFunctions() as $meaning=>$denotative) {
            if ($this->hasDenotative($name, $denotative)) {
                $functions[$meaning] = $denotative;
            }
        }
        return $functions;
    }

    public function getOwnedQuery($userId) {
    	return $this->createQuery('r')
    		->where('r.user_id = ?', $userId);
    }
    
    public function getListQuery($userId) {
    	return $this->getOwnedQuery($userId)
            ->leftJoin('r.Word w')
    		->leftJoin('r.Sector s')
    ;}
    
    public function getList($userId) {
    	$results = $this->getListQuery($userId)->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
        foreach ($results as &$object) {
            $object['functions'] = implode(',', $this->getFunctionsForName($object['Word']['name']));
        }
        return $results;
    }
    
}
