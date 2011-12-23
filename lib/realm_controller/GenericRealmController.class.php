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
        
    }

}
