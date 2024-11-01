<?php
function get_category_slug_marketer($id)
{
	global $wpdb,$wp_marketer_prefix;

	if(!$id)
		return;

	$table_name 	= $wpdb->prefix . $wp_marketer_prefix."_categories";

	$row = $wpdb->get_row("SELECT * FROM ".$table_name." WHERE id=".$id, OBJECT);

	return get_category_slug_marketer($row->parent)."/".sanitize_title($row->category);
}

function get_link_li_marketer($id,$wpAdminSection)
{
	global $wpdb,$wp_marketer_prefix;

	$table_name = $wpdb->prefix . $wp_marketer_prefix."_links";
	$table_name2 = $wpdb->prefix . $wp_marketer_prefix."_categories";

	$result = $wpdb->get_row("SELECT a.*,b.category as categoryName,b.parent as parentid FROM ".$table_name." as a left join ".$table_name2."  as b on (a.category=b.id) WHERE a.id=".$id, OBJECT);
	$clean_cat = get_category_slug_marketer($result->category);
	$clean_link = get_bloginfo('wpurl')."".$clean_cat."/".$result->slug;
	$send_to_editor = "send_to_editor(\"[WPMID=".$result->id."]\");return false;";

	if($result->newwinlink=="target=_blank")
		$newwinlinkval="Yes";
	else
		$newwinlinkval="No";

	if($result->auto_link==1)
		$autoLinkValue="Enabled";
	else
		$autoLinkValue="Disabled";

	if($result->link_type=="htmltext")
	{
		$linkTypeValueIs="HTML Text";
		$anchorTextVal="Anchor Text:";
	}
	if($result->link_type=="htmlimage")
	{
		$linkTypeValueIs="HTML Image";
		$anchorTextVal="Image Alt:";
	}
	if($result->link_type=="javascript")
	{
		$linkTypeValueIs="JavaScript";
		$anchorTextVal="Anchor Text:";
	}

	$js_category = str_replace("-","_",sanitize_title($result->categoryName));


	if($wpAdminSection=='')
	{
		if($result->link_type=="htmltext")
		return "<li id=\"linkli".$result->id."\"><b>WPM Tag:</b> [WPMID=".$result->id."]<br /><b>WPM Link Name:</b> ".$result->link_name."<br /><b>WPM Link Type:</b> ".$linkTypeValueIs."<br /><b>Outbound URL:</b> ".$result->link." <br /><b>Display URL:</b> ".get_bloginfo('wpurl')."".$clean_cat."/".$result->slug." <br /> <b>".$anchorTextVal."</b> ".$result->anchor_text."<br /><b>Link Relationship:</b> ".$result->follow."<br /><b>Link Masking:</b> ".$result->masking."<br /><b>Auto Link:</b> ".$autoLinkValue."<br /><b>Open Link in New Window:</b> ".$newwinlinkval." <br /><a href=\"#\" onclick='".$send_to_editor."'>Send Link To Editor</a>&nbsp;&nbsp;<a href=\"#\" onclick='copyToClipboard	(\"[WPMID=".$result->id."]\");return false;'>Copy Link To Clipboard</a>&nbsp;&nbsp;<a href=\"#\" onclick=\"wp_marketer_ajax_delete_link(".$result->id.");return false;\">Delete Link</a>&nbsp;&nbsp;<a href=\"#\" onclick=\"wp_marketer_show_edit_link_box(".$result->category.",".$result->parentid.", '".$js_category."','".$result->link."','".$result->anchor_text."','".$result->follow."','".$result->masking."','".$result->auto_link."','".$result->newwinlink."','".$result->id."','$wpAdminSection','".urlencode($result->google_code)."','$result->eac','$result->link_type','$result->image_url','$result->image_width','$result->image_height','$result->image_align','".urlencode($result->js_image_url)."','$result->link_name');return false;\">Edit Link</a></li>\n";
		if($result->link_type=="htmlimage")
		return "<li id=\"linkli".$result->id."\"><b>WPM Tag:</b> [WPMID=".$result->id."]<br /><b>WPM Link Name:</b> ".$result->link_name."<br /><b>WPM Link Type:</b> ".$linkTypeValueIs."<br /><b>Outbound URL:</b> ".$result->link." <br /><b>Display URL:</b> ".get_bloginfo('wpurl')."".$clean_cat."/".$result->slug." <br /> <b>".$anchorTextVal."</b> ".$result->anchor_text."<br /><b>Image URL:</b> ".$result->image_url."<br /><b>Image Width:</b> ".$result->image_width."<br /><b>Image Height:</b> ".$result->image_height."<br /><b>Image Align:</b> ".$result->image_align."<br /><b>Link Masking:</b> ".$result->masking."<br /><b>Open Link in New Window:</b> ".$newwinlinkval." <br /><a href=\"#\" onclick='".$send_to_editor."'>Send Link To Editor</a>&nbsp;&nbsp;<a href=\"#\" onclick='copyToClipboard	(\"[WPMID=".$result->id."]\");return false;'>Copy Link To Clipboard</a>&nbsp;&nbsp;<a href=\"#\" onclick=\"wp_marketer_ajax_delete_link(".$result->id.");return false;\">Delete Link</a>&nbsp;&nbsp;<a href=\"#\" onclick=\"wp_marketer_show_edit_link_box(".$result->category.",".$result->parentid.", '".$js_category."','".$result->link."','".$result->anchor_text."','".$result->follow."','".$result->masking."','".$result->auto_link."','".$result->newwinlink."','".$result->id."','$wpAdminSection','".urlencode($result->google_code)."','$result->eac','$result->link_type','$result->image_url','$result->image_width','$result->image_height','$result->image_align','".urlencode($result->js_image_url)."','$result->link_name');return false;\">Edit Link</a></li>\n";
		if($result->link_type=="javascript")
		return "<li id=\"linkli".$result->id."\"><b>WPM Tag:</b> [WPMID=".$result->id."]<br /><b>WPM Link Name:</b> ".$result->link_name."<br /><b>WPM Link Type:</b> ".$linkTypeValueIs."<br /><a href=\"#\" onclick='".$send_to_editor."'>Send Link To Editor</a>&nbsp;&nbsp;<a href=\"#\" onclick='copyToClipboard	(\"[WPMID=".$result->id."]\");return false;'>Copy Link To Clipboard</a>&nbsp;&nbsp;<a href=\"#\" onclick=\"wp_marketer_ajax_delete_link(".$result->id.");return false;\">Delete Link</a>&nbsp;&nbsp;<a href=\"#\" onclick=\"wp_marketer_show_edit_link_box(".$result->category.",".$result->parentid.", '".$js_category."','".$result->link."','".$result->anchor_text."','".$result->follow."','".$result->masking."','".$result->auto_link."','".$result->newwinlink."','".$result->id."','$wpAdminSection','".urlencode($result->google_code)."','$result->eac','$result->link_type','$result->image_url','$result->image_width','$result->image_height','$result->image_align','".urlencode($result->js_image_url)."','$result->link_name');return false;\">Edit Link</a></li>\n";
	}
	else
	{
		if($result->link_type=="htmltext")
		return "<li id=\"linkli".$result->id."\"><b>WPM Tag:</b> [WPMID=".$result->id."]<br /><b>WPM Link Name:</b> ".$result->link_name."<br /><b>WPM Link Type:</b> ".$linkTypeValueIs."<br /><b>Outbound URL:</b> ".$result->link." <br /><b>Display URL:</b> ".get_bloginfo('wpurl')."".$clean_cat."/".$result->slug." <br /> <b>".$anchorTextVal."</b> ".$result->anchor_text."<br /><b>Link Relationship:</b> ".$result->follow."<br /><b>Link Masking:</b> ".$result->masking."<br /><b>Auto Link:</b> ".$autoLinkValue."<br /><b>Open Link in New Window:</b> ".$newwinlinkval." <br /><a href=\"#\" onclick=\"wp_marketer_ajax_delete_link(".$result->id.");return false;\">Delete Link</a>&nbsp;&nbsp;<a href=\"#\" onclick=\"wp_marketer_show_edit_link_box(".$result->category.",".$result->parentid.", '".$js_category."','".$result->link."','".$result->anchor_text."','".$result->follow."','".$result->masking."','".$result->auto_link."','".$result->newwinlink."','".$result->id."','$wpAdminSection','".urlencode($result->google_code)."','$result->eac','$result->link_type','$result->image_url','$result->image_width','$result->image_height','$result->image_align','".urlencode($result->js_image_url)."','$result->link_name');return false;\">Edit Link</a></li>\n";
		if($result->link_type=="htmlimage")
		return "<li id=\"linkli".$result->id."\"><b>WPM Tag:</b> [WPMID=".$result->id."]<br /><b>WPM Link Name:</b> ".$result->link_name."<br /><b>WPM Link Type:</b> ".$linkTypeValueIs."<br /><b>Outbound URL:</b> ".$result->link." <br /><b>Display URL:</b> ".get_bloginfo('wpurl')."".$clean_cat."/".$result->slug." <br /> <b>".$anchorTextVal."</b> ".$result->anchor_text."<br /><b>Image URL:</b> ".$result->image_url."<br /><b>Image Width:</b> ".$result->image_width."<br /><b>Image Height:</b> ".$result->image_height."<br /><b>Image Align:</b> ".$result->image_align."<br /><b>Link Masking:</b> ".$result->masking."<br /><b>Open Link in New Window:</b> ".$newwinlinkval." <br /><a href=\"#\" onclick=\"wp_marketer_ajax_delete_link(".$result->id.");return false;\">Delete Link</a>&nbsp;&nbsp;<a href=\"#\" onclick=\"wp_marketer_show_edit_link_box(".$result->category.",".$result->parentid.", '".$js_category."','".$result->link."','".$result->anchor_text."','".$result->follow."','".$result->masking."','".$result->auto_link."','".$result->newwinlink."','".$result->id."','$wpAdminSection','".urlencode($result->google_code)."','$result->eac','$result->link_type','$result->image_url','$result->image_width','$result->image_height','$result->image_align','".urlencode($result->js_image_url)."','$result->link_name');return false;\">Edit Link</a></li>\n";
		if($result->link_type=="javascript")
		return "<li id=\"linkli".$result->id."\"><b>WPM Tag:</b> [WPMID=".$result->id."]<br /><b>WPM Link Name:</b> ".$result->link_name."<br /><b>WPM Link Type:</b> ".$linkTypeValueIs."<br /><a href=\"#\" onclick=\"wp_marketer_ajax_delete_link(".$result->id.");return false;\">Delete Link</a>&nbsp;&nbsp;<a href=\"#\" onclick=\"wp_marketer_show_edit_link_box(".$result->category.",".$result->parentid.", '".$js_category."','".$result->link."','".$result->anchor_text."','".$result->follow."','".$result->masking."','".$result->auto_link."','".$result->newwinlink."','".$result->id."','$wpAdminSection','".urlencode($result->google_code)."','$result->eac','$result->link_type','$result->image_url','$result->image_width','$result->image_height','$result->image_align','".urlencode($result->js_image_url)."','$result->link_name');return false;\">Edit Link</a></li>\n";
	}
}

