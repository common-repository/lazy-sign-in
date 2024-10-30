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
	  	<h3><?php echo get_option("lsi_signup_heading_text"); ?></h3>
	  	<form method="post" class="signup" action="" id="signup_form" autocomplete="off">
    		<div class="management">
	      	<?php if(!get_option("lsi_generate_username")): ?>
		      	<div class="input-group floating">
		        		<input type="text" class="required" name="uname" id="uname" value="">
		        		<label for="username"><?php _e( 'Username*', 'lazy-sign-in' ); ?></label>
		      	</div>
	      	<?php endif; ?>
	      	<div class="input-group floating">
	        		<input type="email" class="required email" name="email" id="email" value="" required>
	        		<label for="email"><?php _e( 'Email address*', 'lazy-sign-in' ); ?></label>
	      	</div>
	      	<?php if(!get_option("lsi_generate_password")): ?>
	      		<div class="input-group floating">
	        			<input class="required" type="password" name="password" id="password" required value="">
	        			<label for="password"><?php _e( 'Password*', 'lazy-sign-in' ); ?></label>
			      </div>
	      		<div class="input-group floating">
	        			<input class="required" type="password" name="rpassword" id="rpassword" required>
	        			<label for="rpassword"><?php _e( 'Repeat Password*', 'lazy-sign-in' ); ?></label>
	      		</div>
	      	<?php endif; ?>
	     		<div class="new_fields">
	        		<?php $new_fields = get_option('lsi_extra_fields');
	        		if (!empty($new_fields)) {
						foreach($new_fields as $key => $value) {
							$class = ($value[1]>0 ? 'required':'').($value[2]>0 ? ' unique':'').($value[3]>0 ? ' digits':'');
							$input_type = $value[3]>0 ? 'tel':'text';
							echo '<div class="input-group form-row form-row-wide floating">';
							echo '<input type="'.$input_type.'" name="'.sanitize_title($value[0]).'" id="'.sanitize_title($value[0]).'" class="'.$class.'" />';
							echo '<label for="'.sanitize_title($value[0]).'">'.$value[0].($value[1]>0 ? '* ':'').'</label>';
							echo '</div>';
						} 
					}
					$unique_field = array();
					if (!empty($new_fields)) {
						foreach($new_fields as $key => $value) {
							if($new_fields[$key][2]) {
								$unique_field[$new_fields[$key][0]] = $new_fields[$key][2];
							}
						} 
					}
					?>
	      	</div>
	      	<div class="clearfix"></div>
	      	<div class="error"></div>
	      	<div class="success"></div>
	      	<div class="management_submit">
		        	<div class="input-group wrapper_submit">
		          	<?php if(!get_option("lsi_generate_password")): ?>
		          		<input type="submit" class="button signup_submit" name="signup" value="<?php _e( 'signup', 'lazy-sign-in' ); ?>">
	          		<?php else: ?>
		          		<input type="submit" class="button signup_submit" name="signup" value="<?php _e( 'signup', 'lazy-sign-in' ); ?>">
		          	<?php endif; ?>
		          	<img src="<?php echo plugin_dir_url(__FILE__); ?>images/loader-icon.gif" alt="loader" class="ajax-domain-img" />
		          	<div class="clearfix"></div>
		        </div>
	      	</div>
	      	<?php wp_nonce_field(); ?>
	   	</div>
		</form>
	</div>
 	<?php if(get_option("lsi_login_link")!=""): ?>
		<div class="lazy-sign-in-already-account">
			Already have an account? <a href="<?php echo get_option('lsi_login_link'); ?>">Sign In</a>
		</div>
	<?php endif; ?>
<?php else: ?>
	<p><?php _e( 'You are logged in.', 'lazy-sign-in' ); ?></p>
<?php endif; ?>
