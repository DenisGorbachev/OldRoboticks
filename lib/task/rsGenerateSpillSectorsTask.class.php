<?php

class rsGenerateSmartSectorsTask extends rsGenerateSectorsTask {
    protected function configure() {
        parent::configure();

        $this->addOptions(array(
            new sfCommandOption('quadrant-size', 's', sfCommandOption::PARAMETER_REQUIRED, 'Size of quadrant with only one spill', 20),
            new sfCommandOption('min-spill-size', 'i', sfCommandOption::PARAMETER_REQUIRED, 'Minimum size of vowel spill', 1),
            new sfCommandOption('max-spill-size', 'a', sfCommandOption::PARAMETER_REQUIRED, 'Maximum size of vowel spill', 4),
        ));

        $this->name = 'generate-spill-sectors';
    }

    protected function execute($arguments = array(), $options = array()) {
        return parent::execute($arguments, $options);
    }

    public function generateMap($letters) {
        parent::generateMap($letters);
        $this->generateSpills();
    }

    public function generateSpills() {
        for ($y = 0; $y < $this->arguments['size']; $y+=$this->options['quadrant-size']) {
            for ($x = 0; $x < $this->arguments['size']; $x+=$this->options['quadrant-size']) {
                $size = mt_rand($this->options['min-spill-size'], $this->options['max-spill-size']);
                $vowel = $this->generateVowel();
                $sx = mt_rand($x+$size, $x+$this->options['quadrant-size']-$size);
                $sy = mt_rand($y+$size, $y+$this->options['quadrant-size']-$size);
                $this->generateRoundSpill($size, $vowel, $sx, $sy);
            }
        }
    }

    public function generateVowel() {
        return $this->rand($this->language['vowels']);
    }

    public function generateRoundSpill($size, $vowel, $sx, $sy) {
        for ($iy = $sy-$size; $iy <= $sy+$size; $iy++) {
            for ($ix = $sx-$size; $ix <= $sx+$size; $ix++) {
                if (pow($iy-$sy, 2) + pow($ix-$sx, 2) <= pow($size, 2) && array_key_exists($ix, $this->map) && array_key_exists($iy, $this->map[$ix])) {
                    $this->map[$ix][$iy] = $vowel;
                }
            }
        }
    }

    public function generateDiamondSpill($size, $vowel, $sx, $sy) {
        for ($iy = $sy-$size; $iy <= $sy+$size; $iy++) {
            for ($ix = $sx-$size; $ix <= $sx+$size; $ix++) {
                if ((abs($ix - $sx) + abs($iy - $sy) <= $size) && array_key_exists($ix, $this->map) && array_key_exists($iy, $this->map[$ix])) {
                    $this->map[$ix][$iy] = $vowel;
                }
            }
        }
    }

}