function get_link_list_marketer($id,$catname)
{
$wpAdminSection=strpos($_SERVER['REQUEST_URI'],"wp-marketer-admin-functions.php");
global $wpdb,$wp_marketer_prefix;

$table_name = $wpdb->prefix . $wp_marketer_prefix."_links";
$table_name2 = $wpdb->prefix . $wp_marketer_prefix."_categories";

$results = $wpdb->get_results("SELECT a.*,b.category as categoryName,b.parent as parentid  FROM ".$table_name." as a left join ".$table_name2."  as b on (a.category=b.id) WHERE a.category=".$id);

$link_list = "<ul>\n<li></li>\n";
foreach($results as $result)
{
	$clean_cat = get_category_slug_marketer($result->category);
	$clean_link = get_bloginfo('wpurl')."".$clean_cat."/".$result->slug;
	$send_to_editor = "send_to_editor(\"[WPMID=".$result->id."]\");return false;";
	if($result->newwinlink=="target=_blank")
		$newwinlinkval="Yes";
	else
		$newwinlinkval="No";

	if($result->auto_link==1)
		$autoLinkValue="Enabled";
	else
		$autoLinkValue="Disabled";

	if($result->link_type=="htmltext")
	{
		$linkTypeValueIs="HTML Text";
		$anchorTextVal="Anchor Text:";
	}
	if($result->link_type=="htmlimage")
	{
		$linkTypeValueIs="HTML Image";
		$anchorTextVal="Image Alt:";
	}
	if($result->link_type=="javascript")
	{
		$linkTypeValueIs="JavaScript";
		$anchorTextVal="Anchor Text:";
	}

	$js_category = str_replace("-","_",sanitize_title($result->categoryName));

	if($wpAdminSection=='')
	{
		if($result->link_type=="htmltext")
		$link_list .= "<li id=\"linkli".$result->id."\"><b>WPM Tag:</b> [WPMID=".$result->id."]<br /><b>WPM Link Name:</b> ".$result->link_name."<br /><b>WPM Link Type:</b> ".$linkTypeValueIs."<br /><b>Outbound URL:</b> ".$result->link." <br /><b>Display URL:</b> ".get_bloginfo('wpurl')."".$clean_cat."/".$result->slug." <br /> <b>".$anchorTextVal."</b> ".$result->anchor_text." <br /><b>Link Relationship:</b> ".$result->follow."<br /><b>Link Masking:</b> ".$result->masking."<br /><b>Auto Link:</b> ".$autoLinkValue."<br /><b>Open Link in New Window:</b> ".$newwinlinkval." <br /><a href=\"#\" onclick='".$send_to_editor."'>Send Link To Editor</a>&nbsp;&nbsp;<a href=\"#\" onclick='copyToClipboard	(\"[WPMID=".$result->id."]\");return false;'>Copy Link To Clipboard</a>&nbsp;&nbsp;<a href=\"#\" onclick=\"wp_marketer_ajax_delete_link(".$result->id.");return false;\">Delete Link</a>&nbsp;&nbsp;<a href=\"#\" onclick=\"wp_marketer_show_edit_link_box(".$result->category.",".$result->parentid.", '".$js_category."','".$result->link."','".$result->anchor_text."','".$result->follow."','".$result->masking."','".$result->auto_link."','".$result->newwinlink."','".$result->id."','$wpAdminSection','".urlencode($result->google_code)."','$result->eac','$result->link_type','$result->image_url','$result->image_width','$result->image_height','$result->image_align','".urlencode($result->js_image_url)."','$result->link_name');return false;\">Edit Link</a></li>\n";
		if($result->link_type=="htmlimage")
		$link_list .= "<li id=\"linkli".$result->id."\"><b>WPM Tag:</b> [WPMID=".$result->id."]<br /><b>WPM Link Name:</b> ".$result->link_name."<br /><b>WPM Link Type:</b> ".$linkTypeValueIs."<br /><b>Outbound URL:</b> ".$result->link." <br /><b>Display URL:</b> ".get_bloginfo('wpurl')."".$clean_cat."/".$result->slug." <br /> <b>".$anchorTextVal."</b> ".$result->anchor_text."<br /><b>Image URL:</b> ".$result->image_url."<br /><b>Image Width:</b> ".$result->image_width."<br /><b>Image Height:</b> ".$result->image_height."<br /><b>Image Align:</b> ".$result->image_align."<br /><b>Link Masking:</b> ".$result->masking."<br /><b>Open Link in New Window:</b> ".$newwinlinkval." <br /><a href=\"#\" onclick='".$send_to_editor."'>Send Link To Editor</a>&nbsp;&nbsp;<a href=\"#\" onclick='copyToClipboard	(\"[WPMID=".$result->id."]\");return false;'>Copy Link To Clipboard</a>&nbsp;&nbsp;<a href=\"#\" onclick=\"wp_marketer_ajax_delete_link(".$result->id.");return false;\">Delete Link</a>&nbsp;&nbsp;<a href=\"#\" onclick=\"wp_marketer_show_edit_link_box(".$result->category.",".$result->parentid.", '".$js_category."','".$result->link."','".$result->anchor_text."','".$result->follow."','".$result->masking."','".$result->auto_link."','".$result->newwinlink."','".$result->id."','$wpAdminSection','".urlencode($result->google_code)."','$result->eac','$result->link_type','$result->image_url','$result->image_width','$result->image_height','$result->image_align','".urlencode($result->js_image_url)."','$result->link_name');return false;\">Edit Link</a></li>\n";
		if($result->link_type=="javascript")
		$link_list .= "<li id=\"linkli".$result->id."\"><b>WPM Tag:</b> [WPMID=".$result->id."]<br /><b>WPM Link Name:</b> ".$result->link_name."<br /><b>WPM Link Type:</b> ".$linkTypeValueIs."<br /><a href=\"#\" onclick='".$send_to_editor."'>Send Link To Editor</a>&nbsp;&nbsp;<a href=\"#\" onclick='copyToClipboard	(\"[WPMID=".$result->id."]\");return false;'>Copy Link To Clipboard</a>&nbsp;&nbsp;<a href=\"#\" onclick=\"wp_marketer_ajax_delete_link(".$result->id.");return false;\">Delete Link</a>&nbsp;&nbsp;<a href=\"#\" onclick=\"wp_marketer_show_edit_link_box(".$result->category.",".$result->parentid.", '".$js_category."','".$result->link."','".$result->anchor_text."','".$result->follow."','".$result->masking."','".$result->auto_link."','".$result->newwinlink."','".$result->id."','$wpAdminSection','".urlencode($result->google_code)."','$result->eac','$result->link_type','$result->image_url','$result->image_width','$result->image_height','$result->image_align','".urlencode($result->js_image_url)."','$result->link_name');return false;\">Edit Link</a></li>\n";
	}
	else
	{
		if($result->link_type=="htmltext")
		$link_list .= "<li id=\"linkli".$result->id."\"><b>WPM Tag:</b> [WPMID=".$result->id."]<br /><b>WPM Link Name:</b> ".$result->link_name."<br /><b>WPM Link Type:</b> ".$linkTypeValueIs."<br /><b>Outbound URL:</b> ".$result->link." <br /><b>Display URL:</b> ".get_bloginfo('wpurl')."".$clean_cat."/".$result->slug." <br /> <b>".$anchorTextVal."</b> ".$result->anchor_text." <br /><b>Link Relationship:</b> ".$result->follow."<br /><b>Link Masking:</b> ".$result->masking."<br /><b>Auto Link:</b> ".$autoLinkValue."<br /><b>Open Link in New Window:</b> ".$newwinlinkval." <br /><a href=\"#\" onclick=\"wp_marketer_ajax_delete_link(".$result->id.");return false;\">Delete Link</a>&nbsp;&nbsp;<a href=\"#\" onclick=\"wp_marketer_show_edit_link_box(".$result->category.",".$result->parentid.", '".$js_category."','".$result->link."','".$result->anchor_text."','".$result->follow."','".$result->masking."','".$result->auto_link."','".$result->newwinlink."','".$result->id."','$wpAdminSection','".urlencode($result->google_code)."','$result->eac','$result->link_type','$result->image_url','$result->image_width','$result->image_height','$result->image_align','".urlencode($result->js_image_url)."','$result->link_name');return false;\">Edit Link</a></li>\n";
		if($result->link_type=="htmlimage")
		$link_list .= "<li id=\"linkli".$result->id."\"><b>WPM Tag:</b> [WPMID=".$result->id."]<br /><b>WPM Link Name:</b> ".$result->link_name."<br /><b>WPM Link Type:</b> ".$linkTypeValueIs."<br /><b>Outbound URL:</b> ".$result->link." <br /><b>Display URL:</b> ".get_bloginfo('wpurl')."".$clean_cat."/".$result->slug." <br /> <b>".$anchorTextVal."</b> ".$result->anchor_text."<br /><b>Image URL:</b> ".$result->image_url."<br /><b>Image Width:</b> ".$result->image_width."<br /><b>Image Height:</b> ".$result->image_height."<br /><b>Image Align:</b> ".$result->image_align."<br /><b>Link Masking:</b> ".$result->masking."<br /><b>Open Link in New Window:</b> ".$newwinlinkval." <br /><a href=\"#\" onclick=\"wp_marketer_ajax_delete_link(".$result->id.");return false;\">Delete Link</a>&nbsp;&nbsp;<a href=\"#\" onclick=\"wp_marketer_show_edit_link_box(".$result->category.",".$result->parentid.", '".$js_category."','".$result->link."','".$result->anchor_text."','".$result->follow."','".$result->masking."','".$result->auto_link."','".$result->newwinlink."','".$result->id."','$wpAdminSection','".urlencode($result->google_code)."','$result->eac','$result->link_type','$result->image_url','$result->image_width','$result->image_height','$result->image_align','".urlencode($result->js_image_url)."','$result->link_name');return false;\">Edit Link</a></li>\n";
		if($result->link_type=="javascript")
		$link_list .= "<li id=\"linkli".$result->id."\"><b>WPM Tag:</b> [WPMID=".$result->id."]<br /><b>WPM Link Name:</b> ".$result->link_name."<br /><b>WPM Link Type:</b> ".$linkTypeValueIs."<br /><a href=\"#\" onclick=\"wp_marketer_ajax_delete_link(".$result->id.");return false;\">Delete Link</a>&nbsp;&nbsp;<a href=\"#\" onclick=\"wp_marketer_show_edit_link_box(".$result->category.",".$result->parentid.", '".$js_category."','".$result->link."','".$result->anchor_text."','".$result->follow."','".$result->masking."','".$result->auto_link."','".$result->newwinlink."','".$result->id."','$wpAdminSection','".urlencode($result->google_code)."','$result->eac','$result->link_type','$result->image_url','$result->image_width','$result->image_height','$result->image_align','".urlencode($result->js_image_url)."','$result->link_name');return false;\">Edit Link</a></li>\n";
	}
}
$link_list .= "</ul>\n";

return $link_list;
}

