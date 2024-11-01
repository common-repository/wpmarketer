<?php
Navxt_Plugin_Tabulator_marketer::init_marketer();
class Navxt_Plugin_Tabulator_marketer
{
	public static function init_marketer()
	{
		$plugin = new self();
		add_action('admin_head', array($plugin, 'admin_head_marketer'));
		add_action('wp_print_scripts', array($plugin, 'javascript_marketer'));
	}
	public function javascript_marketer()
	{
		//If we are in the dashboard we may need this
		if(is_admin())
		{
			wp_enqueue_script('jquery-ui-tabs');
		}
	}
	
	public function admin_head_marketer()
	{
?>
<style type="text/css">
	ul.ui-tabs-nav {background:#fff; border-bottom:1px solid #c6d9e9; font-size:12px; height:29px; margin:13px 0 0; padding:0; padding-left:8px; list-style:none;}
	ul.ui-tabs-nav li {display:inline; line-height: 200%; list-style:none; margin: 0; padding:0; position:relative; top:1px; text-align:center; white-space:nowrap;}
	/*ul.ui-tabs-nav li a {background:transparent none no-repeat scroll 0%; border:1px transparent #fff; border-bottom:1px solid #c6d9e9; display:block; float:left; line-height:28px; padding:1px 13px 0; position:relative; text-decoration:none;}*/
	ul.ui-tabs-nav li a {background:transparent none no-repeat scroll 0%; border:1px transparent #fff; border-bottom:1px solid #c6d9e9; line-height:42px; padding:1px 13px 0; position:relative; text-decoration:none;}
	ul.ui-tabs-nav li.ui-tabs-selected a {-moz-border-radius-topleft:4px; -moz-border-radius-topright:4px; background:#fff; border:1px solid #c6d9e9; border-bottom-color:#fff; color:#d54e21; font-weight:normal; padding:12px 12px 0 12px;}
	ul.ui-tabs-nav a:focus, a:active {outline: none;}
	#hasadmintabs fieldset {clear:both;}
</style>
<script type="text/javascript">
/* <![CDATA[ */
	
	jQuery(function()
	{
		bcn_tabulator_init_marketer();
	 });

	/**
	 * Tabulator Bootup
	 */
	function bcn_tabulator_init_marketer()
	{
		bcn_admin_init_tabs_marketer();
		bcn_admin_gobal_tabs_marketer(); // comment out this like to disable tabs in admin
	}

	/**
	 * inittialize tabs for admin panel pages (wordpress core)
	 *
	 * @todo add uniqueid somehow
	 */
	function bcn_admin_gobal_tabs_marketer()
	{
		/* if has already a special id quit the global try here */
		if (jQuery('#hasadmintabs').length > 0) return;

		jQuery('#wpbody .wrap form').each(function(f)
		{
			var $formEle = jQuery(this).children();

			var $eleSets      = new Array();
			var $eleSet       = new Array();
			var $eleSetIgnore = new Array();

			for (var i = 0; i < $formEle.size(); i++)
			{
				var curr = $formEle.get(i);
				var $curr = jQuery(curr);
				// cut condition: h3 or stop
				// stop: p.submit
				if ($curr.is('p.submit') || $curr.is('h3'))
				{
					if ($eleSet.length)
					{
						if ($eleSets.length == 0 && $eleSet.length == 1 && jQuery($eleSet).is('p'))	{
							$eleSetIgnore = $eleSetIgnore.concat($eleSet);
						} else {
							$eleSets.push($eleSet);
						}
						$eleSet  = new Array();
					}
					if ($curr.is('p.submit')) break;
					$eleSet.push(curr);
				} else {
					// handle ingnore bag - works only before the first set is created
					var pushto = $eleSet;
					if ($eleSets.length == 0 && $curr.is("input[type='hidden']"))
					{
						pushto = $eleSetIgnore;
					}
					pushto.push(curr);
				}
			}

			// if the page has only one set, quit
			if ($eleSets.length < 2) return;

			// tabify
			formid = 'tabulator-tabs-form-' + f;
			jQuery($eleSetIgnore).filter(':last').after('<div id="' + formid + '"></div>');
			jQuery('#'+formid).prepend("<ul><\/ul>");
			var tabcounter = 0;
			jQuery.each($eleSets, function() {
				tabcounter++;
				id      = formid + '-tab-' + tabcounter;
				hash3   = true;
				h3probe = jQuery(this).filter('h3').eq(0);
				if (h3probe.is('h3')) {
					caption = h3probe.text();
				} else {
					hash3   = false;
					caption = jQuery('#wpbody .wrap h2').eq(0).text();
				}
				if (caption == ''){
					caption = 'FALLBACK';
				}
				tabdiv = jQuery(this).wrapAll('<span id="'+id+'"></span>');
				jQuery('#'+formid+' > ul').append('<li><a href="#'+id+'"><span>'+caption+"<\/span><\/a><\/li>");
				if (hash3) h3probe.hide();
			});
			jQuery('#'+formid+' > ul').tabs({
				select: function(e, ui) {
				jQuery('#wpbody .wrap form').attr("action", (jQuery('#wpbody .wrap form').attr("action")).split('#', 1) + '#' + ui.panel.id);
				}
			});
		});
	}

	/**
	 * inittialize tabs for breadcrumb navxt admin panel
	 */
	function bcn_admin_init_tabs_marketer()
	{
		jQuery('#hasadmintabs').prepend("<ul><\/ul>");
		jQuery('#hasadmintabs > fieldset').each(function(i)
		{
		    id      = jQuery(this).attr('id');
		    caption = jQuery(this).find('h3').text();
		    jQuery('#hasadmintabs > ul').append('<li><a href="#'+id+'"><span>'+caption+"<\/span><\/a><\/li>");
		    jQuery(this).find('h3').hide();
	    });
	    jQuery("#hasadmintabs > ul").tabs({
		    select: function(e, ui) {
			jQuery('#wpbody .wrap form').attr("action", (jQuery('#wpbody .wrap form').attr("action")).split('#', 1) + '#' + ui.panel.id);
			}
		});
	}
/* ]]> */
</script>
<?php
	} // admin_head()
} // class
?>