<?php
 
class ElementsQuestRealmBuilder extends GenericRealmBuilder {
    public $elements = array(
        'WATER' => array(10,10),
        'FIRE' => array(10,40),
        'EARTH' => array(40,40),
        'AIR' => array(40,10),
    );
    
    public function doBuild()
    {
        foreach ($this->elements as $element=>$coords) {
            $this->generateElement($element, $coords[0], $coords[1]);
        }
        return parent::doBuild();
    }

    public function generateElement($element, $sx, $sy) {
        foreach (str_split($element) as $letter) {
            $this->generateSector($sx, $sy, $letter, str_repeat($letter, 5));
            $sx += 2;
        }
    }
    
}
