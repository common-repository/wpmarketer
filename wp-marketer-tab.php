<?php
require_once("tabulator_navigation.php");
?><link rel="stylesheet" href="<? echo get_bloginfo('wpurl'); ?>/wp-content/plugins/wp-marketer/wp-marketer.css" /><?
class bcn_admin1
{

	function admin_panel1()
	{
		//$this->marketer_conf();
		//if($this->key_status_val=="valid")
		//{
			?>
			<h2><?php _e('WPMarketer', ''); ?></h2>
			<form method="post" id="bcn_admin_options">
				<div id="hasadmintabs">
				<fieldset id="1" class="bcn_options">
					<h3>Settings</h3>
						<?
						echo "<h2 class='subtitle'>Settings</h2>";
						require_once ("wp-marketer-aic.php"); ?>
				</fieldset>
				<fieldset id="2" class="bcn_options">
					<h3>Manage Links</h3>
					<?
					echo "<h2 class='subtitle'>Manage WPM Links</h2>";
					require_once ("wp-marketer-mwl.php"); ?>
				</fieldset>
				<fieldset id="3" class="bcn_options">
					<h3>Tracking</h3>
					<? wp_marketer_statistics_page(); ?>
				</fieldset>
				</div>

			</form>
			<?php
		//}
	}

	function marketer_conf() 
	{
		global $wpdb,$wp_marketer_prefix;
		$table_name = $wpdb->prefix . $wp_marketer_prefix."_link_apikey";
		$this->marketer_api_port = 80;
		$this->wpcom_api_key = '';
		global $marketer_nonce;
		if ( isset($_POST['submitAPIKey']) )
		{
			$key = preg_replace( '/[^a-h0-9]/i', '', $_POST['key'] );

			if ( empty($key) )
			{
				$key_status = 'empty';
				$ms[] = 'new_key_empty';
				//delete_option('wordpress_api_key');
				delete_option('wordpress_marketer_api_key');
			} else {
				$key_status = $this->marketer_verify_key( $key );
			}

			if ( $key_status == 'valid' ) 
			{
				//update_option('wordpress_api_key', $key);
				update_option('wordpress_marketer_api_key', $key);
				$ms[] = 'new_key_valid';
			} else if ( $key_status == 'invalid' ) {
				$ms[] = 'new_key_invalid';
			} else if ( $key_status == 'failed' ) {
				$ms[] = 'new_key_failed';
			}
		}

			if ( $key_status != 'valid' ) {
				//$key = get_option('wordpress_api_key');
				$key = get_option('wordpress_marketer_api_key');
				if ( empty( $key ) ) {
					if ( $key_status != 'failed' ) {
						if ( $this->marketer_verify_key( '1234567890ab' ) == 'failed' )
							$ms[] = 'no_connection';
						else
							$ms[] = 'key_empty';
					}
					$key_status = 'empty';
				} else {
				$key_status = $this->marketer_verify_key( $key );
				}
				if ( $key_status == 'valid' ) {
					$ms[] = 'key_valid';
				} else if ( $key_status == 'invalid' ) {
					//delete_option('wordpress_api_key');
					delete_option('wordpress_marketer_api_key');
					$ms[] = 'key_empty';
				} else if ( !empty($key) && $key_status == 'failed' ) {
					$ms[] = 'key_failed';
				}
			}
			$this->key_status_val=$key_status;
			$sql = "update ".$table_name." set api_key_status='".$wpdb->escape($key_status)."'";
			$wpdb->query($sql);
			$messages = array(
				'new_key_empty' => array('color' => 'aa0', 'text' => __('Your key has been cleared.')),
				'new_key_valid' => array('color' => '2d2', 'text' => __('Your key has been verified.!')),
				'new_key_invalid' => array('color' => 'd22', 'text' => __('The key you entered is invalid. Please double-check it.')),
				'new_key_failed' => array('color' => 'd22', 'text' => __('The key you entered could not be verified because a connection to akismet.com could not be established. Please check your server configuration.')),
				'no_connection' => array('color' => 'd22', 'text' => __('There was a problem connecting to the Akismet server. Please check your server configuration.')),
				'key_empty' => array('color' => 'aa0', 'text' => sprintf(__('Please enter an API key. (<a href="%s" style="color:#fff">Get your key.</a>)'), 'http://wordpress.com/profile/')),
				'key_valid' => array('color' => '2d2', 'text' => __('This key is valid.')),
				'key_failed' => array('color' => 'aa0', 'text' => __('The key below was previously validated but a connection to akismet.com can not be established at this time. Please check your server configuration.')));
			?>
		<?php if ( !empty($_POST ) ) : ?>
		<div id="message" class="updated fade"><p><strong><?php _e('Options saved.') ?></strong></p></div>
		<?php endif; ?>
		
		<h2><?php _e('Marketer Configuration'); ?></h2>
		<form action="" method="post">
		<?php if ( !$this->wpcom_api_key ) { ?>
			<p><?php printf(__('If you don\'t have a WordPress.com account yet, you can get one at <a href="%2$s">WordPress.com</a>.'), 'http://akismet.com/', 'http://wordpress.com/api-keys/'); ?></p>

		<h3><label for="key"><?php _e('WordPress.com API Key'); ?></label></h3>
		<?php foreach ( $ms as $m ) : ?>
			<p style="padding: .5em; background-color: #<?php echo $messages[$m]['color']; ?>; color: #fff; font-weight: bold;"><?php echo $messages[$m]['text']; ?></p>
		<?php endforeach; ?>
		<p><input id="key" name="key" type="text" size="15" maxlength="12" value="<?php echo get_option('wordpress_marketer_api_key'); ?>" style="font-family: 'Courier New', Courier, mono; font-size: 1.5em;" /> (<?php _e('<a href="http://faq.wordpress.com/2005/10/19/api-key/">What is this?</a>'); ?>)</p>
		<?php if ( $invalid_key ) { ?>
		<h3><?php _e('Why might my key be invalid?'); ?></h3>
		<p><?php _e('This can mean one of two things, either you copied the key wrong or that the plugin is unable to reach the Akismet servers, which is most often caused by an issue with your web host around firewalls or similar.'); ?></p>
		<?php } ?>
		<?php } ?>
		<p class="submit"><input type="submit" name="submitAPIKey" value="<?php _e('Update options &raquo;'); ?>" /></p>
		</form>
		<?php
	}
	
