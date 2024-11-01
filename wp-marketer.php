<?php
/*
Plugin Name: WPMarketer
Plugin URI: http://www.wpmarketer.com/download/
Description: Easy marketer link masking and more. <br /><br /> With WP Marketer manage your in text and performance marketing programs without having to use multiple tools to set up ads in posts and landing pages, manage your inventory and track results. Now you can manage your online marketing with greater control and ease of use than ever before.<br /><br />
Version: 1.0.8
Author: WPMarketer
Author URI: URI: http://www.wpmarketer.com/
*/


/*
Copyright (C) 2008 wpmarketer.com (info AT wpmarketer DOT com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


/**
 * This file is full of init functionality
 * and globals.
 *
 */

require_once("wp-marketer-start.php");
if(!class_exists('Navxt_Plugin_Tabulator'))
{
require_once("tabulator_navigation.php");
}



function wp_marketer_post_menu()
{
global $wpdb,$wp_marketer_prefix;
$wpAdminSection=strpos($_SERVER['REQUEST_URI'],"wp-marketer-admin-functions.php");
$category_form = "
<h2>Add Top Level Category</h2>
<div id=\"wp_marketer_add_category_results\" style=\"color:red;\"></div>
<table>
<tr><!--<td align=\"right\">
<b>Category Name:</b>
</td>--><td>
<input type=\"text\" name=\"wp_marketer_add_new_category\">
</td><td align=\"right\"><input type=\"button\" value=\"Add Top Level Category\" onclick=\"wp_marketer_ajax_add_category(this.form.wp_marketer_add_new_category,this.form.wp_marketer_category_parent,'".$wpAdminSection."');\" ></td></tr></table>
<input type=\"hidden\" name=\"wp_marketer_category_parent\" value=\"0\">
<!--<input type=\"button\" value=\"Add Top Level Category\" onclick=\"wp_marketer_ajax_add_category(this.form.wp_marketer_add_new_category,this.form.wp_marketer_category_parent);\" >-->
";

echo $category_form;

echo "
<h2>WPMarketer Categories and Links</h2>
<div id=\"wp_marketer_category_list\">
";

$category_list = get_category_list_marketer();

if($category_list)
	echo get_category_list_marketer();
else
	echo "<ul id=\"wpanavmarketer\"><ul>";//"No categories! Add at least one in order to add links!";

echo "
</div>";

}





function wp_marketer_create_menus()
{
	global $wpdb,$wp_marketer_prefix;
	$table_name6 = $wpdb->prefix . $wp_marketer_prefix."_link_apikey";
	$sqlGetTable6Data=$wpdb->get_row("SELECT api_key_status FROM ".$table_name6, OBJECT);
	$sqlGetTable6Data->api_key_status;
	//if ($sqlGetTable6Data->api_key_status=="valid")
	add_meta_box("wp_marketer_link_div","WPMarketer", "wp_marketer_post_menu","post","normal");
}

if (is_admin())
    add_action('admin_menu', 'wp_marketer_create_menus');


/**
 * All functionality for adding a category.
 *
 */
require_once("wp-marketer-category-functions.php");

/**
 * All functionality for adding a link.
 *
 */
require_once("wp-marketer-link-functions.php");



function wp_marketer_rewrite_rules($rules)
{
  global $wp_rewrite,$wpdb,$wp_marketer_prefix;

	$table_name = $wpdb->prefix . $wp_marketer_prefix."_links";

	$results	=	$wpdb->get_results("SELECT * FROM ".$table_name,OBJECT);

	$new_rules=Array();

	if($results)
	{

		foreach($results as $result){
						$the_slug = substr(get_category_slug_marketer($result->category)."/".$result->slug,1);

		        $new_rules[$the_slug."$"]='index.php?wpaaction=wp_marketer_redirect&wpaslug='.urlencode($result->slug);
		}

    $rules=$new_rules + $rules;


	}

  return $rules;
}

$wp_marketer_query_vars=Array('wpaaction','wpaslug');
function wp_marketer_add_query_vars($query_vars)
{
        global $wp_marketer_query_vars;

        return array_merge($query_vars,$wp_marketer_query_vars);
}

function wp_marketer_parse_request($req)
{
	global $wp_marketer_query_vars,$wpdb,$wp_marketer_prefix, $_SERVER;

	$table_name = $wpdb->prefix . $wp_marketer_prefix."_links";
	$table_name2 = $wpdb->prefix . $wp_marketer_prefix."_link_hits";

  foreach($wp_marketer_query_vars as $qv){
  	if(isset($req->query_vars[$qv]))
  	{
    	$_GET[$qv]=$req->query_vars[$qv];
    }
  }


	if($_GET['wpaaction']=='wp_marketer_redirect'){

		$sql	=	"select * from ".$table_name." where slug='".$_GET['wpaslug']."'";
		$row	=	$wpdb->get_row($sql,OBJECT);

		if($row)
		{
			// give it a hit
				$referer = wp_get_referer();
				$agent = $_SERVER['HTTP_USER_AGENT'];
				$ip = $_SERVER['REMOTE_ADDR'];
				if(trim($referer)!='')
				{
					$sql = "INSERT INTO ".$table_name2." (link_id,referer,agent,ip,thestamp) VALUES(".$row->id.",'".$wpdb->escape($referer)."','".$wpdb->escape($agent)."','".$wpdb->escape($ip)."',NOW())";
				}
				$wpdb->query($sql);
			putGoogleCode($row);
			?><script>location.href="<? echo $row->link ?>"</script><?
			exit(0);
		}
	}
}

function putGoogleCode($row)
{
?>
<html>
<head>
	<title><? echo $row->anchor_text ?></title>
</head>
<body>
	<? echo $row->google_code ?>
</body>
</html><?
}

function wp_marketer_init()
{

		global $wp_rewrite;
 	 if (isset($wp_rewrite) && $wp_rewrite->using_permalinks()) {

    add_filter('rewrite_rules_array', 'wp_marketer_rewrite_rules');
		add_filter('query_vars','wp_marketer_add_query_vars');
		add_action('parse_request','wp_marketer_parse_request');



	}
}
add_action('init','wp_marketer_init');


/**
 * All functionality for admin.
 *
 */
require_once("wp-marketer-admin-functions.php");


/**
 * All functionality for installation.
 *
 */
require_once("wp-marketer-install-functions.php");
register_activation_hook(__FILE__,'wp_marketer_install');


$row=getAutoLinkInformation();
function getAutoLinkInformation()
{
	global $wpdb,$wp_marketer_prefix;
	$table_name = $wpdb->prefix . $wp_marketer_prefix."_auto_link";
	$row = $wpdb->get_row("SELECT * FROM ".$table_name, OBJECT);
	return $row;
}
//if($row->auto_link_option==1)
//{
	require_once("mescalero.php");
//}
?>
