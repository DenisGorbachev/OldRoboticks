<?php
	require_once dirname(__FILE__).'/functions.php';
	require_once_dir(dirname(__FILE__));
	require_once_dir(dirname(__FILE__).'/../exception');
    set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__).'/../vendor');
	
	$executable = $_SERVER['argv'][0];
	$cmdName = isset($_SERVER['argv'][1])? $_SERVER['argv'][1] : null;
	if (empty($cmdName) || $cmdName[0] == '-') {
		$cmdName = 'info';
		array_splice($_SERVER['argv'], 1, 0, array($cmdName));
	}

	$cmdClass = preg_replace(
        array(
            '#/(.?)#e',
            '/(^|_|-|:)+(.)/e'
        ),
        array(
            "'::'.strtoupper('\\1')",
            "strtoupper('\\2')"
        ),
        $cmdName
    ).'Command';
	$cmdFilename = LIBDIR.'/command/'.$cmdClass.'.class.php';
	if (!file_exists($cmdFilename)) {
		die('Failure: command not found'.PHP_EOL);
	}
    require_once $cmdFilename;
	$cmd = new $cmdClass();
    $args = $_SERVER['argv'];
    unset($args[1]);

    try {
        $cmd->parse($args);
        $cmd->run();
    } catch (Exception $e) {
        if ($e instanceof RoboticksUserFriendlyException || !DEBUG) {
            $message = $e->getMessage();
            if ($e instanceof Console_CommandLine_Exception) {
                $message .= ' Use "--help" option to get info about a command.';
            }
            $cmd->failure($message);
            exit($e->getCode());
        }
        throw $e;
    }

