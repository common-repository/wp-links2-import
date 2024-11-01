<?php

include_once("admin_sql.php");

/*
 * admin_category.php
 * @author Truong Tan Dat	
 * wordpress plugin website directory project
 * @copyright Copyright 2012, Truong Tan Dat
 * @version 1.0.0
 * 3/23/2012, started the plugin
 * @link http://www.cgito.net
*/
class Links2ImportCats{

	public function add($row){
		global $wpdb;
		$rec = $row;
		if(!isset($row['f']) || !isset($row['v'])){

			$rec = Links2ImportSQL::make_2dimensions($row);
		}
		$rs = Links2ImportSQL::get_insert_query($rec);
		$query="insert into ". LINKS2IMPORT_DB_CATS . "(" . $rs['f'] . ") values(" . $rs['v'] . ")";
		//wp_die($query);
		return $wpdb->query($query);

	}

	public function modify($id,$row){
		global $wpdb;
		$rs = Links2ImportSQL::get_update_query($row);
		$query="update ". LINKS2IMPORT_DB_CATS . " set " . $rs . " where id=" . $id;
		//wp_die($query);
		return $wpdb->query($query);

	}


	public function do_insert_category($row){
		$pos = $row['k']['fullname'];
		global $parentcatids,$wpdb;
		$fullname=$row['v'][$pos];
		$arr = Links2ImportCats::get_category_names($fullname);
		//print_r($arr);wp_die($fullname);
		$name = array_pop($arr);
		$row['f'][]='name';
		$row['f'][]='parentid';
		$row['v'][]= $name;
		$parentid=0;
		if($name == $fullname){
			// root cate
			//$parentid=0;
		}else{
			$parentname = join('/',$arr);
			if(isset($parentcatids["$parentname"]) && $parentcatids["$parentname"] > 0){
				$parentid=$parentcatids["$parentname"];
			}else{

				$parentid = Links2ImportCats::get_cat_id($parentname);
				$parentcatids["$parentname"]=$parentid;
			}
			if($parentid <1){
				print_r($row);wp_die($fullname . " id=" . $parentid);
			}
			
		}
		$row['v'][]= $parentid;
		return Links2ImportCats::add($row);


	}



	public function get_cat_id($name){
		global $wpdb;
		$query="select id from ".LINKS2IMPORT_DB_CATS . " where fullname='" . $name . "'";
		$results = $wpdb->get_var("$query");
		return $results;
	}




	public function get_category_names($fullname){
		return preg_split('/\//',$fullname);
	}





}


?>
