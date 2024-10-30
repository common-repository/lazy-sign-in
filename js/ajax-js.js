jQuery(document).ready(function(){	
	  jQuery.validator.addMethod("alphanumeric", function(value, element) {
        return this.optional(element) || /^[a-z0-9\-_\s]+$/i.test(value);
    	}, "Username must contain only letters, numbers, dashes or underscore."); 

	jQuery("#signup_form").validate({
		errorClass:"ls-error", 
       	errorElement:"div",
		rules: {
			uname:{
				 alphanumeric: true,
			},
			rpassword: {
		      equalTo: ".signup #password"
		    }
		},
		 messages: {
            "uname": {
                alphanumeric: "Username format not valid"
            }
        },
		errorPlacement: function(error, element) {
		    error.insertAfter(jQuery(element).next('label'));
		}
	});
	// login form ajax calling.
	jQuery('.login_submit').click(function(e){
		e.preventDefault();
		jQuery('.success').html('');
		jQuery('.error').html('');
		var user_name= jQuery(this).parents('.management').find('#username').val();
		var user_pass= jQuery(this).parents('.management').find('#password').val();
		var wpnonce= jQuery(this).parents('#login_form').find('#_wpnonce').val();
		var _this=jQuery(this);
		var remember= null;
		if(jQuery('#rememberme').is(':checked')){
			remember=  jQuery(this).parents('.management').find('#rememberme').val();
		}
		jQuery(this).parents('.management').find('img').show();
		var pdata = {
			 	action: "lazy_sign_in_submit_log_action",
			 	username : user_name,
                password : user_pass,
                remember_me : remember,
                wpnonce : wpnonce
	        }
	    	jQuery.post(lsi_ajax_object.ajax_url,pdata,
	            function ( response ) {
	            	var ajax_result = jQuery.parseJSON(response);
	            	if(ajax_result.result == '1'){
						jQuery(_this).parents('.management').find('img').hide();
						jQuery('.login .success').html(ajax_result.message);
						window.location.href = success_url.login;
					}
					else{
						jQuery(_this).parents('.management').find('img').hide();
						jQuery('.login .error').html(ajax_result.message);
					}
	            }  
	        ); 
	});
	// signup form ajax calling.
	jQuery('.signup_submit').click(function(e){
		e.preventDefault();
		if(jQuery("#signup_form").valid()){
			jQuery('.signup .success').html('');
			jQuery('.signup .error').html('');
			var datastring = {};
			if(jQuery(this).parents('.management').find('#uname').length){
			  	datastring['user_name']=jQuery(this).parents('.management').find('#uname').val();
			}
			if(jQuery(this).parents('.management').find('#password').length){
				var pwd = jQuery(this).parents('.management').find('#password').val();
				datastring['password']=pwd;
				datastring['rpassword']=jQuery(this).parents('.management').find('#rpassword').val();
			}
			datastring['user_email']=jQuery(this).parents('.management').find('#email').val();
			jQuery.each(jQuery('.new_fields input[type="text"]'), function(i,val){
				datastring[jQuery(this).attr('name')]=jQuery(this).val();
		    });
		    var wpnonce= jQuery(this).parents('#signup_form').find('#_wpnonce').val();
		    datastring['action']="lazy_sign_up_submit_log_action";
		    datastring['wpnonce']=wpnonce;

			var _this=jQuery(this);
			jQuery(this).parents('.management').find('img').show();
			var pdata = datastring;
		   	jQuery.post(lsi_ajax_object.ajax_url,pdata,
	            function ( response ) {
	            	var ajax_result = jQuery.parseJSON(response);
	                if(ajax_result.result == '1'){
						jQuery(_this).parents('.management').find('img').hide();
						jQuery('.signup .success').html(ajax_result.message);
						window.location.href = success_url.signup;
					}
					else{
						jQuery(_this).parents('.management').find('img').hide();
						jQuery('.signup .error').html(ajax_result.message);
					}
	            }  
	        ); 
		}
	});
});
setTimeout(function(){
	jQuery.each( jQuery('.input-group.floating input'), function( i, val ){
		var text_val = jQuery(this).val();
		if(text_val === "") {
		  jQuery(this).removeClass('has-value');      
		} else {
		  jQuery(this).addClass('has-value'); 
		}
	});
}, 400);
jQuery(function(){
	jQuery('.input-group.floating input').focusout(function(){
		var text_val = jQuery(this).val();
		if(text_val === "") {
		  jQuery(this).removeClass('has-value');      
		} else {
		  jQuery(this).addClass('has-value'); 
		}
	});
});