function get_category_li_marketer($id,$wpAdminSection)
{
	global $wpdb,$wp_marketer_prefix;

	$table_name = $wpdb->prefix . $wp_marketer_prefix."_categories";

	
	$table_name4 = $wpdb->prefix . $wp_marketer_prefix."_auto_link";
	$sqlal = "SELECT * FROM ".$table_name4;
	$rowal = $wpdb->get_row($sqlal, OBJECT);
	$rowal->image_max_width;
	$rowal->image_max_height;



	$result = $wpdb->get_row("SELECT * FROM ".$table_name." WHERE id=".$id, OBJECT);
	$js_category = str_replace("-","_",sanitize_title($result->category));

	return "<li class=\"expandable\" id=\"catli".$result->id."\" ><div class=\"hitarea expandable-hitarea\" onclick=\"temptoggle('tcatli".$result->id."')\"></div><div id=\"tcatli".$result->id."\"></div><div id=\"catName".$result->id."\">".$result->category."</div><a href=\"#\" onclick=\"wp_marketer_show_add_link_box(".$result->id.",".$result->parent.", '".$js_category."','$wpAdminSection','$rowal->image_max_width','$rowal->image_max_height');return false;\">Add Link</a>&nbsp;&nbsp;<a href=\"#\" onclick=\"wp_marketer_show_add_sub_category_box(".$result->id.",".$result->parent.", '".$js_category."','$wpAdminSection');return false;\">Add Sub Category</a>&nbsp;&nbsp;<a href=\"#\" onclick=\"wp_marketer_ajax_delete_category(".$result->id."); return false;\">Delete Sub Category</a>&nbsp;&nbsp;<a href=\"#\" onclick=\"wp_marketer_show_edit_category_box(".$result->id.",".$result->parent.", '".$js_category."','".$result->category."');return false;\">Edit Sub Category</a>&nbsp;<img src='http://".$_SERVER['HTTP_HOST']."/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is Create new categories and subcategories?' onclick='javascript:showMaskingWindow(\"create_new_categories_and_subcategories\");'><div id=\"add-link-".$result->parent."-".$js_category."\" style=\"display:none;\"></div><div id=\"add-sub-category-".$result->parent."-".$js_category."\" style=\"display:none;\"></div><div id=\"edit-link-".$result->parent."-".$js_category."\" style=\"display:none;\"></div><div id=\"edit-sub-category-".$result->parent."-".$js_category."\" style=\"display:none;\"></div>\n<ul style=\"display:none;\"><li></li></ul>";
}

function get_category_list_marketer($parent = 0, $escape=false)
{
	$wpAdminSection=strpos($_SERVER['REQUEST_URI'],"wp-marketer-admin-functions.php");
	global $wpdb,$wp_marketer_prefix;

	$table_name = $wpdb->prefix . $wp_marketer_prefix."_categories";


	$table_name4 = $wpdb->prefix . $wp_marketer_prefix."_auto_link";
	$sqlal = "SELECT * FROM ".$table_name4;
	$rowal = $wpdb->get_row($sqlal, OBJECT);
	$rowal->image_max_width;
	$rowal->image_max_height;

	$results = $wpdb->get_results("SELECT * FROM ".$table_name." WHERE parent=".$parent." ORDER BY category ASC");

	if(!$results)
		return;

	$category_list = "<ul";

	 if($parent ==0)
	 	$category_list .= " id=\"wpanavmarketer\" ";

	 $category_list .= ">\n";
	foreach($results as $result)
	{
		$js_category = str_replace("-","_",sanitize_title($result->category));
		$category_list .= "<li id=\"catli".$result->id."\" ><div id=\"catName".$result->id."\">".$result->category."</div><a href=\"#\" onclick=\"wp_marketer_show_add_link_box(".$result->id.",".$result->parent.", '".$js_category."','$wpAdminSection','$rowal->image_max_width','$rowal->image_max_height');return false;\">Add Link</a>&nbsp;&nbsp;<a href=\"#\" onclick=\"wp_marketer_show_add_sub_category_box(".$result->id.",".$result->parent.", '".$js_category."','$wpAdminSection');return false;\">Add Sub Category</a>&nbsp;&nbsp;<a href=\"#\" onclick=\"wp_marketer_ajax_delete_category(".$result->id."); return false;\">Delete Sub Category</a>&nbsp;&nbsp;<a href=\"#\" onclick=\"wp_marketer_show_edit_category_box(".$result->id.",".$result->parent.", '".$js_category."','".$result->category."');return false;\">Edit Sub Category</a>&nbsp;<img src='http://".$_SERVER['HTTP_HOST']."/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is Create new categories and subcategories?' onclick='javascript:showMaskingWindow(\"create_new_categories_and_subcategories\");'><div id=\"add-link-".$result->parent."-".$js_category."\" style=\"display:none;\"></div><div id=\"add-sub-category-".$result->parent."-".$js_category."\" style=\"display:none;\"></div><div id=\"edit-link-".$result->parent."-".$js_category."\" style=\"display:none;\"></div><div id=\"edit-sub-category-".$result->parent."-".$js_category."\" style=\"display:none;\"></div>\n";


		$category_list .= get_link_list_marketer($result->id,$result->category);



		$category_list .= get_category_list_marketer($result->id);

		$category_list .= "</li>\n";

	}

	$category_list .= "</ul>\n";

	return $category_list;
}
add_action('wp_ajax_wp_marketer_delete_category', 'wp_marketer_delete_category' );

function wp_marketer_delete_category()
{
global $wpdb,$wp_marketer_prefix;
$table_name = $wpdb->prefix . $wp_marketer_prefix."_categories";
$table_name2 = $wpdb->prefix . $wp_marketer_prefix."_links";


$category = $_POST['category'];


if($category == "")
	die("");

if(is_numeric($category))
{
	if($wpdb->get_var("SELECT COUNT(*) FROM ".$table_name." WHERE parent=".$category)>0)
	{
		die("alert('You must delete a category\'s sub-categories first.');");
	}
	else if($wpdb->get_var("SELECT COUNT(*) FROM ".$table_name2." WHERE category=".$category)>0)
	{
		die("alert('You must delete a category\'s links first.');");
	}
	else
	{
		$sql = "DELETE FROM ".$table_name." WHERE id=".$category;
		$wpdb->query($sql);
		echo "document.getElementById('catli".$category."').style.display=\"none\";";
	}
}

}

add_action('wp_ajax_wp_marketer_add_category', 'wp_marketer_add_category' );

function wp_marketer_add_category()
{
global $wpdb,$wp_marketer_prefix;
$table_name = $wpdb->prefix . $wp_marketer_prefix."_categories";


$category = $_POST['category'];
$parent 	= $_POST['parent'];
$wpAdminSection=$_POST['wpAdminSection'];

if($category == "" || $parent == "")
	die("document.getElementById('wp_marketer_add_category_results').innerHTML = \"Category name is missing from your category.\";");


if(preg_match ("/[^a-zA-Z0-9 ]+/", $category))
{
	die("document.getElementById('wp_marketer_add_category_results').innerHTML = \"Category name must be only letters and numbers.\";");
}

$sql = "SELECT * FROM ".$table_name." WHERE category='".$wpdb->escape($category)."' AND parent='".$wpdb->escape($parent)."'";

$row = $wpdb->get_row($sql, OBJECT);

if($row)
	die("document.getElementById('wp_marketer_add_category_results').innerHTML = \"Category already exists at this depth! Try another.\";");

$sql = "INSERT INTO ".$table_name." (category,parent) VALUES('".$wpdb->escape($category)."','".$wpdb->escape($parent)."')";
$wpdb->query($sql);

$new_cat_li = str_replace("\r","",str_replace("\n","",str_replace("'","\'",get_category_li_marketer($wpdb->insert_id,$wpAdminSection))));

echo "var navfil = '".$new_cat_li."';";

if($parent==0)
	echo "jQuery('#wpanavmarketer').prepend(navfil);";
else
	echo "jQuery('#catli".$parent.">ul').prepend(navfil);";


die("document.getElementById('wp_marketer_add_category_results').innerHTML = \"Category ".$category." successfully added!\";");
}


