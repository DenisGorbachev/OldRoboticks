<?php

class Doctrine_Template_Restricted extends Doctrine_Template
{
    protected $_options = array(
        'column' => null,
    );

    public function setTableDefinition()
    {
        $listener = new Doctrine_Template_RestrictedListener();
        $listener->setOption('column', $this->getOption('column'));
        $this->addListener($listener);
    }

}
