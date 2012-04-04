<?php

class ActionArray extends Console_CommandLine_Action {
    public function execute($value = false, $params = array()) {
        $choices = $params['choices'];
        if (!in_array($value, $choices)) {
            $shortcuts = array_keys($choices);
            if (!in_array($value, $shortcuts)) {
                throw new Exception(sprintf(
                    'Value of option %s is not valid. Possible values are: %s.',
                    $this->option->name,
                    implode(', ', $params['choices'])
                ));
            }
            $value = $choices[$value];
        }
        $this->setResult($value);
    }
}
