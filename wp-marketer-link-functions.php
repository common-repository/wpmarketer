<?php

add_action('wp_ajax_wp_marketer_delete_link', 'wp_marketer_delete_link' );

function wp_marketer_delete_link()
{
	global $wpdb,$wp_marketer_prefix;
	$table_name = $wpdb->prefix . $wp_marketer_prefix."_links";
	$table_name3 = $wpdb->prefix . $wp_marketer_prefix."_link_string ";
	
	$table_name5 = $wpdb->prefix . $wp_marketer_prefix."_link_hits";

	$id = $_POST['link'];


	if($id == "")
		die("");

	if(is_numeric($id))
	{

			//start to delete from wp_marketer_link_string table also
			$sqlgetIDData = "SELECT * FROM ".$table_name." WHERE id='$id'";
			$rsgetIDData=mysql_query($sqlgetIDData);
			if($arrgetIDData=mysql_fetch_object($rsgetIDData))
			{
				$wholeStr=$arrgetIDData->category."~!".$arrgetIDData->anchor_text."@#".$arrgetIDData->slug."%&".$arrgetIDData->follow."&*".$arrgetIDData->newwinlink."*!".$arrgetIDData->id."#*".$arrgetIDData->auto_link."~~";
			}

			$sql2 = "SELECT linkstr FROM ".$table_name3;
			$row2 = $wpdb->get_row($sql2, OBJECT);
			$newStringValue=str_replace($wholeStr,"",$row2->linkstr);
			$sql3 = "update ".$table_name3." set linkstr='$newStringValue'";
			$wpdb->query($sql3);
			//end to delete from wp_marketer_link_string table also

			$sql = "DELETE FROM ".$table_name." WHERE id=".$id;
			$wpdb->query($sql);

			$sqlhits = "DELETE FROM ".$table_name5." WHERE link_id=".$id;
			$wpdb->query($sqlhits);

			echo "document.getElementById('linkli".$id."').style.display=\"none\";";
	}
}

add_action('wp_ajax_wp_marketer_add_link', 'wp_marketer_add_link' );

