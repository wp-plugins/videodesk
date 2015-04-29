<?php
	/*
	Plugin Name: VideoDesk plugin
  Plugin URI: http://www.videodesk.com
	Description: Offer face-to-face customer service on your website via text, audio or video chat.
	Author: VideoDesk Team + Zihad D.
	Version: 1.1
  Text Domain: videodesk_plugin
	*/

function ap_action_init()
{
//i18n
load_plugin_textdomain('videodesk_plugin', false, dirname(plugin_basename(__FILE__)).'/lang/');
}

// general init
add_action('init', 'ap_action_init');
/* plugin admin panel */

// create custom plugin menu
add_action('admin_menu', 'create_admin_menu');

$wp_version=get_bloginfo('version');
;
$image = "";

if ( $wp_version >= 3.8 ) {
 $image = "vd-grey";
}else{
  $image = "vd-icon";
}


function create_admin_menu() {

//check wordpress version
$version=get_bloginfo('version');
$image = "";

if ( $version >= 3.8 ) {
 $image = "vd-grey";
}else{
  $image = "vd-icon";
}

	//create menu page
	add_menu_page('Videodesk Plugin settings', 'Videodesk', 'list_users', __FILE__, 'videodesk_settings_page',plugins_url('/img/'.$image.'.gif', __FILE__));

	//call register settings function
	add_action( 'admin_init', 'register_settings' );
}


function register_settings() {
	//register settings
	register_setting( 'vd-settings-group', 'uid' );
	register_setting( 'vd-settings-group', 'display_option' );
}

function videodesk_settings_page() {
	//create the form for user to insert parameters
?>
<div class="wrap">
<h2><?php _e('Videodesk parameters','videodesk_plugin') ?></h2>

<form method="post" action="options.php">
    <?php settings_fields( 'vd-settings-group' ); ?>
    <?php do_settings_sections( 'vd-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Videodesk Website ID</th>
        <td><input type="text" size="40" name="uid" value="<?php echo get_option('uid'); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"></th>
        <td style="color:grey;font-style:italic"><?php _e('Already registered ? Your Videodesk Website ID is available in the Videodesk back-office (<a href="http://my.videodesk.com">my.videodesk.com</a>), in the "Website > Set up > Installation code" menu. <br>Not registered? Please go to <a href="http://www.videodesk.com">videodesk.com</a> and sign up for free in 2 minutes and get your Videodesk Website ID.','videodesk_plugin')?></td>
        </tr>
        
         
        <tr valign="top">
        <th scope="row"><?php _e('Display Videodesk','videodesk_plugin')?></th>
        <br>
        <td><input type="radio" name="display_option" value="1" <?php if(get_option('display_option') == 1 ) echo ' checked="checked"  ' ; ?>  /><?php _e('activated','videodesk_plugin')?> &nbsp;
       <input type="radio" name="display_option" value="0" <?php if(get_option('display_option') == 0 ) echo ' checked="checked" ' ; ?> /><?php _e('deactivated','videodesk_plugin')?> </td>
        </tr>
    </table>
    
    <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes','videodesk_plugin')?>"  /></p>

    <?php if (get_option('uid') == ""){
    	echo "<span style='color:red;font-weight:bold'>".__('Please enter your Videodesk Website ID','videodesk_plugin')."</span><br>" ;
    }

    if (get_option('display_option') == ""){
    	echo "<span style='color:red;font-weight:bold'>".__('Veuillez indiquer si vous souhaitez afficher le module','videodesk_plugin')."</span>" ;
    }

   ?>

</form>
</div>
<?php } ?>
<?php

add_action('wp_head', 'insert_script');

    function insert_script(){

      $lang = get_locale();
      $paramLang = substr($lang,0,2);
      $comment= get_comment_ID();
      $name = $comment == NULL ? '' : get_comment_author($comment);
      $website = get_comment_author_url();
      $email = get_comment_author_email();

      if($paramLang == 'ja') {
       $paramLang = 'jp' ;
      }


    	if (get_option('display_option') == "1"){ //if user selected to display the module in the parameters, insert the script
    	
    	echo " <script type='text/javascript'>
    
     var _videodesk= _videodesk || {};
      _videodesk['firstname'] = '' ;
      _videodesk['lastname'] = '".$name.$comment.  "' ;
      _videodesk['company'] = '".$website."' ;
      _videodesk['email'] = '".$email."' ;
      _videodesk['phone'] = '' ;
      _videodesk['customer_lang'] = '' ;
      _videodesk['customer_id'] = '' ;
      _videodesk['customer_url'] = '' ;
      _videodesk['cart_id'] = '' ;
      _videodesk['cart_url'] = '' ;
      _videodesk['order_id'] = '' ;
      _videodesk['order_url'] = '' ;
      _videodesk['module'] = 'wordpress' ; 
      _videodesk['module_version'] = '1.0' ;
      _videodesk['display'] = 'on' ;
      _videodesk['localstorage'] = 1;

      _videodesk['uid'] = '".get_option('uid')."' ;

    	_videodesk['lang'] = '".$paramLang."' ;
    
     (function() {
	var videodesk = document.createElement('script'); videodesk.type = 'text/javascript'; videodesk.async = true;
	videodesk.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'module-videodesk.com/js/videodesk.js';	    
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(videodesk, s);
	 })();
	 
    </script>";

    }
}
  ?>



