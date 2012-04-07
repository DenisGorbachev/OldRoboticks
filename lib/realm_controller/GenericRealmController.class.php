<?php
 
class GenericRealmController extends BaseRealmController {
    public function attachListeners() {
        $this->dispatcher->connect('robot.post_do_disassemble_action', array($this, 'generateDisassembleNotification'));
        $this->dispatcher->connect('robot.post_do_repair_action', array($this, 'generateRepairNotification'));
        $this->dispatcher->connect('robot.hit', array($this, 'generateHitNotification'));
        $this->dispatcher->connect('robot.destroyed', array($this, 'generateDestroyedNotification'));
    }

    public function getWinningConditions() {
        return array(
            array(
                'text' => 'To eliminate all opponents.',
            )
        );
    }

    public function isWinner(User $user) {
        return $this->getRealm()->isTheOnlyActiveUser($user->getId()) && $this->getRealm()->getUsersCount() > 1;
    }

    public function generateDisassembleNotification(sfEvent $event) {
        if (!$this->isOwnEvent($event->getSubject()->getRealmId())) {
            return;
        }
        return $this->generateNotification($event, 'disasm', 'My robot '.$event->getSubject().' disassembled your robot '.$event['target']);
    }

    public function generateHitNotification(sfEvent $event) {
        if (!$this->isOwnEvent($event->getSubject()->getRealmId())) {
            return;
        }
        return $this->generateNotification($event, 'hit', 'My robot '.$event->getSubject().' hit your robot '.$event['target']->__toStatusString());
    }

    public function generateDestroyedNotification(sfEvent $event) {
        if (!$this->isOwnEvent($event->getSubject()->getRealmId())) {
            return;
        }
        return $this->generateNotification($event, 'destroyed', 'My robot '.$event->getSubject().' destroyed your robot '.$event['target']->__toStatusString());
    }

    public function generateRepairNotification(sfEvent $event) {
        if (!$this->isOwnEvent($event->getSubject()->getRealmId())) {
            return;
        }
        return $this->generateNotification($event, 'repair', 'My robot '.$event->getSubject().' repaired your robot '.$event['target']->__toStatusString());
    }

}
