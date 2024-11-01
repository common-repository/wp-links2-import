<?php


/*
 * admin_category.php
 * @author Truong Tan Dat	
 * wordpress plugin website directory project
 * @copyright Copyright 2012, Truong Tan Dat
 * @version 1.0.0
 * 3/23/2012, started the plugin
 * @link http://www.cgito.net
*/
class Links2ImportSQL{


	public function get_delete_query($row){
		$rs="";
		$conds=array();
		if(is_array($row)){

			foreach($row as $k => $v){
				$conds[]=$k . "='" . $v . "'";
				
			}
			if(count($conds)>0) $rs = join(' and ',$conds);

		}
		return $rs;
	}



	public function get_insert_query($row){
		$rs = array();
		foreach($row['f'] as $k => $v){
			if($rs['f'] != ""){
				$rs['f'].=",";
				$rs['v'].=",";
			}
			$rs['f'].=  $v;
			$rs['v'].="'" . addslashes($row['v'][$k]) . "'";

		}
		return $rs;
	}
	public function get_update_query($row){
		$rs="";
		foreach($row as $k => $v){
			if($rs !="") $rs.=", ";
			$rs.= $k . "='" . $v . "'";
		}

		return $rs;

	}

	public function make_2dimensions($row){

		$rec=array();
		$rec['f']= array();
		$rec['v']=array();

		foreach($row as $k => $v){
			$rec['f'][]=$k;
			$rec['v'][]=$v; 
		}
		
		return $rec;
	}

	public function get_db_date($userdate){
		$months = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
		$arr = preg_split('/-/',$userdate);
		//return print_r($arr,true);
		$mon = array_search($arr[1],$months);
		if($mon === false) $mon=1;
		return "$arr[2]-$mon-$arr[0]";
		
	}




	public function do_parse_row($fields,$row){
		$out=array();
		$i=0;
		foreach($fields as $k => $v){
			if(substr($v,0,3)=='ign') continue;
			$out['f'][]=$v;
			$out['v'][]=$row[$k];
			$out['k'][$v]=$i++;
		}


		return $out;

	}


}


?>
