<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/*
Plugin Name: Cloud Accounts
Plugin URI: https://github.com/jesselsteele/CloudAccounts
Description: Uses WordPress edit user profile page to view usernames and other information for other web apps. Created for verb.ink.
Version: 1.0.0
Author: Jesse Steele
Author URI: http://jessesteele.com
Text Domain: cloudaccounts
Network: true
License: GPL2
*/

// global variables
$cloudaccounts_options = get_option('cloudaccountssettings');

// Set permissions for multisite use (these variables make it easier to modify the plugin for single site)
$cloudaccounts_checkif_admin = "manage_sites";
$cloudaccounts_checkif_user = "manage_options";

// change "New" to "Ink."


// admin bar links
add_action('admin_bar_menu', 'add_toolbar_items', 100);
function add_toolbar_items($admin_bar){
	$admin_bar->add_menu( array(
		'id'    => 'cloudaccountslogin-email',
		'title' => 'Mail',
		'href'  => 'https://mail.verb.ink',	
		'meta'  => array(
			'title' => __('Webmail login | mail.verb.ink'),
			'target' => __('_blank'),
		),
	));
	$admin_bar->add_menu( array(
		'id'    => 'cloudaccountslogin-cloud',
		'title' => 'Cloud',
		'href'  => 'https://cloud.verb.ink',	
		'meta'  => array(
			'title' => __('Cloud login | cloud.verb.ink'),
			'target' => __('_blank'),
		),
	));
}



