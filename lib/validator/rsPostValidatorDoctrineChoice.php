<?php

class rsPostValidatorDoctrineChoice extends sfValidatorBase {
    protected function configure($options = array(), $messages = array()) {
        parent::configure($options, $messages);
        $this->addRequiredOption('model');
        $this->addRequiredOption('columns');
        $this->addOption('query', null);

        $this->setMessage('invalid', 'The object with fields "%s" doesn\'t exist');
    }

    protected function doClean($values) {
        $table = Doctrine_Core::getTable($this->getOption('model'));
        if ($query = $this->getOption('query')) {
            $query = clone $query;
        } else {
            $query = $table->createQuery();
        }
        $columnNames = $this->getOption('columns');
        foreach ($columnNames as $columnName) {
            $column = $table->getColumnName($columnName);
            $query->andWhere(sprintf('%s.%s = ?', $query->getRootAlias(), $column), $values[$columnName]);
        }
        if (!$query->count()) {
            throw new sfValidatorError($this, 'invalid', array('fields' => implode('", "', $columnNames)));
        }
        return $values;
    }
}
