<?
	#------------------------------------------------------
	#
	#   Tiny simple script for Database migrations
	#	uses only "UP" function
	#
	#------------------------------------------------------	
	
	if (file_exists(dirname(__FILE__).'/migrations')) mkdir(dirname(__FILE__).'/migrations', 0775);

?>