function wp_marketer_add_link()
{
global $wpdb,$wp_marketer_prefix;
$table_name = $wpdb->prefix . $wp_marketer_prefix."_links";
$table_name3 = $wpdb->prefix . $wp_marketer_prefix."_link_string ";

$table_name4 = $wpdb->prefix . $wp_marketer_prefix."_auto_link";

$link = $_POST['link'];
$text = $_POST['text'];
$category = $_POST['category'];
$follow= $_POST['follow'];
$masking=$_POST['masking'];
$auto=$_POST['auto'];
$wpAdminSection=$_POST['wpAdminSection'];
$googlecode=stripslashes($_POST['googlecode']);
$eac=$_POST['eac'];
$linkTypeVal=$_POST['linkTypeVal'];
$htmlimagevalue=$_POST['htmlimagevalue'];
$htmlimagewidth=$_POST['htmlimagewidth'];
$htmlimageheight=$_POST['htmlimageheight'];
$htmlimagealign=$_POST['htmlimagealign'];
$jscsvalue=stripslashes($_POST['jscsvalue']);
$linkname=$_POST['linkname'];


if($masking=="true")
	$maskingVal="Enabled";
else
	$maskingVal="Disabled";

if($auto=="true")
	$autoVal=1;
else
	$autoVal=0;


$newwinlink=$_POST['newwinlink'];

if($newwinlink=="true")
	$newwinlinkVal="target=_blank";
else
	$newwinlinkVal="";

if($eac=="true")
{
	$eacval=1;
}
else
{
	$eacval=0;
	$googlecode='';
}

if($linkTypeVal!="javascript")
{
	if($link == "" || $text == "")
	die("document.getElementById('wp_marketer_add_link_results').innerHTML = \"Link or text is missing from your marketer link.\";");


	$sql = "SELECT * FROM ".$table_name." WHERE anchor_text='".$wpdb->escape($text)."'";
	$row = $wpdb->get_row($sql, OBJECT);

	if($row->id!='')
	die("document.getElementById('wp_marketer_add_link_results').innerHTML = \"<b>Text already added.</b>\";");
}

//************************************************************8
//start to check to find image width and height are not null and not maximum then the admin setting
//if linkTypeVal is htmlimage
if($linkTypeVal=="htmlimage")
{
	$sqlal = "SELECT * FROM ".$table_name4;
	$rowal = $wpdb->get_row($sqlal, OBJECT);

	if(!is_numeric($htmlimagewidth) || trim($htmlimagewidth)=='')
	die("document.getElementById('wp_marketer_add_link_results').innerHTML = \"<b>Please enter numeric image width.</b>\";");

	if(!is_numeric($htmlimageheight) || trim($htmlimageheight)=='')
	die("document.getElementById('wp_marketer_add_link_results').innerHTML = \"<b>Please enter numeric image height.</b>\";");

	if($htmlimagewidth>$rowal->image_max_width)
	die("document.getElementById('wp_marketer_add_link_results').innerHTML = \"<b>Image width can not  be greater then ".$rowal->image_max_width.".</b>\";");

	if($htmlimageheight>$rowal->image_max_height)
	die("document.getElementById('wp_marketer_add_link_results').innerHTML = \"<b>Image height can not  be greater then ".$rowal->image_max_height.".</b>\";");

	//auto link will apply if linktype is htmlimage
	$autoVal=1;
	$follow="";
}
//end to check to find image width and height are not null and not maximum then the admin setting
//start if linktype is javascript
if($linkTypeVal=="javascript")
{
	$autoVal=1;
	$maskingVal="Disabled";
	$follow="";
	$newwinlinkVal="";
	$text='';
	$link='';
}
//end if linktype is javascript
//*******************************************************************************

$sql = "INSERT INTO ".$table_name." (anchor_text,link,slug,category,follow,masking,newwinlink,auto_link,google_code,eac,link_type,image_url,image_width,image_height,image_align,js_image_url,link_name) VALUES('".$wpdb->escape($text)."','".$wpdb->escape($link)."','".$wpdb->escape(sanitize_title($text))."',".$wpdb->escape($category).",'".$wpdb->escape($follow)."','".$wpdb->escape($maskingVal)."','".$wpdb->escape($newwinlinkVal)."','".$wpdb->escape($autoVal)."','".$wpdb->escape($googlecode)."','".$wpdb->escape($eacval)."','".$wpdb->escape($linkTypeVal)."','".$wpdb->escape($htmlimagevalue)."','".$wpdb->escape($htmlimagewidth)."','".$wpdb->escape($htmlimageheight)."','".$wpdb->escape($htmlimagealign)."','".$wpdb->escape($jscsvalue)."','".$wpdb->escape($linkname)."')";
$wpdb->query($sql);

update_option('rewrite_rules',"");


$result_link = sanitize_title($text);

$new_link_li = str_replace("\r","",str_replace("\n","",str_replace("'","\'",get_link_li_marketer($wpdb->insert_id,$wpAdminSection))));

echo "var navfil = '".$new_link_li."';";

echo "jQuery('#catli".$category.">ul').prepend(navfil);";

if($linkTypeVal=="htmltext")
{
	//start add data in  wp_wp_marketer_link_string also
	$sql1 = "SELECT max(id) as maxid FROM ".$table_name;
	$row1 = $wpdb->get_row($sql1, OBJECT);
	$row1->maxid;

		//start add to linkstr table also
		$sql2 = "SELECT linkstr FROM ".$table_name3;
		$row2 = $wpdb->get_row($sql2, OBJECT);

		$newString=$row2->linkstr.$wpdb->escape($category)."~!".$wpdb->escape($text)."@#".$wpdb->escape(sanitize_title($text))."%&".$wpdb->escape($follow)."&*".$wpdb->escape($newwinlinkVal)."*!".$row1->maxid."#*".$autoVal."~~";
		$sql = "update ".$table_name3." set linkstr='$newString'";
		$wpdb->query($sql);
	//end add data in  wp_wp_marketer_link_string also
}
die("document.getElementById('wp_marketer_add_link_msg').innerHTML = \"<b>WPM Link Added!</b>\";");
}


add_action('wp_ajax_wp_marketer_edit_link', 'wp_marketer_edit_link' );

