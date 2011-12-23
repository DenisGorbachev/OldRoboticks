<?php
 
abstract class BaseRealmController {
    public $realm;
    public $dispatcher;

    public function __construct(Realm $realm, sfEventDispatcher $dispatcher) {
        $this->realm = $realm;
        $this->dispatcher = $dispatcher;
        
        $this->configure();
    }

    public function configure() {
        $this->attachListeners();
    }

    public function isOwnEvent($realm_id) {
        return $this->realm->getId() == $realm_id;
    }

    public function generateNotification(sfEvent $event, $type, $subject, $text = '') {
        $senderId = $event->getSubject()->getUserId();
        $recipientId = $event['target']->getUserId();
        $realmId = $event->getSubject()->getRealmId();
        if ($senderId == $recipientId) {
            return;
        }
        $notification = new Mail();
        $notification->setSenderId($senderId);
        $notification->setRecipientId($recipientId);
        $notification->setRealmId($realmId);
        $notification->setType($type);
        $notification->setSubject($subject);
        $notification->setText($text);
        $notification->save();
        return $notification;
    }

    abstract public function attachListeners();

    abstract public function initialize();

}
