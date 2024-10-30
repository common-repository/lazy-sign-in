<?php 
if($_POST["submit-login"] && wp_verify_nonce($_POST['_wpnonce'])){
    $login_heading_text=sanitize_text_field($_POST['lsi_login_heading_text']);
	   update_option( 'lsi_login_heading_text', $login_heading_text );
  if(!empty($_POST['lsi_login_redirect_link'])){
	 $login_redirect = esc_url($_POST['lsi_login_redirect_link']);
    if ($login_redirect !== false){
      $login_redirect_url=esc_url($_POST['lsi_login_redirect_link']);
      update_option( 'lsi_login_redirect_link', $login_redirect_url );
    }else{
      $error_login = 'Invalid redirection link after success '.$_POST['lsi_login_redirect_link'];
    }
  }else{
    $login_redirect_url=esc_url($_POST['lsi_login_redirect_link']);
    update_option( 'lsi_login_redirect_link', $login_redirect_url );
  }
}
if($_POST["submit"] && wp_verify_nonce($_POST['_wpnonce']))
{
    $signup_heading_text=sanitize_text_field($_POST['lsi_signup_heading_text']);
    update_option( 'lsi_signup_heading_text', $signup_heading_text );
  if(!empty($_POST['lsi_signup_redirect_link'])){
    $redirect_url = esc_url($_POST['lsi_signup_redirect_link']);
    if ($redirect_url !== false){
        $signup_redirect_url=esc_url($_POST['lsi_signup_redirect_link']);
       update_option( 'lsi_signup_redirect_link', $signup_redirect_url );
      }else{
      $error_signup_success = 'Invalid redirection link after success';
    }
  }
    else{
      $signup_redirect_url=esc_url($_POST['lsi_signup_redirect_link']);
     update_option( 'lsi_signup_redirect_link', $signup_redirect_url );
    }
  if(!empty($_POST['lsi_login_link'])){
    $login_url = esc_url($_POST['lsi_login_link']);
    if ($login_url !== false){
        $signup_login_link=esc_url($_POST['lsi_login_link']);
        update_option( 'lsi_login_link', $signup_login_link );
      }else{
      $error_login_link = 'Invalid login link '.$_POST['lsi_login_link'];
    }
  }
    else{
      $signup_login_link=esc_url($_POST['lsi_login_link']);
      update_option( 'lsi_login_link', $signup_login_link );
    }
  	update_option( 'lsi_signup_role', sanitize_text_field($_POST['lsi_signup_role']) );
	if($_POST['lsi_generate_username']!=""){
		update_option( 'lsi_generate_username', '1' );
	}else{
		update_option( 'lsi_generate_username', '0' );
	}
	if($_POST['lsi_generate_password']!=""){
		update_option( 'lsi_generate_password', '1' );
	}else{
		update_option( 'lsi_generate_password', '0' );
	}
	$extra_fields = array();
  $i=0;
  
	foreach ($_POST['field'] as $key => $value){
    if($_POST['field'][$key]!=""):
	    $extra_fields[$i][] = sanitize_text_field($value);
      if(isset($_POST['require'])){
  	    if(in_array($i+1, $_POST['require'])) {
  	    	$extra_fields[$i][] = '1';
  	    }else {
          $extra_fields[$i][] = '0';
        }
      }else {
        $extra_fields[$i][] = '0';
      }

      if(isset($_POST['unique'])){
  	    if(in_array($i+1, $_POST['unique'])) {
  	    	$extra_fields[$i][] = '1';
    	  }else {
          $extra_fields[$i][] = '0';
        }
      }else {
        $extra_fields[$i][] = '0';
      }

	    $i++;
    endif;
	}
	update_option( 'lsi_extra_fields', $extra_fields );
}
$lsi_login_heading_text = get_option("lsi_login_heading_text");
$lsi_signup_heading_text = get_option("lsi_signup_heading_text");
$lsi_login_redirect_link = get_option("lsi_login_redirect_link");
$lsi_signup_redirect_link = get_option("lsi_signup_redirect_link");
$lsi_login_link = get_option("lsi_login_link");
$lsi_signup_role = get_option("lsi_signup_role");
$lsi_generate_username = get_option("lsi_generate_username");
$lsi_generate_password = get_option("lsi_generate_password");
$new_fields = get_option('lsi_extra_fields');
?>

