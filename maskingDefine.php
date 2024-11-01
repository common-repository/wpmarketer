<font face=Verdana size='2px'><?
if($_REQUEST['helpFrm']=="outbound_url")
	echo "Specifies the URL to which you are linking. Applies only to HTML text and image link types, not JavaScript.";
elseif($_REQUEST['helpFrm']=="anchor_text")
	echo "Specify the anchor text or keyword phrase for your HTML text link. Feature only works with text type links and only when Auto Link is enabled. Occurrences of the specified anchor text within your posts and pages will be replaced by the specified WPMarketer link up to the maximum number of times specified when Auto Linking is enabled.";
elseif($_REQUEST['helpFrm']=="realtion")
	echo "Specify whether the link relationship should be &quot;nofollow&quot; for purposes of search engine optimization. When enabled no &quot;link juice&quot; or &quot;page rank&quot; will be passed by the link. Applies to HTML text and image links only and is not applicable to JavaScript links or ads. It is best to enable &quot;nofollow&quot; for most affiliate links but not always for &quot;In Links&quot; you have sold or exchanged with other Web sites for SEO purposes.";
elseif($_REQUEST['helpFrm']=="link_masking")
	echo "Specify whether link should be masked or not. When enabled, user will see a &quot;pretty&quot; internal link in their browser bar when they hover over the link, thus masking the outbound URL. The only way bots or humans can see the outbound URL is to actually click on the link and visit the outbound URL. Applies to HTML text and image links only and is not applicable to JavaScript links or ads.";
elseif($_REQUEST['helpFrm']=="auto_link")
	echo "Specify that the link should be automatically inserted wherever the anchor text appears in your posts and pages. Auto Linking must be enabled in the WPMarketer Settings panel, where the maximum link limit specified. Applies to HTML text links only and is not applicable to HTML image or JavaScript links or ads.";
elseif($_REQUEST['helpFrm']=="new_window_property")
	echo "Specify whether the outbound URL should be opened in the same or a new browser window. Applies to HTML text and image links only and is not applicable to JavaScript links or ads.";
elseif($_REQUEST['helpFrm']=="adwords_conversions")
	echo "Specify whether the link should be tracked using Google Adwords Conversion Tracking feature.";
elseif($_REQUEST['helpFrm']=="adwords_conversions_snippet")
	echo "Insert the Google Adwords Conversion Snippet in this field in order to track when a user coming from your Adwords Pay-Per-Click campaigns clicks on the link. Applies to HMTL text and image links as well as JavaScript ads and links.";
elseif($_REQUEST['helpFrm']=="image_url")
	echo "Specify the URL to your image link.";
elseif($_REQUEST['helpFrm']=="javascript_code_snippet")
	echo "Input the JavaScript code for your link. You can input any Adsense, YPN, or Affiliate network JavaScript code when the JavaScript link type is selected.";
elseif($_REQUEST['helpFrm']=="link_type")
	echo "WPMarketer provides three Link or Ad type options; HTML Text, HTML Image and JavaScript. This gives you complete flexibility to manage your Performance Marketing Ads and Affiliate Links. You can choose to insert Affiliate HTML links with or without Sub IDs, banner ads or other image-based performance marketing links. Or you can insert JavasScript ads such as Commission Junction, LinkShare, Amazon or even Google Adsense.";
elseif($_REQUEST['helpFrm']=="image_width")
	echo "Specify the width of your image link in pixels.";
elseif($_REQUEST['helpFrm']=="image_height")
	echo "Specify the height of your image link in pixels.";
elseif($_REQUEST['helpFrm']=="image_align")
	echo "Specify the alignment of your image link.";
elseif($_REQUEST['helpFrm']=="image_alt_tag")
	echo "Specify an optional alt tag value for your image link.";
elseif($_REQUEST['helpFrm']=="link_name")
	echo "This optional field gives your WPMarketer Link a name you can more easily remember and reference than the WPMID.";
elseif($_REQUEST['helpFrm']=="Activate_Auto_linking_Option")
	echo "Enable the auto linking feature so that HTML links can be automatically inserted into your Wordpress posts and pages wherever specified Anchor Text appears, replacing the anchor text with the Affiliate Marketing or In Text links you configure. You must also specify which links you want to be auto linked by selecting Auto Link in the Manage WPM Links panel. Enables natural &quot;In Links&quot; that appear within your site content that are &quot;contextual&quot; rather than on a Blog Roll in the sidebar or footer area.";
elseif($_REQUEST['helpFrm']=="Enter_Links_Limit")
	echo "Specify the maximum number of times a WPM auto link can be inserted in your posts and pages, replacing the anchor text up to that maximum. For example, if you set Links Limit to 2 and the anchor text appears 3 times in a given post or page, only the first and second instances will appear as WPM links while the third instance will remain as text only.";
elseif($_REQUEST['helpFrm']=="Enter_Image_Max_Width")
	echo "Specify the maximum width for WPM Image Links in pixels; this value is also used as the default in the case you do not specify a width when adding or editing WPM Image Links.";
elseif($_REQUEST['helpFrm']=="Enter_Image_Max_Height")
	echo "Specify the maximum height for WPM Image Links in pixels; this value is also used as the default in the case you do not specify a height when adding or editing WPM Image Links.";
elseif($_REQUEST['helpFrm']=="create_new_categories_and_subcategories")
	echo "Create new categories and subcategories for Link Masking. This enables your Affiliate links to have a &quot;pretty&quot; internal URL that is masked/cloaked from the view of humans and bots.";
elseif($_REQUEST['helpFrm']=="wpm_tag")
	echo "When you add a WPM link it will have a unique identifier (WPMID). Simply copy and paste the WPMID Tag into your Wordpress post or page to manually insert a WPM link anywhere you want it to appear. Insert a WPMID tag as many times as you want and whenever you update that WPM Link it will automatically change the parameters of that link anywhere the WPMID tag has been inserted.";
?></font>