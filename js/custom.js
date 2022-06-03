"use strict";
var base_url=$("body").attr("data-base_url");

/*
OLD LOGIC FUNCTION. NOT NEEDED
function initLogin(){
	if($("#frm_login").length>0){
		$("#frm_login").on("submit",function(e){
			e.preventDefault();
			alert("hi");
			return false;
		});
	}
}*/

var funcLoginSubmit = function() {

    var showErrorMsg = function(form, type, msg) {
        var alert = $('<div class="alert alert-' + type + '">\
			<div>'+msg+'</div>\
		</div>');

        form.find('.alert').remove();
        alert.prependTo(form).fadeIn("slow");
        alert.delay(3000).fadeOut("slow");
    }

    var handleLoginSubmit = function() {
    	if($("#frm_login").length>0){
    		$('#frm_login').on("submit",function(e) {
                e.preventDefault();
                
                var btn = $("#submit_btn");
                var form = $(this);

                btn.attr('disabled', true);

                var data = {
                	email:$("#email").val(),
                	password:$("#password").val()
                };
                $.ajax({
                	type: "post",  // Request method: post, get
                	data: data,
                    url: base_url+'user/login_submit',
                    success: function(response, status, xhr, $form) {
                		response = JSON.parse(response);
                		if(response.success){
                			window.location.href=base_url;	// its the base url
                		}else{
                			setTimeout(function() {
        	                    btn.attr('disabled', false);
        	                    showErrorMsg(form, 'danger', response.message);
                            }, 1000);
                		}
                    }
                });
            });
    	}
    }

    // Public Functions
    return {
        // public functions
        init: function() {
    		handleLoginSubmit();
        }
    };
}();

var funcMyProfile = function() {

    var showErrorMsg = function(form, type, msg) {
        var alert = $('<div class="alert alert-' + type + '">\
			<div>'+msg+'</div>\
		</div>');

        form.find('.alert').remove();
        alert.prependTo(form).fadeIn("slow");
        alert.delay(3000).fadeOut("slow");
    }

    var handleMyProfile = function() {
    	if($("#my_profile").length>0){
    		$('#my_profile').on("submit",function(e) {
                e.preventDefault();
                
                show_loader();
                
                var btn = $("#submit_btn");
                var form = $(this);

                btn.attr('disabled', true);

                var data = {
                	first_name:$("#first_name").val(),
                	last_name:$("#last_name").val(),
                	email:$("#email").val(),
                	phone:$("#phone").val()
                };
                $.ajax({
                	type: "post",  // Request method: post, get
                	data: data,
                    url: base_url+'user/update_profile',
                    success: function(response, status, xhr, $form) {
                		hide_loader();
                		btn.attr('disabled', false);
                		
                		response = JSON.parse(response);
                		if(response.success){
                			//window.location.href=base_url;	// its the base url
                			showErrorMsg(form, 'success', response.message);
                		}else{
                			showErrorMsg(form, 'danger', response.message);
                		}
                    }
                });
            });
    	}
    }

    // Public Functions
    return {
        // public functions
        init: function() {
    		handleMyProfile();
        }
    };
}();

var funcChangePassword = function() {

    var showErrorMsg = function(form, type, msg) {
        var alert = $('<div class="alert alert-' + type + '">\
			<div>'+msg+'</div>\
		</div>');

        form.find('.alert').remove();
        alert.prependTo(form).fadeIn("slow");
        alert.delay(3000).fadeOut("slow");
    }

    var handleChangePassword = function() {
    	if($("#frm_change_my_password").length>0){
    		$('#frm_change_my_password').on("submit",function(e) {
                e.preventDefault();
                
                show_loader();
                
                var btn = $("#submit_btn");
                var form = $(this);

                btn.attr('disabled', true);

                var data = {
                	current_password:$("#current_password").val(),
                	new_password:$("#new_password").val(),
                	verify_password:$("#verify_password").val()
                };
                
                $.ajax({
                	type: "post",  // Request method: post, get
                	data: data,
                    url: base_url+'user/update_password',
                    success: function(response, status, xhr, $form) {
                		hide_loader();
                		btn.attr('disabled', false);
                		
                		response = JSON.parse(response);
                		if(response.success){
                			//window.location.href=base_url;	// its the base url
                			showErrorMsg(form, 'success', response.message);
                		}else{
                			showErrorMsg(form, 'danger', response.message);
                			
                			$("#current_password").val('');
                			$("#new_password").val('');
                			$("#verify_password").val('');
                			$("#current_password").focus();
                		}
                    }
                });
            });
    	}
    }

    // Public Functions
    return {
        // public functions
        init: function() {
    		handleChangePassword();
        }
    };
}();

