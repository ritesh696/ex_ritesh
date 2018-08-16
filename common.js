//*****  POPUP WINDOW *******************//
$(document).ready(function () {
	var a = $('.animated');
	setInterval(function () {
		$('.animateHeader').removeAttr('id');
		$('.animateHeader').toggleClass('hidden');
	}, 5000);


  if(login_success != '')
  {
       $.toast({
              heading: 'Login success',
              text: 'You have logged in successfully',
              position: 'top-right',
              loaderBg:'#ff6849',
              icon: 'success',
              hideAfter: 4000
		});
  }
});

$(document).bind('keydown', 'alt+f', function (e)
{
	if (e.altKey && (e.which == 70)) {
		//e.preventDefault();
		$('#txt_searchbox').focus();
		return false;
	}
});

$(document).bind('keydown', function (e)
{	
	if (e.which == 27) {
		//e.preventDefault();
		$('.my_popup').hide();
		return false;
	}
});

$(document).ready(function () {

  $(".categories_menu").mouseenter(function(){
    $("#categories_menu").removeClass('hide'); 
  });
  
  $(".branda_menu").mouseenter(function(){
    $("#branda_menu").removeClass('hide'); 
  });
  
  $(".skin_type_menu").mouseenter(function(){
    $("#skin_type_menu").removeClass('hide'); 
  });
  $(".conditions_menu").mouseenter(function(){
    $("#conditions_menu").removeClass('hide'); 
  });
  $(".experts_menu").mouseenter(function(){
    $("#experts_menu").removeClass('hide'); 
  });
  
  $(".branda_menu").mouseleave(function(){
    //console.log('demo');
	$("#branda_menu").addClass('hide'); 
	$(".menu_brand_item_sub").addClass('hide'); 
	//$("#brand_submenunewlaunches").removeClass('hide'); 
	$(".menu_brand_item_sub").first().removeClass('hide'); 
	$(".menu_category_item_sub").first().removeClass('hide'); 
  });

  $(".categories_menu").mouseleave(function(){
    //console.log('demo');
	$("#categories_menu").addClass('hide'); 
	$(".menu_category_item_sub").addClass('hide'); 
	$(".menu_brand_item_sub").first().removeClass('hide'); 
	$(".menu_category_item_sub").first().removeClass('hide'); 
  });

  $(".skin_type_menu").mouseleave(function(){
    //console.log('demo');
	$("#skin_type_menu").addClass('hide'); 
	$(".menu_brand_item_sub").first().removeClass('hide'); 
	$(".menu_category_item_sub").first().removeClass('hide'); 
  });

  $(".conditions_menu").mouseleave(function(){
    //console.log('demo');
	$("#conditions_menu").addClass('hide'); 
	$(".menu_brand_item_sub").first().removeClass('hide'); 
	$(".menu_category_item_sub").first().removeClass('hide'); 
  });

  $(".experts_menu").mouseleave(function(){
    //console.log('demo');
	$("#experts_menu").addClass('hide'); 
	$(".menu_brand_item_sub").first().removeClass('hide'); 
	$(".menu_category_item_sub").first().removeClass('hide'); 
  });

  $(".top_sub_menu").mouseenter(function(){
    console.log($(this).attr('data-id'));
	$(".top_sub_menu_item").addClass('hide'); 
	$("#"+$(this).attr('data-id')).removeClass('hide'); 
	
  });
});
function fun_mobmenu(p_passval)
{
	if(p_passval=='mobmenu')
	{
		console.log('done');
		//$('#menubar').slideToggle('slow');
		$('#mob_menu').addClass('active');
	}
	if(p_passval=='closemobmenu')
	{
		console.log('done');
		//$('#menubar').slideToggle('slow');
		$('#mob_menu').removeClass('active');
	}
}

function fun_submenumob(p_passval, p_elementid)
{
	if(p_passval=='back_to_main')
	{
		$('#sup_box').slideDown('fase');
		$('#sub_box').slideUp('slow');
	}
	else
	{
		$('.second_menu_div').addClass('hide');
		$('#'+p_elementid).removeClass('hide');
		$('#sup_box').slideUp('fase');
		$('#sub_box').slideDown('slow');		
	}
}
$(document).ready(function ()
{	
$('.mob-p-element').click(this,function()
{
	//console.log('oh no');
	if($(this).hasClass('active')==false)
	{	
		$('body,html').addClass('overflow-y-h');
		$(this).find('.nav-second-level').slideDown('fast');
		$('.mob-p-element.active .nav-second-level').slideUp('fast');
		$('.mob-p-element.active a.navtext span').removeClass('fa-angle-down');
		$('.mob-p-element.active a.navtext span').addClass('fa-angle-right');
		$('.mob-p-element').removeClass('active');
		$(this).addClass('active');
		$('.mob-p-element a.navtext span').addClass('fa-angle-right');
		$(this).find('a.navtext span').removeClass('fa-angle-right');
		$(this).find('a.navtext span').addClass('fa-angle-down');
	}
	else
	{
		$('body,html').removeClass('overflow-y-h');
		$(this).find('.nav-second-level').slideUp('fast');
		$('.mob-p-element').removeClass('active');
		$(this).find('a.navtext span').removeClass('fa-angle-down');
		$(this).find('a.navtext span').addClass('fa-angle-right');
	}
});

});




function fun_signup_popup_show()
{
	$("#login_popup").hide();
	$("#register_popup").show();
	$("#txt_fullname").focus();
}
function fun_signin_popup_show()
{
	$("#login_popup").show();
	$("#register_popup").hide();
	$("#forgotpassword_popup").hide();	
	$("#txt_username").val('');
	$("#txt_login_password").val('');
	$("#txt_username").focus();
	

}
function fun_show_forgot_password()
{
	$("#forgotpassword_popup").show();
	$("#login_popup").hide();
	$("#txt_email_phone").focus();
	$("#txt_email_phone").val('');
	$("#div_change_password").addClass('hide');
	$("#div_forgat_password").removeClass('hide');   
}


