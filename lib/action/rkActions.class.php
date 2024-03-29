<?php

class rkActions extends tfExtendedActions {
    public function prepareFailed(rsException $e) {
        return $this->somethingFailed($e);
    }

    public function validateFailed(rsException $e) {
        return $this->somethingFailed($e);
    }

    public function somethingFailed(rsException $e) {
        $type = 'failure';
        if ($e instanceof rsInactivityException) {
            $type = 'wait';
        }
        return $this->{$type}($e->getText(), $e->getArguments());
    }

    public function prepareAutoRealm() {
        $this->argumentUnless('realm_id');
        $this->realm = RealmTable::getInstance()->find($this->realm_id);
        $this->failureUnless($this->realm, 'Realm #%realm_id% doesn\'t exist', array(
            'realm_id' => (string)$this->realm_id
        ));
        $this->failureUnless(UserRealmTable::getInstance()->findOneByRealmIdAndUserId($this->realm_id, $this->getUser()->getUser()->getId()), 'You don\'t have access to realm %realm%', array(
            'realm' => (string)$this->realm
        ));
        sfConfig::set('app_realm_id', $this->realm_id);
    }

    public function prepareAutoSector() {
        $this->argumentUnless('x');
        $this->argumentUnless('y');
        $this->sector = SectorTable::getInstance()->findOneByXAndY($this->x, $this->y);
        $this->failureUnless($this->sector, 'sector %x%,%y% doesn\'t exist', array(
            'x' => $this->x,
            'y' => $this->y,
        ));
    }

    public function success($text, array $arguments = array()) {
        if ($this->getUser()->isAuthenticated()) {
            $realm_id = $this->getRequestParameter('realm_id');
            $counts = MailTable::getInstance()->getNotificationCounts($this->getUser()->getUser()->getId(), $realm_id);
            $notifications = array();
            foreach ($counts as $count) {
                $notifications[] = array(
                    'text' => 'You have %count% unread'.($count['realm_id']? ' realm' : ' personal').' mail.',
                    'arguments' => $count
                );
            }
            $this->add('notifications', array_merge($this->get('notifications', array()), $notifications));
        }
        return parent::success($text, $arguments);
    }

}
