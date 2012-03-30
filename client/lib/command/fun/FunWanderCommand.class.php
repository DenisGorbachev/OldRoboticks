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
        if (empty($this->cache['base']) || $this->cache['base'] != $this->getArgument('base')) {
            $command = new RealmShowCommand($this->getConfig());
            $result = $command->run();
            $localTarget = coords_string_to_array($this->getArgument('base'));
            $this->cache = array(
                'realm' => $result['message']['arguments'],
                'base' => $this->getArgument('base'),
                'target' => $localTarget,
                'local_target' => $localTarget,
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
            $command = $this->getArgument('command');
            echo `$command`;
            $targetX = $this->cache['target']['x'];
            $targetY = $this->cache['target']['y'];
            var_dump($this->cache['target']);
            $baseX = $this->cache['base']['x'];
            $baseY = $this->cache['base']['y'];
            $iterations = 0;
            $targets = array_fill(0, 4, array('x' => $targetX, 'y' => $targetY));
            do {
                var_dump('gogogo');
                $diffX = $targets[0]['x'] - $baseX;
                $diffY = $targets[0]['y'] - $baseY;
                $increment = 2 * $this->getOption('range') + 1;
                if (abs($diffX) > abs($diffY) || abs($diffX) == $diffY) {
                    var_dump('in 1');
                    $targets[0]['x'] = $targets[0]['x'];
                    $sign = $diffX > 0 ? -1 : 1;
                    $targets[0]['y'] += $sign * $increment;

                    $targets[1]['x'] += round($sign * $increment/2);
                    $targets[1]['y'] += $sign * $increment;

                    $targets[2]['x'] = $targets[2]['x'];
                    $targets[2]['y'] += round($sign * $increment/2);

                    $targets[3]['x'] += round($sign * $increment/2);
                    $targets[3]['y'] += round($sign * $increment/2);
                } else {
//                } else if (abs($diffX) < abs($diffY) || $diffX == abs($diffY)) {
                    var_dump('in 2');
                    $sign = $diffY > 0 ? 1 : -1;
                    $targets[0]['x'] += $sign * $increment;
                    $targets[0]['y'] = $targets[0]['y'];

                    $targets[1]['x'] += $sign * $increment;
                    $targets[1]['y'] += round(-1 * $sign * $increment/2);

                    $targets[2]['x'] += round($sign * $increment/2);
                    $targets[2]['y'] = $targets[0]['y'];

                    $targets[3]['x'] += round($sign * $increment/2);
                    $targets[3]['y'] += round(-1 * $sign * $increment/2);
//                } else if () {
//                    var_dump('in 3');
//                    $targets[0]['y'] += ($diffX > 0? -1 : 1) * $increment;
//                    $targets[1]['x'] += ($diffX > 0? -1 : 1) * $halfIncrement; // I guess we should move along Y now?
//                    $targets[1]['y'] += ($diffY > 0? -1 : 1) * $halfIncrement;
//                } else if () {
//                    var_dump('in 4');
//                    $targets[0]['x'] += ($diffY > 0? 1 : -1) * $increment;
//                    $targets[1]['x'] += ($diffX > 0? -1 : 1) * $halfIncrement;
//                    $targets[1]['y'] += ($diffY > 0? -1 : 1) * $halfIncrement;
//                } else {
//                    var_dump('in 5, ITERATIONS='.$iterations);
//                    $targets[0]['y'] += $increment;
//                    $targets[1]['x'] += $targets[0]['x'];
//                    $targets[1]['y'] += $halfIncrement;
//                    $iterations++;
                }
                var_dump($targets);
                foreach ($targets as $localTarget) {
                    if ($localTarget['x'] > 0 && $localTarget['x'] < $this->cache['realm']['width'] && $localTarget['y'] > 0 && $localTarget['y'] < $this->cache['realm']['height']) {
                        var_dump($localTarget);
                        $this->cache['target'] = $targets[0];
                        $this->cache['local_target'] = $localTarget;
                        $this->cache['local_target_reached'] = false;
                        return;
                    }
                }
            } while ($iterations < 2);
            return false;
        }
    }

}
