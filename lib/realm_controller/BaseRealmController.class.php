<?php
 
abstract class BaseRealmController {
    public $realm;

    public $dispatcher;

    public $lettersProvider;

    /** @var BaseRealmBuilder $builder */
    public $builder;

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

    abstract public function getWinningConditions();

    abstract public function isWinner(User $user);

    public function addUser(User $user) {
        $userRealmLink = $this->createUserRealmLink($user->getId());
        $userRealmLink->save();
        $robot = $this->createRobot($user->getId());
        $robot->save();
        return $robot;
    }

    public function createRobot($userId)
    {
        $robot = new Robot();
        $robot->setUserId($userId);
        $robot->setRealmId($this->getRealm()->getId());
        $robot->randomizeSector();
        return $robot;
    }

    public function createUserRealmLink($userId)
    {
        $userRealmLink = new UserRealm();
        $userRealmLink->setUserId($userId);
        $userRealmLink->setRealmId($this->getRealm()->getId());
        return $userRealmLink;
    }

    public function setDispatcher($dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    public function setRealm($realm)
    {
        $this->realm = $realm;
    }

    public function getRealm()
    {
        return $this->realm;
    }

    public function getBuilder() {
        if (empty($this->builder)) {
            $this->builder = $this->createBuilder();
        }
        return $this->builder;
    }

    public function createBuilder() {
        $builderClassname = str_replace('Controller', 'Builder', get_class($this));
        return new $builderClassname($this);
    }

    public function setBuilder(BaseRealmBuilder $builder) {
        $this->builder = $builder;
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

    public function getConnection() {
        return Doctrine_Manager::getInstance()->getCurrentConnection();
    }

}
