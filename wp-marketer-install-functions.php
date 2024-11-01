<?php

/**
 * wp_marketer_install() - run upon activation
 *
 * Creates/Updates database and options.
 *
 */
function wp_marketer_install()
{
global $wpdb;

$wp_marketer_prefix = "wp_marketer";

$table_name = $wpdb->prefix . $wp_marketer_prefix."_links";
$table_name2 = $wpdb->prefix . $wp_marketer_prefix."_categories";
$table_name3 = $wpdb->prefix . $wp_marketer_prefix."_link_hits";
$table_name4 = $wpdb->prefix . $wp_marketer_prefix."_link_string";
$table_name5 = $wpdb->prefix . $wp_marketer_prefix."_auto_link";
$table_name6 = $wpdb->prefix . $wp_marketer_prefix."_link_apikey";

$sql = "CREATE TABLE `".$table_name."` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`anchor_text` VARCHAR( 255 ) NOT NULL ,
`link` TEXT NOT NULL ,
`slug` VARCHAR( 255 ) NOT NULL ,
`category` INT( 11 ) NOT NULL ,
`follow` varchar( 50 ) NOT NULL,
`masking` varchar( 50 ) NOT NULL,
`newwinlink` varchar( 50 ) NOT NULL,
`auto_link` tinyint(4) NOT NULL,
`google_code` TEXT NOT NULL ,
`eac` tinyint(4) NOT NULL,
`link_type` varchar( 50 ) NOT NULL,
`image_url` TEXT NOT NULL ,
`image_width` INT( 11 ) NOT NULL ,
`image_height` INT( 11 ) NULL ,
`image_align` varchar( 50 ) NULL,
`js_image_url` TEXT NOT NULL ,
`link_name` VARCHAR( 250 ) NOT NULL ,
INDEX ( `anchor_text` )
);";

$sql2 = "CREATE TABLE `".$table_name2."` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`category` VARCHAR( 255 ) NOT NULL ,
`parent` INT( 11 ) NOT NULL ,
INDEX ( `parent` )
);";

$sql3 = "CREATE TABLE `".$table_name3."` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`link_id` INT( 11 ) NOT NULL ,
`referer` TEXT NOT NULL ,
`agent` TEXT NOT NULL ,
`ip` VARCHAR(14) NOT NULL ,
`thestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
INDEX ( `link_id` , `thestamp` )
);";

$sql4 = "CREATE TABLE `".$table_name4."` (
`linkstrid` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`linkstr` TEXT NOT NULL
);";

$sqlGetTable4Data=$wpdb->get_row("SELECT linkstrid FROM ".$table_name4, OBJECT);
$sql5 = "insert into `".$table_name4."` (linkstr) values('')";


$sql6 ="CREATE TABLE `".$table_name5."` (
  `id` int(11) NOT NULL auto_increment,
  `auto_link_option` tinyint(4) NOT NULL,
  `auto_link_limit` int(11) NOT NULL,
  `image_max_width` int(11) NOT NULL,
  `image_max_height` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
);";

$sqlGetTable5Data=$wpdb->get_row("SELECT id FROM ".$table_name5, OBJECT);
$sql7 = "insert into `".$table_name5."` (auto_link_option,auto_link_limit,image_max_height,image_max_width) values(1,1,1,1)";


$sql8 ="CREATE TABLE `".$table_name6."` (
  `id` int(11) NOT NULL auto_increment,
  `api_key_status` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`)
);";

$sqlGetTable6Data=$wpdb->get_row("SELECT id FROM ".$table_name6, OBJECT);
$sql9 = "insert into `".$table_name6."` (api_key_status) values('')";

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql);
dbDelta($sql2);
dbDelta($sql3);
dbDelta($sql4);
if($sqlGetTable4Data->linkstrid=='')
{
	$wpdb->query($sql5);
}
dbDelta($sql6);
if($sqlGetTable5Data->id=='')
{
	$wpdb->query($sql7);
}

dbDelta($sql8);
if($sqlGetTable6Data->id=='')
{
	$wpdb->query($sql9);
}


$sql8 ="CREATE TABLE `wp_wp_marketer_link_count_session` (
  `id` int(11) NOT NULL auto_increment,
  `link_str` text NOT NULL,
  `link_str_href` text NOT NULL,
  `session_id` varchar(250) NOT NULL,
  `update_date` date NOT NULL,
  PRIMARY KEY  (`id`)
);";
dbDelta($sql8);

update_option('rewrite_rules',"");

update_option("useStatistics",0);

}


?>
