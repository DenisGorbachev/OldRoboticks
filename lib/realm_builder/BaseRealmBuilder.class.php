<?php

abstract class BaseRealmBuilder {
    /** @var BaseRealmController $controller */
    public $controller;

    public $sectorsInfo = array();

    function __construct(BaseRealmController $controller) {
        $this->controller = $controller;
    }

    public function build() {
        $connection = $this->getConnection();
        $connection->beginTransaction();
        try {
            $this->doBuild();
        } catch (Exception $e) {
            $connection->rollback();
            throw $e;
        }
        $connection->commit();
    }

    abstract public function doBuild();

    public function generateSectors($blX, $blY, $trX, $trY, $letterProbability, $dropProbability) {
        for ($x = $blX; $x <= $trX; $x++) {
            for ($y = $blY; $y <= $trY; $y++) {
                if (!$this->getSectorInfoByCoords($x, $y)) {
                    $this->generateRandomSector($x, $y, $letterProbability, $dropProbability);
                }
            }
        }
    }

    public function generateRandomSector($x, $y, $letterProbability, $dropProbability) {
        return $this->generateSector($x, $y, $this->getRandomLetter($letterProbability), $this->getRandomDrops($dropProbability));
    }

    public function getRandomLetter($letterProbability) {
        return $this->getRandomTruth($letterProbability) ? $this->getLettersProvider()->getRandomLetter() : '';
    }

    public function getRandomDrops($dropProbability) {
        $lettersProvider = $this->getLettersProvider();
        $drops = '';
        while ($this->getRandomTruth($dropProbability)) {
            $drops .= $lettersProvider->getRandomLetter();
        }
        return $drops;
    }

    public function generateSector($x, $y, $letter, $drops) {
        $sector = new Sector();
        $sector->setRealmId($this->getRealm()->getId());
        $sector->setX($x);
        $sector->setY($y);
        $sector->setLetter($letter);
        $sector->setDrops($drops);
        $sector->save($this->getConnection());
        $sector->free(true);
        $this->setSectorInfoByCoords($x, $y, $sector->toArray());
        return $sector;
    }

    public function ensureAllLetters($blX, $blY, $trX, $trY) {
        $this->ensureLetters($blX, $blY, $trX, $trY, $this->getLettersProvider()->getLetters());
    }

    public function ensureLetters($blX, $blY, $trX, $trY, $letters) {
        foreach ($letters as $letter) {
            $this->ensureLetter($blX, $trX, $blY, $trY, $letter);
        }
    }

    public function ensureLetter($blX, $trX, $blY, $trY, $letter) {
        $sieve = array();
        $row = array_flip(range($blY, $trY));
        for ($x = $blX; $x <= $trX; $x++) {
            $existingSectors = $this->getSectorsInfoRow($x);
            $nonExistingSectors = array_diff_key($row, $existingSectors);
            if (!empty($nonExistingSectors)) {
                $sieve[$x] = $nonExistingSectors;
            }
        }
        if (empty($sieve)) {
            throw new sfException('Can\'t ensure letter "'.$letter.'", because all sectors in ('.$blX.','.$blY.')-('.$trX.','.$trY.') square are already generated');
        }
        $letterSectorX = array_rand($sieve);
        $letterSectorY = array_rand($sieve[$x]);
        return $this->generateSector($letterSectorX, $letterSectorY, $letter, '');
    }

    public function getRandomTruth($probability) {
        return mt_rand(0, 100) / 100 < (float)$probability;
    }

    public function getLettersProvider() {
        return $this->controller->getLettersProvider();
    }

    public function getRealm() {
        return $this->controller->getRealm();
    }

    public function getConnection() {
        return $this->controller->getConnection();
    }

    /**
     * @param \BaseRealmController $controller
     */
    public function setController($controller) {
        $this->controller = $controller;
    }

    /**
     * @return \BaseRealmController
     */
    public function getController() {
        return $this->controller;
    }

    public function setSectorsInfo($sectorsInfo) {
        $this->sectorsInfo = $sectorsInfo;
    }

    public function getSectorsInfo() {
        return $this->sectorsInfo;
    }

    public function setSectorInfoByCoords($x, $y, $sectorInfo) {
        if (empty($this->sectorsInfo[$x])) {
            $this->sectorsInfo[$x] = array();
        }
        $this->sectorsInfo[$x][$y] = $sectorInfo;
    }

    public function getSectorInfoByCoords($x, $y) {
        if (isset($this->sectorsInfo[$x]) && isset($this->sectorsInfo[$x][$y])) {
            return $this->sectorsInfo[$x][$y];
        }
    }

    public function getSectorsInfoRow($x) {
        if (empty($this->sectorsInfo[$x])) {
            return array();
        }
        return $this->sectorsInfo[$x];
    }
}
