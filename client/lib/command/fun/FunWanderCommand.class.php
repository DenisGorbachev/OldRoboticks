<?php

require_once dirname(__FILE__) . '/../base/FunCommand.class.php';
require_once dirname(__FILE__) . '/../MvCommand.class.php';
require_once dirname(__FILE__) . '/../realm/RealmShowCommand.class.php';

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
        $base = coords_string_to_array($this->getArgument('base'));
        if (empty($this->cache['base']) || $this->cache['base'] != $base) {
            $command = new RealmShowCommand($this->getConfig());
            $result = $command->run();
            $this->cache = array(
                'realm' => $result['message']['arguments'],
                'base' => $base,
                'target' => $base,
                'local_target' => $base,
                'local_target_reached' => false
            );
        }
        if (empty($this->cache['local_target_reached'])) {
            $command = new MvCommand($this->getConfig());
            $command->setArguments(array(
                'sector' => $this->cache['local_target']
            ));
            $result = $command->run();
            if ($result['success']) {
                if (isset($result['message']['arguments']['sector']) && coords_string_to_array($result['message']['arguments']['sector']) == $this->cache['local_target']) {
                    $this->cache['local_target_reached'] = true;
                }
            } else {
                return false;
            }
        } else {
            $this->checkpoint();
            $targetX = $this->cache['target']['x'];
            $targetY = $this->cache['target']['y'];
            $baseX = $this->cache['base']['x'];
            $baseY = $this->cache['base']['y'];
            $increment = 2 * $this->getOption('range') + 1;
            $halfIncrement = $this->getOption('range') + 1;
            $cycles = 0;
            $targets = array(array('x' => $targetX, 'y' => $targetY));
            do {
                $targets = array_fill(0, 4, $targets[0]);
                $diffX = $targets[0]['x'] - $baseX;
                $diffY = $targets[0]['y'] - $baseY;
                $isUpperLeftCorner = abs($diffX) == $diffY;
                $cycles += $isUpperLeftCorner;
                if (abs($diffX) > abs($diffY) || $diffX == $diffY || $isUpperLeftCorner) {
                    $sign = $diffX > 0 ? -1 : 1;
                    $targets[0]['x'] = $targets[0]['x'];
                    $targets[0]['y'] += $sign * $increment;

                    $targets[1]['x'] += $sign * $halfIncrement;
                    $targets[1]['y'] += $sign * $increment;

                    $targets[2]['x'] = $targets[2]['x'];
                    $targets[2]['y'] += $sign * $halfIncrement;

                    $targets[3]['x'] += $sign * $halfIncrement;
                    $targets[3]['y'] += $sign * $halfIncrement;
                } else {
                    $sign = $diffY > 0 ? 1 : -1;
                    $targets[0]['x'] += $sign * $increment;
                    $targets[0]['y'] = $targets[0]['y'];

                    $targets[1]['x'] += $sign * $increment;
                    $targets[1]['y'] -= $sign * $halfIncrement;

                    $targets[2]['x'] += $sign * $halfIncrement;
                    $targets[2]['y'] = $targets[0]['y'];

                    $targets[3]['x'] += $sign * $halfIncrement;
                    $targets[3]['y'] -= $sign * $halfIncrement;
                }
                foreach ($targets as $localTarget) {
                    if ($localTarget['x'] > 0 && $localTarget['x'] < $this->cache['realm']['width'] && $localTarget['y'] > 0 && $localTarget['y'] < $this->cache['realm']['height']) {
                        $this->cache['target'] = $targets[0];
                        $this->cache['local_target'] = $localTarget;
                        $this->cache['local_target_reached'] = false;
                        return;
                    }
                }
            } while ($cycles < 3);
            return false;
        }
    }

    public function checkpoint() {
        $command = $this->getArgument('command');
        echo `$command`;
    }

}