//ACTIVE INACTIVE
var ActiveInactiveRecord = function() {

	var showErrorMsg = function(form, type, msg) {
        var alert = $('<div class="alert alert-' + type + '">\
			<div>'+msg+'</div>\
		</div>');

        form.find('.alert').remove();
        alert.prependTo(form).fadeIn("slow");
        alert.delay(3000).fadeOut("slow");
    }

    // Private Functions
    // var displaySignUpForm = function() {
    //     login.removeClass('kt-login--forgot');
    //     login.removeClass('kt-login--signin');

    //     login.addClass('kt-login--signup');
    //     KTUtil.animateClass(login.find('.kt-login__signup')[0], 'fadeIn animated');
    // }

    var handleActiveInactiveRecord = function() {
    	var show_message = $("table");
    	$(".active-badge").click(function(e) {
    		var $this = $(this);
    		var ask_for = $(this).attr("ask-for");
        	var title_text = (ask_for!='' && ask_for!=undefined)?'Are you sure you want to deactivate '+ask_for+'?':'Are you sure you want to deactivate this record?';
        	
    		swal.fire({
                //title: title_text,
                text: title_text,
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                reverseButtons: true
            }).then(function(result){
                if (result.value) {
                	
                	show_loader();
        			
        			var data = {
        		         	record_id:$this.attr("record-id"),
        		         	set_status:$this.attr("set-status"),
        		         	table_name:$this.attr("table-name")
        		         };
        			
        			$.ajax({
        	         	type: "post",  // Request method: post, get
        	         	data: data,
        	         	url: base_url+'common/update_status',
        	         	success: function(response, status, xhr, $form) {
        	         		response = JSON.parse(response);
        	         		if(response.success){
        	         			// hide this one and show the nearest span with class name inactive-badge
        	         			$this.removeClass("activeinactive_show");
        	         			$this.addClass("activeinactive_hide");
        	        			
        	         			$this.closest('td').find('.inactive-badge').removeClass("activeinactive_hide");
        	         			$this.closest('td').find('.inactive-badge').addClass("badge-danger activeinactive_show");
        	        			
        	        			hide_loader();
        	        			
        	        			showErrorMsg(show_message, 'success', response.message);
        	         			
        	         		}else{
        	         			showErrorMsg(show_message, 'danger', response.message);
        	         		}
        	             }
        	         });
                }
            });
    	});
    	
    	$(".inactive-badge").click(function(e) {
    		var $this = $(this);
    		var ask_for = $(this).attr("ask-for");
    		var title_text = (ask_for!='' && ask_for!=undefined)?'Are you sure you want to activate '+ask_for+'?':'Are you sure you want to activate this record?';
    		
    		swal.fire({
                //title: title_text,
                text: title_text,
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                reverseButtons: true
            }).then(function(result){
                if (result.value) {
                	
                	show_loader();
        			
        			var data = {
        		         	record_id:$this.attr("record-id"),
        		         	set_status:$this.attr("set-status"),
        		         	table_name:$this.attr("table-name")
        		         };
        			
        			$.ajax({
        	         	type: "post",  // Request method: post, get
        	         	data: data,
        	         	url: base_url+'common/update_status',
        	         	success: function(response, status, xhr, $form) {
        	         		response = JSON.parse(response);
        	         		if(response.success){
        	         			// hide this one and show the nearest span with class name active-badge
        	         			$this.removeClass("activeinactive_show");
        	         			$this.addClass("activeinactive_hide");
        	        			
        	         			$this.closest('td').find('.active-badge').removeClass("activeinactive_hide");
        	         			$this.closest('td').find('.active-badge').addClass("badge-success activeinactive_show");
        	        			
        	        			hide_loader();
        	        			
        	        			showErrorMsg(show_message, 'success', response.message);
        	         			
        	         		}else{
        	         			showErrorMsg(show_message, 'danger', response.message);
        	         		}
        	             }
        	         });
                }
            });
    		
    	});
    }
    
    // Public Functions
    return {
        // public functions
        init: function() {
    		handleActiveInactiveRecord();
        }
    };
}();

