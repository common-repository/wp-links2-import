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

function link2import_admin_links(){
	echo "this function will be implimented.";

}

function link2import_admin_cats(){
	echo "this function will be implimented.";

}




function link2import_admin_page(){
	//add_options_page('My Plugin Options', 'My Plugin', 'manage_options', 'my-unique-identifier', 'link2import_options');
	add_menu_page('Links2 Import', 'Links2 Import', 'manage_options', 'links2import_topmenu', 'link2import_admin_func');
	add_submenu_page( 'links2import_topmenu', 'Plugins Setup', 'Plugins Setup', 'manage_options', 'links2import_topmenu', 'link2import_admin_func');
	add_submenu_page( 'links2import_topmenu', 'Manage Category', 'Manage Category', 'manage_options', 'category', 'link2import_admin_func');
	add_submenu_page( 'links2import_topmenu', 'Manage Links', 'Manage Links', 'manage_options', 'links', 'link2import_admin_func');
	//add_submenu_page( 'links2import_topmenu', 'Import Category', 'Import Category', 'manage_options', 'importcat', 'link2import_admin_func');
	add_submenu_page( 'links2import_topmenu', 'Import Links', 'Import Data', 'manage_options', 'importlinks', 'link2import_admin_func');
	//add_submenu_page('edit.php?post_type=wiki', 'Options', 'Options', 'manage_options', 'wiki-options', array(&$this, 'link2import_options') );
}

function link2import_options() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	echo '<div class="wrap">';
	echo '<p>Here is where the form would go if I actually had options.</p>';
	echo '</div>';
}


function link2import_admin_func(){
	
	$page = $_REQUEST['page'];
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	require_once('admin_import.php');
	require_once('admin_manage.php');
	switch ($page){
		case "setup":
			link2import_admin_setup();
			break;

		case "category":
			link2import_admin_cats();
			break;
		case "links": 
			link2import_admin_links();
			break;
		case "importcat":
			Links2ImportData::link2import_admin_importcats();
			break;
		case "importlinks":
			Links2ImportData::link2import_admin_importlinks();
			break;

		case "links2import_topmenu":
			link2import_admin_topmenu();
			break;
	}
}

	
function link2import_admin_topmenu(){
	link2import_admin_setup();
	return;
	?>
	<div class="wrap">
	<p>Welcome to links2import!<br> Please chose the below links to go</p>
	<h2><a href='?page=setup'>Plugins Setup</a>
	<h2><a href='?page=importcat'>Import Category</a>
	<h2><a href='?page=importlinks'>Import Links</a>
	<h2><a href='?page=category'>Manage Category</a>
	<h2><a href='?page=links'>Manage Links</a>
	</div>
	<?php
}

function link2import_admin_setup(){

	if($_POST['save']){
		$options = $_POST['link2import_options'];
		update_option('link2import_options',$options);
		//print_r($options);
	}else{
		$options = get_option('link2import_options');
		//print_r($options);
	}

	
?>

<div class="wrap">
<h2>link2import setup</h2>

<form method=post>
<input type=hidden name="page" value="setup">
<table>
<tr><td align=right>Links per page:</td><td align=left><input name="link2import_options[link_per_page]" value="<?php echo($options['link_per_page'])?>"></tr>
<tr><td align=right>Col of Link per page:</td><td align=left><input name="link2import_options[link_col_page]" value="<?php echo $options['link_col_page']?>"></tr>
<tr><td align=right>Number of category column per home:</td><td align=left><input name="link2import_options[cat_col_home]" value="<?php echo $options['cat_col_home']?>"></tr>
<tr><td align=right>Number of category column per  page:</td><td align=left><input name="link2import_options[cat_col_page]" value="<?php echo $options['cat_col_page']?>"></tr>
<tr><td align=right>Linked Title separate:</td><td align=left><input name="link2import_options[titled_link_separate]" value="<?php echo $options['titled_link_separate']?>"></tr>
<tr><td align=right>Paging separate:</td><td align=left><input name="link2import_options[paging_sep]" value="<?php echo $options['paging_sep']?>"></tr>

<tr><td align=center colspan=2><input name="save" value="Save" type=submit></tr>
</table>
</form>
</div>
<?php

}
?>
