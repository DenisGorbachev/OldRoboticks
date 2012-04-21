<?php
 
class TutorialRealmBuilder extends GenericRealmBuilder {
    public function doBuild()
    {
        $realm = $this->getRealm();
        $width = $realm->getWidth();
        $height = $realm->getHeight();
        $this->generateSector(14, 14, 'A', '');
        $this->generateSector(14, 13, '', 'T');
        $this->generateSector(12, 13, 'E', '');
        $this->generateSector(13, 12, 'T', '');
        $this->generateSector(13, 13, 'D', '');
        $this->generateSector(7, 18, '', 'EFG');
        $this->generateSectors(0, 0, $width - 1, $height - 1, 0, 0);
    }

}
