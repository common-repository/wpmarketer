<?php

// include class
require_once("lib/nusoap.php");

$wpa_cj_developer_key = get_option("wpa_cj_developer_key");
$wpa_cj_website_id = get_option("wpa_cj_website_id");

if($wpa_cj_developer_key=="" || $wpa_cj_website_id=="")
{
	echo "You must enter a Website ID and a Developer Key on the options page to continue.";
}
else
{

?>
<style type="text/css">
.product {
	margin-bottom:20px;
}	
</style>
<form method="post">
<table class="form-table">
					<tr valign="top">
						<th align="left">CJ Product Search</th>
						<td><input type="text" name="cjquery" /><br />
						Enter the product you'd like to search for.<br /><br />
						
						<input type="text" name="lowprice" /><br />
						(Optional) Enter the lowest price the product you're searching for could possibly be.<br /><br />
						
						<input type="text" name="highprice" /><br />
						(Optional) Enter the highest price the product you're searching for could possibly be.<br /><br />						
						
						<select name="maxresults">
							<?
							for($i=0;$i<100;++$i)
							{
								$imaxresults = ($i+1)*10;
								echo "<option value=\"".$imaxresults."\">".$imaxresults."</option>";
							}
							
							?>
						</select>
						Select the maximum number of results you would like returned from CJ.
						
						
						</td>
					</tr>
				</table>
<div class="submit"><input type="submit" name="updateoption" value="Search &raquo;"/></div>
</form>
<br /><br />
<?

	if(isset($_POST['cjquery']))
	{
	
	
	$query = "+".str_replace(" "," +",$_POST['cjquery']);
	
	// parameters defined                     
	$advertiserIds = "joined";
	$keywords  = $query;
	$serviceableArea = "";
	$upcOrIsbnOrEan = "";
	$manufacturerName = "";
	$advertiserSku = "";
	$lowPrice = $_POST['lowprice'];
	$highPrice = $_POST['highprice'];
	$currency = "USD";
	$sortBy = "";
	$orderIn = "";
	$startAt = 0;
	$maxResults = $_POST['maxresults'];
	
	
	 $params = array(
	                "developerKey" => $wpa_cj_developer_key,
	                "websiteId" => $wpa_cj_website_id,
	                "advertiserIds" => $advertiserIds,//'joined',
	                "keywords"  => $keywords,
	                "serviceableArea" => $serviceableArea,
	                "upc" => $upc,
	                "manufacturerName" => $manufacturerName,
	                "manufacturerSku" => $manufacturerSku,
	                "advertiserSku" => $advertiserSku,
	                "lowPrice" => $lowPrice,
	                "highPrice" => $highPrice,
	                "lowSalePrice" => $lowSalePrice,
	                "highSalePrice" => $highSalePrice,
	                "currency" => $currency,
	                "isbn" => $isbn,
	                "sortBy" => $sortBy,//Price
	                "sortOrder" => $sortOrder,
	                "startAt" => $startAt, //int
	                "maxResults" => $maxResults //int
	                );
	                
	                $client = new soapclient("https://api.cj.com/wsdl/version2/productSearchServiceV2.wsdl",true); //enable wsld
	              
	                 $result = $client->call("search",array('parameters' => $params));
	
	
	
		if(isset($result['faultcode']))
		{
			print_r($result);
		}	
		else if($result['out']['count']==0)
		{
			echo "No Results For ".$_POST['cjquery'];
		}
		else
		{
		$products = $result['out']['products']['Product'];
		
		$pi = 1;
		foreach($products as $product)
		{
			echo $pi.". <div class=\"product\">
			
			<table>";
			
			if(isset($product['imageUrl']))
			{			
			echo "
			<tr><td colspan=\"2\" align=\"center\">
			Image URL:<br/>
			
			<img src=\"".$product['imageUrl']."\"><br />
			<input type=\"text\" value=\"".$product['imageUrl']."\" size=\"40\">	
			</td></tr>";
		}
			echo "					
			<tr><td align=\"right\">
			Name:
			</td><td>
			".$product['name']."	
			</td></tr>		
			<tr><td align=\"right\">
			URL:
			</td><td>
			<input type=\"text\" value=\"".$product['buyUrl']."\" size=\"40\">
			</td></tr>
			<tr><td align=\"right\">
			Store:
			</td><td>
			".$product['advertiserName']."
			</td></tr>
			<tr><td align=\"right\">
			Price:
			</td><td>
			".$product['price']."	
			</td></tr>
			<tr><td align=\"right\">
			Sale Price:
			</td><td>
			".$product['salePrice']."						
			</td></tr></table>
			</div>";
			
			$pi++;
			
		}
	}
	
	}
}
 
?>
