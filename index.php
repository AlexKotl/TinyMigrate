<?
	#------------------------------------------------------
	#
	#   Tiny simple script for Database migrations
	#	uses only "UP" function
	#
	#------------------------------------------------------	
	
	echo "<h1>Migration Tool</h1>";
	
	// parse log
	try {
		$log = json_decode(file_get_contents(dirname(__FILE__)."/migrations/log"), true);
	}
	catch(Exception $e) {
		$log = array();
		echo "<h3>Log file created</h3>";
	}
	
	$domain = $_SERVER['SERVER_NAME'];
	$migrations = scandir(dirname(__FILE__).'/migrations');	
	
	foreach ($migrations as $file) {
		
		// skip not .sql files
		$ext = pathinfo($file);
		$ext = $ext['extension'];
		if ($ext != 'sql') continue;

		// check if migration already applied
		echo "<li>Checking \"{$file}\"... ";
		
		if (isset($log[$domain][$file])) {
			echo "<font color='orange'>Already applied</font>";
		}
		
		// apply migration
		else {
			$log[$domain][$file] = time(); // save to log
		}
		
		
	}
	
	// update log file
	file_put_contents(dirname(__FILE__).'/migrations/log', json_encode($log));
	
	echo "<h3>Task finished</h3>";

?>