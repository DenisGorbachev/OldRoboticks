<?php

class rsPostValidatorSector extends rsPostValidatorDoctrineChoice {
    protected function configure($options = array(), $messages = array()) {
        parent::configure($options, $messages);

        $this->setOption('model', 'Sector');
        $this->setOption('columns', array('x', 'y'));

        $this->addRequiredOption('current');

        $this->setMessage('invalid', 'Sector with such coordinates doesn\'t exist');
    }

    protected function doClean($values) {
        if (!empty($values['relative'])) {
            $current = $this->getOption('current');
            $values['x'] += $current->x;
            $values['y'] += $current->y;
        }
        return parent::doClean($values);
    }
}
