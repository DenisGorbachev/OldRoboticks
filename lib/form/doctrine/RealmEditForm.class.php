<?php

/**
 * Realm form.
 *
 * @package    robotics
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class RealmEditForm extends RealmForm
{
    public function configure() {

        parent::configure();
    }

    public function getSuccessText() {
        return 'edited realm %realm%.';
    }

    public function getSuccessArguments() {
        return array(
            'realm' => (string)$this->getObject(),
        );
    }

}