add_action('wp_ajax_wp_marketer_edit_category', 'wp_marketer_edit_category' );

function wp_marketer_edit_category()
{
global $wpdb,$wp_marketer_prefix;
$table_name = $wpdb->prefix . $wp_marketer_prefix."_categories";


$category = $_POST['category'];
$parent   = $_POST['parent'];

if($category == "" || $parent == "")
	die("document.getElementById('wp_marketer_add_category_results').innerHTML = \"Category name is missing from your category.\";");


if(preg_match ("/[^a-zA-Z0-9 ]+/", $category))
{
	die("document.getElementById('wp_marketer_add_category_results').innerHTML = \"Category name must be only letters and numbers.\";");
}

$sql = "SELECT * FROM ".$table_name." WHERE category='".$wpdb->escape($category)."' AND parent='".$wpdb->escape($parent)."'";

$row = $wpdb->get_row($sql, OBJECT);


$sql = "update ".$table_name." set category='".$wpdb->escape($category)."' where id ='".$wpdb->escape($parent)."'";
$wpdb->query($sql);

echo "document.getElementById('catName".$parent."').innerHTML=\"$category\";";
die("document.getElementById('wp_marketer_add_category_results').innerHTML = \"Category ".$category." successfully updated!\";");
}



add_action('admin_head', 'wp_marketer_category_js_admin_header' );

