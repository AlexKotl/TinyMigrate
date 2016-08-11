<?
	#------------------------------------------------------
	#
	#   Tiny simple script for Database migrations
	#	uses only "UP" function
	#
	#------------------------------------------------------	
	
	echo "<h1>Migration Tool</h1>";
	
	// use your own file to connect to database
	include dirname(__FILE__)."/db_connect.php";
	
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
		echo "<h3>Checking \"{$file}\"...</h3> ";
		
		if (isset($log[$domain][$file])) {
			echo "<font color='orange'>Already applied</font> <small>on ".date('d/m/y H:i', $log[$domain][$file])."</small>";
		}
		
		// apply migration
		else {
			echo "<font color='green'>Applying</font>";
			$sqls = file_get_contents(dirname(__FILE__)."/migrations/{$file}");
			
			// run multiple queries separated by ;
			$sqls = explode(';', $sqls);
			$is_error = false;
			
			foreach ($sqls as $sql) {
				if (empty($sql)) continue;
				echo "<p><pre style='color:grey; '>{$sql}</pre>";
				
				$res = mysql_query($sql);
				if ($res===false) {
					echo "<p><font color='red'>Cannot execute query: </font>" . mysql_error();
					$is_error = true;
				}
			}
			
			if (!$is_error) $log[$domain][$file] = time(); // save to log
			
		}
		
		
	}
	
	// update log file
	file_put_contents(dirname(__FILE__).'/migrations/log', json_encode($log));
	
	echo "<h3>Task finished</h3>";

?>