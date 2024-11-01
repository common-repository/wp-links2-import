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

include_once("admin_category.php");
include_once("admin_links.php");
class Links2ImportData{

function show_import_link($file,$name){
	$page = $_REQUEST['page'];
	$out='<div> To import ' . $name . ', click <a href="?page=' . $page . '&db=' . $name . '&import=1">here</a></div>';

	return $out;

}

function do_data_import($dir){
	global $wpdb;
	global $last_query;
	$db = $_GET['db'];

	$datafile= $dir . $db . ".db";
	$metafile= $dir . $db . ".txt";
	if(!file_exists($datafile)){
		return "$db is not existed";
	}
	$lines = file($metafile,FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	//return print_r($lines,true);
	$metaarr = preg_split('/~~/',$lines[0]);
	//return print_r($metaarr,true);
	$sep = $metaarr[1]?$metaarr[1]:'|';
	$patern = "/\\" . $sep . '/';
	$lines = file($datafile,FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	$msg="";
	$fields = preg_split($patern,$lines[0]);
	$total=0;
	$c=0;
	// purge table if it si there
	if($metaarr[0] != ""){

		$query="delete  from ";
		if($db== 'category'){

			$query.=LINKS2IMPORT_DB_CATS;
		}else{
			$query.=LINKS2IMPORT_DB_LINKS;

			$query2="delete  from " . LINKS2IMPORT_DB_CATLINKS;
			$wpdb->query($query2);
		}

		$wpdb->query($query);
		//return $query;
		
	}
	$miss="";
	foreach($lines as $k => $v){
		if($k==0) continue;
		$arr = preg_split($patern,$v);
		$total++;
		$row = Links2ImportSQL::do_parse_row($fields,$arr);
		$rs =0;
		$last_query="";
		if($db == 'category'){
			$rs =Links2ImportCats::do_insert_category($row);
		}else{
			$rs =Links2ImportLinks::do_insert_links($row);
		}
		//return print_r($rs,true);
		if($rs) $c++;
		else $miss.="$last_query\n<br>";
			
	}
	return "$c rows was imported from $total lines\n\n<br>Missinge lines:$miss";
	//return print_r($lines,true);


}

//function do_insert_categoryxxx($row){
//	$pos = $row['k']['fullname'];
//	global $parentcatids,$wpdb;
//	$fullname=$row['v'][$pos];
//	$arr = Links2ImportData::get_category_names($fullname);
//	//print_r($arr);wp_die($fullname);
//	$name = array_pop($arr);
//	$row['f'][]='name';
//	$row['f'][]='parentid';
//	$row['v'][]= $name;
//	$parentid=0;
//	if($name == $fullname){
//		// root cate
//		//$parentid=0;
//	}else{
//		$parentname = join('/',$arr);
//		if(isset($parentcatids["$parentname"]) && $parentcatids["$parentname"] > 0){
//			$parentid=$parentcatids["$parentname"];
//		}else{
//
//			$parentid = Links2ImportData::get_cat_id($parentname);
//			$parentcatids["$parentname"]=$parentid;
//		}
//		if($parentid <1){
//			print_r($row);wp_die($fullname . " id=" . $parentid);
//		}
//		
//	}
//	$row['v'][]= $parentid;
//	$rs = Links2ImportData::get_insert_query($row);
//	$query="insert into ". LINKS2IMPORT_DB_CATS . "(" . $rs['f'] . ") values(" . $rs['v'] . ")";
//	//wp_die($query);
//	return $wpdb->query($query);
//
//}
//
//function get_insert_query($row){
//	$rs = array();
//	foreach($row['f'] as $k => $v){
//		if($rs['f'] != ""){
//			$rs['f'].=",";
//			$rs['v'].=",";
//		}
//		$rs['f'].=  $v;
//		$rs['v'].="'" . addslashes($row['v'][$k]) . "'";
//
//	}
//	return $rs;
//}
//
//function get_cat_id($name){
//	global $wpdb;
//	$query="select id from ".LINKS2IMPORT_DB_CATS . " where fullname='" . $name . "'";
//	$results = $wpdb->get_var("$query");
//	return $results;
//}
//
//function get_db_date($userdate){
//	$months = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
//	$arr = preg_split('/-/',$userdate);
//	//return print_r($arr,true);
//	$mon = array_search($arr[1],$months);
//	if($mon === false) $mon=1;
//	return "$arr[2]-$mon-$arr[0]";
//	
//}
//
//function do_insert_links($row){
//	global $wpdb,$catids,$last_query;
//	$pos = $row['k']['id'];
//	$id= $row['v'][$pos];
//	$pos = $row['k']['category'];
//	$fullname=$row['v'][$pos];
//	array_splice(&$row['v'],$pos,1);
//	array_splice(&$row['f'],$pos,1);
//	$pos = $row['k']['adddate'];
//	$row['v'][$pos] = Links2ImportData::get_db_date($row['v'][$pos]);
//
//	//print_r($row);wp_die("$fullname");
//	$rs = Links2ImportData::get_insert_query($row);
//	$query="insert into ". LINKS2IMPORT_DB_LINKS . "(" . $rs['f'] . ") values(" . $rs['v'] . ")";
//	//wp_die($query);
//	$rs = $wpdb->query($query);
//	$last_query=$query;
//	if($rs){
//
//		$catid = 0;
//		if(isset($catids["$fullname"]) && $catids["$fullname"] > 0){
//			$catid = $catids["$fullname"];
//		}else{
//			$catid = Links2ImportData::get_cat_id($fullname);
//			$catids["$fullname"]=$catid;
//		}
//		$query = "insert into " . LINKS2IMPORT_DB_CATLINKS . "(catid,linkid) values(" . $catid . "," . $id . ")";
//		$wpdb->query($query);
//		//wp_die($query);
//	///return "$c rows was imported from $total lines";
//	}
//	return $rs;
//}
//function get_category_names($fullname){
//	return preg_split('/\//',$fullname);
//}
//
//
//function do_parse_row($fields,$row){
//	$out=array();
//	$i=0;
//	foreach($fields as $k => $v){
//		if(substr($v,0,3)=='ign') continue;
//		$out['f'][]=$v;
//		$out['v'][]=$row[$k];
//		$out['k'][$v]=$i++;
//	}
//
//
//	return $out;
//
//}

function link2import_admin_importlinks(){
	global $plugin_prefix_root;
	$msg="";
	$data=array('category','links');
	$save_dir = $plugin_prefix_root . "data/";
	//print_r($_FILES);
	if($_GET['import']){
		$msg=Links2ImportData::do_data_import($save_dir);
	}else if($_POST['save'] && $_FILES['db']['size'] > 0){
		// check to immport here
		//print_r($options);
		$save_file= $save_dir . $_POST['dest'] . ".db";
		$msg=$_FILES['db']['name'];
		if(move_uploaded_file($_FILES['db']['tmp_name'],$save_file)){
			$msg.=" file has been saved sucessfully at $save_file<br>";
			$msg.= Links2ImportData::show_import_link($save_file, $_POST['dest']);

			// also app the meta data for file??
			$meta = $save_dir . $_POST['dest'] . ".txt";
			file_put_contents($meta, $_POST['purge'] . '~~' . $_POST['separate'] . '~~' . $_POST['quote']);
//			if($fh = fopen($meta,"w")){
//				fwrite ($fh,$_POST['purge'] . '~~' . $_POST['separate'] . '~~' . $fh,$_POST['separate']  );
//				fclose($fh);
//			}

		}else{
			$msg.=" file has FAILED to saved. Please try agiain!<br>";
		}
		
	}else{
		foreach ($data as $k => $v){
			$save_file= $save_dir . $v . ".db";
			if(file_exists($save_file) && filesize($save_file) > 0){
				$msg.=Links2ImportData::show_import_link($save_file, $v);
			}
		}
	}
	$separate= '|';

	


?>
<div class="wrap">
<h2>link2import Import Data</h2>
<p>Please add the first row of the file as the fields of table which data to be import.</p>

<p><?php if($msg) echo $msg;?></p>

<form method=post enctype="multipart/form-data">
<input type=hidden name="page" value="importlinks">

<table>
<tr><td align=right>Purge data before import:</td><td align=left><input name="purge" value="1" type="checkbox"></tr>

<tr><td align=right>Import to:</td><td align=left><select name="dest" size=1>
<?php 
	foreach ($data as $k => $v){
		echo '<option value="' . $v . '" ';
		if($_POST['dest'] == $v) echo " selected ";
		echo ">$v</option>";
	
	}
	
?>
</select></tr>
<tr><td align=right>Field separate:</td><td align=left><input size=4 name="separate" value="<?php echo $separate?>"></tr>
<tr><td align=right>Field have quoted as ":</td><td align=left><input name="quote" value="1" type="checkbox"></tr>

<tr><td align=right>Data file:</td><td align=left><input name="db" type="file"></tr>
<tr><td align=center colspan=2><input name="save" value="Upload data" type=submit></tr>
</table>
</form>
</div>


<?php

}

}


?>
