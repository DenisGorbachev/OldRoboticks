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
        $connection = Doctrine_Manager::getInstance()->getCurrentConnection();
        $connection->beginTransaction();
        try {
            for ($x = 0; $x < $realm->getWidth(); $x++) {
                for ($y = 0; $y < $realm->getHeight(); $y++) {
                    $this->generateSector($x, $y, $connection);
                }
            }
        } catch (Exception $e) {
            $connection->rollback();
            throw $e;
        }
        $connection->commit();
    }

    public function generateSector($x, $y, $connection) {
        $realm = $this->getRealm();
        $sector = new Sector();
        $sector->setRealm($realm);
        $sector->setX($x);
        $sector->setY($y);
        if ($this->getRandomTruth($realm->getOption('letter_probability', 0.1))) {
            $sector->setLetter(WordTable::getInstance()->getRandomLetter());
        }
        while ($this->getRandomTruth($realm->getOption('drop_probability', 0.1))) {
            $sector->setDrops($sector->getDrops().WordTable::getInstance()->getRandomLetter());
        }
        $sector->save($connection);
    }

    public function getRandomTruth($probability) {
        return mt_rand(0, 100) / 100 < (float)$probability;
    }

}
