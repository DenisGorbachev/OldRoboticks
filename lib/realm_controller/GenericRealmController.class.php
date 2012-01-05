<?php
 
class GenericRealmController extends BaseRealmController {
    public $lettersProvider;

    public function attachListeners() {
        $this->dispatcher->connect('robot.post_do_disassemble_action', array($this, 'generateDisassembleNotification'));
        $this->dispatcher->connect('robot.post_do_fire_action', array($this, 'generateFireNotification'));
        $this->dispatcher->connect('robot.post_do_repair_action', array($this, 'generateRepairNotification'));
    }

    public function generateDisassembleNotification(sfEvent $event) {
        if (!$this->isOwnEvent($event->getSubject()->getRealmId())) {
            return;
        }
        return $this->generateNotification($event, 'disasm', 'My robot '.$event->getSubject().' disassembled your robot '.$event['target']);
    }

    public function generateFireNotification(sfEvent $event) {
        if (!$this->isOwnEvent($event->getSubject()->getRealmId())) {
            return;
        }
        return $this->generateNotification($event, 'fire', 'My robot '.$event->getSubject().' fired at your robot '.$event['target']->__toStatusString());
    }

    public function generateRepairNotification(sfEvent $event) {
        if (!$this->isOwnEvent($event->getSubject()->getRealmId())) {
            return;
        }
        return $this->generateNotification($event, 'repair', 'My robot '.$event->getSubject().' repaired your robot '.$event['target']->__toStatusString());
    }

    public function initialize() {
        $connection = Doctrine_Manager::getInstance()->getCurrentConnection();
        $connection->beginTransaction();
        try {
            $existingSectorsInfo = $this->doInitialize($connection);
        } catch (Exception $e) {
            $connection->rollback();
            throw $e;
        }
        $connection->commit();
        return $existingSectorsInfo;
    }

    public function doInitialize($connection)
    {
        $realm = $this->getRealm();
        $width = $realm->getWidth();
        $height = $realm->getHeight();
        $existingSectorsInfo = array();
        $existingSectorsInfo = $this->ensureAllLetters(0, 0, $width-1, $height-1, $existingSectorsInfo, $connection);
        $existingSectorsInfo = $this->generateSectors(0, 0, $width-1, $height-1, $realm->getOption('letter_probability', 0.1), $realm->getOption('drop_probability', 0.1), $existingSectorsInfo, $connection);
        return $existingSectorsInfo;
    }

    public function generateSectors($blX, $blY, $trX, $trY, $letterProbability, $dropProbability, $existingSectorsInfo, $connection)
    {
        for ($x = $blX; $x <= $trX; $x++) {
            for ($y = $blY; $y <= $trY; $y++) {
                if (empty($existingSectorsInfo[$x][$y])) {
                    $sector = $this->generateRandomSector($x, $y, $letterProbability, $dropProbability, $connection);
                    if (empty($existingSectorsInfo[$x])) {
                        $existingSectorsInfo[$x] = array();
                    }
                    $existingSectorsInfo[$x][$y] = $sector->toArray();
                }
            }
        }
        return $existingSectorsInfo;
    }

    public function generateRandomSector($x, $y, $letterProbability, $dropProbability, $connection) {
        return $this->generateSector($x, $y, $this->getRandomLetter($letterProbability), $this->getRandomDrops($dropProbability), $connection);
    }

    public function getRandomLetter($letterProbability)
    {
        return $this->getRandomTruth($letterProbability) ? $this->getLettersProvider()->getRandomLetter() : '';
    }

    public function getRandomDrops($dropProbability)
    {
        $lettersProvider = $this->getLettersProvider();
        $drops = '';
        while ($this->getRandomTruth($dropProbability)) {
            $drops .= $lettersProvider->getRandomLetter();
        }
        return $drops;
    }

    public function generateSector($x, $y, $letter, $drops, $connection) {
        $sector = new Sector();
        $sector->setRealmId($this->getRealm()->getId());
        $sector->setX($x);
        $sector->setY($y);
        $sector->setLetter($letter);
        $sector->setDrops($drops);
        $sector->save($connection);
        $sector->free(true);
        return $sector;
    }

    public function ensureAllLetters($blX, $blY, $trX, $trY, $existingSectorsInfo, $connection) {
        $this->ensureLetters($blX, $blY, $trX, $trY, $existingSectorsInfo, $this->getLettersProvider()->getLetters(), $connection);
    }

    public function ensureLetters($blX, $blY, $trX, $trY, $existingSectorsInfo, $letters, $connection) {
        if (count($existingSectorsInfo) >= (($trX - $blX + 1) * ($trY - $blY + 1) - count($letters))) {
            // TODO: weak check
            throw new Exception('All sectors are taken, can\'t ensure that letters "' . implode(', ', $letters) . '" exist');
        }
        foreach ($letters as $letter) {
//            do {
//                $x = rand($blX, $trX);
//                $y = rand($blY, $trY);
//            } while ()
            $this->generateSector($x, $y, $letter, '', $connection);
        }
    }

    public function getRandomTruth($probability) {
        return mt_rand(0, 100) / 100 < (float)$probability;
    }

    public function setLettersProvider($randomLettersProvider)
    {
        $this->lettersProvider = $randomLettersProvider;
    }

    public function getLettersProvider()
    {
        if (empty($this->lettersProvider)) {
            $this->lettersProvider = $this->createLettersProvider();
        }
        return $this->lettersProvider;
    }

    public function createLettersProvider() {
        return WordTable::getInstance();
    }
    
}
