<?php

/**
 * Realm form.
 *
 * @package    robotics
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class RealmForm extends BaseRealmForm
{
    public function setup()
    {
        parent::setup();
        $this->validatorSchema['controller_class'] = new rsValidatorRealmControllerClass();
    }

    public function configure()
  {
      parent::configure();
  }
}
