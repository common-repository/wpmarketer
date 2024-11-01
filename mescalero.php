<?php
session_start();
$table_name_link_count_session 	= $wpdb->prefix . $wp_marketer_prefix."_link_count_session";
$sqlLinkCountSessionCreate ="CREATE TABLE IF NOT EXISTS `".$table_name_link_count_session."` (
  `id` int(11) NOT NULL auto_increment,
  `link_str` text NOT NULL,
  `link_str_href` text NOT NULL,
  `session_id` varchar(250) NOT NULL,
  `update_date` datetime NOT NULL,
  PRIMARY KEY  (`id`)
);";
mysql_query($sqlLinkCountSessionCreate) or die(mysql_error());
$sqlAlterSEssionCreate ="ALTER TABLE `".$table_name_link_count_session."` CHANGE `update_date` `update_date` DATETIME NOT NULL";
mysql_query($sqlAlterSEssionCreate) or die(mysql_error());
$session_id_value=session_id();
$_SESSION['session_id_val']=$session_id_value;

$complete_url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$arrayval=array();
$searchvalNew=wpSlugValue($complete_url);
$wpAdminSection=strpos($complete_url,"wp-admin");
$wpLoginSection=strpos($complete_url,"wp-login");
$templatePosition=strpos($complete_url,"&template=");

