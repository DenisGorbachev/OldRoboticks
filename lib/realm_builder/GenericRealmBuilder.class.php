<?php

class GenericRealmBuilder extends BaseRealmBuilder {
    public function doBuild() {
        $realm = $this->getRealm();
        $width = $realm->getWidth();
        $height = $realm->getHeight();
        $this->ensureAllLetters(0, 0, $width - 1, $height - 1);
        $this->generateSectors(0, 0, $width - 1, $height - 1, $realm->getOption('letter_probability', 0.1), $realm->getOption('drop_probability', 0.1));
    }
    
}

