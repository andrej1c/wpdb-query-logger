<?php
/**
 * Plugin Name: WPDB Query Logger
 * Description: Log INSERT, UPDATE, REPLACE queries into file structure. 
 * Version: 0.1
 * Author: Andrej Ciho
 * Author URI: http://andrejciho.com
 */


Plugin Name: Pods CMS Framework
Plugin URI: http://podsframework.org/
Description: Pods is a CMS framework for creating, managing, and deploying customized content types.
Version: 1.12.2
Author: The Pods CMS Team
Author URI: http://podsframework.org/about/

class CS_WPDB_Logger
{
	function log($query)
	{
		// Do not log queries that only SELECT
		$pos1 = strpos( $query, 'INSERT'  );
		$pos2 = strpos( $query, 'UPDATE'  );
		$pos3 = strpos( $query, 'REPLACE' );
		if (($pos1 === false) && ($pos2 === false) && ($pos3 === false)) return $query;

		// Grab username
		global $current_user;
		$user_name = empty($current_user) ? 'notloggedin' : $current_user->user_login;
		
		// Open File and Write
		$filename = dirname(__FILE__). '/logs/wpdb_queries-'.date('Y-m-d').'-'.$user_name.'.sql';
		if (!$handle = @fopen($filename, 'a')) return $query;
		@fwrite($handle, $query . "; --".date('H:i:s')."\n");
		@fclose($handle);
		
		// This is a filter so we have to return $query
		return $query;
	}
}

if (defined('LOG_QUERIES')) if (true == LOG_QUERIES) add_filter('query', array('CS_WPDB_Logger', 'log'),1,1);