<?php


/*
 * install.php
 * @author Truong Tan Dat	
 * wordpress plugin website directory project
 * @copyright Copyright 2012, Truong Tan Dat
 * @version 1.0.0
 * 3/23/2012, started the plugin
 * @link http://www.cgito.net
*/


class Links2Import{
	
	public function install(){

		$dbverion = get_option("links2_db_version");
//		_e("dbverion:" . $dbverion);exit;
//		if($dbverion == 1) return;
//		echo "To install here";
//		exit;
		global $wpdb;
		$prefix = $wpdb->prefix;
		$table_name = $prefix . "links2_cats"; 

		$sql = "CREATE TABLE wp_links2_cats (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  name varchar(100) NOT NULL,
  fullname varchar(255) NOT NULL,
  description longtext,
  parentid mediumint(9) NOT NULL,
  UNIQUE KEY id (id),
  INDEX pid_ind (parentid),
  INDEX name_ind (name),
  INDEX fullname_ind (fullname)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;";
		//_e("Creating table:" . $sql);exit;
		//wp_die("Creating table:" . $sql);

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);

		$table_name = $prefix . "links2_links"; 

				$sql = "CREATE TABLE $table_name (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  adddate date DEFAULT '0000-00-00' NOT NULL,
		  moddate date DEFAULT '0000-00-00' NOT NULL,
		  title varchar(255) NOT NULL,
		  description longtext,
		  price decimal(12,2)  DEFAULT '0.00',
		  discount tinyint(2),
		  url VARCHAR(255) DEFAULT 'http://',
		  UNIQUE KEY id (id),
		  INDEX url_ind (url)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;";
		//_e("Creating table:" . $sql);
		dbDelta($sql);

		$table_name = $prefix . "links2_catlinks"; 

				$sql = "CREATE TABLE $table_name (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  catid mediumint(9) NOT NULL,	
		  linkid mediumint(9) NOT NULL,
		  UNIQUE KEY id (id),
		  INDEX catid_ind (catid),
		  INDEX linkid_ind (linkid),
		  INDEX catlinkid_ind (catid,linkid)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;";
		//_e("Creating table:" . $sql);
		dbDelta($sql);

		$table_name = $prefix . "links2_catrelations"; 

				$sql = "CREATE TABLE $table_name (
		  relatedid mediumint(9) NOT NULL,
		  catid mediumint(9) NOT NULL,	
		  relationname varchar(50),
		  INDEX catid_ind (catid),
		  INDEX relatedid_ind (relatedid)		  
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;";
		//_e("Creating table:" . $sql);
		dbDelta($sql);

		add_option("links2_db_version", 1);
	}

	public function uninstall(){
//		echo "To uninstall here";
//		exit;
		$dbverion = get_option("links2_db_version");
		
		if($dbverion != 1) return;
		global $wpdb;
		$prefix = $wpdb->prefix;
		$table_name = $prefix . "links2_catlinks";
		//srequire_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		$sql="drop table " . $table_name;
		
		$wpdb->query($sql);
		$table_name = $prefix . "links2_catrelations"; 
		$sql="drop table " . $table_name;
		$wpdb->query($sql);
		$table_name = $prefix . "links2_links"; 
		$sql="drop table " . $table_name;
		$wpdb->query($sql);
		$table_name = $prefix . "links2_cats"; 
		$sql="drop table " . $table_name;
		$wpdb->query($sql);
		update_option("links2_db_version", '');
		
	}

public function deinstall(){
		Links2Import::uninstall();
//		echo "To uninstall here";
//		exit;
//		global $wpdb;
//		$prefix = $wpdb->prefix;
//		$table_name = $prefix . "links2_catlinks";
//		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
//		$sql="drop table " . $table_name;
//		_e($sql);
//		dbDelta($sql);
//		$table_name = $prefix . "links2_catrelations"; 
//		$sql="drop table " . $table_name;
//		dbDelta($sql);
//		$table_name = $prefix . "links2_links"; 
//		$sql="drop table " . $table_name;
//		dbDelta($sql);
//		$table_name = $prefix . "links2_cats"; 
//		$sql="drop table " . $table_name;
//		dbDelta($sql);
		
	}
}
?>
