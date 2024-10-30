<?php if ( ! defined( 'ABSPATH' ) ) exit; 
class lazy_sign_in {
    function lazy_sign_in_scripts() {
        wp_enqueue_style('client-form-css', LAZY_SIGN_IN_PLUGIN_URL . '/css/form.css');
    	wp_enqueue_script('validate-js', LAZY_SIGN_IN_PLUGIN_URL . 'js/jquery.validate.min.js', array('jquery'),false,true);
        wp_enqueue_script('setting-js', LAZY_SIGN_IN_PLUGIN_URL . 'js/ajax-js.js', array('validate-js'),false,true);
        wp_localize_script( 'setting-js', 'success_url', array(
            'login'  => get_option('lsi_login_redirect_link'),
            'signup'    => get_option('lsi_signup_redirect_link'),
        ));
        wp_localize_script( 'setting-js', 'lsi_ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
    }
    function lazy_sign_in_scripts_admin() {
        wp_enqueue_style('bootstrap-css', LAZY_SIGN_IN_PLUGIN_URL . 'admin/css/bootstrap.css', array());
        wp_enqueue_style('fontawesome-css', LAZY_SIGN_IN_PLUGIN_URL . 'admin/fonts/awesome/css/font-awesome.css', array());
        wp_enqueue_style('plugin-css', LAZY_SIGN_IN_PLUGIN_URL . 'admin/css/plugin.css', array());
        wp_enqueue_script('bootstrap-js', LAZY_SIGN_IN_PLUGIN_URL . 'admin/js/bootstrap.js', array('jquery'),false,true);
        wp_enqueue_script('login-form-js', LAZY_SIGN_IN_PLUGIN_URL . 'admin/js/setting.js', array('jquery'),false,true);
    }
    function lazy_sign_in_login_form( $atts ) {  
        ob_start();
        include "login_form.php";
        return ob_get_clean();
    }
    function lazy_sign_in_signup_form( $atts ) {
        ob_start();
        include "signup_form.php";
        return ob_get_clean();
    }
    function lazy_sign_in_profile_fields( $user ) { ?> 
        <h2>User Meta Fields</h2>
        <table class="form-table">
        <?php $new_fields = get_option('lsi_extra_fields');
        if(!empty($new_fields)){
	            foreach($new_fields as $key => $value) { 
	                $class = ($value[1]>0 ? 'required ':'').($value[2]>0 ? 'unique':'');
	                echo '<tr><th>';
	                echo '<label for="'.sanitize_title($value[0]).'">'.ucfirst($value[0]).'</label></th><td>';
	                echo '<input type="text" name="'.sanitize_title($value[0]).'" value="'.get_user_meta($user->ID,sanitize_title($value[0]),true).'" id="'.sanitize_title($value[0]).'" placeholder="'.$value[0].'" class="input-text '.$class.'" />';
	            echo '</td></tr>';
	        }
        } ?>

        </table>
    <?php }
    function lazy_sign_in_save_profile_fields( $user_id ) {
        if ( !current_user_can( 'edit_user', $user_id ) )
            return false;
        $new_fields = get_option('lsi_extra_fields');
        if(!empty($new_fields)){
	        foreach($new_fields as $key => $value) {
	            if(isset($_POST[sanitize_title($new_fields[$key][0])]))
	            update_user_meta( $user_id, sanitize_title($new_fields[$key][0]), $_POST[sanitize_title($new_fields[$key][0])] );
	        }
        }
    }
}
$lazy_sign_in_Class = new lazy_sign_in();
add_action('login_enqueue_scripts',  array($lazy_sign_in_Class, 'lazy_sign_in_scripts'));
add_action( 'wp_enqueue_scripts', array($lazy_sign_in_Class,'lazy_sign_in_scripts' ));
if($_GET['page']=='lazy_sign_in_options'){
    add_action('admin_enqueue_scripts', array($lazy_sign_in_Class,'lazy_sign_in_scripts_admin'));
}
add_shortcode( 'kwt_ajax_login_form', array($lazy_sign_in_Class,'lazy_sign_in_login_form' ));
add_shortcode( 'kwt_ajax_signup_form', array($lazy_sign_in_Class,'lazy_sign_in_signup_form' ));
add_action( 'show_user_profile', array($lazy_sign_in_Class,'lazy_sign_in_profile_fields'), 10 );
add_action( 'edit_user_profile', array($lazy_sign_in_Class,'lazy_sign_in_profile_fields'), 10 );
add_action( 'personal_options_update', array($lazy_sign_in_Class,'lazy_sign_in_save_profile_fields' ));
add_action( 'edit_user_profile_update', array($lazy_sign_in_Class,'lazy_sign_in_save_profile_fields' ));
//Log in form
add_action('wp_ajax_lazy_sign_in_submit_log_action', 'lazy_sign_in_submit_log_action');
add_action('wp_ajax_nopriv_lazy_sign_in_submit_log_action', 'lazy_sign_in_submit_log_action');
function lazy_sign_in_submit_log_action() {
    // golbal variable ajax returns
    $msg='';
    $user_input=sanitize_text_field($_POST['username']);
    $user_password=sanitize_text_field($_POST['password']);
    if (  !wp_verify_nonce($_POST['wpnonce'])  ) {
       $msg = array('result' => '0','message' => 'Unauthorized access');
    } else {
        // checking is username and password is blank or not
        if( $user_input == '' || $user_password == '' ){
            $msg = array('result' => '0','message' => 'Please enter username and password.');
        }
        // checking email has @ or not
        elseif( strpos($user_input, '@') ) {
            $user = get_user_by('email', $user_input);
            $user_name= array();
            $user_name['user_login'] = $user->user_login;
            $user_name['user_password']=$user_password;
            if($_POST['remember_me'] == 'null') {
                $user_name['remember'] = false; 
            } else  {
                $user_name['remember'] = true;
            }

            $user_verify = wp_signon($user_name, false); 
            if (is_wp_error($user_verify) ) {
                $msg = array('result' => '0','message' => $user_verify->get_error_message());
            } else {
                 $msg = array('result' => '1','message' => 'Login Success');
            }
        }else {
            $user_name= array();
            $user_name['user_login'] = $user_input;
            $user_name['user_password'] = $user_password;

            if($_POST['remember_me'] == 'null') {
                $user_name['remember'] = false; 
            } else {
                $user_name['remember'] = true;
            }
            
            $user_verify = wp_signon($user_name, false); 
            if (is_wp_error($user_verify) ) {
                $msg = array('result' => '0','message' => $user_verify->get_error_message());
            } else {
                $msg = array('result' => '1','message' => 'Login Success');
            }
        }
        echo json_encode($msg);
        wp_die();
    }
}
//sign in form
add_action('wp_ajax_lazy_sign_up_submit_log_action', 'lazy_sign_up_submit_log_action');
add_action('wp_ajax_nopriv_lazy_sign_up_submit_log_action', 'lazy_sign_up_submit_log_action');
function lazy_sign_up_submit_log_action() {
   // golbal variable ajax returns
    $msg='';
    // $wpdb wordpress query.
    global $wpdb;
    if (  !wp_verify_nonce($_POST['wpnonce'])  ) {
        $msg = array('result' => '0','message' => 'Unauthorized access'); 
    } else {
        // assign post email value to $email variable.
        $email = $wpdb->escape(trim($_POST['user_email']));
        if(isset($_POST['password'])){
            $pwd = $wpdb->escape(trim($_POST['password']));
            $rpwd = $wpdb->escape(trim($_POST['rpassword']));
        }
        if(isset($_POST['user_name'])){
            $user_name = $wpdb->escape(trim($_POST['user_name']));
        }else{
            $user_name = '';
        }
        // get user role from option table.
        $user_role = get_option('lsi_signup_role');
        if($user_role==""){
            $user_role = 'subscriber';
        }
        // get extra field value from option table.
        $new_fields = get_option('lsi_extra_fields');
        // new fields array
        $new_field = array();
        foreach($new_fields as $key => $value) {
            $new_field[] = sanitize_title($new_fields[$key][0]);
        }
        // if sign up field is unique.
        $unique_field = array();
        foreach($new_fields as $key => $value) {
            if($new_fields[$key][2]) {
                $unique_field[$new_fields[$key][0]] = sanitize_title( $_POST[ strtolower($new_fields[$key][0]) ] );
            }
        }
        // checking user email , username and password isset or not
        if( $email == "" || (isset($_POST['user_name']) && $user_name=="") || (isset($_POST['password']) && ( $pwd=="" || $rpwd=="") )  ) {
            $err = 'Please don\'t leave the required fields blank.';
            $msg = array('result' => '0','message' => $err);
        } else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $err = 'Invalid email address.';
            $msg = array('result' => '0','message' => $err);
        } else if(email_exists($email) ) {
            $err = 'Email already exist.';
            $msg = array('result' => '0','message' => $err);
        } else if(isset($_POST['password']) && $pwd <> $rpwd ){
            $err = 'Password do not match.'; 
            $msg = array('result' => '0','message' => $err);   
        } else {
            // checking unique field.
            $unique_err = 0;
            if(!empty($unique_field)){
                foreach ($unique_field as $key => $value) {
                    if($value!="") {
                        $args = array (
                        'role' => $user_role,
                        'order' => 'ASC',
                        'orderby' => 'display_name'
                        );
                        $wp_user_query = new WP_User_Query($args);
                        $authors = $wp_user_query->get_results();
                        if (!empty($authors)) {
                            foreach ($authors as $author)
                            {
                                if(get_the_author_meta($key,$author->ID)) {
                                    $meta_value = get_the_author_meta($key,$author->ID);
                                }
                                if($meta_value!='' && $meta_value == $value) {
                                    $err .= "$value - $key already exists <br/>"; 
                                    // if field is already exit, $unique_err 0.
                                    $unique_err = 1;
                                    $msg = array('result' => '0','message' => $err);
                                    break;
                                }
                            }
                        }
                    }
                }
            }
            // if unique has not error.
            if($unique_err!=1) {
                if($user_name=="" && isset($_POST['password'])) {
                    $user_name = substr($email, 0, strpos($email, '@'));
                }else if($user_name=="" && !isset($_POST['password'])) {
                    $user_name = substr($email, 0, strpos($email, '@'));
                    $pwd = wp_generate_password( 10, true, true );
                }else if($user_name!="" && !isset($_POST['password'])) {
                    $pwd = wp_generate_password( 10, true, true );
                }
                $user_id = wp_insert_user( array ( 'user_pass' => apply_filters('pre_user_user_pass', $pwd), 'user_login' => apply_filters('pre_user_user_login', $user_name), 'user_email' => apply_filters('pre_user_user_email', $email), 'role' => $user_role ) );
                if( is_wp_error($user_id) ) {
                    $err = $user_id->get_error_message();
                    $msg = array('result' => '0','message' => $err);
                } else {
                    do_action('user_register', $user_id);
                    $user_status = 1;
                    if(!isset($_POST['password'])) {
                        global $wp_hasher;
                        wp_set_password($pwd,$user_id);
                        
                        $headers[] = 'Content-Type: text/html; charset=UTF-8';
                        $headers[] = 'From: '.get_bloginfo('name').' <noreply@'.get_site_url().'>' . "\r\n";
                        $body = 'Your login credential for the site'.get_bloginfo('name');
                        $body .= "<br>Username: $user_name <br> Password: <strong>$pwd</strong>";
                        wp_mail( $email, 'Password generated from'.get_bloginfo('name'), $body, $headers);
                    }
                    $register_verify = wp_signon(array( 'user_login' => $user_name, 'user_password' => $pwd, 'user_status' => $user_status), false); 
                    if (is_wp_error($register_verify) ) 
                    {
                        $err = $register_verify->get_error_message();
                        $msg = array('result' => '0','message' => $err);
                    }
                    else
                    {
                        $success = 'You\'re successfully register';
                        foreach($new_fields as $key => $value) {
                            update_user_meta( $user_id, $new_fields[$key][0], sanitize_title( $_POST[ strtolower($new_fields[$key][0]) ] ));
                        }
                        $msg = array('result' => '1','message' => $success);
                    }
                }
            }
        }
        echo json_encode($msg);
        wp_die();
    }
}
add_action( 'plugins_loaded', 'lazy_sign_in_load_textdomain' );
/**
 * Load plugin textdomain.
*/
function lazy_sign_in_load_textdomain() {
  load_plugin_textdomain( 'lazy-sign-in', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
