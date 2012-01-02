<?php
	function require_once_dir($dir) {
		walk_dir($dir, 'req_once');
	}

	function req_once($file) {
		require_once $file;
	}
	
	function walk_dir($dir, $fileCallback, $dirCallback = null) {
		$results = array();
		foreach (scandir($dir) as $file) {
			if ($file[0] == '.') {
				continue;
			}
			$path = $dir.'/'.$file;
			if (is_file($path) && $fileCallback) {
				$results[$file] = call_user_func($fileCallback, $path);
			} else if (is_dir($path)) {
				if ($dirCallback) {
					$results = array_merge($results, call_user_func($dirCallback, $path));
				}
				$results = array_merge($results, walk_dir($path, $fileCallback, $dirCallback));
			}
		}
		return $results;
	}

    function array_fill_negative($start_index, $num, $value) {
        $array = array();
        while ($num--) {
            $array[$start_index] = $value;
            $start_index++;
        }
        return $array;
    }

	function vd() {
		$args = func_get_args();
		foreach ($args as &$arg) {
			if (is_object($arg) && method_exists($arg, 'toArray')) {
				$arg = $arg->toArray();
			}
		}
		if (empty($args)) $args[] = 'hello';
		return call_user_func_array('var_dump', $args);
	}
	
	function dive() {
		$args = func_get_args();
		die(call_user_func_array('vd', $args));
	}

	function __(array $message) {
		$translator = new Translator($message);
		return $translator->translate();
	}
