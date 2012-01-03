<?php
 
class GenericRealmController extends BaseRealmController {
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
        $realm = $this->getRealm();
        $width = $realm->getWidth();
        $height = $realm->getHeight();
        $randomLettersProvider = WordTable::getInstance();
        $connection = Doctrine_Manager::getInstance()->getCurrentConnection();
        $connection->beginTransaction();
        try {
            for ($x = 0; $x < $width; $x++) {
                for ($y = 0; $y < $height; $y++) {
                    $this->generateSector($realm, $x, $y, $randomLettersProvider, $connection);
                }
            }
        } catch (Exception $e) {
            $connection->rollback();
            throw $e;
        }
        $connection->commit();
    }

    public function generateSector($realm, $x, $y, $randomLettersProvider, $connection) {
        $sector = new Sector();
        $sector->setRealmId($realm->getId());
        $sector->setX($x);
        $sector->setY($y);
        if ($this->getRandomTruth($realm->getOption('letter_probability', 0.1))) {
            $sector->setLetter($randomLettersProvider->getRandomLetter());
        }
        while ($this->getRandomTruth($realm->getOption('drop_probability', 0.1))) {
            $sector->setDrops($sector->getDrops(). $randomLettersProvider->getRandomLetter());
        }
        $sector->save($connection);
        $sector->free(true);
        unset($sector);
    }

    public function getRandomTruth($probability) {
        return mt_rand(0, 100) / 100 < (float)$probability;
    }

}
