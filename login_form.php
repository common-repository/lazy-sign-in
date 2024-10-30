<?php if ( ! defined( 'ABSPATH' ) ) exit; 

if(!is_user_logged_in()): ?>
   <script type="text/javascript">
      jQuery(function(){
      	jQuery.each( jQuery('.input-group.floating input'), function( i, val ){
      		var text_val = jQuery(this).val();
      		if(text_val === "") {
      		  jQuery(this).removeClass('has-value');      
      		} else {
      		  jQuery(this).addClass('has-value'); 
      		}
         });
         jQuery('.input-group.floating input').focusout(function(){
      		var text_val = jQuery(this).val();
      		if(text_val === "") {
      		  jQuery(this).removeClass('has-value');      
      		} else {
      		  jQuery(this).addClass('has-value'); 
      		}
         });
      });
   </script>
   <div class="lazy-sign-in-signup">
      <h3><?php echo get_option("lsi_login_heading_text"); ?></h3>
      <form method="post" class="login" action="" id="login_form" autocomplete="off">
            <?php wp_nonce_field(); ?>
         <div class="management">
            <div class="error"></div>
            <div class="input-group floating">
               <input type="text" class="required" name="username" id="username" value="" required>
               <label for="username"><?php _e( 'Username or email address*', 'lazy-sign-in' ); ?></label>
            </div>
            <div class="input-group floating">
               <input class="required" type="password" name="password" id="password" required>
               <label for="password"><?php _e( 'Password*', 'lazy-sign-in' ); ?></label>
            </div>
            <div class="input-group form-row">
               <div class="remember-me">
                  <input id="rememberme" type="checkbox" value="forever" name="rememberme">
                  <label for="rememberme" class="inline"><?php _e( 'Remember me', 'lazy-sign-in' ); ?></label>
               </div>
               <div class="ls-login">
                  <input type="submit" class="button login_submit" name="login" value="<?php _e( 'Login', 'lazy-sign-in' ); ?>">
                  <img src="<?php echo plugin_dir_url(__FILE__); ?>images/loader-icon.gif" alt="loader" class="ajax-domain-img" />
               </div>
               <div class="clearfix"></div>
            </div>
            <?php do_action('login_form'); ?>
            <div class="success"></div>
            <div class="management_submit">
               <?php do_action('login_form', 'resetpass'); ?>
               <div class="lost_password"> <a href="<?php echo wp_login_url(); ?>"><?php _e( 'Lost your password?', 'lazy-sign-in' ); ?></a> </div>
            </div>
		</div>
      </form>
   </div>
<?php else: ?>
   <p><?php _e( 'You are logged in.', 'lazy-sign-in' ); ?></p>
<?php endif; 