<?php


class UserTable extends Doctrine_Table
{
    /**
     * @static
     * @return UserTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('User');
    }

    public function generatePassword() {
        return sha1(md5(mt_rand()));
    }

    public function getFreeBotUserQuery($botId, $userIds) {
        return $this->createQuery('u')
            ->innerJoin('u.UserBot ub')
            ->andWhere('ub.bot_id = ?', $botId)
            ->andWhereNotIn('u.id', $userIds)
    ;}

    public function getFreeBotUserForRealm($botId, $realmId) {
        $userIds = UserRealmTable::getInstance()->getUserIdsByRealmId($realmId);
        return $this->getFreeBotUserQuery($botId, $userIds)->fetchOne();
    }

}