function wp_marketer_edit_link()
{
global $wpdb,$wp_marketer_prefix;
$table_name = $wpdb->prefix . $wp_marketer_prefix."_links";
$table_name3 = $wpdb->prefix . $wp_marketer_prefix."_link_string ";

$table_name4 = $wpdb->prefix . $wp_marketer_prefix."_auto_link";

$link = $_POST['link'];
$text = $_POST['text'];
$category = $_POST['category'];
$follow= $_POST['follow'];
$masking=$_POST['masking'];
$auto=$_POST['auto'];
$linkid=$_POST['linkid'];
$eac=$_POST['eac'];
$wpAdminSection=$_POST['wpAdminSection'];
$googlecode=stripslashes($_POST['googlecode']);

$linkTypeVal=$_POST['linkTypeVal'];
$htmlimagevalue=$_POST['htmlimagevalue'];
$htmlimagewidth=$_POST['htmlimagewidth'];
$htmlimageheight=$_POST['htmlimageheight'];
$htmlimagealign=$_POST['htmlimagealign'];
$jscsvalue=stripslashes($_POST['jscsvalue']);

$linkname=$_POST['linkname'];

if($masking=="true")
	$maskingVal="Enabled";
else
	$maskingVal="Disabled";

if($auto=="true")
	$autoVal=1;
else
	$autoVal=0;

$newwinlink=$_POST['newwinlink'];

if($newwinlink=="true")
	$newwinlinkVal="target=_blank";
else
	$newwinlinkVal="";

if($eac=="true")
	$eacVal=1;
else
{
	$eacVal=0;
	$googlecode='';
}

if($linkTypeVal!="javascript")
{
	if($link == "" || $text == "")
	die("document.getElementById('wp_marketer_add_link_results').innerHTML = \"Link or text is missing from your marketer link.\";");


	$sql = "SELECT * FROM ".$table_name." WHERE anchor_text='".$wpdb->escape($text)."' && id!='$linkid'";
	$row = $wpdb->get_row($sql, OBJECT);

	if($row->id!='')
	die("document.getElementById('wp_marketer_add_link_results').innerHTML = \"<b>Text already added.</b>\";");
}

//************************************************************8
//start to check to find image width and height are not null and not maximum then the admin setting
//if linkTypeVal is htmlimage
if($linkTypeVal=="htmlimage")
{
	$sqlal = "SELECT * FROM ".$table_name4;
	$rowal = $wpdb->get_row($sqlal, OBJECT);

	if(!is_numeric($htmlimagewidth) || trim($htmlimagewidth)=='')
	die("document.getElementById('wp_marketer_add_link_results').innerHTML = \"<b>Please enter numeric image width.</b>\";");

	if(!is_numeric($htmlimageheight) || trim($htmlimageheight)=='')
	die("document.getElementById('wp_marketer_add_link_results').innerHTML = \"<b>Please enter numeric image height.</b>\";");

	if($htmlimagewidth>$rowal->image_max_width)
	die("document.getElementById('wp_marketer_add_link_results').innerHTML = \"<b>Image width can not  be greater then ".$rowal->image_max_width.".</b>\";");

	if($htmlimageheight>$rowal->image_max_height)
	die("document.getElementById('wp_marketer_add_link_results').innerHTML = \"<b>Image height can not  be greater then ".$rowal->image_max_height.".</b>\";");

	//auto link will apply if linktype is htmlimage
	$autoVal=1;
	$follow="";
}
//end to check to find image width and height are not null and not maximum then the admin setting
//start if linktype is javascript
if($linkTypeVal=="javascript")
{
	$autoVal=1;
	$maskingVal="Disabled";
	$follow="";
	$newwinlinkVal="";
	$text='';
	$link='';
}
//end if linktype is javascript
//*******************************************************************************

if($linkTypeVal=="htmltext")
{
//start to edit from wp_marketer_link_string table also
	$sqlgetIDData = "SELECT * FROM ".$table_name." WHERE id='$linkid'";
	$rsgetIDData=mysql_query($sqlgetIDData);
	if($arrgetIDData=mysql_fetch_object($rsgetIDData))
	{
		$wholeStr=$arrgetIDData->category."~!".$arrgetIDData->anchor_text."@#".$arrgetIDData->slug."%&".$arrgetIDData->follow."&*".$arrgetIDData->newwinlink."*!".$arrgetIDData->id."#*".$arrgetIDData->auto_link."~~";
	}

	$sql2 = "SELECT linkstr FROM ".$table_name3;
	$row2 = $wpdb->get_row($sql2, OBJECT);
	$newStringValue=str_replace($wholeStr,"",$row2->linkstr);

	$newString=$newStringValue.$wpdb->escape($category)."~!".$wpdb->escape($text)."@#".$wpdb->escape(sanitize_title($text))."%&".$wpdb->escape($follow)."&*".$wpdb->escape($newwinlinkVal)."*!".$linkid."#*".$autoVal."~~";
	$sql = "update ".$table_name3." set linkstr='$newString'";
	$wpdb->query($sql);
//end to edit from wp_marketer_link_string table also
}


$sql = "update ".$table_name." set anchor_text='".$wpdb->escape($text)."',link='".$wpdb->escape($link)."',slug='".$wpdb->escape(sanitize_title($text))."',category=".$wpdb->escape($category).",follow='".$wpdb->escape($follow)."',masking='".$wpdb->escape($maskingVal)."',newwinlink='".$wpdb->escape($newwinlinkVal)."',auto_link='".$wpdb->escape($autoVal)."',google_code='".$wpdb->escape($googlecode)."',eac='".$wpdb->escape($eacVal)."',link_type='".$wpdb->escape($linkTypeVal)."',image_url='".$wpdb->escape($htmlimagevalue)."',image_width='".$wpdb->escape($htmlimagewidth)."',image_height='".$wpdb->escape($htmlimageheight)."',image_align='".$wpdb->escape($htmlimagealign)."',js_image_url='".$wpdb->escape($jscsvalue)."',link_name='".$wpdb->escape($linkname)."' where id='$linkid'";
$wpdb->query($sql);


update_option('rewrite_rules',"");


$result_link = sanitize_title($text);

$new_link_li = str_replace("\r","",str_replace("\n","",str_replace("'","\'",get_link_li_marketer($linkid,$wpAdminSection))));

echo "var navfil = '".$new_link_li."';";

echo "jQuery('#linkli".$linkid."').html(navfil);";

die("document.getElementById('wp_marketer_edit_link_msg').innerHTML = \"<b>WPM Link Updated!</b>\";");

}