<div class="lazy_sign_in_login-wrap nosubsub">
   <div class="lazy_sign_in_login-tabs clearfix">
      <div class="lazy_sign_in_login-sidebar">
         <div class="lazy_sign_in_login-logo"> <img src="<?php echo LAZY_SIGN_IN_PLUGIN_URL; ?>images/logo.png" class="logo-large" alt=""/> <img src="<?php echo LAZY_SIGN_IN_PLUGIN_URL; ?>images/logo-sm.png" class="logo-sm" alt=""/> </div>
         <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab"> <i class="fa fa-lock"></i><?php _e( 'Login Settings', 'lazy-sign-in' );?></a></li>
            <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab"> <i class="fa fa-user-plus"></i> <?php _e( 'Sign up settings', 'lazy-sign-in' );?></a></li>
         </ul>
      </div>
      <div class="lazy_sign_in_login-content">
         <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="home">
               <div class="lazy_sign_in_login-content-form">
                  <h2> <?php _e( 'Log In Form Settings', 'lazy-sign-in' ); ?></h2>
                  <form class="validate" method="post" id="addtag" autocomplete="off">
                  <?php wp_nonce_field(); ?>
                     <div class="input-group floating">
                        <input type="text" id="lsi_login_heading_text" class="validate" name="lsi_login_heading_text" value="<?php echo $lsi_login_heading_text; ?>">
                        <label for="lsi_login_heading_text"><?php _e( 'Page Heading', 'lazy-sign-in' ); ?></label>
                     </div>
                     <div class="input-group floating">
                        <input type="text" id="lsi_login_redirect_link" class="validate" name="lsi_login_redirect_link" value="<?php echo $lsi_login_redirect_link; ?>">
                        <label for="lsi_login_redirect_link"> <?php _e( 'Redirection link after success', 'lazy-sign-in' ); ?></label>
                        <?php if(!empty($error_login)){?>
                          <div class="error"><?php printf(__( '%s.', 'my-plugin' ),$error_login); ?></div>
                          <?php } ?>
                     </div>
                     <div class="input-group">
                        <input type="submit" value="<?php _e( 'Save Settings', 'lazy-sign-in' );?>" class="save-setting-btn" id="submit-login" name="submit-login"/>
                        <div class="clearfix"></div>
                     </div>
                  </form>
               </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="profile">
               <div class="lazy_sign_in_login-content-form">
                  <h2><?php _e( 'Sign Up Form Fields', 'lazy-sign-in' ); ?></h2>
                  <form class="validate" method="post" id="addtag" autocomplete="off">
                  <?php wp_nonce_field(); ?>
                     <div class="input-group floating">
                        <input type="text" class="validate" value="<?php echo $lsi_signup_heading_text; ?>" id="lsi_signup_heading_text" name="lsi_signup_heading_text"/>
                        <label for="lsi_signup_heading_text"><?php _e( 'Page Heading', 'lazy-sign-in' ); ?></label>
                     </div>
                     <div class="input-group floating">
                        <input type="text" class="validate" id="lsi_signup_redirect_link" name="lsi_signup_redirect_link" value="<?php echo $lsi_signup_redirect_link; ?>"/>
                        <label for="lsi_signup_redirect_link"><?php _e( 'Redirection link after success', 'lazy-sign-in' ); ?></label>
                        <?php if(!empty($error_signup_success)){?>
                          <div class="error"><?php printf(__( '%s.', 'my-plugin' ),$error_signup_success); ?></div>
                          <?php } ?>
                     </div>
                     <div class="input-group floating">
                        <input type="text" class="validate" id="lsi_login_link" name="lsi_login_link" value="<?php echo $lsi_login_link; ?>"/>
                        <label for="lsi_login_link"><?php _e( 'Login Page Link', 'lazy-sign-in' ); ?></label>
                        <?php if(!empty($error_login_link)){?>
                          <div class="error"><?php printf(__( '%s.', 'my-plugin' ),$error_login_link); ?></div>
                          <?php } ?>
                     </div>
                     <div class="input-group">
                        <select name="lsi_signup_role" id="lsi_signup_role">
                           <option value=""><?php _e( 'Assign Role', 'lazy-sign-in' ); ?></option>
                           <?php wp_dropdown_roles( $lsi_signup_role ); ?>
                        </select>
                     </div>
                     <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                           <div class="input-group">
                              <input type="checkbox" id="lsi_generate_username" name="lsi_generate_username" <?php if($lsi_generate_username=='1') echo 'checked="checked"'; ?> />
                              <label for="lsi_generate_username"><?php _e( 'Auto generate username', 'lazy-sign-in' ); ?></label>
                           </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                           <div class="input-group">
                              <input type="checkbox" id="lsi_generate_password" name="lsi_generate_password" <?php if($lsi_generate_password=='1') echo 'checked="checked"'; ?> />
                              <label for="lsi_generate_password"><?php _e( 'Auto generate password', 'lazy-sign-in' ); ?></label>
                           </div>
                        </div>
                        <div class="password-note"><?php _e( 'Password will contain special characters like !@#$%^&*()-_ []{}<>~`+=,.;:/?|', 'lazy-sign-in' ); ?></div>
                     </div>
                     <div class="add-new-text-field-wrap">
                        <h3> <?php _e( 'Add new text field', 'lazy-sign-in' ); ?></h3>
                        <ul>
                           <?php $i=1; if($new_fields):
                           foreach($new_fields as $key => $value): ?>
                           <li class="clone">
                              <div class="remove-icon"><a href="#" class="remove_field"><i class="fa fa-times" aria-hidden="true"></i></a></div>
                              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                 <tbody>
                                    <tr>
                                       <th><?php _e( 'Field name', 'lazy-sign-in' ); ?></th>
                                       <td><?php echo $value[0]; ?></td>
                                       <input type="hidden" name="field[]"  value="<?php echo $value[0]; ?>" />
                                    </tr>
                                    <tr>
                                       <th><?php _e( 'Require field', 'lazy-sign-in' ); ?>?</th>
                                       <td><?php echo ($value[1]?"Yes":"No"); ?></td>
                                       <input type="hidden" name="require[]"  value="<?php echo $value[1] == 1 ? $i : ''; ?>" />
                                    </tr>
                                    <tr>
                                       <th><?php _e( 'Unique field', 'lazy-sign-in' ); ?>?</th>
                                       <td><?php echo ($value[2]?"Yes":"No"); ?></td>
                                       <input type="hidden" name="unique[]"  value="<?php echo $value[2] == 1 ? $i : ''; ?>" />
                                    </tr>
                                 </tbody>
                              </table>
                           </li>
                           <?php $i++; endforeach;
                           endif; ?>
                        </ul>
                        <div class="clone">
                           <div class="add-new-text-field-extra">
                              <div class="input-group floating">
                                 <input type="text" class="validate" name="field[]" id="field[]" />
                                 <label><?php _e( 'Field name', 'lazy-sign-in' ); ?></label>
                              </div>
                              <div class="row">
                                 <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="input-group">
                                       <input type="checkbox" name="require[]" value = "<?php echo $i ?>" />
                                       <label><?php _e( 'Require field', 'lazy-sign-in' ); ?></label>
                                    </div>
                                 </div>
                                 <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="input-group">
                                       <input type="checkbox" name="unique[]" value = "<?php echo $i ?>"/>
                                       <label><?php _e( 'Unique field', 'lazy-sign-in' ); ?></label>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="add-new-text-field-extra-btn"> <a href="javascript:;" class="add_field"><?php _e( 'Add', 'lazy-sign-in' ); ?></a> </div>
                        </div>
                     </div>
                     <div class="input-group">
                        <input type="submit" value="<?php _e( 'Save Settings', 'lazy-sign-in' );?>" class="save-setting-btn" id="submit" name="submit"/>
                        <div class="clearfix"></div>
                     </div>
                  </form>
               </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="messages"></div>
            <div role="tabpanel" class="tab-pane fade" id="settings"></div>
         </div>
      </div>
   </div>
</div>