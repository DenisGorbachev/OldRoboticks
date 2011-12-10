<?php
	require_once dirname(__FILE__).'/functions.php';
	require_once_dir(dirname(__FILE__));
	require_once_dir(dirname(__FILE__).'/../exception');
	
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
		throw new RoboticksException('Command not found', 1);
	}
	
	require_once $cmdFilename;
	$cmd = new $cmdClass();
	$cmd->parse();
	$cmd->run();
