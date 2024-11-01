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
include_once("admin_sql.php");
include_once("admin_category.php");
class Links2ImportPages{

	public $has_done=0;

	public function showhome($thecontent){
		//global $wp_query;
		//wp_die( '<pre>' . var_export( $wp_query, true ) . '</pre>' );
		//wp_die($GLOBALS['post']->post_name);
		if ($GLOBALS['post']->post_name == 'directory') {
			//wp_die($GLOBALS['post']->post_name);
			 $thecontent= Links2ImportPages::build_category();
		}
		return $thecontent;
	}
	public function make_detailed_page($root_url,$ids,$options){
		global $wpdb;
		$cats="";
		//return var_export($ids);
		$cats="<p>" . Links2ImportPages::make_page_linked_title($root_url,$ids,$options) . "</p>";

		$query="select * from " . LINKS2IMPORT_DB_LINKS . " where id=" . $ids[1] ;
		$link = $wpdb->get_row($query);

		$cats.="<table><tr>";
		$cats.= "<td>Title:</td><td><a href='" . $link->url . "' rel='nofollow' target=_blank>" . $link->title . "</a></td></tr>";
		$cats.= "<td colspan=2>" . $link->description . "</td></tr>";

		$cats.="</table>";
		return $cats;

	}

	public function build_category(){
		
		global $wpdb,$wp_query;
		$ids = Links2ImportPages::get_page_id($wp_query->query_vars['page']);
		// page detailed
		//return var_export($ids);

		$catid = $ids[0];
		//wp_die( '<pre>' . var_export( $catid, true ) . '</pre>' );
		$root_url = get_home_url() . "/" . $wp_query->query_vars['pagename'];
		// cats
		$query="select * from " . LINKS2IMPORT_DB_CATS ;
		$col_num = 2;
		$options = get_option('link2import_options');
		if($ids[1] > 0){

			return Links2ImportPages::make_detailed_page($root_url,$ids,$options);

		}
	
		if($catid > 0){
			#$query.=" where id = " . $catid;
			$col_num = $options['cat_col_page']?$options['cat_col_page']:2;	
		}else{
			
			$col_num = $options['cat_col_home']?$options['cat_col_home']:2;
		}
		$query.=" where parentid = " . $catid ;
		$query.=" order by fullname";
		$results = $wpdb->get_results($query);
		//wp_die($results);
		

		$cols=array();
		$i=0;
		foreach($results as $cat){
			$fullname = $cat->fullname;
			$name=$cat->name;
			$name=Links2ImportPages::make_cat_name($name);
			$cols[$i++][]="<a href='" . $root_url . "/" . Links2ImportPages::make_page_id($cat->id) . "'>" . $name. "</a>";
			if($i>=$col_num) $i=0;
		}
		
		$cats="<p>" . Links2ImportPages::make_page_linked_title($root_url,$ids,$options) . "</p>";
		//$cats.=var_export( $query, true );
		//$cats.=var_export( $GLOBALS['query_string'], true );

		$i=0;
		if(count($cols[0])>0){
			$cats.= "<table>";
			foreach($cols[0] as $k => $v){
				$cats.="<tr>";
				$cats.="<td>" . $v . "</td>";
				$cats.="<td>" . $cols[1][$i] . "</td>";
				$cats.="<td>" . $cols[2][$i] . "</td>";
				$i++;
				$cats.="</tr>";
			}
			$cats.="</table>";
		}	
		// links
		$query="select count(*) from " . LINKS2IMPORT_DB_CATLINKS . " where catid=" . $catid;
		$total = $wpdb->get_var($query);
		
		$offsets =0;
		$pages="";//"total=$total";
		if($total > $options['link_per_page']) {
			$offsets = Links2ImportPages::get_page_offset($total,$options['link_per_page'],$ids[2]);
			$pages.=Links2ImportPages::get_page_pages($root_url,$ids,$total,$options['link_per_page'],$options);

		}


		

		$query="select l.title,cl.catid,cl.linkid from " . LINKS2IMPORT_DB_LINKS . " l inner join " . LINKS2IMPORT_DB_CATLINKS . " cl on(l.id=cl.linkid) where catid=" . $catid . " LIMIT  " . $offsets ."," . $options['link_per_page'] ;
		$results = $wpdb->get_results($query);
		//$cats.=var_export( $query, true );

		//wp_die( '<pre>' . $cats.=var_export( $query, true ) . '</pre>' );
		$cols=array();
		$i=0;

		$col_num = $options['link_col_page']?$options['link_col_page']:2;
		foreach($results as $link){
			//$fullname = $cat->fullname;
			$name=$link->title;
			$name=ucfirst(preg_replace("/_/"," ",$name));
			$cols[$i++][]="<a href='" . $root_url . "/" . Links2ImportPages::make_page_id($link->catid, $link->linkid ). "'>" . $name. "</a>";
			if($i>=$col_num) $i=0;
		}

		//$cats="<p>" . $wp_query->query_vars['pagename'] . "</p>";
		$i=0;
		if(count($cols[0])>0){
			$cats.= "<table>";
			foreach($cols[0] as $k => $v){
				$cats.="<tr>";
				$cats.="<td>" . $v . "</td>";
				$cats.="<td>" . $cols[1][$i] . "</td>";
				$cats.="<td>" . $cols[2][$i] . "</td>";
				$i++;
				$cats.="</tr>";
			}
			$cats.="</table>";
		}	

		if($pages != "") $cats.="<p>" . $pages . "</p>";

		return $cats;
	}

