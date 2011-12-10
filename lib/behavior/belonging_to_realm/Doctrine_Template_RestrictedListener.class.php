<?php

class Doctrine_Template_RestrictedListener extends Doctrine_Record_Listener
{
    public function preDqlDelete(Doctrine_Event $event)
    {
        $this->restrictAccess($event);
    }

    public function preDqlUpdate(Doctrine_Event $event)
    {
        $this->restrictAccess($event);
    }

    public function preDqlSelect(Doctrine_Event $event)
    {
        $this->restrictAccess($event);
    }

    protected function restrictAccess($event)
    {
        if (!sfContext::hasInstance()) {
            return;
        }

        /** @var $invoker Doctrine_Record */
        $invoker = $event->getInvoker();

        /** @var $query Doctrine_Query */
        $query = $event->getQuery();

        $parameters = $event->getParams();
        $alias = $parameters['alias'];
        $column = $this->getOption('column');
        $query->addWhere($alias . '.' . $column . ' = ?', sfConfig::get('app_' . $column));

    }

}