function wp_marketer_category_js_admin_header() // this is a PHP function
{
  // use JavaScript SACK library for Ajax
  wp_print_scripts( array( 'sack' ));

  // Define custom JavaScript function
?>

<script src="<? echo get_bloginfo('wpurl'); ?>/wp-content/plugins/wp-marketer/jquery.treeview.js" type="text/javascript"></script>
<script type="text/javascript">
//<![CDATA[



var jtree_settings;


jQuery(document).ready(function(){

	jtree_settings = {animated: "slow",collapsed: true};

	// first example
	jQuery("#wpanavmarketer").treeview(jtree_settings);


});

function temptoggle(obj)
{
	jQuery('#'+obj)
                                        .parent()
                                        // swap classes for hitarea
                                        .find(">.hitarea")
                                                .swapClass( jQuery.fn.treeview.classes.collapsableHitarea, jQuery.fn.treeview.classes.expandableHitarea )
                                                .swapClass( jQuery.fn.treeview.classes.lastCollapsableHitarea, jQuery.fn.treeview.classes.lastExpandableHitarea )
                                        .end()
                                        // swap classes for parent li
                                        .swapClass( jQuery.fn.treeview.classes.collapsable, jQuery.fn.treeview.classes.expandable )
                                        .swapClass( jQuery.fn.treeview.classes.lastCollapsable, jQuery.fn.treeview.classes.lastExpandable )
                                        // find child lists
                                        .find( ">ul" )
                                        // toggle them
                                        .heightToggle( jtree_settings.animated, jtree_settings.toggle );
}

function wp_marketer_show_add_sub_category_box(idc, parentid, jscategory,wpAdminSection)
{
	var display_type = jQuery('#add-link-'+parentid+'-'+jscategory).css("display");
	var display_type2 = jQuery('#add-sub-category-'+parentid+'-'+jscategory).css("display");

	if(!jQuery('#add-sub-category-'+parentid+'-'+jscategory).html())
		jQuery('#add-sub-category-'+parentid+'-'+jscategory).html("<h2 class='subtitle'>Add A Sub Category</h2> <form><input type=\"text\" name=\"c"+jscategory+parentid+"\"><input type=\"button\" value=\"Add Sub Category\" onclick=\"wp_marketer_ajax_add_category(this.form.c"+jscategory+parentid+","+idc+",'"+wpAdminSection+"')\"><input type=\"button\" value=\"Cancel\" onclick=\"wp_marketer_show_add_sub_category_box('"+idc+"','"+parentid+"','"+jscategory+"','"+wpAdminSection+"')\"></form>");

	jQuery('#add-sub-category-'+parentid+'-'+jscategory).toggle("slow");

	var display_hit = jQuery('#add-sub-category-'+parentid+'-'+jscategory).parent().find(">.hitarea").css("zIndex");

	if((display_type=="none" && display_type2!="none" && display_hit=="99") || (display_type=="none" && display_type2=="none" && display_hit!="99"))
	{
	jQuery('#add-sub-category-'+parentid+'-'+jscategory)
                                        .parent()
                                        // swap classes for hitarea
                                        .find(">.hitarea")
                                                .swapClass( jQuery.fn.treeview.classes.collapsableHitarea, jQuery.fn.treeview.classes.expandableHitarea )
                                                .swapClass( jQuery.fn.treeview.classes.lastCollapsableHitarea, jQuery.fn.treeview.classes.lastExpandableHitarea )
                                        .end()
                                        // swap classes for parent li
                                        .swapClass( jQuery.fn.treeview.classes.collapsable, jQuery.fn.treeview.classes.expandable )
                                        .swapClass( jQuery.fn.treeview.classes.lastCollapsable, jQuery.fn.treeview.classes.lastExpandable )
                                        // find child lists
                                        .find( ">ul" )
                                        // toggle them
                                        .heightToggle( jtree_settings.animated, jtree_settings.toggle );
	}

}


function wp_marketer_show_edit_category_box(idc, parentid, jscategory,category)
{
	var display_type = jQuery('#add-link-'+parentid+'-'+jscategory).css("display");
	var display_type2 = jQuery('#edit-sub-category-'+parentid+'-'+jscategory).css("display");

	if(!jQuery('#edit-sub-category-'+parentid+'-'+jscategory).html())
		jQuery('#edit-sub-category-'+parentid+'-'+jscategory).html("<h2 class='subtitle'>Edit Sub Category</h2> <form><input type=\"text\" value=\""+category+"\" name=\"c"+jscategory+parentid+"\"><input type=\"button\" value=\"Edit Sub Category\" onclick=\"wp_marketer_ajax_edit_category(this.form.c"+jscategory+parentid+","+idc+")\"><input type=\"button\" value=\"Cancel\" onclick=\"wp_marketer_show_edit_category_box('"+idc+"','"+parentid+"','"+jscategory+"','"+category+"')\"></form>");

	jQuery('#edit-sub-category-'+parentid+'-'+jscategory).toggle("slow");

	var display_hit = jQuery('#edit-sub-category-'+parentid+'-'+jscategory).parent().find(">.hitarea").css("zIndex");

	if((display_type=="none" && display_type2!="none" && display_hit=="99") || (display_type=="none" && display_type2=="none" && display_hit!="99"))
	{
	jQuery('#edit-sub-category-'+parentid+'-'+jscategory)
                                        .parent()
                                        // swap classes for hitarea
                                        .find(">.hitarea")
                                                .swapClass( jQuery.fn.treeview.classes.collapsableHitarea, jQuery.fn.treeview.classes.expandableHitarea )
                                                .swapClass( jQuery.fn.treeview.classes.lastCollapsableHitarea, jQuery.fn.treeview.classes.lastExpandableHitarea )
                                        .end()
                                        // swap classes for parent li
                                        .swapClass( jQuery.fn.treeview.classes.collapsable, jQuery.fn.treeview.classes.expandable )
                                        .swapClass( jQuery.fn.treeview.classes.lastCollapsable, jQuery.fn.treeview.classes.lastExpandable )
                                        // find child lists
                                        .find( ">ul" )
                                        // toggle them
                                        .heightToggle( jtree_settings.animated, jtree_settings.toggle );
	}

}



function wp_marketer_show_add_link_box(idc, parentid, jscategory,wpAdminSection,image_max_width,image_max_height)
{
	var display_type = jQuery('#add-sub-category-'+parentid+'-'+jscategory).css("display");
	var display_type2 = jQuery('#add-link-'+parentid+'-'+jscategory).css("display");

	if(!jQuery('#add-link-'+parentid+'-'+jscategory).html())
	{
		var str = "<h2 class='subtitle'>Add Link</h2>";
		str += "<div id=\"wp_marketer_add_link_results\"></div>";
		str += "<form>  <table border=0><tr><td align=\"left\">";
		str += "<b>WPM Link Type:</b><img src='http://"+top.location.host+"/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is Link Type?' onclick='javascript:showMaskingWindow(\"link_type\")'></td><td>&nbsp;&nbsp;HTML Text<input type=\"radio\" name=\"wp_marketer_add_new_link_linktype"+idc+"_"+parentid+"_"+jscategory+"\" value=\"htmltext\" checked onclick='showRelatedLinkField(this,\"htmlimage"+idc+"_"+parentid+"_"+jscategory+"\",\"javascript"+idc+"_"+parentid+"_"+jscategory+"\",\"autolinkdiv"+idc+"_"+parentid+"_"+jscategory+"\",\"reldiv"+idc+"_"+parentid+"_"+jscategory+"\",\"linkmaskingdiv"+idc+"_"+parentid+"_"+jscategory+"\",\"opennewwindiv"+idc+"_"+parentid+"_"+jscategory+"\",\"anchorlablediv"+idc+"_"+parentid+"_"+jscategory+"\",\"imgalttagdiv"+idc+"_"+parentid+"_"+jscategory+"\",\"outbounddiv"+idc+"_"+parentid+"_"+jscategory+"\",\"anchor_img_tag"+idc+"_"+parentid+"_"+jscategory+"\")'>&nbsp;&nbsp;HTML Image<input type=\"radio\" name=\"wp_marketer_add_new_link_linktype"+idc+"_"+parentid+"_"+jscategory+"\" value=\"htmlimage\" onclick='showRelatedLinkField(this,\"htmlimage"+idc+"_"+parentid+"_"+jscategory+"\",\"javascript"+idc+"_"+parentid+"_"+jscategory+"\",\"autolinkdiv"+idc+"_"+parentid+"_"+jscategory+"\",\"reldiv"+idc+"_"+parentid+"_"+jscategory+"\",\"linkmaskingdiv"+idc+"_"+parentid+"_"+jscategory+"\",\"opennewwindiv"+idc+"_"+parentid+"_"+jscategory+"\",\"anchorlablediv"+idc+"_"+parentid+"_"+jscategory+"\",\"imgalttagdiv"+idc+"_"+parentid+"_"+jscategory+"\",\"outbounddiv"+idc+"_"+parentid+"_"+jscategory+"\",\"anchor_img_tag"+idc+"_"+parentid+"_"+jscategory+"\")'>&nbsp;&nbsp;JavaScript<input type=\"radio\" name=\"wp_marketer_add_new_link_linktype"+idc+"_"+parentid+"_"+jscategory+"\" value=\"javascript\" onclick='showRelatedLinkField(this,\"htmlimage"+idc+"_"+parentid+"_"+jscategory+"\",\"javascript"+idc+"_"+parentid+"_"+jscategory+"\",\"autolinkdiv"+idc+"_"+parentid+"_"+jscategory+"\",\"reldiv"+idc+"_"+parentid+"_"+jscategory+"\",\"linkmaskingdiv"+idc+"_"+parentid+"_"+jscategory+"\",\"opennewwindiv"+idc+"_"+parentid+"_"+jscategory+"\",\"anchorlablediv"+idc+"_"+parentid+"_"+jscategory+"\",\"imgalttagdiv"+idc+"_"+parentid+"_"+jscategory+"\",\"outbounddiv"+idc+"_"+parentid+"_"+jscategory+"\",\"anchor_img_tag"+idc+"_"+parentid+"_"+jscategory+"\")'>";

		str += "</td></tr></table>  <table border=0 width='100%'> <tr><td align=\"left\" width='30%'>";
		str += "<b>WPM Link Name:</b><img src='http://"+top.location.host+"/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is Link Name?' onclick='javascript:showMaskingWindow(\"link_name\")'></td><td> <input type=\"text\" name=\"wp_marketer_add_new_link_linkname"+idc+"_"+parentid+"_"+jscategory+"\"> ";

		str += "</td></tr> <tr><td align=\"left\" width='100%' colspan=2><div id=\"outbounddiv"+idc+"_"+parentid+"_"+jscategory+"\"><table border=0 width='100%'><tr><td width='30%'>";
		str += "<b>Outbound URL:</b><img src='http://"+top.location.host+"/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is Outbound URL?' onclick='javascript:showMaskingWindow(\"outbound_url\")'></td><td> <input type=\"text\" name=\"wp_marketer_add_new_link_link_"+idc+"_"+parentid+"_"+jscategory+"\"> ";

		str += "</td></tr></table></div></td></tr><tr><td align=\"left\" width='100%' colspan=2><div id=\"anchor_img_tag"+idc+"_"+parentid+"_"+jscategory+"\"><table border=0 width='100%'><tr><td width='30%'>";
		str += "<div id=\"anchorlablediv"+idc+"_"+parentid+"_"+jscategory+"\"><b>Anchor Text:</b><img src='http://"+top.location.host+"/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is Anchor Text?' onclick='javascript:showMaskingWindow(\"anchor_text\")'></div><div id=\"imgalttagdiv"+idc+"_"+parentid+"_"+jscategory+"\" style=\"display:none\"><b>Image Alt:</b><img src='http://"+top.location.host+"/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is Image Alt?' onclick='javascript:showMaskingWindow(\"image_alt_tag\")'></div></td><td> <input type=\"text\" name=\"wp_marketer_add_new_link_text"+idc+"_"+parentid+"_"+jscategory+"\">";

		str += "</td></tr></table></div></td></tr></table>    <table border=0 width='100%'><tr><td valign=\"top\" align=\"left\"><div id=\"htmlimage"+idc+"_"+parentid+"_"+jscategory+"\" style=\"display:none\"><table border=0 width='100%'><tr><td valign=\"top\" align=\"left\" width='30%'>";
		str += "<b>Image URL:</b><img src='http://"+top.location.host+"/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is Image URL?' onclick='javascript:showMaskingWindow(\"image_url\")'></td><td><input type=\"text\" size=40 name=\"wp_marketer_add_new_link_imageurl"+idc+"_"+parentid+"_"+jscategory+"\">";

		str += "</td></tr>   <tr><td valign=\"top\" align=\"left\">";
		str += "<b>Image Width:</b><img src='http://"+top.location.host+"/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is Image Width?' onclick='javascript:showMaskingWindow(\"image_width\")'></td><td> <input type=\"text\" size=10 name=\"wp_marketer_add_new_link_imagewidth"+idc+"_"+parentid+"_"+jscategory+"\">";

		str += "</td></tr>   <tr><td valign=\"top\" align=\"left\">";
		str += "<b>Image Height:</b><img src='http://"+top.location.host+"/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is Image Height?' onclick='javascript:showMaskingWindow(\"image_height\")'></td><td> <input type=\"text\" size=10 name=\"wp_marketer_add_new_link_imageheight"+idc+"_"+parentid+"_"+jscategory+"\">";

		str += "</td></tr>  <tr><td valign=\"top\" align=\"left\">";
		str += "<b>Image Align:</b><img src='http://"+top.location.host+"/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is Image Align?' onclick='javascript:showMaskingWindow(\"image_align\")'></td><td> <select name=\"wp_marketer_add_new_link_imagealign"+idc+"_"+parentid+"_"+jscategory+"\"><option value=''>None</option><option value='middle'>middle</option><option value='left'>left</option><option value='right'>right</option><option value='top'>top</option><option value='bottom'>bottom</option><option value='absmiddle'>absmiddle</option><option value='absbottom'>absbottom</option><option value='texttop'>texttop</option><option value='baseline'>baseline</option></select>";

		str += "</td></tr>  </table></div></td></tr></table>    <table border=0 width='100%'><tr><td align=\"left\" width='100%' colspan=2><div id=\"reldiv"+idc+"_"+parentid+"_"+jscategory+"\"><table width='100%'><tr><td width='30%'>";
		str += "<b>Link Relationship:</b><img src='http://"+top.location.host+"/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is Link Relationship?' onclick='javascript:showMaskingWindow(\"realtion\")'></td><td><select name=\"wp_marketer_add_new_link_follow"+idc+"_"+parentid+"_"+jscategory+"\"><option value='dofollow'>dofollow</option><option value='nofollow'>nofollow</option></select>";

		str += "</td></tr></table></div></td></tr><tr><td align=\"left\" colspan=2><div id=\"linkmaskingdiv"+idc+"_"+parentid+"_"+jscategory+"\"><table border=0 width='100%'><tr><td width='30%'>";
		str += "<b>Link Masking:</b><img src='http://"+top.location.host+"/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is Link Masking?' onclick='javascript:showMaskingWindow(\"link_masking\")'></td><td><input type=\"checkbox\" name=\"wp_marketer_add_new_link_masking"+idc+"_"+parentid+"_"+jscategory+"\">";

		str += "</td></tr></table></div></td></tr><tr><td align=\"left\" width='100%' colspan=2><div id=\"autolinkdiv"+idc+"_"+parentid+"_"+jscategory+"\"><table width='100%'><tr><td width='30%'>";
		str += "<b>Auto Link:</b><img src='http://"+top.location.host+"/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is Auto Link?' onclick='javascript:showMaskingWindow(\"auto_link\")'></td><td><input type=\"checkbox\" name=\"wp_marketer_add_new_link_auto"+idc+"_"+parentid+"_"+jscategory+"\">";

		str += "</td></tr></table></div></td></tr><tr><td align=\"left\" colspan=2><div id=\"opennewwindiv"+idc+"_"+parentid+"_"+jscategory+"\"><table width='100%'><tr><td width='30%'>";
		str += "<b>Open Link in New Window:</b><img src='http://"+top.location.host+"/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is Open Link in New Window?' onclick='javascript:showMaskingWindow(\"new_window_property\")'></td><td>  <input type=\"checkbox\" name=\"wp_marketer_add_new_link_newwinlink"+idc+"_"+parentid+"_"+jscategory+"\">";

		str += "</td></tr></table></div></td></tr><tr><td align=\"left\" width='30%'>";
		str += "<b>Enable Adwords Conversions:</b><img src='http://"+top.location.host+"/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is Enable Adwords Conversions?' onclick='javascript:showMaskingWindow(\"adwords_conversions\")'></td><td>  <input type=\"checkbox\" name=\"wp_marketer_add_new_link_eac"+idc+"_"+parentid+"_"+jscategory+"\" onclick='javascript:showHideACS(this,\"eac"+idc+"_"+parentid+"_"+jscategory+"\")'>";
		str += "</td></tr></table><table border=0 width='100%'><tr><td valign=\"top\" align=\"left\" width='100%'><div id=\"eac"+idc+"_"+parentid+"_"+jscategory+"\" style=\"display:none\"><table width='100%'><tr><td width='30%' valign='top'>";
		str += "<b>Adwords Conversion Snippet:</b><img src='http://"+top.location.host+"/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is Adwords Conversion Snippet?' onclick='javascript:showMaskingWindow(\"adwords_conversions_snippet\")'></td><td> <textarea cols=50 rows=10 name=\"wp_marketer_add_new_link_googlecode"+idc+"_"+parentid+"_"+jscategory+"\"></textarea>";

		str += "</td></tr></table></div></td></tr></table><table border=0 width='100%'><tr><td valign=\"top\" align=\"left\" width='100%'><div id=\"javascript"+idc+"_"+parentid+"_"+jscategory+"\" style=\"display:none\"><table width='100%' border=0><tr><td width='30%' valign='top'>";
		str += "<b>JavaScript Code Snippet:</b><img src='http://"+top.location.host+"/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is JavaScript Code Snippet?' onclick='javascript:showMaskingWindow(\"javascript_code_snippet\")'></td><td> <textarea cols=50 rows=10 name=\"wp_marketer_add_new_link_jscs"+idc+"_"+parentid+"_"+jscategory+"\"></textarea>";

		
		str += "</td></tr></table></div></td></tr></table>";
		str += "<table border=0><tr><td><input type=\"button\" value=\"Add WPM Link\" onclick=\"wp_marketer_ajax_add_link(this.form.wp_marketer_add_new_link_link_"+idc+"_"+parentid+"_"+jscategory+",this.form.wp_marketer_add_new_link_text"+idc+"_"+parentid+"_"+jscategory+",this.form.wp_marketer_add_new_link_follow"+idc+"_"+parentid+"_"+jscategory+",this.form.wp_marketer_add_new_link_masking"+idc+"_"+parentid+"_"+jscategory+",this.form.wp_marketer_add_new_link_auto"+idc+"_"+parentid+"_"+jscategory+",this.form.wp_marketer_add_new_link_newwinlink"+idc+"_"+parentid+"_"+jscategory+", "+idc+",'"+wpAdminSection+"',this.form.wp_marketer_add_new_link_googlecode"+idc+"_"+parentid+"_"+jscategory+",this.form.wp_marketer_add_new_link_eac"+idc+"_"+parentid+"_"+jscategory+",this.form.wp_marketer_add_new_link_linktype"+idc+"_"+parentid+"_"+jscategory+",this.form.wp_marketer_add_new_link_imageurl"+idc+"_"+parentid+"_"+jscategory+",this.form.wp_marketer_add_new_link_jscs"+idc+"_"+parentid+"_"+jscategory+",this.form.wp_marketer_add_new_link_imagewidth"+idc+"_"+parentid+"_"+jscategory+",this.form.wp_marketer_add_new_link_imageheight"+idc+"_"+parentid+"_"+jscategory+",this.form.wp_marketer_add_new_link_imagealign"+idc+"_"+parentid+"_"+jscategory+",this.form.wp_marketer_add_new_link_linkname"+idc+"_"+parentid+"_"+jscategory+","+image_max_width+","+image_max_height+");\" />";
		str += "<input type=\"button\" value=\"Cancel\" onclick=\"wp_marketer_show_add_link_box('"+idc+"','"+parentid+"','"+jscategory+"','"+wpAdminSection+"','"+image_max_width+"','"+image_max_height+"');\" /></td></tr></table>";

		str += "</form><div id=\"wp_marketer_add_link_msg\" style=\"color:red;\"></div>";

		jQuery('#add-link-'+parentid+'-'+jscategory).html(str);
	}
	jQuery('#add-link-'+parentid+'-'+jscategory).toggle("slow");

	var display_hit = jQuery('#add-link-'+parentid+'-'+jscategory).parent().find(">.hitarea").css("zIndex");

	if((display_type=="none" && display_type2!="none" && display_hit=="99") || (display_type=="none" && display_type2=="none" && display_hit!="99"))
	{
	jQuery('#add-link-'+parentid+'-'+jscategory)
                                        .parent()
                                        // swap classes for hitarea
                                        .find(">.hitarea")
                                                .swapClass( jQuery.fn.treeview.classes.collapsableHitarea, jQuery.fn.treeview.classes.expandableHitarea )
                                                .swapClass( jQuery.fn.treeview.classes.lastCollapsableHitarea, jQuery.fn.treeview.classes.lastExpandableHitarea )
                                        .end()
                                        // swap classes for parent li
                                        .swapClass( jQuery.fn.treeview.classes.collapsable, jQuery.fn.treeview.classes.expandable )
                                        .swapClass( jQuery.fn.treeview.classes.lastCollapsable, jQuery.fn.treeview.classes.lastExpandable )
                                        // find child lists
                                        .find( ">ul" )
                                        // toggle them
                                        .heightToggle( jtree_settings.animated, jtree_settings.toggle );
	}

}
function wp_marketer_ajax_delete_link(idl)
{
	if(confirm("Are you sure you want to delete this link?"))
	{
	   var mysack = new sack(
	       "<?php bloginfo( 'wpurl' ); ?>/wp-admin/admin-ajax.php" );

	  mysack.execute = 1;
	  mysack.method = 'POST';
	  mysack.setVar( "action", "wp_marketer_delete_link" );
	  mysack.setVar( "link", idl );
	  mysack.encVar( "cookie", document.cookie, false );
	  mysack.onError = function() { alert('Ajax error in deleting the link' )};
	  mysack.runAJAX();
	}

}

function wp_marketer_ajax_delete_category(idc)
{
	if(confirm("Are you sure you want to delete this category?"))
	{
	   var mysack = new sack(
	       "<?php bloginfo( 'wpurl' ); ?>/wp-admin/admin-ajax.php" );

	  mysack.execute = 1;
	  mysack.method = 'POST';
	  mysack.setVar( "action", "wp_marketer_delete_category" );
	  mysack.setVar( "category", idc );
	  mysack.encVar( "cookie", document.cookie, false );
	  mysack.onError = function() { alert('Ajax error in deleting the category' )};
	  mysack.runAJAX();
	}

}

function copyToClipboard(s)
{
 	if (window.clipboardData)
   {

   // the IE-manier
   window.clipboardData.setData("Text", s);

   // waarschijnlijk niet de beste manier om Moz/NS te detecteren;
   // het is mij echter onbekend vanaf welke versie dit precies werkt:
   }
   else if (window.netscape)
   {

   // dit is belangrijk maar staat nergens duidelijk vermeld:
   netscape.security.PrivilegeManager.enablePrivilege('UniversalXPConnect');

   // maak een interface naar het clipboard
   var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
   if (!clip) return;

   // maak een transferable
   var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);
   if (!trans) return;

   // specificeer wat voor soort data we op willen halen; text in dit geval
   trans.addDataFlavor('text/unicode');

   // om de data uit de transferable te halen hebben we 2 nieuwe objecten nodig   om het in op te slaan
   var str = new Object();
   var len = new Object();

   var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);

   var copytext=s;

   str.data=copytext;

   trans.setTransferData("text/unicode",str,copytext.length*2);

   var clipid=Components.interfaces.nsIClipboard;

   if (!clip) return false;

   clip.setData(trans,null,clipid.kGlobalClipboard);

   }
}

