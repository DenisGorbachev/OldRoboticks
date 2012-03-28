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
            $baseX = $this->cache['base']['x'];
            $baseY = $this->cache['base']['y'];
            $iterations = 0;
            do {
                $halfTargetX = $targetX;
                $halfTargetY = $targetX;
                $diffX = $targetX - $baseX;
                $diffY = $targetY - $baseY;
                $increment = 2 * $this->getOption('range') + 1;
                $halfIncrement = $this->getOption('range') + 1;
                if (abs($diffX) > abs($diffY)) {
                    var_dump('in 1');
                    $targetY += ($diffX > 0? -1 : 1) * $increment;
                    $halfTargetY += ($diffX > 0? -1 : 1) * $halfIncrement; // It's like a jump in wrong direction: 19,13 instead of 19,2 (from 19,8)
                    $halfTargetX = $targetX;
                } else if (abs($diffX) < abs($diffY)) {
                    var_dump('in 2');
                    $targetX += ($diffY > 0? 1 : -1) * $increment;
                    $halfTargetX = $targetX;
                    $halfTargetY += ($diffY > 0? -1 : 1) * $halfIncrement;
                } else if ($diffX == $diffY) {
                    var_dump('in 3');
                    $targetY += ($diffX > 0? -1 : 1) * $increment;
                    $halfTargetX += ($diffX > 0? -1 : 1) * $halfIncrement; // I guess we should move along Y now?
                    $halfTargetY += ($diffY > 0? -1 : 1) * $halfIncrement;
                } else if ($diffX == abs($diffY)) {
                    var_dump('in 4');
                    $targetX += ($diffY > 0? 1 : -1) * $increment;
                    $halfTargetX += ($diffX > 0? -1 : 1) * $halfIncrement;
                    $halfTargetY += ($diffY > 0? -1 : 1) * $halfIncrement;
                } else {
                    var_dump('in 5, ITERATIONS='.$iterations);
                    $targetY += $increment;
                    $halfTargetX += $targetX;
                    $halfTargetY += $halfIncrement;
                    $iterations++;
                }
                $target = array(
                    'x' => $targetX,
                    'y' => $targetY,
                );
                $halfTarget = array(
                    'x' => $halfTargetX,
                    'y' => $halfTargetY,
                );
                var_dump($halfTarget);
                foreach (array($target, $halfTarget) as $localTarget) {
                    if ($localTarget['x'] > 0 && $localTarget['x'] < $this->cache['realm']['width'] && $localTarget['y'] > 0 && $localTarget['y'] < $this->cache['realm']['height']) {
                        var_dump($localTarget);
                        $this->cache['target'] = $target;
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
