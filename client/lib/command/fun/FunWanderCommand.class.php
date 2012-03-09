<?php

require_once dirname(__FILE__) . '/../base/FunCommand.class.php';
require_once dirname(__FILE__) . '/../MvCommand.class.php';

class FunWanderCommand extends FunCommand {
	public function getParserConfig() {
		return array_merge(parent::getParserConfig(), array(
			'description' => 'Move in circles and execute a supplied command at each checkpoint. Example action succession: mv 10,10; command; mv 10,21; command; mv 21,21; command; mv 21,10; command; ...'
		));
	}

    public function getOptionConfigs() {
        return array_merge(parent::getOptionConfigs(), array(
            'range' => array(
                'short_name' => '-r',
                'long_name' => '--range',
                'description' => 'Half the jump range',
                'action' => 'StoreInt',
                'default' => 5
            )
        ));
    }

	public function getArgumentConfigs() {
		return array_merge(parent::getArgumentConfigs(), array(
            'base' => array(
                'description' => 'The center of circle'
            ),
            'command' => array(
                'description' => 'The command to execute at checkpoint'
            ),
		));
	}

    public function step($options, $arguments) {
        if (empty($this->cache['base']) || $this->cache['base'] != $this->getArgument('base')) {
            $this->cache = array(
                'base' => $this->getArgument('base'),
                'target' => coords_string_to_array($this->getArgument('base')),
            );
        }
        if (empty($this->cache['target_reached'])) {
            $command = new MvCommand($this->getConfig());
            $command->setArguments(array(
                'sector' => $this->cache['target']
            ));
            $result = $command->run();
            if (isset($result['message']['arguments']['sector']) && coords_string_to_array($result['message']['arguments']['sector']) == $this->cache['target']) {
                $this->cache['target_reached'] = true;
            }
        } else {
            $command = $this->getArgument('command');
            echo `$command`;
            $targetX = $this->cache['target']['x'];
            $targetY = $this->cache['target']['y'];
            $diffX = $this->cache['target']['x'] - $this->cache['base']['x'];
            $diffY = $this->cache['target']['y'] - $this->cache['base']['y'];
            var_dump($diffX);
            var_dump($diffY);
            $increment = 2 * $this->getOption('range') + 1;
            if (abs($diffX) > abs($diffY)) {
                $targetY += ($diffX > 0? -1 : 1) * $increment;
            } else if (abs($diffX) < abs($diffY)) {
                $targetX += ($diffY > 0? 1 : -1) * $increment;
            } else if ($diffX == $diffY) {
                $targetY += ($diffX > 0? -1 : 1) * $increment;
            } else if (abs($diffX) == $diffY) {
                $targetY += $increment;
            } else {
                $targetX += ($diffY > 0? 1 : -1) * $increment;
            }
            $this->cache['target'] = array(
                'x' => $targetX,
                'y' => $targetY,
            );
            $this->cache['target_reached'] = false;
        }
    }

}
