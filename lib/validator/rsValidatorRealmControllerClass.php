<?php

class rsValidatorRealmControllerClass extends sfValidatorString {
    protected function configure($options = array(), $messages = array())
    {
        $this->addMessage('not_found', 'Realm controller class "%controllerClass%" not found.');
        $this->addMessage('not_subclass_of_base_controller', 'Realm controller class "%controllerClass%" is not a subclass of "%baseControllerClass%".');

        $this->addOption('baseControllerClass', 'BaseRealmController');
        parent::configure($options, $messages);
    }

    protected function doClean($value)
    {
        $controllerClass = parent::doClean($value);
        if (!class_exists($controllerClass)) {
            throw new sfValidatorError($this, 'not_found', array(
                'controllerClass' => $controllerClass
            ));
        }
        $baseControllerClass = $this->getOption('baseControllerClass');
        if (!is_subclass_of($controllerClass, $baseControllerClass)) {
            throw new sfValidatorError($this, 'not_subclass_of_base_controller', array(
                'controllerClass' => $controllerClass,
                'baseControllerClass' => $baseControllerClass
            ));
        }
        return $controllerClass;
	}
}
