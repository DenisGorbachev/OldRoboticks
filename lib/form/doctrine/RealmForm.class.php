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
        $this->validatorSchema['width']->setOption('min', 10);
        $this->validatorSchema['width']->setOption('max', 1000);
        $this->validatorSchema['height']->setOption('min', 10);
        $this->validatorSchema['height']->setOption('max', 1000);
        $this->validatorSchema['password']->setOption('required', false);
    }

    public function configure()
    {
        parent::configure();
    }
}