	function marketer_verify_key( $key ) 
	{
		$blog = urlencode( get_option('home') );
		if ( $this->wpcom_api_key )
			$key = $this->wpcom_api_key;
		$response = $this->marketer_http_post("key=$key&blog=$blog", 'rest.akismet.com', '/1.1/verify-key', $this->marketer_api_port);
		if ( !is_array($response) || !isset($response[1]) || $response[1] != 'valid' && $response[1] != 'invalid' )
			return 'failed';
		return $response[1];
	}

	function marketer_http_post($request, $host, $path, $port = 80)
	{
		global $wp_version;

		$http_request  = "POST $path HTTP/1.0\r\n";
		$http_request .= "Host: $host\r\n";
		$http_request .= "Content-Type: application/x-www-form-urlencoded; charset=" . get_option('blog_charset') . "\r\n";
		$http_request .= "Content-Length: " . strlen($request) . "\r\n";
		$http_request .= "User-Agent: WordPress/$wp_version | Akismet/2.0\r\n";
		$http_request .= "\r\n";
		$http_request .= $request;

		$response = '';
		if( false != ( $fs = @fsockopen($host, $port, $errno, $errstr, 10) ) ) {
			fwrite($fs, $http_request);

			while ( !feof($fs) )
				$response .= fgets($fs, 1160); // One TCP-IP packet
			fclose($fs);
			$response = explode("\r\n\r\n", $response, 2);
		}
		return $response;
	}
}
$bcn_admin = new bcn_admin1;
$bcn_admin->admin_panel1();
?>