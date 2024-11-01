<?php 

/*
Plugin Name: Zoho CRM Data Post
Author: BBKP
Plugin URL: http://bibunkaplan.com
Description:It's possible to post the data From WP to Zoho CRM 
Verison: 1.0
Author URL: http://bibunkaplan.com
Domain Path /zoho-data-post
Text Domain: zoho-data-post
*/

add_action( 'admin_menu', 'zoho_data_post_admin_menu' );

function zoho_data_post_admin_menu() {
	
	add_options_page(
		__('Zoho CRM Data Post', 'zoho-data-post'), 
		__('Zoho CRM Data Post', 'zoho-data-post'), 
		'manage_options', 
		'zoho-data-post', 
		'zoho_data_post'
	); 

}

function zoho_data_post() {
?>

<div class="wrap">
<h2>Zoho CRM Data Post</h2>
<form id="zoho-data-post" method="post" action="">
	<?php wp_nonce_field( 'zoho-data-post-nonce-key', 'zoho-data-post' ); ?>

	
	<p><?php echo esc_html(__( 'Zoho CRM API Key', 'zoho-data-post' )); ?>:
	<input type="text" name="oauth-key" value="<?php echo esc_attr(get_option('oauth-key')); ?>" size="85"/></p>
	<p><a href="https://www.zoho.com/crm/help/api/" target="_blank">What's Zoho CRM API?</a></p>
	
	
	<select name="tab-zoho">
		<option value="leads" <?php selected($_POST['tab-zoho'], 'leads');?> >Leads</option>
		<option value="contacts" <?php selected($_POST['tab-zoho'], 'contacts');?> >Contact</option>
	</select>
	
	<p><?php echo esc_html(__( '*First Name', 'zoho-data-post' )); ?>:
	<input type="text" name="first-name-zoho" value="<?php echo esc_attr(get_option('first-name-zoho')); ?>" size="30"/></p>	
	
	<p><?php echo esc_html(__( '*Last Name', 'zoho-data-post' )); ?>:
	<input type="text" name="last-name-zoho" value="<?php echo esc_attr(get_option('last-name-zoho')); ?>" size="30"/></p>	
	
	<p><?php echo esc_html(__( 'Email', 'zoho-data-post' )); ?>:
	<input type="text" name="zoho-email-zoho" value="<?php echo esc_attr(get_option('zoho-email-zoho')); ?>" size="30"/></p>	
	
	<p><?php echo esc_html(__( 'Company', 'zoho-data-post' )); ?>:
	<input type="text" name="company-zoho" value="<?php echo esc_attr(get_option('company-zoho')); ?>" size="30"/></p>	
	
	
	<p><input type="submit" value="<?php echo esc_attr(__( 'Post', 'zoho-data-post' ));?>" class="button button-primary button-large"></p>
	
</form>
</div>


<?php
}

//Save the data
add_action( 'admin_init', 'my_admin_init_zoho');

