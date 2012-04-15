<?php

/**
 * Realm form.
 *
 * @package    robotics
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class BotAddForm extends RealmForm
{
    public function getSuccessText() {
        return 'created realm %realm% for %owner%, got new robots: %robots%.';
    }

    public function getSuccessArguments() {
        $robots = RobotTable::getInstance()->getOwned($this->getObject()->getOwnerId());
        $robotStrings = array();
        foreach ($robots as $robot) {
            $robotStrings[] = (string)$robot;
        }
        return array(
            'realm' => (string)$this->getObject(),
            'owner' => (string)$this->getObject()->getOwner(),
            'robots' => implode(', ', $robotStrings),
        );
    }

}
