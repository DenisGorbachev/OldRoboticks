<?php

/**
 * Realm form.
 *
 * @package    robotics
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class RealmCreateForm extends RealmForm
{
    public function getSuccessText() {
        return 'created realm %realm% for %owner%';
    ;}

    public function getSuccessArguments() {
        return array(
            'realm' => (string)$this->getObject(),
            'owner' => (string)$this->getObject()->getOwner()
        );
    }

}