//start if this page is not wp-admin or wp-login
function contentValues($contentValueis)
{
	global $row,$searchvalNew;

	$contentValueis=$contentValueis." ";
	$content=getLinkDetailsMarketer($contentValueis,$searchvalNew,$row->auto_link_option,$row->auto_link_limit);
		
	$content = str_replace(']]>', ']]&gt;', $content);
	$content=substr($content,0,strlen($content)-1);
	return $content;
}
function getLinkDetailsMarketer($content,$searchvalNew,$auto_link_option,$auto_link_limit)
{
	$specialCharsArray=array('&','¢','©','÷','µ','·','¶','±','€','£','®','§','™','¥','?',',','.',"</T","</U","</S","</P","</O","</M","</L","</I","</H","</F","</E","</D","</C","</B","</t","</u","</s","</p","</o","</m","</l","</i","</h","</f","</e","</d","</c","</b",':','~','@','#','$','%','^','*','!',"<T","<U","<S","<P","<O","<M","<L","<I","<H","<F","<E","<D","<C","<B","<t","<u","<s","<p","<o","<m","<l","<i","<h","<f","<e","<d","<c","<b",'`','"','‘','’','“','”','+','-',')','(',';');
	global $wpdb,$wp_marketer_prefix;
	$table_name 	= $wpdb->prefix . $wp_marketer_prefix."_links";
	$table_name2 	= $wpdb->prefix . $wp_marketer_prefix."_categories";
	$table_name3 	= $wpdb->prefix . $wp_marketer_prefix."_link_string";

	$pos = strpos($content, "WPMID=");
	if($pos!='' || $searchvalNew>=0)//start if no1
	{
		preg_match_all('#\[WPMID=([0-9,]+){1,}\]#si', $content, $linkidvalue);
		$f=0;
		foreach($linkidvalue[1] as $linkidval)
		{
			$sqlLinkInfo = "SELECT a.*,b.category as categoryName FROM $table_name as a left join $table_name2 as b on (a.category=b.id) WHERE a.id='$linkidval'";
			$rsLinkInfo=mysql_query($sqlLinkInfo);
			if($arrLinkInfo=mysql_fetch_object($rsLinkInfo))
			{
				if(trim($arrLinkInfo->newwinlink)=="target=_blank")
					$arrLinkInfo->newwinlink="target='_blank'";
				if($arrLinkInfo->link_type=="htmltext")
				{
					$arrLinkInfo->masking;
					$arrLinkInfo->link;
					$clean_cat = get_category_slug_marketer($arrLinkInfo->category);
					$clean_link = get_bloginfo('wpurl')."".$clean_cat."/".$arrLinkInfo->slug;
					//start check for maskig enabled or disabled
					if($arrLinkInfo->masking=="Enabled")
					$anchorTagVal="<a href='".$clean_link."' rel='".$arrLinkInfo->follow."' ".$arrLinkInfo->newwinlink.">".$arrLinkInfo->anchor_text."</a>";
					else
					$anchorTagVal="<a href='".$arrLinkInfo->link."' rel='".$arrLinkInfo->follow."' ".$arrLinkInfo->newwinlink.">".$arrLinkInfo->anchor_text."</a>";
					//end check for maskig enabled or disabled
					$content=str_replace("[WPMID=".$linkidval."]",$anchorTagVal,$content);
				}
				if($arrLinkInfo->link_type=="htmlimage")
				{
					$arrLinkInfo->masking;
					$arrLinkInfo->link;
					$clean_cat = get_category_slug_marketer($arrLinkInfo->category);
					$clean_link = get_bloginfo('wpurl')."".$clean_cat."/".$arrLinkInfo->slug;

					if($arrLinkInfo->masking=="Enabled")
					$anchorTagVal="<a href='$clean_link' $arrLinkInfo->newwinlink title='$arrLinkInfo->anchor_text'><img src='$arrLinkInfo->image_url' width='$arrLinkInfo->image_width' height='$arrLinkInfo->image_height' align='$arrLinkInfo->image_align' border=0></a>";
					else
					$anchorTagVal="<a href='$arrLinkInfo->link' $arrLinkInfo->newwinlink title='$arrLinkInfo->anchor_text'><img src='$arrLinkInfo->image_url' width='$arrLinkInfo->image_width' height='$arrLinkInfo->image_height' align='$arrLinkInfo->image_align' border=0></a>";

					$content=str_replace("[WPMID=".$linkidval."]",$anchorTagVal,$content);

				}
				if($arrLinkInfo->link_type=="javascript")
				{
					//start to check for if page is rss feed
					$complete_url1="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
					$feedPosition = strpos($complete_url1,"/feed");
					$wprssPosition = strpos($complete_url1,"wp-rss.php");
					$wprsstwoPosition = strpos($complete_url1,"wp-rss2.php");
					$wprdfPosition = strpos($complete_url1,"wp-rdf.php");
					$wpatomPosition = strpos($complete_url1,"wp-atom.php");
					$wpquesPosition = strpos($complete_url1,"?feed");
					if($feedPosition=='' && $wprssPosition=='' && $wprsstwoPosition=='' && $wprdfPosition=='' && $wpatomPosition=='' && $wpquesPosition=='')
					{
						$anchorTagVal="".$arrLinkInfo->js_image_url."";
						$content=str_replace("[WPMID=".$linkidval."]",$anchorTagVal,$content);
					}
					//end to check for if page is rss feed
				}
			}
		}
		if($auto_link_option==1)
		{
			//start to get links on the other keywords from wp_wp_marketer_link_string
			$content;
			$loopval=100001;
			$sqlLinkStrInfo = "SELECT linkstr FROM ".$table_name3;
			$rsLinkStrInfo=mysql_query($sqlLinkStrInfo);
			if($arrLinkStrInfo=mysql_fetch_object($rsLinkStrInfo))
			{
				$arrLinkStrInfo->linkstr;
			}


			preg_match_all('/(<a (.*?)<\/a>)/ixs', $content, $matches);
			$srchHrefPositionExp=$matches[2];
			sort($srchHrefPositionExp);
			foreach($srchHrefPositionExp as $srchHrefPositionExplode)
			{
				if($srchHrefPositionExplode!='' && $srchHrefPositionExplode!='/')
				{
					$hrefpos = strpos($content, $srchHrefPositionExplode);
					if($hrefpos!='' && $hrefpos!=0)
					{
						$newHrefArray[$loopval]=$srchHrefPositionExplode;
						$content=str_replace($srchHrefPositionExplode,"^*^*".$loopval,$content);
						$loopval++;
					}
				}
			}


			preg_match_all('/(<img(.*?)>)/ixs', $content, $matches1);
			$srchHrefPositionExp1=$matches1[2];
			sort($srchHrefPositionExp1);
			foreach($srchHrefPositionExp1 as $srchHrefPositionExplode1)
			{
				if($srchHrefPositionExplode1!='' && $srchHrefPositionExplode1!='/')
				{
					$hrefpos1 = strpos($content, $srchHrefPositionExplode1);
					if($hrefpos1!='' && $hrefpos1!=0)
					{
						$newHrefArray[$loopval]=$srchHrefPositionExplode1;
						$content=str_replace($srchHrefPositionExplode1,"^*^*".$loopval,$content);
						$loopval++;
					}
				}
			}

			$linkStrExplode=explode("~~",$arrLinkStrInfo->linkstr);
			foreach($linkStrExplode as $linkStrExp)
			{
				$linkStrExp;
				$categoryidval=explode("~!",$linkStrExp);
				$categoryidvalue=$categoryidval[0];

				$keywordval=explode("@#",$categoryidval[1]);
				$keywordvalue=$keywordval[0];

				$slug=explode("%&",$keywordval[1]);
				$slugValue=$slug[0];

				$followval=explode("&*",$slug[1]);
				$followvalue=$followval[0];


				$newlinkval=explode("*!",$followval[1]);
				$newlinkvalue=$newlinkval[0];

				if(trim($newlinkvalue)=="target=_blank")
						$newlinkvalue="target='_blank'";

				$linkidv=explode("#*",$newlinkval[1]);
				$linkidvval=$linkidv[0];
				$autoLinkOPtion=$linkidv[1];


				$sqlMaskingInfo = "SELECT * FROM $table_name WHERE id='$linkidvval'";
				$rsMaskingInfo=mysql_query($sqlMaskingInfo);
				if($arrMaskingInfo=mysql_fetch_object($rsMaskingInfo))
				{
					$maskingValueis=$arrMaskingInfo->masking;
					$realURL=$arrMaskingInfo->link;
					$link_type=$arrMaskingInfo->link_type;
					$image_url=$arrMaskingInfo->image_url;
					$image_width=$arrMaskingInfo->image_width;
					$image_height=$arrMaskingInfo->image_height;
					$image_align=$arrMaskingInfo->image_align;
					$js_image_url=$arrMaskingInfo->js_image_url;
				}

				if($keywordvalue!='' && $autoLinkOPtion==1)
				{

					$keywordvalue_strtolower=strtolower($keywordvalue);
					$keywordvalue_strtoupper=strtoupper($keywordvalue);
					$keywordvalue_ucfirst=ucfirst($keywordvalue);
					$keywordvalue_ucwords=ucwords($keywordvalue);
					$content=str_replace($keywordvalue_strtolower,$keywordvalue,$content);
					$content=str_replace($keywordvalue_strtoupper,$keywordvalue,$content);
					$content=str_replace($keywordvalue_ucfirst,$keywordvalue,$content);
					$content=str_replace($keywordvalue_ucwords,$keywordvalue,$content);


					$clean_cat = get_category_slug_marketer($categoryidvalue);
					$clean_link = get_bloginfo('wpurl')."".$clean_cat."/".$slugValue;

					//start check for maskig enabled or disabled
					if($maskingValueis=="Enabled")
					{
						if($link_type=="htmltext")
						{
							$idcount=getTotalLinkRec("<a href='$clean_link' rel='$followvalue' $newlinkvalue>$keywordvalue</a>");
							if($idcount<$auto_link_limit)
							{

								$keywordCountExp=explode($keywordvalue,$content);
								count($keywordCountExp);
								$remainingLinkToPublish=$auto_link_limit-$idcount;
								if(count($keywordCountExp)>1)
								{
									$xy=0;$newContent='';
									foreach($keywordCountExp as $keywordCountExplode)
									{
										$xy++;
										if($xy<count($keywordCountExp))
										{
											if($xy<=$auto_link_limit)
											{
												if($xy<=$remainingLinkToPublish)
												$newContent.=$keywordCountExplode.$keywordvalue;
												else
												$newContent.=$keywordCountExplode."!!!!!";
											}
											else
											$newContent.=$keywordCountExplode."!!!!!";
										}
										else
										{
											if($xy<=$auto_link_limit)
											$newContent.=$keywordCountExplode;
											else
											$newContent.=$keywordCountExplode;
										}

									}$content=$newContent;
								}
								if($newContent=='')
								$content=$content;

							$content=str_replace($keywordvalue." ","<a href='$clean_link' rel='$followvalue' $newlinkvalue>$keywordvalue</a> ",$content);
							foreach($specialCharsArray as $specialCharsArr)
							{
								$content=str_replace($keywordvalue.$specialCharsArr,"<a href='$clean_link' rel='$followvalue' $newlinkvalue>$keywordvalue</a>".$specialCharsArr,$content);
							}
							
							$content=str_replace("!!!!!",$keywordvalue,$content);

								$expContent=explode("<a href='$clean_link' rel='$followvalue' $newlinkvalue>$keywordvalue</a>",$content);
								$counLinkStr=count($expContent);

							addToLinkCountSession($keywordvalue,"<a href='$clean_link' rel='$followvalue' $newlinkvalue>$keywordvalue</a>",$counLinkStr);
							
							$newHrefArray[$loopval]="<a href='$clean_link' rel='$followvalue' $newlinkvalue>$keywordvalue</a>";
							$content=str_replace("<a href='$clean_link' rel='$followvalue' $newlinkvalue>$keywordvalue</a>","^*^*".$loopval,$content);
							//$content=str_replace($srchHrefPositionExplode1,"^*^*".$loopval,$content);
							$loopval++;
							}
						}
					}
					else
					{
						if($link_type=="htmltext")
						{

							$idcount=getTotalLinkRec("<a href='$realURL' rel='$followvalue' $newlinkvalue>$keywordvalue</a>");
							if($idcount<$auto_link_limit)
							{

								$keywordCountExp=explode($keywordvalue,$content);
								count($keywordCountExp);
								$remainingLinkToPublish=$auto_link_limit-$idcount;
								if(count($keywordCountExp)>1)
								{
									$xy=0;$newContent='';
									foreach($keywordCountExp as $keywordCountExplode)
									{
										$xy++;
										if($xy<count($keywordCountExp))
										{
											if($xy<=$auto_link_limit)
											{
												if($xy<=$remainingLinkToPublish)
												$newContent.=$keywordCountExplode.$keywordvalue;
												else
												$newContent.=$keywordCountExplode."!!!!!";
											}
											else
											$newContent.=$keywordCountExplode."!!!!!";
										}
										else
										{
											if($xy<=$auto_link_limit)
											$newContent.=$keywordCountExplode;
											else
											$newContent.=$keywordCountExplode;
										}

									}$content=$newContent;
								}
								if($newContent=='')
								$content=$content;

							$content=str_replace($keywordvalue." ","<a href='$realURL' rel='$followvalue' $newlinkvalue>$keywordvalue</a> ",$content);
							foreach($specialCharsArray as $specialCharsArr)
							{
								$content=str_replace($keywordvalue.$specialCharsArr,"<a href='$realURL' rel='$followvalue' $newlinkvalue>$keywordvalue</a>".$specialCharsArr,$content);
							}
							
							$content=str_replace("!!!!!",$keywordvalue,$content);


								$expContent=explode("<a href='$realURL' rel='$followvalue' $newlinkvalue>$keywordvalue</a>",$content);
								$counLinkStr=count($expContent);

							addToLinkCountSession($keywordvalue,"<a href='$realURL' rel='$followvalue' $newlinkvalue>$keywordvalue</a>",$counLinkStr);

							$newHrefArray[$loopval]="<a href='$realURL' rel='$followvalue' $newlinkvalue>$keywordvalue</a>";
							$content=str_replace("<a href='$realURL' rel='$followvalue' $newlinkvalue>$keywordvalue</a>","^*^*".$loopval,$content);
							//$content=str_replace($srchHrefPositionExplode1,"^*^*".$loopval,$content);
							$loopval++;
							}
						}
					}
					//end check for maskig enabled or disabled
					$f++;
				}
			}

			for($k=100001;$k<$loopval;$k++)
			{
				$content=str_replace("^*^*".$k,$newHrefArray[$k],$content);
			}
			//end to get links on the other keywords from wp_wp_marketer_link_string
		}//end of if $auto_link_option==1



		$content=str_replace("?nl=1",'',$content);
		$content=str_replace("&nl=1",'',$content);



		$complete_url1="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$feedPosition = strpos($complete_url1,"/feed");
		$wprssPosition = strpos($complete_url1,"wp-rss.php");
		$wprsstwoPosition = strpos($complete_url1,"wp-rss2.php");
		$wprdfPosition = strpos($complete_url1,"wp-rdf.php");
		$wpatomPosition = strpos($complete_url1,"wp-atom.php");
		$wpquesPosition = strpos($complete_url1,"?feed");
		//start check for rss feed
		if($feedPosition!='' || $wprssPosition!='' || $wprsstwoPosition!='' || $wprdfPosition!='' || $wpatomPosition!='' || $wpquesPosition!='')
		{
				preg_match_all('/(<iframe(.*?)<\/iframe>)/ixs', $content, $matches6);
				$srchHrefPositionExp6=$matches6[2];
				sort($srchHrefPositionExp6);
				foreach($srchHrefPositionExp6 as $srchHrefPositionExplode6)
				{
					if($srchHrefPositionExplode6!='' && $srchHrefPositionExplode6!='/')
					{
						$hrefpos6 = strpos($content, $srchHrefPositionExplode6);
						if($hrefpos6!='' && $hrefpos6!=0)
						{
							$newHrefArray[$loopval]=$srchHrefPositionExplode6;
							$content=str_replace($srchHrefPositionExplode6,"",$content);
						}
					}
				}
				$content=str_replace("<iframe</iframe>","",$content);

				preg_match_all('/(<script(.*?)<\/script>)/ixs', $content, $matches7);
				$srchHrefPositionExp7=$matches7[2];
				sort($srchHrefPositionExp7);
				foreach($srchHrefPositionExp7 as $srchHrefPositionExplode7)
				{
					if($srchHrefPositionExplode7!='' && $srchHrefPositionExplode7!='/')
					{
						$hrefpos7 = strpos($content, $srchHrefPositionExplode7);
						if($hrefpos7!='' && $hrefpos7!=0)
						{
							$newHrefArray[$loopval]=$srchHrefPositionExplode7;
							$content=str_replace($srchHrefPositionExplode7,"",$content);
						}
					}
				}
				$content=str_replace("<script</script>","",$content);
		}
		//end check for rss feed




		return $content;

	}//end if no1
}
function wpTermValueMarketer($complete_url)
{
	$searchval=0;
	global $wpdb,$wp_marketer_prefix;
	$table_name 	= $wpdb->prefix ."terms";
	$sqlSlugInfo = "SELECT slug FROM ".$table_name;
	$rsSlugInfo=mysql_query($sqlSlugInfo);
	while($arrSlugInfo=mysql_fetch_object($rsSlugInfo))
	{
		$wpSlugPos=strpos($complete_url,$arrSlugInfo->slug);
		if($wpSlugPos!='')
		{
			$searchval=1;
			break;
		}
	}
	return $searchval;
}
function wpSlugValue($complete_url)
{
	$searchvalnew=0;
	global $wpdb,$wp_marketer_prefix;
	$complete_url_array=explode('/',$complete_url);
	$slugValueis1=$complete_url_array[count($complete_url_array)-2];
	$slugValueis2=$complete_url_array[count($complete_url_array)-1];
	$table_name 	= $wpdb->prefix .$wp_marketer_prefix."_links";

	if($slugValueis1!='' && $slugValueis2!='')
		$sqlSlugInfoLink = "SELECT id FROM ".$table_name." where (slug='$slugValueis1' || slug='$slugValueis2')";
	elseif($slugValueis1=='' && $slugValueis2!='')
		$sqlSlugInfoLink = "SELECT id FROM ".$table_name." where slug='$slugValueis2'";
	elseif($slugValueis1!='' && $slugValueis2=='')
		$sqlSlugInfoLink = "SELECT id FROM ".$table_name." where slug='$slugValueis1'";

	$rsSlugInfoLink=mysql_query($sqlSlugInfoLink);
	if($arrSlugInfoLink=mysql_fetch_object($rsSlugInfoLink))
	{
		$searchvalnew=$arrSlugInfoLink->id;
	}
	return $searchvalnew;
}


