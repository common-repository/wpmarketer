<?php
if(isset($_POST['enabledisablemescalero']))
{
	$msg="Auto linking Option activated.";
 	global $wpdb,$wp_marketer_prefix;
 	$table_name = $wpdb->prefix . $wp_marketer_prefix."_auto_link";
	if($_POST['mescalerooption']=="on")
		$sql = "update ".$table_name." set auto_link_option=1";
	else
		$sql = "update ".$table_name." set auto_link_option=0";

	$wpdb->query($sql);
}
if(isset($_POST['updatelinklimit']))
{
	$msg="Links Limit updated.";
	global $wpdb,$wp_marketer_prefix;
 	$table_name = $wpdb->prefix . $wp_marketer_prefix."_auto_link";

	$sql = "update ".$table_name." set auto_link_limit=".$_POST['linklimit'];

	$wpdb->query($sql);
}
if(isset($_POST['updateimgsize']))
{
	$msg="Image settings updated.";
	global $wpdb,$wp_marketer_prefix;
 	$table_name = $wpdb->prefix . $wp_marketer_prefix."_auto_link";

	$sql = "update ".$table_name." set image_max_width=".$_POST['imgwidth'].",image_max_height=".$_POST['imgheight'];

	$wpdb->query($sql);
}
$row=getAutoLinkInformation();
$row->auto_link_option;
if($row->auto_link_option==1)
$checkedLinkOption="checked";
else
$checkedLinkOption="";
?>
<table align="center" width='100%'><?
if(isset($_POST['enabledisablemescalero']) || isset($_POST['updatelinklimit']) || isset($_POST['updateimgsize']))
{?>
	<tr valign="top">
		<td colspan=2 align="center"><font color='red'><b><? echo $msg; ?></b></font></td>
	</tr>
<?
}?>
</table>
<form method="post" action="#1">
	<table class="form-table">
		<tr valign="top">
			<th align="left">Activate Auto linking Option <img src='http://<? echo $_SERVER['HTTP_HOST'] ?>/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is Activate Auto linking Option?' onclick='javascript:showMaskingWindow("Activate_Auto_linking_Option")'></th>
			<td><input type="checkbox" name="mescalerooption" <? echo $checkedLinkOption ?>/><br />
			Check this box and update to activate Auto linking Option.</td>
		</tr>
	</table>
	<div class="submit"><input type="submit" name="enabledisablemescalero" value="Update &raquo;"/></div>
</form>
<form method="post" action="#1">
	<table class="form-table">
		<tr valign="top">
			<th align="left">Enter Links Limit <img src='http://<? echo $_SERVER['HTTP_HOST'] ?>/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is Enter Links Limit?' onclick='javascript:showMaskingWindow("Enter_Links_Limit")'></th>
			<td><input type="text" name="linklimit" value="<? echo $row->auto_link_limit  ?>" /><br />
			</td>
		</tr>
	</table>
	<div class="submit"><input type="submit" name="updatelinklimit" value="Update &raquo;"/></div>
</form>
<form method="post" action="#1">
	<table class="form-table">
		<tr valign="top">
			<th align="left">Enter Image Max Width <img src='http://<? echo $_SERVER['HTTP_HOST'] ?>/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is Enter Image Max Width?' onclick='javascript:showMaskingWindow("Enter_Image_Max_Width")'></th>
			<td><input type="text" name="imgwidth" value="<? echo $row->image_max_width  ?>" /><br />
			</td>
		</tr>
		<tr valign="top">
			<th align="left">Enter Image Max Height <img src='http://<? echo $_SERVER['HTTP_HOST'] ?>/wp-content/plugins/wp-marketer/images/question.jpg' width='18px' height='18px' border=0 alt='What is Enter Image Max Height?' onclick='javascript:showMaskingWindow("Enter_Image_Max_Height")'></th>
			<td><input type="text" name="imgheight" value="<? echo $row->image_max_height  ?>" /><br />
			</td>
		</tr>
	</table>
	<div class="submit"><input type="submit" name="updateimgsize" value="Update &raquo;"/></div>
</form>