//************** user name validation *********************//
function user_name_validate(e,t,disp_error,txt_element_id) 
{ 
  var regex =/^[a-zA-Z0-9\ ]*$/;
  var tname = t.value;
  
  var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
  //alert(lname);
  var charCode = !e.charCode ? e.which : e.charCode;
  if(charCode == 8 || charCode == 0)
  {
    $("#"+txt_element_id).removeClass('error_control');
    document.getElementById(disp_error).innerHTML = '';
    return true;
  }
  else if(regex.test(str))
  {
    var flag_space = 0;
    if(tname != '')
    {
      var arr_lname = new Array();
      arr_lname = tname.split("  ");
      if(arr_lname.length>1)
      {
        flag_space = 1;
      }
    }
    if(flag_space == 1)
    {
      e.preventDefault();	
     $("#"+txt_element_id).addClass('error_control');
      document.getElementById(disp_error).innerHTML = 'Only one space allowed!';
      return false;
    }
    else
    {
     $("#"+txt_element_id).removeClass('error_control');
      document.getElementById(disp_error).innerHTML = '';
      return true;
    }
  } 
  else
  {
    e.preventDefault();
    document.getElementById(disp_error).innerHTML = 'Please enter only characters.';
    $("#"+txt_element_id).addClass('error_control');
    return false;
  }
}
function fun_remove_error_message(txt_element_id,err_element_id)  // TO remove  error message from all input 
{
  $("#"+err_element_id).text('');
  $("#"+txt_element_id).removeClass('error_control');
} 
function password_validate(txt_element_id,err_element_id)
{
	var pass = document.getElementById(txt_element_id);
    var p = /^[0-9A-Za-z!@#$%]{5,25}$/;
    var password_return_value = '';
    if(!p.test(pass.value))
    {
        $("#"+err_element_id).text('Enter minimum 5 characters.');
      //  pass.focus();
        pass.value = '';
    	$("#"+txt_element_id).addClass('error_control');
    	password_return_value = 1 ;
    	return false;

    } 
    else
    {
    	password_return_value = 0 ;
        $("#"+err_element_id).text('');
        return true;
    }  
    return password_return_value;
}   
//////////////////////////////////////////

function fun_ValidateEmail(e,email,disp_error,id) 
{
  var regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;

 if (regex.test(email.value))
  {
    var email_id = email.value;
    var customer_id = id;
    var email_return_value = '';
    document.getElementById(disp_error).innerHTML ='';
    $.ajax({
            url: base_url+'c_ajax_main_menu/email_validate',
            type:'POST',
            data:{email:email_id,
                  customer_id:customer_id},
            async:false,
          success:function(data)
          {
            //alert(data);
            if(data == 1)
            {
               document.getElementById(disp_error).innerHTML = "Email address already exists...";
               $("#txt_email").addClass('error_control');
               email.focus();
               email_return_value = 'error';  
               return false;
            
            }
            else
            {
               email_return_value = '';   
               document.getElementById(disp_error).innerHTML = "";
               return true;
            }
            
            callback.call(email_return_value);
          }      

    });

    
   
  }
  else
  {
    e.preventDefault();
    //email.focus();
    document.getElementById(disp_error).innerHTML = "You have entered an invalid email address!";
    email.value = '';
    $("#txt_email").addClass('error_control');
    return false;
    email_return_value = 'error';
  }

  return email_return_value;
}

function fun_ValidatePhoneno(e,phone,disp_error,id) 
{
  //var regex = /^\+?([0]{1})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
  var regex = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/;

 if (regex.test(phone.value))
  {
    var val_phone = phone.value;
    var customer_id = id;
    var phone_return_value = '';
    document.getElementById(disp_error).innerHTML ='';
    $.ajax({
            url:base_url+'c_ajax_main_menu/phone_validate',
            type:'POST',
            data:{phone:val_phone,
                  customer_id:customer_id},
            async:false,
          success:function(data)
          {
            //alert(data);
            if(data == 1)
            {
               phone_return_value = 'error';
               document.getElementById(disp_error).innerHTML = "Phone no. already exists...";
               phone.focus();
               return false;
            }
            else
            {
               phone_return_value = '';
               document.getElementById(disp_error).innerHTML = "";
               return true;
            }
             callback.call(phone_return_value);
          }      

    });
  }
  else
  {
    e.preventDefault();
   // phone.focus();
    document.getElementById(disp_error).innerHTML = "You have entered an invalid phone no.!";
    phone.value = '';
    return false;
    phone_return_value = 'error';
  }
  return phone_return_value;
}
function ValidatePhoneno(e,phone,disp_error) 
{
  //var regex = /^\+?([0]{1})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
  var regex = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/;

 if (regex.test(phone.value))
  {
    var val_phone = phone.value;
    var customer_id = '';
     document.getElementById(disp_error).innerHTML = "";

     return true;
  }
  else
  {
    e.preventDefault();
   // phone.focus();
    document.getElementById(disp_error).innerHTML = "You have entered an invalid phone no.!";
    phone.value = '';
    return false;
  }
}
function fun_auto_fill_address(pincode,city_element_id,l_element_id,s_element_id,c_element_id)
{
  if(pincode != '')
  {
      $("#txt_pincode").removeClass('error_control');
      $("#err_txt_pincode").text('');    
      $("#loader_save_pincode").removeClass('hide');

     $.ajax({
            url:base_url+'c_ajax_main_menu/auto_fill_address',
            type:'POST',
            data:{pincode:pincode
                  },
          success:function(data)
          {
            //alert(data);
            $("#loader_save_pincode").addClass('hide');
            Obj = JSON.parse(data);
            $("#"+city_element_id).val(Obj.city);
            $("#"+l_element_id).val(Obj.locality);
            $("#"+s_element_id).val(Obj.state);
            $("#"+c_element_id).val(Obj.country);
          }
                

    });
  }


}function fun_valid_price(evt, elementObject) // only numeric allowed
{
  var charCode = (evt.which) ? evt.which : evt.keyCode        
  
  if (charCode > 31 && (charCode != 46 || $(elementObject).val().indexOf('.') != -1) && (charCode != 45 || $(elementObject).val().indexOf('.') != -1) && (charCode < 48 || charCode > 57))
  {return false;}
  else if(charCode >= 37 && charCode <= 40)
  {return true;}
  else{return true;}

}
// USER sign up 
function new_user_singUP(e)
{
      var full_name = $("#txt_fullname").val();
      var email = $("#txt_email").val();
      var password = $("#txt_password").val();
      var c_password = $("#txt_c_password").val();

      if(full_name == '')
      {
        $("#txt_fullname").addClass('error_control');
        $("#err_txt_fullname").text('Please enter your full name.');
        $("#txt_fullname").focus();
        return false;
      }
      if(email == '')
      {
        $("#txt_email").addClass('error_control');
        $("#err_txt_email").text('Please enter email adress');
        $("#txt_email").focus();
        return false;
      }
      var em = document.getElementById("txt_email");
      if(fun_ValidateEmail(e,em,'err_txt_email') != '')
      {
        return false;
      }
      if($("#err_txt_email").val() != '')
      {
        return false;
      } 
      if(password == '')
      {
        $("#txt_password").addClass('error_control');
        $("#err_txt_password").text('Please enter password');
        $("#txt_password").focus();
        return false;
      }
      if(c_password == '')
      {
        $("#err_txt_c_password").text('Please enter confirm password');
        $("#txt_c_password").addClass('error_control');
        $("#txt_c_password").focus();
        return false;
      }
      if(password !=  c_password)
      {
        $("#err_txt_c_password").text('Password and confirm password does not match');
        $("#txt_c_password").focus();
        $("#txt_c_password").val('');
        return false;
      }
      else
      {
        var form = document.getElementById("frm_signup");
            var form_data = new FormData(form);
            $("#btn_signup").attr('disabled',true); 
            $("#loader_signup").removeClass('hide');
            $.ajax({
                    url:base_url+'/c_ajax_common/signup',
                    type:'POST',
                    data:form_data,
                    contentType:false,
                    cache:false,
                    processData:false,
                    success:function(data)
                    {
                      // alert(data);
                       if(data == 'insert')
                        {
                          $("#register_popup").hide();
                             document.getElementById("frm_signup").reset();
                             $("#txt_fullname").focus();
                             $("#btn_signup").attr('disabled',false); 
                             $("#loader_signup").addClass('hide');

                              $.toast({
                                    heading: 'Welcome to The skin store',
                                    text: 'Thank you for registration! you have successfully registered ...',
                                    position: 'top-right',
                                    loaderBg:'#ff6849',
                                    icon: 'success',
                                    hideAfter: 3500
                                    
                                  });
                              setTimeout("window.location = current_url",3400);
                            
                        }
                        else
                        {
                            $("#btn_signup").attr('disabled',false); 
                            $("#loader_signup").addClass('hide');
                             $.toast({
                                    heading: 'Warning',
                                    text: 'Somthing Wrong! please try again.',
                                    position: 'top-right',
                                    loaderBg:'#ff6849',
                                    icon: 'error',
                                    hideAfter: 3500
                                    
                                  });
                           
                        }
                    }
                });


      }

}
$(document).ready(function()
{
  // SIGN UP 
  $("#btn_signup").click(function(e){
      new_user_singUP();
  });
  $("#txt_c_password").keydown(function(e){   
    if(e.keyCode == 13)
    {
        new_user_singUP();
    }
  });
// end SIGN UP
// SIGN IN 
  $("#btn_signin").click(function(){

    fun_user_login();  
 });

   $("#txt_username").keyup(function(){
        $("#err_txt_username").text('');
    });
    $("#txt_login_password").keyup(function(){
        $("#err_txt_login_password").text('');
    });

    $("#txt_login_password").keydown(function(e){
       if(e.keyCode == 13)
       {
         fun_user_login(); 
       }
    });
// end SIGN IN

// FORGATE PASSWORD
    $("#btn_forgot_password").click(function(){
      fun_validate_forgot_password(); 
    });
    $("#txt_email_phone").keydown(function(e){
      if(e.keyCode == 13)
      {
        fun_validate_forgot_password(); 
      }
    });


    $("#txt_f_c_password").keydown(function(e){
      if(e.keyCode == 13)
      {
        fun_reset_password()
      }
    });
    $("#btn_reset_password").click(function(){
      //alert(glob_otp);  
      //alert(glob_mobile_no);  
      fun_reset_password();
    });

// end FORGATE PASSWORD

});


function fun_user_login()
	{
         // 
           var username = document.getElementById('txt_username').value;
            var login_password = document.getElementById('txt_login_password').value;
            if(username == '')
            {
               $("#err_txt_username").text("Please enter email OR phone no.");
               $("#txt_username").addClass('error_control');
               return false;
            }
            if(login_password == '')
            {
              $("#err_txt_login_password").text("Plaese enter password.");
              $("#txt_login_password").addClass('error_control');
              return false;
            }

              $("#loader_signin").removeClass('hide');
                var form = document.getElementById('frm_signin');
                var form_data = new FormData(form);
                $.ajax({
                  url:base_url+'c_ajax_common/signin',
                  type:'POST',
                  data:form_data,
                  contentType:false,
                  cache:false,
                  processData:false,
                  success:function(data){
                        
                 //   alert(data);
                    if(data == 1)
                    {

                      $("#loader_signin").addClass('hide'); 

                      /* $.toast({
                                  heading: 'Login success',
                                  text: 'You have logged in successfully',
                                  position: 'top-right',
                                  loaderBg:'#ff6849',
                                  icon: 'success',
                                  hideAfter: 3500
                                  
                                });*/
                       window.location.href=current_url;
                    }
                    else
                    {
                       $('#err_login_message').text(data);
                        $("#loader_signin").addClass('hide');
                       document.getElementById('txt_login_password').value = '';
                       return false;
                    }
                  }

                });
    } 

var glob_mobile_no = '';
var glob_otp = '';


    function fun_validate_forgot_password()
    {
      $("#loader_forgate").removeClass('hide');
       
      var em_mob = document.getElementById("txt_email_phone");
      var m_no=/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/;
      var em=  /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;

        if(em_mob.value == '')
        {   
            $("#err_txt_email_phone").text('Please Enter The Mobile No. OR E-Mail ID...');
            $("#txt_email_phone").addClass('error_control');
            $("#err_txt_email_phone").focus();
            return false; 
        }
        if(!m_no.test(em_mob.value) && !em.test(em_mob.value))
        {
			$("#err_txt_email_phone").text('Please Enter Valid Email Or Mob no.');
            $("#txt_email_phone").addClass('error_control');
            $("#err_txt_email_phone").focus();
            return false;
        }
        else
        {
           var form = document.getElementById('frm_forgotpassword');
                var form_data = new FormData(form);
                $.ajax({
                  url:base_url+'c_ajax_common/forgate_password',
                  type:'POST',
                  data:form_data,
                  contentType:false,
                  cache:true,
                  processData:false,
                  success:function(data)
				  {
                    $("#loader_forgate").addClass('hide'); 
					var v_parsedJson = JSON.parse(data);
                    if (v_parsedJson['STATUS']==0)
                    {
                        $('#err_msg_forgotpassword').text('Enter email or phone no. does not match');
                        $('#txt_email_phone').value = '';
                        $('#txt_email_phone').focus();
                        return false;
                    }
                    if (v_parsedJson['STATUS']==1)
                    {
                          $('#err_msg_forgotpassword').text('Enter email or phone no. does not match');
                           $('#txt_email_phone').value = '';
                          $('#txt_email_phone').focus();
                          return false;
                    }
                    if (v_parsedJson['STATUS']=='email')
                    {
                      //$("#success_msg_forgotpassword").text('Reset password link has been sent to your email');
                        $("#div_change_password").addClass('hide');
                        $("#div_forgat_password").removeClass('hide');                    
                        $("#forgotpassword_popup").hide();
                         $.toast({
                                  heading: 'Reset password',
                                  text: 'Reset password link has been sent to your email',
                                  position: 'top-right',
                                  loaderBg:'#ff6849',
                                  icon: 'success',
                                  hideAfter: 4000
                                  
                                });
                    }
					if (v_parsedJson['STATUS']=='phone')
                    {
                        glob_otp = v_parsedJson['OTP'];
                        glob_mobile_no =  em_mob.value;

                        $.toast({
                                  heading: 'Reset password',
                                  text: 'OTP has been sent to your mobile no.',
                                  position: 'top-right',
                                  loaderBg:'#ff6849',
                                  icon: 'success',
                                  hideAfter: 4000
                                });
                        $("#txt_otp").val('');  
                        $("#txt_f_new_password").val(''); 
                        $("#txt_f_c_password").val(''); 
						//  $("#success_msg_forgotpassword").text('OTP has been sent to your mobile no.')
                        $("#div_change_password").removeClass('hide');
                        $("#div_forgat_password").addClass('hide');
                    }
                  }

                });
        }
    } 
    function fun_reset_password()
    {
      $("#loader_reset").removeClass('hide');
      var otp = $("#txt_otp").val();
      var new_password = $("#txt_f_new_password").val();
      var c_password = $("#txt_f_c_password").val();

      if(otp == '')
      {
        $("#err_txt_otp").text('Please enter OTP.');
        $("#txt_otp").addClass('error_control');
        $("#txt_otp").focus();
        return false; 
      }
      if(otp != glob_otp)
      {
        $("#err_txt_otp").text('INVALID OTP.');
        $("#txt_otp").addClass('error_control');
        $("#txt_otp").focus();
        return false;
      } 
      if(new_password == '')
      {
        $("#err_txt_f_new_password").text('Please enter new password.');
        $("#txt_f_new_password").addClass('error_control');
        $("#txt_f_new_password").focus();
        return false;
      }
    if(c_password == '')
    {
      $("#err_txt_f_c_password").text('Please enter confirm password');
      $("#txt_f_c_password").addClass('error_control');
      $("#txt_f_c_password").focus();
      return false;
    }
    if(new_password !=  c_password)
    {
      $("#err_txt_f_c_password").text('Password and confirm password does not match');
      $("#txt_f_c_password").val('');
      return false;
    }
    else
    {
       $.ajax({
                  url:base_url+'c_ajax_common/reset_password',
                  type:'POST',
                  data:{mob_no:glob_mobile_no,
                      password:new_password},
                  success:function(data){
                     if(data == '1')
                    {
                      glob_otp = '';
                      glob_mobile_no = '';
                      $("#forgotpassword_popup").hide();
                      $("#loader_reset").addClass('hide');
                      document.getElementById("frm_forgotpassword").reset();
                        $.toast({
                                heading: 'Reset password',
                                text: 'Your password has been changed successfully.',
                                position: 'top-right',
                                loaderBg:'#ff6849',
                                icon: 'success',
                                hideAfter: 4000
                                
                              });
                         
                }
                  }

                });
    }
      

    }

// ADD NEW CUSTOMER ADDRESS
function field_validate()
{
    var address_name = $("#txt_addressname").val();
        var address_phone = $("#txt_addressphoneno").val();
        var address = $("#txt_address").val();
        var locality = $("#txt_locality").val();
        var pincode = $("#txt_pincode").val();
        var city = $("#txt_city").val();
        var state = $("#txt_state").val();
        var country = $("#txt_country").val();
        var address_type = $('input[name="radio_address_type"]:checked').val();
      //  alert(address_type);
        
       
        if(address_name.trim() == '' )
        {
            $("#err_txt_addressname").text('Please enter address name.');
            $("#txt_addressname").focus();
            $("#txt_addressname").addClass('error_control');
            return false;
        }
        if(address_phone.trim() == '' )
        {
            $("#err_txt_addressphoneno").text('Please enter address phone no.');
            $("#txt_addressphoneno").focus();
            $("#txt_addressphoneno").addClass('error_control');
            return false;
        }
        if(address.trim() == '' )
        {
            $("#err_txt_address").text('Please enter address.');
            $("#txt_address").focus();
            $("#txt_address").addClass('error_control');
            return false;
        }
        
        if(pincode.trim() == '' )
        {
            $("#err_txt_pincode").text('Please enter pin code.');
            $("#txt_pincode").focus();
            $("#txt_pincode").addClass('error_control');
            return false;
        }
        if(city.trim() == '' )
        {
            $("#err_txt_city").text('Please enter city.');
            $("#txt_city").focus();
            $("#txt_city").addClass('error_control');
            return false;
        }
        if(locality.trim() == '' )
        {
            $("#err_txt_locality").text('Please enter locality.');
            $("#txt_locality").focus();
            $("#txt_locality").addClass('error_control');
            return false;
        }
        if(state.trim() == '' )
        {
            $("#err_txt_state").text('Please enter state.');
            $("#txt_state").focus();
            $("#txt_state").addClass('error_control');
            return false;
        }
        if(country.trim() == '' )
        {
            $("#err_txt_country").text('Please enter country.');
            $("#txt_country").focus();
            $("#txt_country").addClass('error_control');
            return false;
        }
        if(address_type == null )
        {
            $("#err_radio_address_type").text('Please select address type.');
            return false;
        }

        else
        {
          return true;
        }
}

function fun_user_add_address()
{
  $("#useraddres_popup").show();

  $("#popup_title_address").text('Add New Address');
  document.getElementById("frm_updateaddress").reset();
}

function fun_user_edit_address(id,default_id) // Edit customer address
{
    
    $("#useraddres_popup").show();
    $("#txt_addressID").val(id);

    $("#popup_title_address").text('Update Address');
    $("#txt_addressname").val($("#name_"+id).text());
      $("#txt_addressphoneno").val($("#phone_"+id).text());
      $("#txt_address").val($("#address_"+id).text());
      $("#txt_locality").val($("#locality_"+id).text());
      $("#txt_pincode").val($("#pincode_"+id).text());
      $("#txt_city").val($("#city_"+id).text());
      $("#txt_state").val($("#state_"+id).text());
      $("#txt_country").val($("#country_"+id).text());

      var add_type = $("#add_type_"+id).text();
      $("input[name=radio_address_type][value="+add_type+"]").prop("checked",true);

      if(default_id == 1)
      {
        document.getElementById("chk_default_address").checked = true;
      }
      else
      {
        document.getElementById("chk_default_address").checked = false; 
      }

    
}
// address delete confirmation
 function alert_message_delete_confirmation(id,function_name,message)
 {
  swal({   
            title: "Are you sure?",   
            text: message,   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Yes",   
            closeOnConfirm: false,
      }, 

        function(){
        function_name(id);
        });
 }
  function delete_address(address_id)
  {
     $.ajax({

        url:base_url+'c_ajax_main_menu/delete_address',
        type:'POST',
        data:{address_id:address_id},
        success:function(data)
        {
           if(data == 0)
            {
               swal({
                      title:'',
                      text:'Address deleted successfully...',
                      type:''
                  },
                  function(){
                    
                 /* $.toast({
                        heading: 'My acoount delete address',
                        text: 'Address deleted successfully...',
                        position: 'top-right',
                        loaderBg:'#ff6849',
                        icon: 'success',
                        hideAfter: 2000 
                        
                      });*/
                  window.location = base_url+'my_account/address'
              // setTimeout("window.location = base_url+'my_account/address'",2000);
              });  
            }
            else
            {
                   $.toast({
                        heading: 'My acoount delete address',
                        text: 'Somthing Wrong! please try again.',
                        position: 'top-right',
                        loaderBg:'#ff6849',
                        icon: 'error',
                        hideAfter: 2000 
                        
                      });
              
            }
        }
    });    
  }  
function make_default_address(address_id)
{
  var user_id = $("#txt_customerID").val();
    $.ajax({

        url:base_url+'c_ajax_main_menu/make_default_address',
        type:'POST',
        data:{address_id:address_id,
            user_id:user_id},
        success:function(data)
        {
           if(data == 0)
            {
                swal({
                      title:"",
                      text:'Successfully set as default address ',
                      type:''
                  },
                  function(){
                    
                  window.location = base_url+"my_account/address";         
                  });
                
            }
            else
            {
                swal("", "Somthing Wrong! please try again.", "warning"); 
            }
        }
    });    
}



$(document).ready(function(){


  $("#btn_submit_address").click(function(e){ // for add new address

     var address_name = $("#txt_addressname").val();
        var address_phone = $("#txt_addressphoneno").val();
        var address = $("#txt_address").val();
        var locality = $("#txt_locality").val();
        var pincode = $("#txt_pincode").val();
        var city = $("#txt_city").val();
        var state = $("#txt_state").val();
        var country = $("#txt_country").val();
        var address_type = $('input[name="radio_address_type"]:checked').val();

       if(field_validate())
       {
		var form = document.getElementById("frm_addaddress");
		var form_data = new FormData(form);
			$("#btn_submit_address").addClass('disabled'); 
			$("#loader_save_add").removeClass('hide');
			$.ajax({
                url: base_url+'c_ajax_main_menu/insert_customer_address',
                type:'POST',
                data:form_data,
                contentType:false,
                cache:false,
                processData:false,
                success:function(data)
                {
                  // alert(data);
                 var Obj = JSON.parse(data);
           
                   if(Obj.status == 'insert')
                    {
                         //document.getElementById("frm_addaddress").reset();
                         // $("#btn_submit_address").attr('disabled',false); 
						 $("#btn_submit_address").removeclass('disabled'); 
                         $("#loader_save_add").addClass('hide');
                         $("#useraddres_popup").hide();
                       
                         $.toast({
                                heading: 'My acoount add new address',
                                text: 'Your new address added successfully...',
                                position: 'top-right',
                                loaderBg:'#ff6849',
                                icon: 'success',
                                hideAfter: 2000 
                                
                              });
                          
                       setTimeout("window.location = base_url+'my_account/address'",2000);   
                       //setTimeout("window.location = address",3400);         
                       
                    }
                   else if(Obj.status == 'update')
                    {
                        document.getElementById("frm_addaddress").reset();
                         $("#btn_submit_address").attr('disabled',false); 
                         $("#loader_save_add").addClass('hide');
                         $("#useraddres_popup").hide();
                             $.toast({
                                heading: 'My acoount update address',
                                text: 'Address updated successfully...',
                                position: 'top-right',
                                loaderBg:'#ff6849',
                                icon: 'success',
                                hideAfter: 2000 
                                
                              });
                          
                       setTimeout("window.location = base_url+'my_account/address'",2000);
                        
                    } 
                    else
                    {
                        $("#btn_submit_address").attr('disabled',false); 
                        $("#loader_save_add").addClass('hide');
                           $.toast({
                                heading: 'My acoount address',
                                text: 'Somthing Wrong! please try again.',
                                position: 'top-right',
                                loaderBg:'#ff6849',
                                icon: 'error',
                                hideAfter: 2000 
                                
                              });
                        
                    }
                }
            });
        }

    });




  $("#btn_save_newletter").click(function(e){

      
      fun_validate_email_newsletter(e);
  });

});


// start news letter
function fun_savenewsletter(e)
{
  if(e.keyCode == 13)
  {
    fun_validate_email_newsletter(e);
  }
} 
function fun_validate_email_newsletter(e)
{
  var newsletter_email = $("#txt_email_newsletter").val();
  var regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
  if(newsletter_email == '')
  {
    e.preventDefault();
    $("#err_txt_email_newsletter").text("Please enetr email");
    $("#txt_email_newsletter").val('');  
    $("#txt_email_newsletter").addClass('error_control');
    return false; 
  }
  if (regex.test(newsletter_email))
  {
    $("#err_txt_email_newsletter").text("");
    $("#txt_email_newsletter").removeClass('error_control');

      var form = document.getElementById("frm_newsletter");
      var form_data = new FormData(form);
      $("#btn_save_newletter").attr('disabled',true); 
      $("#loader_newsletter").removeClass('hide');      
            $.ajax({
                    url:base_url+'/c_ajax_common/subscribe_newsletter',
                    type:'POST',
                    data:form_data,
                    contentType:false,
                    cache:false,
                    processData:false,
                    success:function(data)
                    {
                      // alert(data);
                       if(data == '1' || data == '2')
                        {
                           // document.getElementById("frm_newsletter").reset();
                             $("#txt_email_newsletter").val('');
                             $("#btn_save_newletter").attr('disabled',false); 
                             $("#loader_newsletter").addClass('hide');
                             $("#success_msg").text('Successfully subscribe for newsletter.').fadeIn();
                             $("#success_msg").delay(2000).fadeOut(); 
                        }
                        else
                        {
                            $("#btn_signup").attr('disabled',false); 
                            $("#err_txt_email_newsletter").text("Somthing Wrong! please try again.").fadeIn();
                            $("#err_txt_email_newsletter").delay(2000).fadeOut(); 
                        }
                    }
                });

  }
  else
  { 
    e.preventDefault();
    $("#err_txt_email_newsletter").text("You have entered an invalid email address!");
    $("#txt_email_newsletter").val('');  
    $("#txt_email_newsletter").addClass('error_control');
      return false;
  } 

}

// change password 
$(document).ready(function() {
    
    $("#btn_change_password").click(function(){
        fun_change_password();
    });

    $("#txt_c_password").keydown(function(e){
        if(e.keyCode == 13)
        {
            fun_change_password();
        }
    });

});

function fun_change_password()
{
    var old_password = $("#txt_old_password").val();
    var new_password = $("#txt_new_password").val();
    var c_password = $("#txt_c_password").val();

    if(old_password == '')
    {
        $("#txt_old_password").addClass('error_control');
        $("#err_txt_old_password").text('Please enter old password');
        $("#txt_old_password").focus();
        return false;
    }
    if(new_password == '')
    {
        $("#txt_new_password").addClass('error_control');
        $("#err_txt_new_password").text('Please enter new password');
        $("#txt_new_password").focus();
        return false;
    }
    if(c_password == '')
    {
        $("#err_txt_c_password").text('Please enter confirm password');
        $("#txt_c_password").addClass('error_control');
        $("#txt_c_password").focus();
        return false;
    }
    if(new_password !=  c_password)
    {
        $("#err_txt_c_password").text('New password  and confirm password does not match');
        $("#txt_c_password").focus();
        $("#txt_c_password").val('');
        return false;
    }
    else
    {
            var form = document.getElementById("frm_changepassword");
            var form_data = new FormData(form);
            $("#btn_change_password").attr('disabled',true); 
            $("#loader_change_password").removeClass('hide');
            $.ajax({
                    url: base_url+'c_ajax_common/change_password',
                    type:'POST',
                    data:form_data,
                    contentType:false,
                    cache:false,
                    processData:false,
                    success:function(data)
                    {
                      // alert(data);
                       if(data == '0')
                        {
                             document.getElementById("frm_changepassword").reset();
                             $("#btn_change_password").attr('disabled',false); 
                            $("#loader_change_password").addClass('hide');
                             $.toast({
                                heading: 'My acoount change password',
                                text: 'Password change successfully...',
                                position: 'top-right',
                                loaderBg:'#ff6849',
                                icon: 'success',
                                hideAfter: 3500 
                                
                              });
                          
                       // setTimeout("window.location = base_url+'my_account'",2000);
                          
                        }
                        else if(data == '1')
                        {
                            $("#btn_change_password").attr('disabled',false); 
                            $("#txt_old_password").focus();
                            $("#loader_change_password").addClass('hide');
                             $.toast({
                                heading: 'My acoount change password',
                                text: 'Old password does not match.',
                                position: 'top-right',
                                loaderBg:'#ff6849',
                                icon: 'error',
                                hideAfter: 5000 
                              });
                           
                        }
                        else
                        {
                            $("#btn_change_password").attr('disabled',false); 
                            $("#loader_change_password").addClass('hide');
                            $.toast({
                                heading: 'My acoount change password',
                                text: 'Somthing Wrong! please try again.',
                                position: 'top-right',
                                loaderBg:'#ff6849',
                                icon: 'error',
                                hideAfter: 4000 
                              });

                        }
                    }
                });

    }

}


//  Category and brand 

function fun_filtershowhide(p_icon, p_fltbody)
{
  //console.log(p_icon, p_fltbody);
  if($('#'+p_icon).hasClass('fa-angle-down'))
  {
    $('#'+p_icon).removeClass('fa-angle-down');
    $('#'+p_icon).addClass('fa-angle-up');
  }
  else
  {
    $('#'+p_icon).addClass('fa-angle-down');
    $('#'+p_icon).removeClass('fa-angle-up');
  }
  
  if($('#'+p_fltbody).css('display')=='none')
  {
    $('#'+p_fltbody).slideDown();   
  }
  else
  {
    $('#'+p_fltbody).slideUp();   
  }
  
}
function fun_show_super_sub_cat(id)
{
  var chk = document.getElementById('chk_sub_category_'+id).checked;
  //alert(chk);
  if(chk == true)
  {
    
    $("#ul_"+id).removeClass('hide');
    $("#ul_"+id).slideDown();
  }
  else
  {
    $("#ul_"+id).addClass('hide');  
    $("#ul_"+id).slideUp(); 
    var checkboxes = document.getElementsByClassName("super_"+id); //checkbox items
  //deselect all checkboxes
      for (i = 0; i < checkboxes.length; i++) { 
          checkboxes[i].checked = false;
      }
  }
  
}

function fun_clear_filter(val,f_type)
{
  var checkboxes = document.getElementsByClassName("checkbox"); //checkbox items
  //deselect all checkboxes
      for (i = 0; i < checkboxes.length; i++) { 
          checkboxes[i].checked = false;
      }
  
  $("#txt_price_from").val('');
  $("#txt_price_to").val(''); 
  glob = '';  
  var start_id ='';
  var sort_by = '';
  arr_category = [];
  arr_sub_category = [];
  arr_super_sub_category = [];
  arr_brand_id = [];
  arr_price = [];
  arr_conditions = [];
  arr_discount = [];
  arr_skin_type = [];
  var price_from = '';
  var price_to = '';
  var arr_skin_type = [];  
  search_byname = '';
 if(f_type == 'brand')
 {
	arr_brand_id.push(val); 
 }
 else
 {
	var arr_val = val.split("-");
	var type = arr_val[0];
	var id = arr_val[1];
	if(type == 'ct')
	{
		arr_category.push(id);
	}
	if(type == 'sc')
	{
		arr_sub_category.push(id);
	}
	if(type == 'ss')
	{
		arr_super_sub_category.push(id);
	}
	if(type == 'c')
	{
		arr_conditions.push(id);
	}
	if(type == 's')
	{
		arr_skin_type.push(id);	
	}
	if(type != '' && id == undefined)
	{
		arr_brand_id.push(type);	
	}
		
 }	 
	

  fun_filter_product(search_byname,arr_category,arr_sub_category,arr_super_sub_category,arr_brand_id,arr_price,arr_conditions,arr_discount,price_from,price_to,arr_skin_type,start_id,sort_by); 
  
  
}

function fun_search_data(e,val,table_name)
{
  if(e.keyCode == 13)
  {
    var array_id = $("#txt_arr_"+table_name+"_id").val();

    $.ajax({
        type:'POST',
        url: base_url+"c_ajax_site_home/search_data_for_filter",
        data:{txt_value:val,
          table_name:table_name,
          array_id:array_id},
        success:function(data)
        {
          //alert(data);
          $("#div_body").html("");
          $("#div_body").append(data);

        }

    });
  }
}
  
function fun_select_value(id)
{
  glob = '';
  var start_id ='';
 

  var sort_by = $("#sel_sort_by").val();

  var price_from = $("#txt_price_from").val();
  var price_to = $("#txt_price_to").val();
  
  var name = $("#txt_hidden_brand"+id).val();
  
  var val = document.getElementById('chk_brand_'+id).checked;
  
  if(val == true)
  {
    
      arr_brand_id.push(id);
      arr_brand_name.push(name);  
    
  
  } 
  else
  {
      
      var index_id = arr_brand_id.indexOf(id);
      if(index_id > -1)
      {
        arr_brand_id.splice(index_id,1);
      }
      var index_name = arr_brand_name.indexOf(name);
      if(index_name > -1)
      {
        arr_brand_name.splice(index_name,1);
      }
    
  }
  //alert(JSON.stringify(arr_brand_id));

  $("#txt_arr_brand_id").val(JSON.stringify(arr_brand_id));
  $("#txt_arr_brand_name").val(JSON.stringify(arr_brand_name));
 
  fun_filter_product(search_byname,arr_category,arr_sub_category,arr_super_sub_category,arr_brand_id,arr_price,arr_conditions,arr_discount,price_from,price_to,arr_skin_type,start_id,sort_by);
  
}

function fun_chk_sub_category(id)
{
  glob = '';
  var start_id ='';

  var sort_by = $("#sel_sort_by").val();
  var price_from = $("#txt_price_from").val();
  var price_to = $("#txt_price_to").val();

  var val = document.getElementById('chk_sub_category_'+id).checked;
  
  if(val == true)
  {
    
      arr_sub_category.push(id);
  } 
  else
  {
      var index_id = arr_sub_category.indexOf(id);
      if(index_id > -1)
      {
        arr_sub_category.splice(index_id,1);
      }
  }



  fun_filter_product(search_byname,arr_category,arr_sub_category,arr_super_sub_category,arr_brand_id,arr_price,arr_conditions,arr_discount,price_from,price_to,arr_skin_type,start_id,sort_by);
}
function fun_chk_super_sub_category(id)
{
  glob = '';
  var start_id ='';

  var sort_by = $("#sel_sort_by").val();
  var price_from = $("#txt_price_from").val();
  var price_to = $("#txt_price_to").val();
  
  var val = document.getElementById('chk_super_sub_category_'+id).checked;
  
  if(val == true)
  {
    
      arr_super_sub_category.push(id);
  } 
  else
  {
      var index_id = arr_super_sub_category.indexOf(id);
      if(index_id > -1)
      {
        arr_super_sub_category.splice(index_id,1);
      }
  }

  fun_filter_product(search_byname,arr_category,arr_sub_category,arr_super_sub_category,arr_brand_id,arr_price,arr_conditions,arr_discount,price_from,price_to,arr_skin_type,start_id,sort_by);
}
function fun_chk_price(id)
{

//  alert(id.value);

  glob = '';
  var start_id ='';
  var sort_by = $("#sel_sort_by").val();
 
  var price_from = $("#txt_price_from").val();
  var price_to = $("#txt_price_to").val();
  

  var val = document.getElementById('chk_price_'+id).checked;
  
  if(val == true)
  {
    
      arr_price.push(id);
  } 
  else
  {
      var index_id = arr_price.indexOf(id);
      if(index_id > -1)
      {
        arr_price.splice(index_id,1);
      }
  }


  fun_filter_product(search_byname,arr_category,arr_sub_category,arr_super_sub_category,arr_brand_id,arr_price,arr_conditions,arr_discount,price_from,price_to,arr_skin_type,start_id,sort_by);
}

function fun_search_by_price_range()
{

  glob = '';
  var start_id ='';
  var sort_by = $("#sel_sort_by").val();
 
  var price_from = $("#txt_price_from").val();
  var price_to = $("#txt_price_to").val();
 

   fun_filter_product(search_byname,arr_category,arr_sub_category,arr_super_sub_category,arr_brand_id,arr_price,arr_conditions,arr_discount,price_from,price_to,arr_skin_type,start_id,sort_by); 
}


function fun_chk_conditions(id)
{
  glob = '';
  var start_id ='';

  var sort_by = $("#sel_sort_by").val();
  var price_from = $("#txt_price_from").val();
  var price_to = $("#txt_price_to").val();
  


  var val = document.getElementById('chk_conditions_'+id).checked;
  
  if(val == true)
  {
    
      arr_conditions.push(id);
  } 
  else
  {
      var index_id = arr_conditions.indexOf(id);
      if(index_id > -1)
      {
        arr_conditions.splice(index_id,1);
      }
  }


  fun_filter_product(search_byname,arr_category,arr_sub_category,arr_super_sub_category,arr_brand_id,arr_price,arr_conditions,arr_discount,price_from,price_to,arr_skin_type,start_id,sort_by);
}

function fun_chk_discount(id)
{
  glob = '';
  var start_id ='';

  var sort_by = $("#sel_sort_by").val();
  var price_from = $("#txt_price_from").val();
  var price_to = $("#txt_price_to").val();
  


  var val = document.getElementById('chk_discount_'+id).checked;
  
  if(val == true)
  {
    
      arr_discount.push(id);
  } 
  else
  {
      var index_id = arr_discount.indexOf(id);
      if(index_id > -1)
      {
        arr_discount.splice(index_id,1);
      }
  }


  fun_filter_product(search_byname,arr_category,arr_sub_category,arr_super_sub_category,arr_brand_id,arr_price,arr_conditions,arr_discount,price_from,price_to,arr_skin_type,start_id,sort_by);
}

function next_page(start_id)
{
  glob = '';
  var sort_by = $("#sel_sort_by").val();
  //var start_id = id;
  var price_from = $("#txt_price_from").val();
  var price_to = $("#txt_price_to").val();
  // var search_value = $("#txt_searchbox").val();
 

  fun_filter_product(search_byname,arr_category,arr_sub_category,arr_super_sub_category,arr_brand_id,arr_price,arr_conditions,arr_discount,price_from,price_to,arr_skin_type,start_id,sort_by);
}

function fun_sort_by_product(sort_by)
{
  glob = '';
  var start_id = '';
  var price_from = $("#txt_price_from").val();
  var price_to = $("#txt_price_to").val();
  

  fun_filter_product(search_byname,arr_category,arr_sub_category,arr_super_sub_category,arr_brand_id,arr_price,arr_conditions,arr_discount,price_from,price_to,arr_skin_type,start_id,sort_by);
}





function fun_filter_product(search_byname,arr_category,arr_sub_category,arr_super_sub_category,arr_brand_id,arr_price,arr_conditions,arr_discount,price_from,price_to,arr_skin_type,start_id,sort_by)
{


    $("#div_filter_product").empty();
    $("#div_pagination").empty();
    $("#loader").removeClass('hide');
    $.ajax({
                url: base_url+"c_ajax_site_home/filter_product",
                type:'GET',

                dataType:'JSON',
                data:{search_value_byname:search_byname, 
                       arr_category:arr_category,
                      arr_sub_category:arr_sub_category,
                       arr_super_sub_category:arr_super_sub_category,
                       arr_brand_id:arr_brand_id,
                       arr_price:arr_price,
                       arr_conditions:arr_conditions,
                       arr_discount:arr_discount,
                       price_from:price_from,
                       price_to:price_to,
                       arr_skin_type:arr_skin_type,
                       start:start_id,
                       sort_by:sort_by
           },
                success:function(data)
                {
				   // alert(JSON.stringify(data));
					if(glob != 'window_load')
					{
					$("#div_banner_product_section").addClass('hide');
					
					}
					
					$("#txt_serchbox").focus();
					$("#loader").addClass('hide');
					//$("#div_total_records").empty();
					// var jsonObj = $.parseJSON(data);
					$(jQuery.parseJSON(JSON.stringify(data))).each(function(){
					  //alert(this.pagination);
					  $("#div_filter_product").append(this.records);
					  $("#div_pagination").append(this.pagination);
					  
					  //$("#div_total_records").append(this.total_records);
					});         
                }
                
            });
}

//----

function fun_proqty(p_passtype)
{
	var v_p_qty= $('#product_qty').val();
	var v_one='1';
	if(p_passtype=="plush")
	{
		//console.log(v_p_qty);
		v_p_qty = (Number(v_p_qty) + Number(v_one));
		$('#product_qty').val(v_p_qty);
		return false ;
	}
	if(p_passtype=='minush')
	{
		if(v_p_qty>1)
		{
			v_p_qty = (Number(v_p_qty) - Number(v_one));
			$('#product_qty').val(v_p_qty);
			return false ;
		}
	}
}
function  fun_add_to_cart(product_id,model_no, p_proqty,p_passtype)
{
	var v_adddata='';
	var v_qty=p_proqty;
	console.log(product_id,model_no,p_proqty);
	if(p_proqty=='undefined')
	{
		v_qty = 1;
	}
	
  if(product_id != '')
  {
      $.ajax({
            type:'POST',
            url: base_url+"c_ajax_site_home/add_to_cart",
            data:{
				product_id:product_id,
				model_no:model_no,
				qty:v_qty
				},
            success:function(data)
            {
              //alert(data);
              $("#header_cart").html('');
              $("#header_cart").html(data);
				v_adddata='done';
				if(p_passtype!='buy_now')
				{			
					$.toast({
							heading: 'Add to cart',
							text: 'Product has been added to cart.',
							position: 'top-right',
							loaderBg:'#ff6849',
							icon: 'success',
							hideAfter: 3500 							
						});
				}
				else
				{
					//console.log('jaydeep');
					window.location.href = base_url+'cart';
				}
            }

        });
  }
  
}
function fun_add_to_wishlist(product_id,model_no)
{
  if(product_id != '')
  {
      $.ajax({
            type:'POST',
            url:base_url+"c_ajax_site_home/add_to_wishlist",
            data:{
                product_id:product_id,
                model_no:model_no 
                },
            success:function(data)
            {
            
				if(data == 1)
				{ 
                  $("#heart_wishlist_"+product_id).removeClass('fa-heart-o');
                  $("#heart_wishlist_"+product_id).addClass('fa-heart');
                  $.toast({
                            heading: 'Add to wishlist',
                            text: 'Product has been added to wishlist.',
                            position: 'top-right',
                            loaderBg:'#ff6849',
                            icon: 'success',
                            hideAfter: 3500 
                            
                          });
                }
                else if(data == 0)
                {
                  $("#heart_wishlist_"+product_id).removeClass('fa-heart');
                  $("#heart_wishlist_"+product_id).addClass('fa-heart-o');
                   $.toast({
                            heading: 'Remove from wishlist',
                            text: 'Product has been removed from wishlist.',
                            position: 'top-right',
                            loaderBg:'#ff6849',
                            icon: 'error',
                            hideAfter: 3500 
                            
                          });

                }
            }

        });
  }
}

// For Desktop site
function fun_setfocus(evt,p_ulid)
{
	//console.log('123');
	var charCode = (evt.which) ? evt.which : event.keyCode
	if (charCode == 40)
	{
		$('#search_list_id li').first().focus();
	}
}
//$('#search_list_id').keydown();

$('#search_list_id').on('focus', 'li', function() {
    var $this = $(this);
    $this.addClass('active').siblings().removeClass();
    $this.closest('#search_list_id').scrollTop($this.index() * $this.outerHeight());
}).on('keydown', 'li', function(e) {
    var $this = $(this);
    if (e.keyCode === 40) {        
        $this.next().focus();
        return false;
    } else if (e.keyCode === 38) {        
        $this.prev().focus();
        return false;
    }
}).find('li').first().focus();

// For Mobile site
function fun_setfocus_M(evt,p_ulid)
{
  //console.log('123');
  var charCode = (evt.which) ? evt.which : event.keyCode
  if (charCode == 40)
  {
    $('#search_list_id_M li').first().focus();
  }
}
//$('#search_list_id').keydown();

$('#search_list_id_M').on('focus', 'li', function() {
    var $this = $(this);
    $this.addClass('active').siblings().removeClass();
    $this.closest('#search_list_id_M').scrollTop($this.index() * $this.outerHeight());
}).on('keydown', 'li', function(e) {
    var $this = $(this);
    if (e.keyCode === 40) {        
        $this.next().focus();
        return false;
    } else if (e.keyCode === 38) {        
        $this.prev().focus();
        return false;
    }
}).find('li').first().focus();






function set_tabindex(p_elementid)
{
	var v_tabindex=10;
	$("body  #"+p_elementid+" li,body  #"+p_elementid).each(function(index)
	{// Reset Inward master tab index STARTS
		$(this).attr("tabindex",v_tabindex++);
		//console.log($(this).attr("id")+"----"+$(this).attr("tabindex"));
	});
}
function search_autofill(e_value,disp_id)
{

  //alert(e_value);
  var min_length = 1; // min caracters to display the autocomplete
  var search_val = e_value;//$("#txt_searchbox").val();
  if (search_val.length >= min_length)
  {
    $.ajax({
      url: base_url+'c_ajax_site_home/auto_search_fill',
      type: 'POST',
      data: {search_val:search_val},
      success:function(data){
        //alert(data);
        $('#'+disp_id).show();
        $('#'+disp_id).html(data);
        set_tabindex(disp_id);
      }
    });
  }
  else 
  {
    $('#'+disp_id).hide();
  }
  
}

function set_item(url,id,e_type)
{
  if(e_type == 'product')
  {
    window.location = base_url+url+'/'+id;
  }
  else
  {
    window.location = base_url+'search/'+url+'/'+id; 
  }

   
}

function fun_set_item(url,id,e,e_type)
{
  if(e.keyCode == 13)
  {
    set_item(url,id,e_type);
  }
}
var search_byname = '';
$("#txt_searchbox").keydown(function(e){

  search_byname = $("#txt_searchbox").val();
  if(e.keyCode == 13)
  {
       window.location = base_url+'search'+'/'+search_byname;     
  }
});

function fun_searchpopup(p_passval)
{
	// console.log('hie');
	if(p_passval=='close')
	{
		$('#search_popup').removeClass('active');
		// $('#search_popup').hide();
		return false ;
	}
	$('#search_popup').show();
	$('#search_popup').addClass('active');
	// $('#txt_search_mob').focus();
}
