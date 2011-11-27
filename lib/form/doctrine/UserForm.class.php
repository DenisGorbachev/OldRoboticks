<?php

/**
 * User form.
 *
 * @package		robotics
 * @subpackage form
 * @author		 Your name here
 * @version		SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class UserForm extends BaseUserForm {
	public function configure() {
		parent::configure();

        foreach (array('username', 'password') as $field) {
            $this->validatorSchema[$field]->setOption('min_length', 3);
            $this->validatorSchema[$field]->setOption('max_length', 32);
            $this->validatorSchema[$field] = new sfValidatorAnd(array(
                $this->validatorSchema[$field],
                new sfValidatorRegex(array(
                    'pattern' => '/^[\d\w-_]+$/'
                ))
            ));
        }
	}
}
