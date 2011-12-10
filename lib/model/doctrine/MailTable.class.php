<?php

/**
 * MailTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class MailTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object MailTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Mail');
    }

    public function getNextMailQuery($recipient_id, $realm_id) {
        $query = $this->createQuery('m')
            ->andWhere('m.recipient_id = ?', $recipient_id)
            ->orderBy('m.created_at');
        if ($realm_id) {
            $query->andWhere('m.realm_id = ?', $realm_id);
        }
        return $query;
    ;}

    public function getNextUnreadMailQuery($recipient_id, $realm_id) {
        return $this->getNextMailQuery($recipient_id, $realm_id)
            ->andWhere('m.is_read = ?', false)
    ;}

    public function getNextUnreadMail($recipient_id, $realm_id) {
        return $this->getNextUnreadMailQuery($recipient_id, $realm_id)->fetchOne();
    }
    
}
