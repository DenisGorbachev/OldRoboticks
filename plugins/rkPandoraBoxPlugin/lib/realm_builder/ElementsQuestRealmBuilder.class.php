<?php
 
class ElementsQuestRealmBuilder extends GenericRealmBuilder {
    public function doBuild() {
        $realm = $this->getRealm();
        foreach ($this->getController()->getElements() as $element=>$percents) {
            $this->generateElement($element, $realm->getWidth()*$percents[0], $realm->getHeight()*$percents[1]);
        }
        return parent::doBuild();
    }

    public function generateElement($element, $sx, $sy) {
        $letters = str_split($element);
        $sx -= count($letters) - 1;
        foreach ($letters as $letter) {
            $this->generateSector($sx, $sy, $letter, str_repeat($letter, 5));
            $sx += 2;
        }
    }
    
}