function wp_marketer_ajax_add_category( category, parentf,wpAdminSection )
{
   var mysack = new sack(
       "<?php bloginfo( 'wpurl' ); ?>/wp-admin/admin-ajax.php" );

  mysack.execute = 1;
  mysack.method = 'POST';
  mysack.setVar( "action", "wp_marketer_add_category" );
  mysack.setVar( "category", category.value );
   mysack.setVar( "wpAdminSection", wpAdminSection);
  mysack.setVar( "parent", parentf );
  mysack.encVar( "cookie", document.cookie, false );
  mysack.onError = function() { alert('Ajax error in adding the category' )};
  mysack.runAJAX();


} // end of JavaScript function
//]]>

function wp_marketer_ajax_edit_category( category, parentf )
{
   var mysack = new sack(
       "<?php bloginfo( 'wpurl' ); ?>/wp-admin/admin-ajax.php" );

  mysack.execute = 1;
  mysack.method = 'POST';
  mysack.setVar( "action", "wp_marketer_edit_category" );
  mysack.setVar( "category", category.value );
  mysack.setVar( "parent", parentf );
  mysack.encVar( "cookie", document.cookie, false );
  mysack.onError = function() { alert('Ajax error in adding the category' )};
  mysack.runAJAX();


} // end of JavaScript function