add_action('admin_print_scripts', 'wp_marketer_js_admin_header' );

function wp_marketer_js_admin_header() // this is a PHP function
{
  // use JavaScript SACK library for Ajax
  wp_print_scripts( array( 'sack' ));

  // Define custom JavaScript function
?>
<script type="text/javascript">
//<![CDATA[

function IsNumeric(strString)
//  check for valid numeric strings
{
   var strValidChars = "0123456789.-";
   var strChar;
   var blnResult = true;

   if (strString.length == 0) return false;

   //  test strString consists of valid characters listed above
   for (i = 0; i < strString.length && blnResult == true; i++)
      {
      strChar = strString.charAt(i);
      if (strValidChars.indexOf(strChar) == -1)
         {
         blnResult = false;
         }
      }
   return blnResult;
}


function trimAll(sString)
{
	while (sString.substring(0,1) == ' ')
	{
		sString = sString.substring(1, sString.length);
	}
	while (sString.substring(sString.length-1, sString.length) == ' ')
	{
		sString = sString.substring(0,sString.length-1);
	}
	return sString;
}


function wp_marketer_ajax_add_link( link, text,follow,masking,auto,newwinlink,category,wpAdminSection,googlecode,eac,linktype,imageurl,jscs,imagewidth,imageheight,imagealign,linkname,image_max_width,image_max_height)
{
   var mysack = new sack(
       "<?php bloginfo( 'wpurl' ); ?>/wp-admin/admin-ajax.php" );

  if(linktype[0].checked==true)
  {
	if(trimAll(link.value)=='')
	{
  		alert('Please enter Outbound URL!');
  		link.focus();
  		return false;
  	}
	if(trimAll(text.value)=='')
	{
  		alert('Please enter Image Alt!');
  		text.focus();
  		return false;
  	}
	var linkTypeVal=linktype[0].value;
	var htmlimagevalue='';
	var htmlimagewidth='';
	var htmlimageheight='';
	var htmlimagealign='';
  	var jscsvalue='';
  }
  if(linktype[1].checked==true)
  {
	if(trimAll(link.value)=='')
	{
  		alert('Please enter Outbound URL!');
  		link.focus();
  		return false;
  	}
	if(trimAll(text.value)=='')
	{
  		alert('Please enter Image Alt!');
  		text.focus();
  		return false;
  	}
  	if(trimAll(imageurl.value)=='')
  	{
  		alert('Please enter Image URL!');
  		imageurl.focus();
  		return false;
  	}
	if(IsNumeric(imagewidth.value) == false)
	{
		alert('Please enter numeric image width!');
  		imagewidth.focus();
  		return false;
	}
	if(IsNumeric(imageheight.value) == false)
	{
		alert('Please enter numeric image height!');
  		imageheight.focus();
  		return false;
	}

	if(parseInt(imagewidth.value)>parseInt(image_max_width))
	{
		alert("Image width can not  be greater then "+image_max_width);
		imagewidth.focus();
  		return false;
	}

	if(parseInt(imageheight.value)>parseInt(image_max_height))
	{
		alert("Image height can not  be greater then "+image_max_height);
		imageheight.focus();
  		return false;
	}

  	var linkTypeVal=linktype[1].value;
  	var htmlimagevalue=imageurl.value;
	var htmlimagewidth=imagewidth.value;
	var htmlimageheight=imageheight.value;
	var htmlimagealign=imagealign.value;
  	var jscsvalue='';
  }
  if(linktype[2].checked==true)
  {
  	if(trimAll(jscs.value)=='')
  	{
  		alert('Please enter JavaScript Code Snippet!');
  		jscs.focus();
  		return false;
  	}
  	var linkTypeVal=linktype[2].value;
  	var htmlimagevalue='';
	var htmlimagewidth='';
	var htmlimageheight='';
	var htmlimagealign='';
  	var jscsvalue=jscs.value;
  }

  mysack.execute = 1;
  mysack.method = 'POST';
  mysack.setVar( "action", "wp_marketer_add_link" );
  mysack.setVar( "link", link.value );
  mysack.setVar( "text", text.value );
  mysack.setVar( "follow", follow.value );
  mysack.setVar( "masking", masking.checked);
  mysack.setVar( "auto", auto.checked);
  mysack.setVar( "newwinlink", newwinlink.checked);
  mysack.setVar( "category", category );
  mysack.setVar( "wpAdminSection", wpAdminSection );
  mysack.setVar( "googlecode", googlecode.value );
  mysack.setVar( "eac", eac.checked);
  mysack.setVar( "linkTypeVal",linkTypeVal);
  mysack.setVar( "htmlimagevalue",htmlimagevalue);
  mysack.setVar( "htmlimagewidth",htmlimagewidth);
  mysack.setVar( "htmlimageheight",htmlimageheight);
  mysack.setVar( "htmlimagealign",htmlimagealign);
  mysack.setVar( "jscsvalue",jscsvalue);
  mysack.setVar( "linkname",linkname.value);
  mysack.encVar( "cookie", document.cookie, false );
   mysack.onError = function() { alert('Ajax error in adding marketer link' )};
  mysack.runAJAX();


  linkname.value='';
  link.value='';
  text.value='';
  imageurl.value='';
  imagewidth.value='';
  imageheight.value='';
  imagealign.value='';
  follow.value="dofollow";
  masking.checked=false;
  auto.checked=false;
  newwinlink.checked=false;
  eac.checked=false;
  googlecode.value='';
  jscs.value='';

  return true;

} // end of JavaScript function

function showMaskingWindow(helpFrm)
{
	var maskingUpWindow;
	maskingUpWindow =window.open("http://"+top.location.host+"/wp-content/plugins/wp-marketer/maskingDefine.php?helpFrm="+helpFrm+"",
    "DescriptiveWindowName","resizable=no,scrollbars=yes,status=yes,height=200,width=400,left=150,top=400");
}


function wp_marketer_ajax_edit_link(link, text,follow,masking,auto,newwinlink,category,linkid,wpAdminSection,googlecode,eac,linktype,imageurl,jscs,imagewidth,imageheight,imagealign,linkname)
{
	var mysack = new sack(
       "<?php bloginfo( 'wpurl' ); ?>/wp-admin/admin-ajax.php" );


  if(linktype[0].checked==true)
  {
	var linkTypeVal=linktype[0].value;
	var htmlimagevalue='';
	var htmlimagewidth='';
	var htmlimageheight='';
	var htmlimagealign='';
  	var jscsvalue='';
  }
  if(linktype[1].checked==true)
  {
  	if(trimAll(imageurl.value)=='')
  	{
  		alert('Please enter Image URL!');
  		imageurl.focus();
  		return false;
  	}
  	var linkTypeVal=linktype[1].value;
  	var htmlimagevalue=imageurl.value;
	var htmlimagewidth=imagewidth.value;
	var htmlimageheight=imageheight.value;
	var htmlimagealign=imagealign.value;
  	var jscsvalue='';
  }
  if(linktype[2].checked==true)
  {
  	if(trimAll(jscs.value)=='')
  	{
  		alert('Please enter JavaScript Code Snippet!');
  		jscs.focus();
  		return false;
  	}
  	var linkTypeVal=linktype[2].value;
  	var htmlimagevalue='';
	var htmlimagewidth='';
	var htmlimageheight='';
	var htmlimagealign='';
  	var jscsvalue=jscs.value;
  }

  mysack.execute = 1;
  mysack.method = 'POST';
  mysack.setVar( "action", "wp_marketer_edit_link" );
  mysack.setVar( "link", link.value );
  mysack.setVar( "text", text.value );
  mysack.setVar( "follow", follow.value );
  mysack.setVar( "masking", masking.checked);
  mysack.setVar( "auto", auto.checked);
  mysack.setVar( "newwinlink", newwinlink.checked);
  mysack.setVar( "category", category );
  mysack.setVar( "wpAdminSection", wpAdminSection );
  mysack.setVar( "googlecode", googlecode.value );
  mysack.setVar( "eac", eac.checked);
  mysack.setVar( "linkTypeVal",linkTypeVal);
  mysack.setVar( "htmlimagevalue",htmlimagevalue);
  mysack.setVar( "htmlimagewidth",htmlimagewidth);
  mysack.setVar( "htmlimageheight",htmlimageheight);
  mysack.setVar( "htmlimagealign",htmlimagealign);
  mysack.setVar( "jscsvalue",jscsvalue);
  mysack.setVar( "linkname", linkname.value );
  mysack.setVar( "linkid", linkid );
  mysack.encVar( "cookie", document.cookie, false );
  mysack.onError = function() { alert('Ajax error in adding marketer link' )};
  mysack.runAJAX();

  return true;
}

function showHideACS(a,eacdiv)
{
	if(a.checked==true)
		document.getElementById(eacdiv).style.display = "block";
	else
		document.getElementById(eacdiv).style.display = "none";
}

function hideEditForm(WPELinkDiv)
{
	document.getElementById(WPELinkDiv).style.display = "none";
}
function showRelatedLinkField(a,htmlimg,jscript,autolinkdiv,reldiv,linkmaskingdiv,opennewwindiv,anchorlablediv,imgalttagdiv,outbounddiv,anchor_img_tag)
{
	if(a.value=="htmltext")
	{
		document.getElementById(htmlimg).style.display = "none";
		document.getElementById(jscript).style.display = "none";
		document.getElementById(autolinkdiv).style.display = "block";
		document.getElementById(reldiv).style.display = "block";
		document.getElementById(linkmaskingdiv).style.display = "block";
		document.getElementById(opennewwindiv).style.display = "block";
		document.getElementById(anchorlablediv).style.display = "block";
		document.getElementById(imgalttagdiv).style.display = "none";
		document.getElementById(outbounddiv).style.display = "block";
		document.getElementById(anchor_img_tag).style.display = "block";
	}
	if(a.value=="htmlimage")
	{
		document.getElementById(htmlimg).style.display = "block";
		document.getElementById(jscript).style.display = "none";
		document.getElementById(autolinkdiv).style.display = "none";
		document.getElementById(reldiv).style.display = "none";
		document.getElementById(linkmaskingdiv).style.display = "block";
		document.getElementById(opennewwindiv).style.display = "block";
		document.getElementById(anchorlablediv).style.display = "none";
		document.getElementById(imgalttagdiv).style.display = "block";
		document.getElementById(outbounddiv).style.display = "block";
		document.getElementById(anchor_img_tag).style.display = "block";
	}
	if(a.value=="javascript")
	{
		document.getElementById(htmlimg).style.display = "none";
		document.getElementById(jscript).style.display = "block";
		document.getElementById(autolinkdiv).style.display = "none";
		document.getElementById(reldiv).style.display = "none";
		document.getElementById(linkmaskingdiv).style.display = "none";
		document.getElementById(opennewwindiv).style.display = "none";
		document.getElementById(anchorlablediv).style.display = "block";
		document.getElementById(imgalttagdiv).style.display = "none";
		document.getElementById(outbounddiv).style.display = "none";
		document.getElementById(anchor_img_tag).style.display = "none";
	}
}
//]]>
</script>
<?php
} // end of PHP function myplugin_js_admin_header

