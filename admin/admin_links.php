<?php


/*
 * admin_import.php
 * @author Truong Tan Dat	
 * wordpress plugin website directory project
 * @copyright Copyright 2012, Truong Tan Dat
 * @version 1.0.0
 * 3/23/2012, started the plugin
 * @link http://www.cgito.net
*/

include_once("admin_sql.php");
include_once("admin_category.php");
class Links2ImportLinks{

	function add($row){
		global $wpdb,$last_query,$catids;
		$pos = $row['k']['id'];
		$id= $row['v'][$pos];
		$pos = $row['k']['category'];
		$fullname=$row['v'][$pos];
		array_splice(&$row['v'],$pos,1);
		array_splice(&$row['f'],$pos,1);
		$rs = Links2ImportSQL::get_insert_query($row);
		$query="insert into ". LINKS2IMPORT_DB_LINKS . "(" . $rs['f'] . ") values(" . $rs['v'] . ")";
		//wp_die($query);
		$rs = $wpdb->query($query);
		//wp_die($rs);
		$last_query=$query;
		if($rs){

			$catid = 0;
			if(isset($catids["$fullname"]) && $catids["$fullname"] > 0){
				$catid = $catids["$fullname"];
			}else{
				$catid = Links2ImportCats::get_cat_id($fullname);
				//wp_die($fullname . " = " . var_export($catid,true));
				$catids["$fullname"]=$catid;
			}
			$query = "insert into " . LINKS2IMPORT_DB_CATLINKS . "(catid,linkid) values(" . $catid . "," . $id . ")";
			$wpdb->query($query);
			
		///return "$c rows was imported from $total lines";
		}
		return $rs;

	}
	function modify($row){
		global $wpdb;
	}



	function do_insert_links($row){
		global $wpdb,$catids,$last_query;
		
		
		$pos = $row['k']['adddate'];
		$row['v'][$pos] = Links2ImportSQL::get_db_date($row['v'][$pos]);

		//print_r($row);wp_die("$fullname");
		return Links2ImportLinks::add($row);

		
	}


}


?>