// admin page
function cloudaccounts_admin_page(){
global $cloudaccounts_options;
ob_start();?>

<div class="wrap">
<form action="options.php" method="POST">

<?php settings_fields('cloudaccountsgroup'); ?>

<h1>Cloud Accounts</h1>

<!-- Network Admin -->
  <?php if ( current_user_can('manage_sites') ) { ?>

  <!-- Email -->
  <p>
  <h3>Email</h3>
  </p>
  <p>
  <h4>Email Address/Username/Login:</h4>
  </p>
  <input name="cloudaccountssettings[email_username]" type="text" maxlength="95" size="40" value="<?php echo $cloudaccounts_options['email_username']; ?>" />
  <h4>Temporary Password:</h4>
  <input name="cloudaccountssettings[email_password]" type="text" maxlength="95" size="40" value="<?php echo $cloudaccounts_options['email_password']; ?>" />
  
  <!-- ownCloud -->
  <p>
  <h3>ownCloud</h3>
  </p>
  <p>
  <h4>Username:</h4>
  </p>
  <input name="cloudaccountssettings[owncloud_username]" type="text" maxlength="95" size="40" value="<?php echo $cloudaccounts_options['owncloud_username']; ?>" />
  <h4>Temporary Password: (hidden once saved)</h4>
  <input name="cloudaccountssettings[owncloud_password]" type="text" maxlength="95" size="40" value="<?php echo $cloudaccounts_options['owncloud_password']; ?>" />
  <p>
  <input type="submit" class="button-primary" value="Save All">
  </p>
  </form>

<!-- Site Admin -->
  <?php
  } else {
  if ( current_user_can('manage_options') ) { ?>
  <!-- Unverified Account -->
  <p>
  <?php
  if (isset($cloudaccounts_options['email_username'], $cloudaccounts_options['owncloud_username'])) {
  echo 'This page lists usernames, login URLs, and useful information for your other web apps at verb.ink, such as email and ownCloud. These cannot be changed, but are listed here for your convenience. We hope to add more web apps for you as time goes on.';
  
  ?></p>

  <!-- Verified Account -->
  
  <!-- Email -->
  <p>
  <h3>Email</h3>
  </p>
  <p>
  <strong>Email Address/Username/Login: </strong>
  <?php if (empty($cloudaccounts_options['email_username'])) {
    echo '<em>No email.</em>'; ?></p><?php
  } else {
  echo $cloudaccounts_options['email_username']; ?></p><?php
  } ?>

  <!-- Shows password to user until cleared -->
  <?php if (empty($cloudaccounts_options['email_password'])) {
  }
  else {
  ?>
  <!-- This will hide the Temporary Password if the Superadmin removes it. User should be able to also, later in the roadmap -->
  <p>
  <strong>Temporary Password: </strong>
  <?php echo $cloudaccounts_options['email_password']; ?><br /><em>(This will be irrelevant once you login and change your password.)</em></p><?php

  } ?>
  
  <p>
  <strong>Login URL: </strong>
  <a target="_blank" href="https://mail.verb.ink">mail.verb.ink</a>
  <br />
  <em>(Login to use webmail, set email forwarding, and change your password.)</em>
  </p>

  <!-- Makes the email information clilckable with two div tags-->
<div 
    onclick="document.getElementById('email_info').style.display = document.getElementById('email_info').style.display == 'none' ? 'block' : 'none';"
>
  <p>
  <h4>[ Click to show email server information... ]</h4>
  </p>
    <div id="email_info" style="display: none;">
  
  
  <p>
  <em>This is for setting-up email with clients like Thunderbird, Outlook, Gmail, etc. Default settings should work when you enter email and password in the client email setup. Use these if default settings don't work:</em>
  </p>
  <br /><strong>Username: </strong> 
  <?php if (empty($cloudaccounts_options['email_username'])) {
    echo '<em>No email.</em><br />';
  } else {
  echo $cloudaccounts_options['email_username']; ?><br /><?php
  } ?>
  <strong>Password:</strong> <em>the same password for logging in to webmail</em><br />
  <br /><strong>IMAP</strong><br />
  <strong>Server hostname:</strong> imap.verb.ink<br /><strong>Port:</strong> 143<br /><strong>SSL:</strong> STARTTLS<br /><strong>Authentication:</strong> Normal password<br />
  <br /><strong>POP3</strong><br />
  <strong>Server hostname:</strong> pop3.verb.ink<br /><strong>Port:</strong> 110<br /><strong>SSL:</strong> STARTTLS<br /><strong>Authentication:</strong> Normal password<br />
  <br /><strong>SMTP</strong><br />
  <strong>Server hostname:</strong> smtp.verb.ink<br /><strong>Port:</strong> 465<br /><strong>SSL:</strong> STARTTLS<br /><strong>Authentication:</strong> Normal password<br />
  <p>
  <em>*About POP3 and IMAP: POP3 pulls email from the server and deletes mail from the server if you configure your client to do so. IMAP will sync email between your client and the server, keeping a copy of your email in both places.</em>
  </p>
  <p>
  <em>If you are using a $1 subscription, then your inbox is very small and you should either:</em>
  </p>
  <p>
  <em>A. set up at least one email client with POP3 to keep the email off the server so your Inbox doesn't get full or</em>
  </p>
  <p>
  <em>B. login to webmail at <a target="_blank" href="https://mail.verb.ink">mail.verb.ink</a>, go to settings, and set up a forwarding alias email address that your email will go to and uncheck the box so that email does not stay on the server. (This is probably the best option, you can still send mail using SMTP.)</em>
  </p>
  <p>
  <em>If you have a $2 subscription, then we will allow your Inbox to slowly take more email up to 10GB (not all at once, you must take at least three years to max out or we'll want to know why.) We may increase this even more, several years into the future. With this, you can use IMAP on your email clients, which will sync your email with the server, you can then view all your email on the webserver and any email clients that you set up, and space won't be as big of a problem. Even after 10 years a lots of email, you probably won't even fill up 5GB. If you don't plan to receive email at your verb.ink email address, then the $1 subscription should be fine and you can still use it to send email via SMTP.</em>
  </p>
  
</div>
</div>
  
  <!-- ownCloud -->  
  <p>
  <h3>ownCloud</h3>
  </p>
  <p>
  <strong>Username: </strong>
  <?php if (empty($cloudaccounts_options['owncloud_username'])) {
    echo '<em>No ownCloud account.</em>'; ?></p><?php
  } else {

  echo $cloudaccounts_options['owncloud_username']; ?></p><?php
  } ?>


  <!-- Shows password to user until cleared -->
  <?php if (empty($cloudaccounts_options['owncloud_password'])) {
  }
 else {
  ?>
  <!-- This will hide the Temporary Password if the Superadmin removes it. User should be able to also, later in the roadmap -->
  <p>
  <strong>Temporary Password: </strong>
  <?php echo $cloudaccounts_options['owncloud_password']; ?><br /><em>(This will be irrelevant once you login and change your password.)</em></p><?php

  } ?>
  
  
  <p>
  <strong>Login URL: </strong>
  <a target="_blank" href="https://cloud.verb.ink">cloud.verb.ink</a>
  </p>  

<?php
  } else {
    echo 'Your account is not set up yet. We are still verifying whether you are a non-spamming human. When finished, this page will list usernames, login URLs, and useful information for your other web apps at verb.ink, such as email and ownCloud.';
 } ?>


<!-- Pondscum -->
<!-- This will probably never display, but, we never know. In case a non-admin somehow accesses this, he'll get a good laugh.-->
<?php
  } else { ?>
    <p>
    <h3>Her Magesty, the Queen, has not granted you permission. Now be ye off!</h3>
    </p>
  <?php }
  }
  ?>
<!-- End of Network/Site/Pondscum differences -->  

<?php

echo ob_get_clean();
}

// admin tab

function cloudaccounts_tab(){

add_options_page('Cloud Accounts','Cloud Accounts','manage_options','cloud-accounts','cloudaccounts_admin_page');
}
add_action('admin_menu','cloudaccounts_tab');

// notice to user (first notice that the user's account is approved)

// ** Check to see if ownCloud and email have been setup, otherwise the notice won't be activated
if (isset($cloudaccounts_options['email_username'], $cloudaccounts_options['owncloud_username'])) {


add_action('admin_notices', 'approved_admin_notice');

function approved_admin_notice() {
	global $current_user ;
        $user_id = $current_user->ID;
        /* Check that the user hasn't already clicked to ignore the notice */
	if ( ! get_user_meta($user_id, 'approved_ignore_notice') ) {
        echo '<div class="updated"><p>'; 
        printf(__('Congratulations! We decided that you are a normal human. You have email, SMTP, ownCloud... the works. Go to > Settings > <a href="options-general.php?page=cloud-accounts">Cloud Accounts</a> to get the scoop... <a href="%1$s">Coolio! Get rid of this.</a>'), '?approved_nag_ignore=0');
        echo "</p></div>";
	}
}

add_action('admin_init', 'approved_nag_ignore');

function approved_nag_ignore() {
	global $current_user;
        $user_id = $current_user->ID;
        /* If user clicks to ignore the notice, add that to their user meta */
        if ( isset($_GET['approved_nag_ignore']) && '0' == $_GET['approved_nag_ignore'] ) {
             add_user_meta($user_id, 'approved_ignore_notice', 'true', true);
	}
}
}



// register settings

function cloudaccounts_settings(){

register_setting('cloudaccountsgroup','cloudaccountssettings');

}
add_action('admin_init','cloudaccounts_settings');
