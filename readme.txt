=== wp-links2-import ===
Contributors: datcgi
Donate link: http://cgito.net/products/services/
Tags: links 2.0, directory, web directory, category and products
Requires at least: 3.3.1
Tested up to: 3.3.1
Stable tag: 1.0

The plugin can be used to migrate links 2.0 data from text file to wordpress and to function like that one.

== Description ==

The plugin can be used to migrate links 2.0 or cvs, text files data as category and products to wordpress and to function like that one. That is visitors will then be able to add/modify their links, browse by categories and search the directory. And admin can import/re-import and manage the data as prducts/category and comments.

== Installation ==

You can install it via wordpress' plugin interface or install manually as below:

1. Upload wp-links2-import to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use the admin function to import data and setup its settings.

== Frequently Asked Questions ==

= What is the text file should be looked like? =

It can be any text files with fields separated as csv or similar like below, the 'ignxx' field will be ignored by the import:

id|fullname|ign|ign2|ign3|ign4|ign5|ign6|description|ign7|ign8|ign9
48|computers_and_internet/software/web|||||||Web software and other relative issues||2|

id|title|url|adddate|category|description|ign|ign2|ign3|ign4|ign5|ign6|ign7|ign8|ign9|ign10|ign11|ign12|ign13|ign14
33|CGI2.NET|http://www.cgito.net|15-Nov-2002|computers_and_internet/software/web|A web site to go.|dat|xxx|xxx|No|No|0|0|xxx|xxx|xxx|xxx||xxx|xxx


= Which fields must be had =

For category tables:
fullname

For links or 'products' like table:
title,url,adddate,category

The category will be matched with the 'fullname' of category

== Screenshots ==

1. import.jpg is the import form from which data file can be uploaded.
2. setup.jpg is the setup form from which the plugin settings can be changed
3. directory.jpg is the display of directory or products/category

== Changelog ==

= 1.0 =
* 
* This is the first release



== Todo list ==
* Manage products/links/category in admin page
* internationalize the text at user's side
* allow member to add/modify their links/products
* enable search from directory(products/category)
* to have css in directory

`<?php code(); // goes in backticks ?>`
