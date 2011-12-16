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

	public function getOwnMailQuery($recipient_id) {
		return $this->createQuery('m')
            ->andWhere('m.recipient_id = ?', $recipient_id)
	;}

    public function getNextMailQuery($recipient_id, $realm_id) {
        $query = $this->getOwnMailQuery($recipient_id)
            ->orderBy('m.created_at');
        if ($realm_id) {
            $query->andWhere('m.realm_id = ?', $realm_id);
        } else {
            $query->andWhere('m.realm_id IS NULL');
        }
        return $query;
    }

    public function getNextUnreadMailQuery($recipient_id, $realm_id) {
        return $this->getNextMailQuery($recipient_id, $realm_id)
            ->andWhere('m.is_read = ?', false)
    ;}

    public function getNextUnreadMail($recipient_id, $realm_id) {
        return $this->getNextUnreadMailQuery($recipient_id, $realm_id)->fetchOne();
    }

	public function getNotificationCountQuery($recipient_id, $realm_id = null) {
		$query = $this->getOwnMailQuery($recipient_id)
            ->select('m.realm_id, COUNT(id) as count')
            ->andWhere('m.is_read = ?', false)
			->groupBy('m.realm_id');
		if ($realm_id) {
			$query->andWhere('m.realm_id = ? OR m.realm_id IS NULL', $realm_id);
		} else {
			$query->andWhere('m.realm_id IS NULL');
		}
		return $query;
	;}

    public function getNotificationCounts($recipient_id, $realm_id = null) {
        return $this->getNotificationCountQuery($recipient_id, $realm_id)->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
    }
    
}