function wp_marketer_show_edit_link_box(idc, parentid, jscategory,link,anchor_text,follow,masking,auto,newwinlink,linkid,wpAdminSection,google_code,eacval,link_type,image_url,image_width,image_height,image_align,js_image_url,link_name)
{
	var lsRegExp = /\+/g;
	var lsRegExpAmp = /\&/g;
 	 // Return the decoded string
 	 var googlecode=unescape(String(google_code).replace(lsRegExp, " "));
 	 var googlecodenew=unescape(String(googlecode).replace(lsRegExpAmp, "&amp;"));

	 var js_image_url1=unescape(String(js_image_url).replace(lsRegExp, " "));
 	 var js_image_url_new=unescape(String(js_image_url1).replace(lsRegExpAmp, "&amp;"));


	if(image_align=="middle")
		var imgmiddleselect="selected";
	else if(image_align=="left")
		var imgleftselect="selected";
	else if(image_align=="right")
		var imgrightselect="selected";
	else if(image_align=="top")
		var imgtopselect="selected";
	else if(image_align=="bottom")
		var imgbottomselect="selected";
	else if(image_align=="absmiddle")
		var imgabsmiddleselect="selected";
	else if(image_align=="absbottom")
		var imgabsbottomselect="selected";
	else if(image_align=="texttop")
		var imgtexttopselect="selected";
	else if(image_align=="baseline")
		var imgbaselineselect="selected";
	else
		var imgnullselect="selected";


	if(follow=="nofollow")
		var nofollowselect="selected";
	else
		var dofollowselect="selected";

	if(masking=="Enabled")
		var maskingVal="checked";
	else
		var maskingVal="";

	if(auto==1)
		var autoValue="checked";
	else
		var autoValue="";

	if(newwinlink=="target=_blank")
		var newwinlinkVal="checked";
	else
		var newwinlinkVal="";

	var eacdiv="eacEdit"+idc+"_"+parentid+"_"+jscategory;
	if(eacval==1)
		var eacValue="checked";
	else
		var eacValue="";

	if(link_type=="htmltext")
	{
		var htmlTextChecked="checked";
		var htmlImageChecked="";
		var jscsChecked="";
	}
	if(link_type=="htmlimage")
	{
		var htmlTextChecked="";
		var htmlImageChecked="checked";
		var jscsChecked="";
	}
	if(link_type=="javascript")
	{
		var htmlTextChecked="";
		var htmlImageChecked="";
		var jscsChecked="checked";
	}


	var display_type = jQuery('#add-sub-category-'+parentid+'-'+jscategory).css("display");
	var display_type2 = jQuery('#edit-link-'+parentid+'-'+jscategory).css("display");

	
	{
		var str = "<div id=\"wp_marketer_edit_link_div"+idc+"_"+parentid+"_"+jscategory+"\"><h2 class='subtitle'>Edit Link</h2>";
		str += "<div id=\"wp_marketer_add_link_results\"></div>";
		str += "<form>  <table><tr><td align=\"left\">";
		str += "<b>WPM Link Type:</b><img src='http://"+top.location.host+"/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is Link Type?' onclick='javascript:showMaskingWindow(\"link_type\")'></td><td>HTML Text<input type=\"radio\" name=\"wp_marketer_add_new_link_linktype"+idc+"_"+parentid+"_"+jscategory+"\" value=\"htmltext\" "+htmlTextChecked+" onclick='showRelatedLinkField(this,\"htmlimageEdit"+idc+"_"+parentid+"_"+jscategory+"\",\"javascriptEdit"+idc+"_"+parentid+"_"+jscategory+"\",\"autolinkdivEdit"+idc+"_"+parentid+"_"+jscategory+"\",\"reldivEdit"+idc+"_"+parentid+"_"+jscategory+"\",\"linkmaskingdivEdit"+idc+"_"+parentid+"_"+jscategory+"\",\"opennewwindivEdit"+idc+"_"+parentid+"_"+jscategory+"\",\"anchorlabledivEdit"+idc+"_"+parentid+"_"+jscategory+"\",\"imgalttagdivEdit"+idc+"_"+parentid+"_"+jscategory+"\",\"outbounddivEdit"+idc+"_"+parentid+"_"+jscategory+"\",\"anchor_img_tag_edit"+idc+"_"+parentid+"_"+jscategory+"\")'>&nbsp;&nbsp;HTML Image<input type=\"radio\" name=\"wp_marketer_add_new_link_linktype"+idc+"_"+parentid+"_"+jscategory+"\" value=\"htmlimage\" "+htmlImageChecked+" onclick='showRelatedLinkField(this,\"htmlimageEdit"+idc+"_"+parentid+"_"+jscategory+"\",\"javascriptEdit"+idc+"_"+parentid+"_"+jscategory+"\",\"autolinkdivEdit"+idc+"_"+parentid+"_"+jscategory+"\",\"reldivEdit"+idc+"_"+parentid+"_"+jscategory+"\",\"linkmaskingdivEdit"+idc+"_"+parentid+"_"+jscategory+"\",\"opennewwindivEdit"+idc+"_"+parentid+"_"+jscategory+"\",\"anchorlabledivEdit"+idc+"_"+parentid+"_"+jscategory+"\",\"imgalttagdivEdit"+idc+"_"+parentid+"_"+jscategory+"\",\"outbounddivEdit"+idc+"_"+parentid+"_"+jscategory+"\",\"anchor_img_tag_edit"+idc+"_"+parentid+"_"+jscategory+"\")'>&nbsp;&nbsp;JavaScript<input type=\"radio\" name=\"wp_marketer_add_new_link_linktype"+idc+"_"+parentid+"_"+jscategory+"\" value=\"javascript\" "+jscsChecked+" onclick='showRelatedLinkField(this,\"htmlimageEdit"+idc+"_"+parentid+"_"+jscategory+"\",\"javascriptEdit"+idc+"_"+parentid+"_"+jscategory+"\",\"autolinkdivEdit"+idc+"_"+parentid+"_"+jscategory+"\",\"reldivEdit"+idc+"_"+parentid+"_"+jscategory+"\",\"linkmaskingdivEdit"+idc+"_"+parentid+"_"+jscategory+"\",\"opennewwindivEdit"+idc+"_"+parentid+"_"+jscategory+"\",\"anchorlabledivEdit"+idc+"_"+parentid+"_"+jscategory+"\",\"imgalttagdivEdit"+idc+"_"+parentid+"_"+jscategory+"\",\"outbounddivEdit"+idc+"_"+parentid+"_"+jscategory+"\",\"anchor_img_tag_edit"+idc+"_"+parentid+"_"+jscategory+"\")'>";

		str += "</td></tr></table>  <table border=0 width='100%'> <tr><td align=\"left\" width='30%'>";
		str += "<b>WPM Tag:</b><img src='http://"+top.location.host+"/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is WPM Tag?' onclick='javascript:showMaskingWindow(\"wpm_tag\")'></td><td>[WPMID="+linkid+"]";

		str += "</td></tr><tr><td align=\"left\" width='30%'>";
		str += "<b>WPM Link Name:</b><img src='http://"+top.location.host+"/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is Link Name?' onclick='javascript:showMaskingWindow(\"link_name\")'></td><td> <input type=\"text\" value=\""+link_name+"\" name=\"wp_marketer_add_new_link_linkname"+idc+"_"+parentid+"_"+jscategory+"\"> ";

		str += "</td></tr>  <tr><td align=\"left\" width='100%' colspan=2><div id=\"outbounddivEdit"+idc+"_"+parentid+"_"+jscategory+"\"><table width='100%' border=0><tr><td width='30%'>";
		str += "<b>Outbound URL:</b><img src='http://"+top.location.host+"/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is Outbound URL?' onclick='javascript:showMaskingWindow(\"outbound_url\")'></td><td> <input type=\"text\" value=\""+link+"\" name=\"wp_marketer_add_new_link_link_"+idc+"_"+parentid+"_"+jscategory+"\"> ";

		str += "</td></tr></table></div></td></tr><tr><td align=\"left\" width='100%' colspan=2><div id=\"anchor_img_tag_edit"+idc+"_"+parentid+"_"+jscategory+"\"><table width='100%'><tr><td width='30%'>";
		str += "<div id=\"anchorlabledivEdit"+idc+"_"+parentid+"_"+jscategory+"\"><b>Anchor Text:</b><img src='http://"+top.location.host+"/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is Anchor Text?' onclick='javascript:showMaskingWindow(\"anchor_text\")'></div><div id=\"imgalttagdivEdit"+idc+"_"+parentid+"_"+jscategory+"\"><b>Image Alt:</b><img src='http://"+top.location.host+"/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is Image Alt?' onclick='javascript:showMaskingWindow(\"image_alt_tag\")'></div></td><td> <input type=\"text\" value=\""+anchor_text+"\" name=\"wp_marketer_add_new_link_text"+idc+"_"+parentid+"_"+jscategory+"\">";

		str += "</td></tr></table></div></td></tr></table>   <table width='100%'><tr><td valign=\"top\" align=\"left\"><div id=\"htmlimageEdit"+idc+"_"+parentid+"_"+jscategory+"\" style=\"display:none\"><table width='100%'><tr><td valign=\"top\" align=\"left\" width='30%'>";
		str += "<b>Image URL:</b><img src='http://"+top.location.host+"/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is Image URL?' onclick='javascript:showMaskingWindow(\"image_url\")'></td><td> <input type=\"text\" size=40 value=\""+image_url+"\"  name=\"wp_marketer_add_new_link_imageurl"+idc+"_"+parentid+"_"+jscategory+"\">";

		str += "</td></tr>  <tr><td valign=\"top\" align=\"left\">";
		str += "<b>Image Width:</b><img src='http://"+top.location.host+"/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is Image Width?' onclick='javascript:showMaskingWindow(\"image_width\")'></td><td> <input type=\"text\" size=10 value=\""+image_width+"\" name=\"wp_marketer_add_new_link_imagewidth"+idc+"_"+parentid+"_"+jscategory+"\">";

		str += "</td></tr>   <tr><td valign=\"top\" align=\"left\">";
		str += "<b>Image Height:</b><img src='http://"+top.location.host+"/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is Image Height?' onclick='javascript:showMaskingWindow(\"image_height\")'></td><td> <input type=\"text\" size=10 value=\""+image_height+"\" name=\"wp_marketer_add_new_link_imageheight"+idc+"_"+parentid+"_"+jscategory+"\">";

		str += "</td></tr>  <tr><td valign=\"top\" align=\"left\">";
		str += "<b>Image Align:</b><img src='http://"+top.location.host+"/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is Image Align?' onclick='javascript:showMaskingWindow(\"image_align\")'></td><td> <select name=\"wp_marketer_add_new_link_imagealign"+idc+"_"+parentid+"_"+jscategory+"\"><option value=''>None</option><option value='middle' "+imgmiddleselect+">middle</option><option value='left' "+imgleftselect+">left</option><option value='right' "+imgrightselect+">right</option><option value='top' "+imgtopselect+">top</option><option value='bottom' "+imgbottomselect+">bottom</option><option value='absmiddle' "+imgabsmiddleselect+">absmiddle</option><option value='absbottom' "+imgabsbottomselect+">absbottom</option><option value='texttop' "+imgtexttopselect+">texttop</option><option value='baseline' "+imgbaselineselect+">baseline</option></select>";

		str += "</td></tr>  </table></div></td></tr></table>    <table width='100%'><tr><td align=\"left\" width='100%' colspan=2><div id=\"reldivEdit"+idc+"_"+parentid+"_"+jscategory+"\"><table width='100%'><tr><td width='30%'>";
		str += "<b>Link Relationship:</b><img src='http://"+top.location.host+"/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is Link Relationship?' onclick='javascript:showMaskingWindow(\"realtion\")'></td><td><select name=\"wp_marketer_add_new_link_follow"+idc+"_"+parentid+"_"+jscategory+"\"><option value='dofollow' "+dofollowselect+">dofollow</option><option value='nofollow' "+nofollowselect+">nofollow</option></select>";

		str += "</td></tr></table></div></td></tr><tr><td align=\"left\" width='100%' colspan=2><div id=\"linkmaskingdivEdit"+idc+"_"+parentid+"_"+jscategory+"\"><table width='100%'><tr><td width='30%'>";
		str += "<b>Link Masking:</b><img src='http://"+top.location.host+"/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is Link Masking?' onclick='javascript:showMaskingWindow(\"link_masking\")'></td><td> <input type=\"checkbox\" "+maskingVal+" name=\"wp_marketer_add_new_link_masking"+idc+"_"+parentid+"_"+jscategory+"\">";

		str += "</td></tr></table></div></td></tr><tr><td align=\"left\" width='100%' colspan=2><div id=\"autolinkdivEdit"+idc+"_"+parentid+"_"+jscategory+"\"><table width='100%'><tr><td width='30%'>";
		str += "<b>Auto Link:</b><img src='http://"+top.location.host+"/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is Auto Link?' onclick='javascript:showMaskingWindow(\"auto_link\")'></td><td> <input type=\"checkbox\" "+autoValue+" name=\"wp_marketer_add_new_link_auto"+idc+"_"+parentid+"_"+jscategory+"\">";

		str += "</td></tr></table></div></td></tr><tr><td align=\"left\" width='100%' colspan=2><div id=\"opennewwindivEdit"+idc+"_"+parentid+"_"+jscategory+"\"><table width='100%'><tr><td width='30%'>";
		str += "<b>Open Link in New Window:</b><img src='http://"+top.location.host+"/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is Open Link in New Window?' onclick='javascript:showMaskingWindow(\"new_window_property\")'></td><td> <input type=\"checkbox\" "+newwinlinkVal+" name=\"wp_marketer_add_new_link_newwinlink"+idc+"_"+parentid+"_"+jscategory+"\">";

		str += "</td></tr></table></div></td></tr><tr><td align=\"left\" width='30%'>";
		str += "<b>Enable Adwords Conversions:</b><img src='http://"+top.location.host+"/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is Enable Adwords Conversions?' onclick='javascript:showMaskingWindow(\"adwords_conversions\")'></td><td> <input type=\"checkbox\" "+eacValue+" name=\"wp_marketer_add_new_link_eac"+idc+"_"+parentid+"_"+jscategory+"\" onclick='javascript:showHideACS(this,\"eacEdit"+idc+"_"+parentid+"_"+jscategory+"\")'>";

		str += "</td></tr></table><table width='100%' border=0><tr><td valign=\"top\" align=\"left\" width='100%'><div id=\"eacEdit"+idc+"_"+parentid+"_"+jscategory+"\"><table width='100%'><tr><td width=30% valign='top'>";
		str += "<b>Adwords Conversion Snippet:</b><img src='http://"+top.location.host+"/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is Adwords Conversion Snippet?' onclick='javascript:showMaskingWindow(\"adwords_conversions_snippet\")'></td><td> <textarea cols=50 rows=10 name=\"wp_marketer_add_new_link_googlecode"+idc+"_"+parentid+"_"+jscategory+"\">"+googlecodenew+"</textarea>";

		str += "</td></tr></table></div></td></tr></table><table width='100%'><tr><td align=\"left\" width='100%'><div id=\"javascriptEdit"+idc+"_"+parentid+"_"+jscategory+"\" style=\"display:none\"><table border=0 width='100%'><tr><td width='30%' valign='top'>";
		str += "<b>JavaScript Code Snippet:</b><img src='http://"+top.location.host+"/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is JavaScript Code Snippet?' onclick='javascript:showMaskingWindow(\"javascript_code_snippet\")'></td><td> <textarea cols=50 rows=10 name=\"wp_marketer_add_new_link_jscs"+idc+"_"+parentid+"_"+jscategory+"\">"+js_image_url_new+"</textarea>";

		str += "</td></tr></table></div></td></tr></table>";
		str += "<table border=0><tr><td><input type=\"button\" value=\"Update WPM Link\" onclick=\"wp_marketer_ajax_edit_link(this.form.wp_marketer_add_new_link_link_"+idc+"_"+parentid+"_"+jscategory+",this.form.wp_marketer_add_new_link_text"+idc+"_"+parentid+"_"+jscategory+",this.form.wp_marketer_add_new_link_follow"+idc+"_"+parentid+"_"+jscategory+",this.form.wp_marketer_add_new_link_masking"+idc+"_"+parentid+"_"+jscategory+",this.form.wp_marketer_add_new_link_auto"+idc+"_"+parentid+"_"+jscategory+",this.form.wp_marketer_add_new_link_newwinlink"+idc+"_"+parentid+"_"+jscategory+", "+idc+","+linkid+",'"+wpAdminSection+"',this.form.wp_marketer_add_new_link_googlecode"+idc+"_"+parentid+"_"+jscategory+",this.form.wp_marketer_add_new_link_eac"+idc+"_"+parentid+"_"+jscategory+",this.form.wp_marketer_add_new_link_linktype"+idc+"_"+parentid+"_"+jscategory+",this.form.wp_marketer_add_new_link_imageurl"+idc+"_"+parentid+"_"+jscategory+",this.form.wp_marketer_add_new_link_jscs"+idc+"_"+parentid+"_"+jscategory+",this.form.wp_marketer_add_new_link_imagewidth"+idc+"_"+parentid+"_"+jscategory+",this.form.wp_marketer_add_new_link_imageheight"+idc+"_"+parentid+"_"+jscategory+",this.form.wp_marketer_add_new_link_imagealign"+idc+"_"+parentid+"_"+jscategory+",this.form.wp_marketer_add_new_link_linkname"+idc+"_"+parentid+"_"+jscategory+");\" />";
		str += "<input type=\"button\" value=\"Cancel\" onclick=\"wp_marketer_show_edit_link_box('"+idc+"','"+parentid+"','"+jscategory+"','"+link+"','"+anchor_text+"','"+follow+"','"+masking+"','"+auto+"','"+newwinlink+"','"+linkid+"','"+wpAdminSection+"','"+google_code+"','"+eacval+"','"+link_type+"','"+image_url+"','"+image_width+"','"+image_height+"','"+image_align+"','"+js_image_url+"','"+link_name+"');\">";
		str += "</td></tr></table></form></div><div id=\"wp_marketer_edit_link_msg\" style=\"color:red;\"></div>";

		jQuery('#edit-link-'+parentid+'-'+jscategory).html(str);
	}
	

	if(eacval==1)
		document.getElementById(eacdiv).style.display="block";
	else
		document.getElementById(eacdiv).style.display="none";


	var htmlImageDiv="htmlimageEdit"+idc+"_"+parentid+"_"+jscategory;
	var jscsDiv="javascriptEdit"+idc+"_"+parentid+"_"+jscategory;

	var reldivEdit="reldivEdit"+idc+"_"+parentid+"_"+jscategory;
	var linkmaskingdivEdit="linkmaskingdivEdit"+idc+"_"+parentid+"_"+jscategory;
	var autolinkdivEdit="autolinkdivEdit"+idc+"_"+parentid+"_"+jscategory;
	var opennewwindivEdit="opennewwindivEdit"+idc+"_"+parentid+"_"+jscategory;

	var anchorlabledivEdit="anchorlabledivEdit"+idc+"_"+parentid+"_"+jscategory;
	var imgalttagdivEdit="imgalttagdivEdit"+idc+"_"+parentid+"_"+jscategory;

	var anchor_img_tag_edit="anchor_img_tag_edit"+idc+"_"+parentid+"_"+jscategory;

	var outbounddivEdit="outbounddivEdit"+idc+"_"+parentid+"_"+jscategory;

	if(link_type=="htmltext")
	{
		document.getElementById(htmlImageDiv).style.display = "none";
		document.getElementById(jscsDiv).style.display = "none";

		document.getElementById(reldivEdit).style.display = "block";
		document.getElementById(linkmaskingdivEdit).style.display = "block";
		document.getElementById(autolinkdivEdit).style.display = "block";
		document.getElementById(opennewwindivEdit).style.display = "block";

		document.getElementById(anchor_img_tag_edit).style.display = "block";

		document.getElementById(anchorlabledivEdit).style.display = "block";
		document.getElementById(imgalttagdivEdit).style.display = "none";

		document.getElementById(outbounddivEdit).style.display = "block";
	}
	if(link_type=="htmlimage")
	{
		document.getElementById(htmlImageDiv).style.display = "block";
		document.getElementById(jscsDiv).style.display = "none";

		document.getElementById(reldivEdit).style.display = "none";
		document.getElementById(linkmaskingdivEdit).style.display = "block";
		document.getElementById(autolinkdivEdit).style.display = "none";
		document.getElementById(opennewwindivEdit).style.display = "block";

		document.getElementById(anchor_img_tag_edit).style.display = "block";

		document.getElementById(anchorlabledivEdit).style.display = "none";
		document.getElementById(imgalttagdivEdit).style.display = "block";

		document.getElementById(outbounddivEdit).style.display = "block";
	}
	if(link_type=="javascript")
	{
		document.getElementById(htmlImageDiv).style.display = "none";
		document.getElementById(jscsDiv).style.display = "block";

		document.getElementById(reldivEdit).style.display = "none";
		document.getElementById(linkmaskingdivEdit).style.display = "none";
		document.getElementById(autolinkdivEdit).style.display = "none";
		document.getElementById(opennewwindivEdit).style.display = "none";

		document.getElementById(anchor_img_tag_edit).style.display = "none";
		document.getElementById(anchorlabledivEdit).style.display = "none";
		document.getElementById(imgalttagdivEdit).style.display = "none";

		document.getElementById(outbounddivEdit).style.display = "none";
	}


	jQuery('#edit-link-'+parentid+'-'+jscategory).toggle("slow");

	var display_hit = jQuery('#edit-link-'+parentid+'-'+jscategory).parent().find(">.hitarea").css("zIndex");

	if((display_type=="none" && display_type2!="none" && display_hit=="99") || (display_type=="none" && display_type2=="none" && display_hit!="99"))
	{
	jQuery('#edit-link-'+parentid+'-'+jscategory)
                                        .parent()
                                        // swap classes for hitarea
                                        .find(">.hitarea")
                                                .swapClass( jQuery.fn.treeview.classes.collapsableHitarea, jQuery.fn.treeview.classes.expandableHitarea )
                                                .swapClass( jQuery.fn.treeview.classes.lastCollapsableHitarea, jQuery.fn.treeview.classes.lastExpandableHitarea )
                                        .end()
                                        // swap classes for parent li
                                        .swapClass( jQuery.fn.treeview.classes.collapsable, jQuery.fn.treeview.classes.expandable )
                                        .swapClass( jQuery.fn.treeview.classes.lastCollapsable, jQuery.fn.treeview.classes.lastExpandable )
                                        // find child lists
                                        .find( ">ul" )
                                        // toggle them
                                        .heightToggle( jtree_settings.animated, jtree_settings.toggle );
	}

}


</script>
<link rel="stylesheet" href="<? echo get_bloginfo('wpurl'); ?>/wp-content/plugins/wp-marketer/jquery.treeview.css" />

<?php
} // end of PHP function myplugin_js_admin_header