function my_admin_init_zoho() {
	if ( isset( $_POST['zoho-data-post']) && $_POST['zoho-data-post'] ) {
		$e = new WP_Error(); 
		
		
		if (check_admin_referer( 'zoho-data-post-nonce-key', 'zoho-data-post' ) ) {
			//oauth-key
			//$e = new WP Error(); 
			
			if ( isset ($_POST['oauth-key'] ) && $_POST['oauth-key'] ) {
				update_option( 'oauth-key', sanitize_text_field( $_POST['oauth-key'] ) ); 
			} else {
				update_option( 'oauth-key', ''); 
				$e->add(
						'error', 
						__('Please enter Zoho CRM Key.', 'zoho-data-post' )
					); 
					set_transient( 'my-custom-admin-errors', $e->get_error_messages(), 10 ); 
			}
			
			if ( isset ($_POST['last-name-zoho'] ) && $_POST['last-name-zoho'] ) {
				update_option( 'last-name-zoho', sanitize_text_field( $_POST['last-name-zoho'] ) ); 
												
			} else {
				update_option( 'last-name-zoho', ''); 
				$e->add('error', 
					__('Please enter into the Last name.', 'zoho-data-post')
				
				); 
				set_transient( 'my-custom-admin-errors-zoho', $e->get_error_messages(), 10 ); 
				
			}
			
			if ( isset ($_POST['first-name-zoho'] ) && $_POST['first-name-zoho'] ) {
				update_option( 'first-name-zoho', sanitize_text_field( $_POST['first-name-zoho'] ) ); 
			} else {
				update_option( 'first-name-zoho', ''); 
				$e->add('error', 
					__('Please enter into the First name.', 'zoho-data-post')
				
				); 
				set_transient( 'my-custom-admin-errors-zoho', $e->get_error_messages(), 10 ); 
			}
			
			//email
			if ( isset ($_POST['zoho-email-zoho'] ) && $_POST['zoho-email-zoho'] ) {
				if (is_email( sanitize_email( $_POST['zoho-email-zoho'] ) ) ){
					update_option( 'zoho-email-zoho',  sanitize_email( $_POST['zoho-email-zoho'] )  ); 
				} else {
					$e->add(
						'error', 
						__('Please enter a valid email address.', 'zoho-data-post' )
					); 
					set_transient( 'my-custom-admin-errors-zoho', $e->get_error_messages(), 10 ); 
				}
			} else {
				update_option( 'zoho-email-zoho', ''); 
			}
			
			
			if ( isset ($_POST['company-zoho'] ) && $_POST['company-zoho'] ) {
				update_option( 'company-zoho', sanitize_text_field( $_POST['company-zoho'] ) ); 
			} else {
				update_option( 'company-zoho', ''); 
			}
			
			wp_safe_redirect( menu_page_url( 'oauth-key', false) ); 
			
			
		}
	}

}
 
//エラーの表示
add_action( 'admin_notices', 'my_admin_notices_zoho' );

function my_admin_notices_zoho() {
?>
	<?php if ( $messages = get_transient( 'my-custom-admin-errors-zoho' ) ): ?>
	<div class="error">
		<ul>
			<?php foreach( $messages as $message ) :?>
				<li><?php echo esc_html($message); ?></</li>
			<?php endforeach; ?>
		</</ul>
	
	</div>
	
	<?php endif; ?>
<?php 
}


//データの出力

add_action( 'admin_init', 'my_zoho_crm_api' );

function my_zoho_crm_api() {
	$last  = get_option( 'last-name-zoho' ); 
	$first = get_option( 'first-name-zoho' ); 
	$email = get_option( 'zoho-email-zoho' ); 
	$company = get_option( 'company-zoho' );  
	$select = $_POST['tab-zoho']; 
		
	
	$xml = '<?xml version="1.0" encoding="UTF-8"?>
       <Contacts>
		<row no="1">
		<FL val="First Name">'.$first.'</FL>
		<FL val="Last Name">'.$last.'</FL>
		<FL val="Email">'.$email.'</FL>
		<FL val="Company">'.$company.'</FL>
		</row>
	</Contacts>'; 

	$auth= get_option( 'oauth-key' ); 
	
	if ($select == 'leads') {
		$url ="https://crm.zoho.com/crm/private/xml/Leads/insertRecords";
	} else {
		$url ="https://crm.zoho.com/crm/private/xml/Contacts/insertRecords";
	}

	
	$query="authtoken=".$auth."&scope=crmapi&xmlData=".$xml;
	
	//var_dump($query); 
	
	$ch = curl_init();
   
	//echo $query; 
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $query);


	curl_exec($ch);
	curl_close($ch);

	
	//データの削除
	
	if(!get_transient( 'my-custom-admin-errors-zoho' ) && $_POST['zoho-data-post']) {
		
		delete_option('last-name-zoho'); 
		delete_option('first-name-zoho'); 
		delete_option('zoho-email-zoho'); 
		delete_option('company-zoho'); 
		echo '<div class="updated"><ul><li>the Post to Zoho CRM!</li></ul></div>' ; 

	}	
	

}