//DELETE
var DeleteRecord = function() {

	var showErrorMsg = function(form, type, msg) {
        var alert = $('<div class="alert alert-' + type + '">\
			<div>'+msg+'</div>\
		</div>');

        form.find('.alert').remove();
        alert.prependTo(form).fadeIn("slow");
        alert.delay(3000).fadeOut("slow");
    }

   var handleDeleteRecord = function() {
	   var show_message = $("table");
	   
	   $(".delete-button").click(function(e) {
    		// ajax request
        	var $this = $(this);
        	var ask_for = $(this).attr("ask-for");
    		var title_text = (ask_for!='' && ask_for!=undefined)?'Are you sure you want to delete '+ask_for+'?':'Are you sure you want to delete this record?';
    		
    		swal.fire({
                title: title_text,
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then(function(result){
                if (result.value) {
                	
        			show_loader();
        			
        			var data = {
        		         	record_id:$this.attr("record-id"),
        		         	set_status:$this.attr("set-status"),
        		         	table_name:$this.attr("table-name")
        		         };
        			
        			$.ajax({
        	         	type: "post",  // Request method: post, get
        	         	data: data,
        	         	url: base_url+'common/update_status',
        	         	success: function(response, status, xhr, $form) {
        	         		response = JSON.parse(response);
        	         		if(response.success){
        	         			showErrorMsg(show_message, 'success', response.message);
        	         			
        	         			setTimeout(function(){
        	         				window.location.href=window.location.href;
         	                    },1000);
        	         		}else{
        	         			showErrorMsg(show_message, 'danger', response.message);
        	         			hide_loader();
        	         		}
        	             }
        	         });
                    
                }
            });    		
    	});
    }
    
    // Public Functions
    return {
        // public functions
        init: function() {
    		handleDeleteRecord();
        }
    };
}();

//Generate Password
var GeneratePassword = function() {

	var showErrorMsg = function(form, type, msg) {
		var alert = $('<div class="alert alert-' + type + '">\
			<div>'+msg+'</div>\
		</div>');

        form.find('.alert').remove();
        alert.prependTo(form).fadeIn("slow");
        alert.delay(3000).fadeOut("slow");
    }
	
	var handleGeneratePassword = function() {
		
		var show_message = $("form");
		
	     $('#generate_password').click(function(e) {
	     	e.preventDefault();
	     	
	     	// main password generator logic
	     	var length = 8;
	        var string = "abcdefghijklmnopqrstuvwxyz"; //to upper 
	        var numeric = '0123456789';
	        var punctuation = '!@#$%^&*()_+~`|}{[]\:;?><,./-=';
	        var password = "";
	        var character = "";
	        var crunch = true;
	        while( password.length<length ) {
	            var entity1 = Math.ceil(string.length * Math.random()*Math.random());
	            var entity2 = Math.ceil(numeric.length * Math.random()*Math.random());
	            var entity3 = Math.ceil(punctuation.length * Math.random()*Math.random());
	            var hold = string.charAt( entity1 );
	            hold = (password.length%2==0)?(hold.toUpperCase()):(hold);
	            character += hold;
	            character += numeric.charAt( entity2 );
	            character += punctuation.charAt( entity3 );
	            password = character;
	        }
	        password=password.split('').sort(function(){return 0.5-Math.random()}).join('');
	        
	        var new_password = password.substr(0,length);
	        
	        // now set this value in textboxex for new password and verify password
	        $("#new_password").val(new_password);
	        $("#verify_password").val(new_password);
	        
	        // copy to clipboard
	        var dummy = document.createElement("input");
	        document.body.appendChild(dummy);
	        dummy.setAttribute("id", "clipboard");
	        document.getElementById("clipboard").value=new_password;
	        dummy.select();
	        document.execCommand("copy");
	        document.body.removeChild(dummy);
	     	
	     	//
	     	showErrorMsg(show_message, 'success', "Password Generated and Copied to CLIPBOARD !");         
	     });
	 }
	 
	 // Public Functions
	 return {
	     // public functions
	     init: function() {
		 	handleGeneratePassword();
	     }
	 };
}();

//ADD/UPDATE Profile
var AddUpdateProfile = function() {

	var showErrorMsg = function(form, type, msg) {
		var alert = $('<div class="alert alert-' + type + '">\
			<div>'+msg+'</div>\
		</div>');

        form.find('.alert').remove();
        alert.prependTo(form).fadeIn("slow");
        alert.delay(3000).fadeOut("slow");
    }
	
	var handleAddUpdateProfile = function() {
		if($("#frm_add_profile").length>0){
			$("#frm_add_profile").on("submit",function(e) {
				e.preventDefault();
		     	
				show_loader();
				
		         var btn = $("#submit_btn");
	             var form = $(this);
	             var show_message = form;
	             
	             btn.attr('disabled', true);
		
		         var redirect_page = $("#redirect_page").val();
		         
		         var data = {
		        	id:$("#id").val(),
		        	first_name:$("#first_name").val(),
		         	last_name:$("#last_name").val(),
		         	email:$("#email").val(),
		         	phone:$("#phone").val(),
		         	new_password:($("#new_password").length>0)?$("#new_password").val():"",
		         	verify_password:($("#verify_password").length>0)?$("#verify_password").val():""
		         };
		         $.ajax({
		         	type: "post",  // Request method: post, get
		         	data: data,
		            url: base_url+'profile/save_profile',
		            success: function(response, status, xhr, $form) {
		         		response = JSON.parse(response);
		         		if(response.success){
		         			showErrorMsg(show_message, 'success', response.message);
		 	                    
	 	                    setTimeout(function(){
	 	                    	window.location.href=base_url+redirect_page;
	 	                    },1000);
		         		}else{
		         			hide_loader();
		         			btn.attr('disabled', false);
	 	                    showErrorMsg(show_message, 'danger', response.message);
		         		}
		             }
		         });
		     });
		}
	 }
	 
	 // Public Functions
	 return {
	     // public functions
	     init: function() {
		 	handleAddUpdateProfile();
	     }
	 };
}();

//CHANGE PASSWORD
var ChangeProfilePassword = function() {

	var showErrorMsg = function(form, type, msg) {
		var alert = $('<div class="alert alert-' + type + '">\
			<div>'+msg+'</div>\
		</div>');

        form.find('.alert').remove();
        alert.prependTo(form).fadeIn("slow");
        alert.delay(3000).fadeOut("slow");
    }

 var handleChangeProfilePasswordSubmit = function() {
	 if($("#frm_change_profile_password").length>0){
		 $("#frm_change_profile_password").on("submit",function(e) {
			 e.preventDefault();
	     	
			 show_loader();
			
	         var btn = $("#submit_btn");
             var form = $(this);
             var show_message = form;
             
             btn.attr('disabled', true);
	
	         var redirect_page = $("#redirect_page").val();
	         
	         var data = {
	             	id:$("#id").val(),
	              	new_password:$("#new_password").val(),
	              	verify_password:$("#verify_password").val()
	          };
              $.ajax({
              	type: "post",  // Request method: post, get
              	data: data,
                url: base_url+'profile/update_password',
                success: function(response, status, xhr, $form) {
              		response = JSON.parse(response);
              		if(response.success){
              			showErrorMsg(show_message, 'success', response.message);
  	                    
  	                    setTimeout(function(){
  	                    	window.location.href=base_url+redirect_page;
 	                    },1000);
              			
              		}else{
              			hide_loader();
              			btn.attr('disabled', false);
          				
  	                    showErrorMsg(show_message, 'danger', response.message);
  	                    
  	                    // now reset the values of all three password fields
  	                    $("#new_password").val('');
  	                    $("#verify_password").val('');
  	                    $("#new_password").focus();
              		}
                  }
              });
		 });
	 }
 }
 
 // Public Functions
 return {
     // public functions
     init: function() {
	 	handleChangeProfilePasswordSubmit();
     }
 };
}();

//ADD/UPDATE Customer
var AddUpdateCustomer = function() {

	var showErrorMsg = function(form, type, msg) {
		var alert = $('<div class="alert alert-' + type + '">\
			<div>'+msg+'</div>\
		</div>');

        form.find('.alert').remove();
        alert.prependTo(form).fadeIn("slow");
        alert.delay(3000).fadeOut("slow");
    }
	
	var handleAddUpdateCustomer = function() {
		if($("#frm_add_customer").length>0){
			$("#frm_add_customer").on("submit",function(e) {
				e.preventDefault();
		     	
				show_loader();
		        var btn = $("#submit_btn");
	            var form = $(this);
	            var show_message = form;
	             
	            btn.attr('disabled', true);
		
		        var redirect_page = $("#redirect_page").val();
		         
		        var data = {
		        	id:$("#id").val(),
		        	customer_name:$("#customer_name").val(),
		        	contact_name:$("#contact_name").val(),
		        	fk_industry_id:$("#fk_industry_id").val(),
		        	fk_accountmanager_id:$("#fk_accountmanager_id").val(),
		        	email:$("#email").val(),
		        	phone:$("#phone").val(),
		        	address_line_1:$("#address_line_1").val(),
		        	address_line_2:$("#address_line_2").val(),
		        	city:$("#city").val(),
		        	state:$("#state").val(),
		        	country:$("#country").val(),
		        	followup_date:$("#followup_date").val()
		        };
		        $.ajax({
		         	type: "post",  // Request method: post, get
		         	data: data,
		            url: base_url+'customer/save_customer',
		            success: function(response, status, xhr, $form) {
		         		response = JSON.parse(response);
		         		if(response.success){
		         			showErrorMsg(show_message, 'success', response.message);
		 	                    
	 	                    setTimeout(function(){
	 	                    	window.location.href=base_url+redirect_page;
	 	                    },1000);
		         		}else{
		         			hide_loader();
		         			btn.attr('disabled', false);
	 	                    showErrorMsg(show_message, 'danger', response.message);
		         		}
		             }
		         });
		     });
		}
	 }
	 
	 // Public Functions
	 return {
	     // public functions
	     init: function() {
		 	handleAddUpdateCustomer();
	     }
	 };
}();

//ADD Customer Comment
var AddCustomerComment = function() {

	var showErrorMsg = function(form, type, msg) {
		var alert = $('<div class="alert alert-' + type + '">\
			<div>'+msg+'</div>\
		</div>');

        form.find('.alert').remove();
        alert.prependTo(form).fadeIn("slow");
        alert.delay(3000).fadeOut("slow");
    }
	
	var handleAddCustomerComment = function() {
		if($("#frm_add_customer_comments").length>0){
			$("#frm_add_customer_comments").on("submit",function(e) {
				e.preventDefault();
				
				show_loader();
		        var btn = $("#submit_btn");
	            var form = $(this);
	            var show_message = form;
	             
	            btn.attr('disabled', true);
		
		        var redirect_page = $("#redirect_page").val();
		         
		        /*var data = {
		        	customer_id:$("#customer_id").val(),
		        	comments:$("#comments").val()
		        };*/
		        
		        var formD = document.getElementById('frm_add_customer_comments');
				var data = new FormData(formD);
		        
		        $.ajax({
		         	type: "post",  // Request method: post, get
		         	data: data,
		            url: base_url+'customer/save_comment',
		            enctype: 'multipart/form-data',
		            processData: false,
		            contentType: false,
		            success: function(response, status, xhr, $form) {
		         		response = JSON.parse(response);
		         		if(response.success){
		         			showErrorMsg(show_message, 'success', response.message);
		 	                    
	 	                    setTimeout(function(){
	 	                    	window.location.href=base_url+redirect_page;
	 	                    },1000);
		         		}else{
		         			hide_loader();
		         			btn.attr('disabled', false);
	 	                    showErrorMsg(show_message, 'danger', response.message);
		         		}
		             }
		         });
		     });
		}
	 }
	 
	 // Public Functions
	 return {
	     // public functions
	     init: function() {
		 	handleAddCustomerComment();
	     }
	 };
}();

//ADD/UPDATE Cateogry
var AddUpdateCategory = function() {

	var showErrorMsg = function(form, type, msg) {
		var alert = $('<div class="alert alert-' + type + '">\
			<div>'+msg+'</div>\
		</div>');

        form.find('.alert').remove();
        alert.prependTo(form).fadeIn("slow");
        alert.delay(3000).fadeOut("slow");
    }
	
	var handleAddUpdateCategory = function() {
		if($("#frm_add_category").length>0){
			$("#frm_add_category").on("submit",function(e) {
				e.preventDefault();
		     	
				show_loader();
		        var btn = $("#submit_btn");
	            var form = $(this);
	            var show_message = form;
	             
	            btn.attr('disabled', true);
		
		        var redirect_page = $("#redirect_page").val();
		         
		        var data = {
		        	id:$("#id").val(),
		        	name:$("#name").val(),
		        };
		        $.ajax({
		         	type: "post",  // Request method: post, get
		         	data: data,
		            url: base_url+'category/save_category',
		            success: function(response, status, xhr, $form) {
		         		response = JSON.parse(response);
		         		if(response.success){
		         			showErrorMsg(show_message, 'success', response.message);
		 	                    
	 	                    setTimeout(function(){
	 	                    	window.location.href=base_url+redirect_page;
	 	                    },1000);
		         		}else{
		         			hide_loader();
		         			btn.attr('disabled', false);
	 	                    showErrorMsg(show_message, 'danger', response.message);
		         		}
		             }
		         });
		     });
		}
	 }
	 
	 // Public Functions
	 return {
	     // public functions
	     init: function() {
		 	handleAddUpdateCategory();
	     }
	 };
}();

//ADD/UPDATE Income
var AddUpdateIncome = function() {

	var showErrorMsg = function(form, type, msg) {
		var alert = $('<div class="alert alert-' + type + '">\
			<div>'+msg+'</div>\
		</div>');

        form.find('.alert').remove();
        alert.prependTo(form).fadeIn("slow");
        alert.delay(3000).fadeOut("slow");
    }
	
	var handleAddUpdateIncome = function() {
		if($("#frm_add_income").length>0){
			$("#frm_add_income").on("submit",function(e) {
				e.preventDefault();
		     	
				show_loader();
		        var btn = $("#submit_btn");
	            var form = $(this);
	            var show_message = form;
	             
	            btn.attr('disabled', true);
		
		        var redirect_page = $("#redirect_page").val();
		         
		        var data = {
		        	id:$("#id").val(),
		        	fk_category_id:$("#fk_category_id").val(),
		        	title:$("#title").val(),
		        	amount:$("#amount").val(),
		        	income_date:$("#income_date").val(),
		        	description:$("#description").val(),
		        };
		        $.ajax({
		         	type: "post",  // Request method: post, get
		         	data: data,
		            url: base_url+'income/save_income',
		            success: function(response, status, xhr, $form) {
		         		response = JSON.parse(response);
		         		if(response.success){
		         			showErrorMsg(show_message, 'success', response.message);
		 	                    
	 	                    setTimeout(function(){
	 	                    	window.location.href=base_url+redirect_page;
	 	                    },1000);
		         		}else{
		         			hide_loader();
		         			btn.attr('disabled', false);
	 	                    showErrorMsg(show_message, 'danger', response.message);
		         		}
		             }
		         });
		     });
		}
	 }
	 
	 // Public Functions
	 return {
	     // public functions
	     init: function() {
		 	handleAddUpdateIncome();
	     }
	 };
}();

//ADD/UPDATE Expense
var AddUpdateExpense = function() {

	var showErrorMsg = function(form, type, msg) {
		var alert = $('<div class="alert alert-' + type + '">\
			<div>'+msg+'</div>\
		</div>');

        form.find('.alert').remove();
        alert.prependTo(form).fadeIn("slow");
        alert.delay(3000).fadeOut("slow");
    }
	
	var handleAddUpdateExpense = function() {
		if($("#frm_add_expense").length>0){
			$("#frm_add_expense").on("submit",function(e) {
				e.preventDefault();
		     	
				show_loader();
		        var btn = $("#submit_btn");
	            var form = $(this);
	            var show_message = form;
	             
	            btn.attr('disabled', true);
		
		        var redirect_page = $("#redirect_page").val();
		         
		        var data = {
		        	id:$("#id").val(),
		        	fk_category_id:$("#fk_category_id").val(),
		        	title:$("#title").val(),
		        	amount:$("#amount").val(),
		        	expense_date:$("#expense_date").val(),
		        	description:$("#description").val(),
		        };
		        $.ajax({
		         	type: "post",  // Request method: post, get
		         	data: data,
		            url: base_url+'expense/save_expense',
		            success: function(response, status, xhr, $form) {
		         		response = JSON.parse(response);
		         		if(response.success){
		         			showErrorMsg(show_message, 'success', response.message);
		 	                    
	 	                    setTimeout(function(){
	 	                    	window.location.href=base_url+redirect_page;
	 	                    },1000);
		         		}else{
		         			hide_loader();
		         			btn.attr('disabled', false);
	 	                    showErrorMsg(show_message, 'danger', response.message);
		         		}
		             }
		         });
		     });
		}
	 }
	 
	 // Public Functions
	 return {
	     // public functions
	     init: function() {
		 	handleAddUpdateExpense();
	     }
	 };
}();

function toggleMenuInit(){
	if($("#menu-toggle").length>0){
		$("#menu-toggle").click(function(e) {
			e.preventDefault();
			$("#wrapper").toggleClass("toggled");
		});
	}
}

function tooltipInit(){
	if($('[data-toggle="tooltip"]').length>0){
		$('[data-toggle="tooltip"]').tooltip();
	}
}

function datepickerInit(){
	if($(".datepicker").length>0){
		$('.datepicker').datepicker({
		    format: 'yyyy-mm-dd',
		    autoclose:true
		});
	}
}

function accountManagerFilterInit(){
	if($("#customer_filter_by_account_manager").length>0){
		$("#customer_filter_by_account_manager").on("change",function(){
			
			var account_manager_id=$(this).val();
			
			if(account_manager_id==""){
				// show all tr 
				$("table > tbody > tr").show();
				
			}else{
				// go through each tbody tr and check if has class of the selected account manager, then show it, else hide
				$('table > tbody  > tr').each(function(index, tr) { 
					if($(this).hasClass("fk_accountmanager_id_"+account_manager_id)){
						// show
						$(this).show();
					}else{
						// hide
						$(this).hide();
					}
				});
			}
		});
	}
}

function categoryFilterInit(){
	if($("#filter_by_category").length>0){
		$("#filter_by_category").on("change",function(){
			
			var category_id=$(this).val();
			
			if(category_id==""){
				// show all tr 
				$("table > tbody > tr").show();
				
			}else{
				// go through each tbody tr and check if has class of the selected account manager, then show it, else hide
				$('table > tbody  > tr').each(function(index, tr) { 
					if($(this).hasClass("fk_category_id_"+category_id)){
						// show
						$(this).show();
					}else{
						// hide
						$(this).hide();
					}
				});
			}
		});
	}
}

function searchInTableInit(){
	if($("#search_in_table").length>0){
		$("#search_in_table").on("keyup",function(){
			var search_text = $(this).val().toLowerCase().trim();
			if(search_text!=""){
				
				$("table tr").each(function (index) {
			        if (!index) return;
			        $(this).find("td").each(function () {
			            var id = $(this).text().toLowerCase().trim();
			            var not_found = (id.indexOf(search_text) == -1);
			            $(this).closest('tr').toggle(!not_found);
			            return not_found;
			        });
			    });
			}else{
				// show all rows
				$("table > tbody > tr").show();
			}
			
		});
	}
}

function searcbByDateInit(){
	if($("#search_by_date").length>0){
		$("#search_by_date").on("change",function(){
			var search_date = $(this).val().toLowerCase().trim();
			if(search_date!=""){
				
				$('table > tbody  > tr').each(function(index, tr) { 
					if($(this).hasClass("followup_date_"+search_date)){
						// show
						$(this).show();
					}else if($(this).hasClass("income_date_"+search_date)){
						// show
						$(this).show();
					}else if($(this).hasClass("expense_date_"+search_date)){
						// show
						$(this).show();
					}else{
						// hide
						$(this).hide();
					}
				});
			}else{
				// show all rows
				$("table > tbody > tr").show();
			}
			
		});
	}
}

function clearSearchFiltersInit(){
	if($("#clear_search_filters").length>0){
		$("#clear_search_filters").on("click",function(){
			// empty all values, it will trigger to reset the search/filters
			$("#search_by_date").val('');
			$("#search_in_table").val('');
			$("#customer_filter_by_account_manager").val('');
			$("#filter_by_category").val('');
			
			// now trigger events for all
			$("#search_by_date").trigger('change');
			$("#search_in_table").trigger('keyup');
			$("#customer_filter_by_account_manager").trigger('change');
			$("#filter_by_category").trigger('change');
		});
	}
}

/*function updateFollowupDate(){
	$(".customer_list_followup_date_textbox").change(function(){
		var data = {
        	customer_id:$(this).attr("data-customer-id"),
        	followup_date:$(this).val()
        };
        $.ajax({
        	type: "post",  // Request method: post, get
        	data: data,
            url: base_url+'customer/update_followup_date',
            success: function(response, status, xhr, $form) {
        		response = JSON.parse(response);
        		if(response.success){
        			window.location.href=base_url;	// its the base url
        		}else{
        			setTimeout(function() {
	                    btn.attr('disabled', false);
	                    showErrorMsg(form, 'danger', response.message);
                    }, 1000);
        		}
            }
        });
	}); 
}*/

var updateFollowupDate = function() {

	var showErrorMsg = function(form, type, msg) {
		var alert = $('<div class="alert alert-' + type + '">\
			<div>'+msg+'</div>\
		</div>');

        form.find('.alert').remove();
        alert.prependTo(form).fadeIn("slow").delay(3000).fadeOut("slow");;
        //alert.delay(3000).fadeOut("slow");
    }
	
	var handleUpdateFollowupDate = function() {
		if($(".customer_list_followup_date_textbox").length>0){
			
			var show_message = $("table");
			
			$(".customer_list_followup_date_textbox").change(function(){
				var data = {
		        	customer_id:$(this).attr("data-customer-id"),
		        	followup_date:$(this).val()
		        };
		        $.ajax({
		        	type: "post",  // Request method: post, get
		        	data: data,
		            url: base_url+'customer/update_followup_date',
		            success: function(response, status, xhr, $form) {
		        		response = JSON.parse(response);
		        		
		        		showErrorMsg(show_message, 'success', response.message);
		            }
		        });
			});
		}
	 }
	 
	 // Public Functions
	 return {
	     // public functions
	     init: function() {
		 	handleUpdateFollowupDate();
	     }
	 };
}();


function show_loader(){
	$(".ajax-loading").show();
}

function hide_loader(){
	$(".ajax-loading").hide();
}

$( document ).ready(function() {
	
	// toggle menu
	toggleMenuInit();
	
	// initToolTip
	tooltipInit();
	
	// datepicker init
	datepickerInit();
	
	// login
	funcLoginSubmit.init();
	
	// my profile
	funcMyProfile.init();
	
	// change password
	funcChangePassword.init();
	
	// initialize active inactive click functionality
	ActiveInactiveRecord.init();
	
	// initialize delete record
	DeleteRecord.init();
	
	// generate password button behaviour
	GeneratePassword.init();
	
	// account manager profile add update
	AddUpdateProfile.init();
	ChangeProfilePassword.init();
	
	// customer add update
	AddUpdateCustomer.init();
	
	// customer comments
	AddCustomerComment.init();
	
	updateFollowupDate.init();

	// category add update
	AddUpdateCategory.init();

	// income add update
	AddUpdateIncome.init();
	
	// expense add update
	AddUpdateExpense.init();
	
	// filters
	accountManagerFilterInit();
	categoryFilterInit();
	searchInTableInit();
	searcbByDateInit();
	clearSearchFiltersInit();
});