function addToLinkCountSession($keywordvalue,$hrefContentVal,$counLinkStr)
{
	$currentDate=date("Y-m-d H:i:s");
	global $wpdb,$wp_marketer_prefix;
	$table_name_link_session 	= $wpdb->prefix . $wp_marketer_prefix."_link_count_session";
	if($counLinkStr>1)
	{
		for($i=1;$i<$counLinkStr;$i++)
		{
			$sqlAddToLinkCountSession="insert into ".$table_name_link_session." (link_str,link_str_href,session_id,update_date) values('".addslashes($keywordvalue)."','".addslashes($hrefContentVal)."','".$_SESSION['session_id_val']."','".$currentDate."')";
			$rsAddToLinkCountSession=mysql_query($sqlAddToLinkCountSession) or die('can not run the query because of '.mysql_error());
		}
	}
}

function getTotalLinkRec($hrefKeyword)
{
	$idcount=0;
	global $wpdb,$wp_marketer_prefix;
	$table_name_link_session 	= $wpdb->prefix . $wp_marketer_prefix."_link_count_session";
	$sqlGetNumberOfRecord="select count(id) as id from ".$table_name_link_session." where link_str_href='".addslashes($hrefKeyword)."' && session_id='".$_SESSION['session_id_val']."'";
	$rsGetNumberOfRecord=mysql_query($sqlGetNumberOfRecord) or die('Can not run the query because of '.mysql_error());
	if($arrGetNumberOfRecord=mysql_fetch_object($rsGetNumberOfRecord))
	{
		$idcount=$arrGetNumberOfRecord->id;
	}
	return $idcount;
}
function removeSessionFromLinkSessionTable()
{
	global $wpdb,$wp_marketer_prefix;
	$yesterDayDate=date("Y-m-d",mktime(0,0,0,date('m'),date('d')-1,date('Y')));
	$timeBeforeTenSecDate=date("Y-m-d H:i:s",mktime(date('H'),date('i'),date('s')-20,date('m'),date('d'),date('Y')));
	$table_name_link_session 	= $wpdb->prefix . $wp_marketer_prefix."_link_count_session";

	//start to delete record of yesterday
	$sqlDelYesterdayRecord="delete from ".$table_name_link_session." where update_date<='".$yesterDayDate."'";
	$rsDelYesterdayRecord=mysql_query($sqlDelYesterdayRecord) or die('Can not run the query because of '.mysql_error());
	//end to delete record of yesterday

	//start to delete record of timeBeforeTenSecDate
	$sqlDelTimeBeforeTenSecDate="delete from ".$table_name_link_session." where update_date<='".$timeBeforeTenSecDate."'";
	$rsDelTimeBeforeTenSecDate=mysql_query($sqlDelTimeBeforeTenSecDate) or die('Can not run the query because of '.mysql_error());
	//end to delete record of timeBeforeTenSecDate


	$sqlDelRecord="delete from ".$table_name_link_session." where session_id='".$_SESSION['session_id_val']."'";
	$rsDelRecord=mysql_query($sqlDelRecord) or die('Can not run the query because of '.mysql_error());
}

function wp_marketer_process($content) {
	return contentValues($content);
}

//start if this page is not wp-admin or wp-login
if($wpAdminSection=='' && $wpLoginSection=='' && $templatePosition=='')
{
$working_dir = getcwd();
$working_dir_Arr=explode("\\",$working_dir);
$working_dir_Arr_New=implode("/",$working_dir_Arr);
//start to get excerpt value
		$themeName=get_option("template");
		if(@$excerptPosition=file_get_contents($working_dir_Arr_New."/wp-content/themes/".$themeName."/index.php"))
		$the_excerpt_pos=strpos($excerptPosition,"the_excerpt(");
		if($the_excerpt_pos!='')
		{
			add_filter('the_excerpt', 'wp_marketer_process');
		}
		else
			add_filter('the_content', 'wp_marketer_process');
		removeSessionFromLinkSessionTable();
//end to get excerpt value
}
?>