	public function get_page_offset($total,$mh,$nh){
		if($nh < 1) $nh=1;
		$start = ($nh-1)*$mh;
		//$end = $start+$mh;
		return $start;
	}

	public function get_page_pages($root_url,$ids,$total,$mh,$options){
		$nh = $ids[2];
		if($nh < 1) $nh=1;

		$pn = floor($total/$mh);
		if($pn < ($total/$mh)) $pn++;
		$pages=array();
		$pages[]= "<a href=" . $root_url . "/" . Links2ImportPages::make_page_id($ids[0],0,1) . "/>&lt;&lt;</a>";
		for($i=1;$i<=$pn;$i++){
			$page="<a href=" . $root_url . "/" . Links2ImportPages::make_page_id($ids[0],0,$i) . "/>";
			if($nh == $i) $page=$i;
			else{
				$page.=$i;	
				$page.="</a>";
			}
			$pages[]= $page;
		}

		$pages[]= "<a href=" . $root_url . "/" . Links2ImportPages::make_page_id($ids[0],0,$pn) . "/>&gt;&gt;</a>";

		$delim = $options['paging_sep']?$options['paging_sep']:'&nbsp;&nbsp;';
		return join($delim,$pages);
	}
	public function make_cat_name($name){

		return ucfirst(preg_replace("/_/"," ",$name));

	}
	
	public function make_page_id($catid = 0, $linkid =0, $page=0){
		//catid - linkid - page number
		//999   - 999999  - 999
		//1000000000000 
		//		 1000000000 
		//				    1000
		$id = sprintf("%03d",$catid) . sprintf("%06d",$linkid) . sprintf("%03d",$page); 	
		//$id=($catbase*$catid)+($linkbase*$linkid)+($page);
		return $id;
	}

	public function get_page_id($pageid){
		//catid - linkid - page number
		//999   - 999999  - 999
		//1000000000000 
		//		 1000000000 
		//				    1000
		$ids = array();
		$base=1000;
		$page= $pageid % $base;
//		if($page > 0) $pageid=($pageid-$page)/$page;
//		else $pageid/=$base;
		$pageid/=$base;
		$pageid = floor($pageid);
		$base=1000000;
		$linkid= $pageid % $base;
//		if($linkid > 0)	$pageid=($pageid-$linkid)/$linkid;
//		else $pageid/=$base;
		
		$pageid/=$base;
		$pageid = floor($pageid);
		//($x - ($x % $y)) / $y;

		$ids[]=$pageid;
		$ids[]=$linkid;
		$ids[]=$page;
		
		return $ids;
	}

	public function make_page_linked_title($root_url,$arr,$options){
		
		global $wpdb;
		$query="select * from " . LINKS2IMPORT_DB_CATS  . " where id=" . $arr[0];
		$cat = $wpdb->get_row($query);
		$cats = preg_split('/\//',$cat->fullname);
		//return $cat->fullname . ":" . var_export($cats);
		$last="";
		if($arr[1] <= 0) $last = array_pop($cats);
		
		
		$titles=array();
		if(count($cats)){
			$fullname="";
			foreach($cats as $k => $name){
				if($fullname != "") $fullname.="/";
				$fullname.=$name;
				$catid = Links2ImportCats::get_cat_id($fullname);
				$titles[]= "<a href='" . $root_url . "/" . Links2ImportPages::make_page_id($catid) . "'>" . Links2ImportPages::make_cat_name($name). "</a>";
			}
		}

		if($last != "") array_push($titles,Links2ImportPages::make_cat_name($last));
		$delim =$options['titled_link_separate']?$options['titled_link_separate']:' > ';
		$linkedtitle = join($delim,$titles);

		return $linkedtitle;

	}
}


?>
