<?php
global $wpdb,$wp_marketer_prefix;
$wpAdminSection=strpos($_SERVER['REQUEST_URI'],"wp-marketer-admin-functions.php");
$category_form = "
<h2 class='subtitle'>Add Top Level Category</h2>
<div id=\"wp_marketer_add_category_results\" style=\"color:red;\"></div>
<form><table>
<tr><!--<td align=\"right\">
<b>Category Name:</b>
</td>--><td>
<input type=\"text\" name=\"wp_marketer_add_new_category\">
</td><td align=\"right\"><input type=\"button\" value=\"Add Top Level Category\" onclick=\"wp_marketer_ajax_add_category(this.form.wp_marketer_add_new_category,this.form.wp_marketer_category_parent,'".$wpAdminSection."');\" ></td></tr></table>
<input type=\"hidden\" name=\"wp_marketer_category_parent\" value=\"0\">
<!--<input type=\"button\" value=\"Add Top Level Category\" onclick=\"wp_marketer_ajax_add_category(this.form.wp_marketer_add_new_category,this.form.wp_marketer_category_parent);\" >-->
</form>";

echo $category_form;

echo "
<h2 class='subtitle'>WPMarketer Categories and Links</h2>
<div id=\"wp_marketer_category_list\">
";

$category_list = get_category_list_marketer();

if($category_list)
	echo get_category_list_marketer();
else
	echo "<ul id=\"wpanav\"><ul>";//"No categories! Add at least one in order to add links!";

echo "
</div>";
?>
