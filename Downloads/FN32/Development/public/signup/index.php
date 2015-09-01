<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Textmunication signup form</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js"></script>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js"></script>
        <script type="text/javascript" src="../js/datavalidate.js"></script>
        <script type="text/javascript" >

          var uentry =["#sub_first","#sub_business","#sub_last","#sub_login","#sub_password","#sub_title","#sub_adress","#sub_city","#sub_state","#sub_email","#sub_cell","#sub_zip","#sub_cell"];
          var arcount = uentry.length;
         $(function(){
            $('#u_selecturname').hide();
            $('#u_signature').hide();
//            $('#u_creditcard_view').hide();
        });
        $(function(){
            var uentry =["#sub_first","#sub_business","#sub_last","#sub_login","#sub_password","#sub_title","#sub_adress","#sub_city","#sub_state","#sub_email","#sub_cell","#sub_zip","#sub_cell"];
             var arcount = uentry.length;
        $('#u_sign').click(function(){
           var flagdown = false;
          for(i = 0; i < arcount; i++){
               if($(uentry[i]).val() == "" ){
                 flagdown = false;
                 alert('All fields with * are mandatory');
                 $('#u_sign').removeAttr('checked');
                 return;
               }else{
                   flagdown = true;
               }
          }
          
            if(flagdown){
             var addopt = $('<option></option>').val($('#sub_first').val() +" "+$('#sub_last').val()).html($('#sub_first').val() +" "+$('#sub_last').val());
              $('#u_signature').append(addopt);   
             $('#u_selecturname').slideDown();
             $('#u_signature').slideDown();
            }
        });
        });
        
//      $(function(){
//       $('#u_creidcardinfo').click(function(){
//            $('#u_creditcard_view').show();
//           if( !$('#u_creidcardinfo').is(':checked')) {
//               $('#u_creditcard_view').hide();
//           }
//       }); 
//        });
        
        
    $(function(){
    $('#cancel_newaccount').click(function(){
         for(i = 0; i < arcount; i++){
               $(uentry[i]).val("");
          }
    });
        });
        
    $(function(){
        
    $('#sub_cardnumber').ForceNumericOnly();
    $('#sub_securecode').ForceNumericOnly();
       
   });
   
    $(function(){
    $('#sub_email').blur(function(){
        var email = $('#sub_email').val();
        if(validateEmail(email)){
            $('#e_validate').text("").hide();
        }
       else{
            $('#e_validate').text("Please enter valid email").show();
        }
    });
     });
     
     $(function(){
        $('#save_newaccount').click(function(e){
//            alert('it clicked');
                var msgplan = $('#sub_message').val();//!='smp'
                var sccode = $('#sub_card').val();       //!='sc'
                var cexpday = $('#sub_expday').val();
                var cexpyear = $('#sub_year').val();
                var csecurecode = $('#sub_securecode').val();
                var ccnumber = $('#sub_cardnumber').val();
                var send = true;
                
                for(i = 0; i < arcount; i++){
                  if($(uentry[i]).val() ==""){
                      send = false;
                      alert("Make sure all fields are filled");
                      e.preventDefault();
                      return;
                  }
               }
               if(sccode == 'sc' || cexpday == 'day' || cexpyear == 'year' || csecurecode == "" || ccnumber ==""){
                    alert('All creadit card fields are mandatory');
                   send = false;
                   e.preventDefault();
               }
                
                  var expd = $('#sub_expday').val()+"-"+$('#sub_year').val();
                  var expdm = parseInt($('#sub_expday').val());
                  var expdy = $('#sub_year').val();
                  
                  var curdate = dateFormat(new Date(),"MM-YYYY");
                  var curdatem = parseInt(curdate.substring(0,2));
                  var curdatey = curdate.substring(3);
                   if(expdm < curdatem && expdy == curdatey){
                       alert("Card Expired");
                       send = false;
                       e.preventDefault();
                   } else if(expdy < curdatey){
                       alert("Card Expired");
                       send = false;
                       e.preventDefault();
                   }else{send = true;}
               if(send){
//                   var expd = $('#sub_expday').val()+"-"+$('#sub_year').val();
                   if(msgplan == 'smp')
                    {
                        msgplan = "";
                    }   
                   var data = "firstname="+$('#sub_first').val()+"&lastname="+$('#sub_last').val() +"&title="+$('#sub_title').val()+"&address="+$('#sub_adress').val()+"&city="+$('#sub_city').val()+
                        "&state="+$('#sub_state').val() +"&zip="+$('#sub_zip').val()+"&username="+$('#sub_login').val()+"&password="+$('#sub_password').val()+
                        "&cell="+$('#sub_cell').val() +"&businessname="+$('#sub_business').val()+"&email="+$('#sub_email').val()+"&messagelimit="+msgplan+"&cardname="+$('#sub_card').val()+
                        "&cardnumber="+$('#sub_cardnumber').val()+"&cardexpdate="+expd +"&cardsecurecode="+$('#sub_securecode').val();
//                 alert(data);
//                 return;
                 var fn = $('#sub_first').val();
                 var ln = $('#sub_last').val();
                 var fl = fn+" "+ln;
                 if($('#u_signature').val() == fl ){
                  $.ajax({
                        type: "POST",
                        url: 'createaccount.php',
                        data: data,
//                        dataType: dataType,
                        success: function(data){
//                           alert(data);
                        }
                        
                  });
                   for(i = 0; i < arcount; i++){
                      $(uentry[i]).val("");
                   }
                   $('#sub_securecode').val("");
                   $('#sub_cardnumber').val("");
                   $('#sub_card').val("select credit card");
                   $('#sub_expday').val("month");
                   $('#sub_year').val("year");
                   $('#u_signature').val("select your name");
                   $('#sub_message').val("select monthly plan");
                   $('#account_success').text('Your account hasbeen set successfully').css('color','red');
              }else{
                  alert('Please check the confirm box and select your name');
              }
                   
               }
            });
  });
    $(function(){
      $('#sub_cardnumber').change(function(e){
          var cardnum = $(this).val();
//          alert(cardnum);
          if(cardnum){
              if(!cardvalidate(cardnum)){
                  $('#sub_cardvalidate').text("Not volid card number").css('color','red').css('font-size','12px');
                  $('#save_newaccount').attr('disabled', 'disabled');
                e.preventDefault();  
              }else{
                  $('#sub_cardvalidate').text("");
                  $('#save_newaccount').removeAttr('disabled')
              }
           }
          });
    });
 jQuery.fn.ForceNumericOnly =
function()
{
    return this.each(function()
    {
        $(this).keydown(function(e)
        {
            var key = e.charCode || e.keyCode || 0;
            // allow backspace, tab, delete, arrows, numbers and keypad numbers ONLY
            // home, end, period, and numpad decimal
            return (
                key == 8 || 
                key == 9 ||
                key == 46 ||
                key == 110 ||
                key == 190 ||
                (key >= 35 && key <= 40) ||
                (key >= 48 && key <= 57) ||
                (key >= 96 && key <= 105));
        });
    });
};

 function validateEmail($email) {
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  if( !emailReg.test( $email ) ) {
    return false;
  } else {
    return true;
  }
}
    
 
        
    </script>
        <style>
            .wrapper{
                margin: auto;
                /*background: #acf;*/
                background: #FFF;
                width: 100%;
                border-bottom-left-radius: 5px;
                border-bottom-right-radius: 5px;
                border-top-left-radius: 5px;
                border-top-right-radius: 5px;
            }
            .wrapper table input{
                border-collapse:collapse;
                border: 1px solid darkgreen;
                width: 200px;
                height: 15px;
            }
            .wrapper table{
                padding: 15px;
            }
            .top{
                height: 150px;
                background:#263849;
                color:white;
                font-size: 20px;
                border-top-left-radius: 5px;
                border-top-right-radius: 5px;
            }
            .top header{
                margin-left: 20px;
            }
            .tagged{
                color:red;
                size: 5px;
                margin-left: 2px;
            }
            #save_newaccount, #cancel_newaccount{
                border-bottom-left-radius: 3px;
                border-bottom-right-radius: 3px;
                border-top-left-radius: 3px;
                border-top-right-radius: 3px;
                height: 30px;
                width: 80px;
                background:#263849;
                color:#FFF;
            }
            .webform{
                position: relative;
                left:25%;
                bottom: 20px;
                margin-top: 20px;
            }
            
            #e_validate{
                color:red;
                font-size: 11px;
            }
            .lsection div{
               margin-left: 300px; 
            }
            .lsection div span{
                margin: 100px;
            }
        </style>

    </head>
    <body>
<!--     sub_first,sub_business,sub_last,sub_login,sub_adress,sub_password,sub_message!='smp',sub_state,sub_email,sub_cell,sub_zip,sub_cell,sub_card!='sc'
     sub_expday!='day',sub_year!='year'-->
     
        <div class="wrapper">
            <section class="top">   
                <div style="margin-left: 15px; float: left"><img src="../images/logo/textmunication.png" alt="Textmunication"></div>
                <div style="margin-left: 250px; float: right; margin-top: 15px; margin-right: 20px">
                  <span style="color:orange;margin-top: 15px;" id="account_success"></span>
                </div>
            </section>
            <div class="webform">
            <table cellpadding="6">
                <tr><td>First<span class="tagged">*</span></td><td><input type="text" id="sub_first" tabindex="1"></td>
                <td width="90">&nbsp;&nbsp;Business<span class="tagged">*</span></td><td><input type="text" id="sub_business" ></td></tr>
                <tr><td>Last<span class="tagged">*</span></td><td><input type="text" id="sub_last" tabindex="2"></td>
                <td>&nbsp;&nbsp;Username<span class="tagged">*</span></td><td><input type="text" id="sub_login"></td></tr>
                <tr><td>&nbsp;&nbsp;Title<span class="tagged">*</span></td><td><input type="text" id="sub_title" tabindex="3"></td></tr>
                <tr><td>Address<span class="tagged">*</span></</td><td><input type="text" id="sub_adress" tabindex="4"></td>
                <td>&nbsp;&nbsp;Password<span class="tagged">*</span></td><td><input type="text" id="sub_password"></td></tr>
                <tr><td>City<span class="tagged">*</span></td><td><input type="text" id="sub_city" tabindex="5"></td>
                 <td>&nbsp;&nbsp;Message<span class="tagged">&nbsp;</span></td><td><select id="sub_message">
                            <option value="smp" selected>select monthly plan</opiton>
                             <option value="3000">3000</opiton>   
                             <option value="5000">5000</opiton>
                            <option value="8000">8000</opiton>
                            <option value="custom">custom</opiton>
                        </select></td></tr>
                <tr><td>State<span class="tagged">*</span></td><td><input type="text" id="sub_state" tabindex="6"></td>
                <td>&nbsp;&nbsp;Email<span class="tagged">*</span></td><td><input type="text" id="sub_email"><span id="e_validate"></span></td></tr>
                <tr><td>Zip<span class="tagged">*</span></td><td><input type="text" id="sub_zip" tabindex="7"></td>
                <td>&nbsp;&nbsp;Cellphone</td><td><input type="text" id="sub_cell"></td></tr>
                
                <tr><td colspan="4"><hr></td></tr>
                <!--<tr><td colspan="4">Credit card info<input type="checkbox" id="u_creidcardinfo"></td></tr>-->
                <tr><td colspan="4"><table id="u_creditcard_view" cellpadding="4"><tr>
                    <td>Card<span class="tagged">*</span></td><td><select id="sub_card">
                            <option value="sc" selected>select credit card</option>
                            <option value="visa">Visa</option>
                            <option value="master">Master card</option>
                            <option value="ame">American express</option>
                        </select>
                    </td><td>&nbsp;&nbsp;Card #<span class="tagged">*</span></td><td><input type="text" id="sub_cardnumber"><span id="sub_cardvalidate"></span></td></tr>
                <tr><td>Exp date<span class="tagged">*</span></td><td><select id="sub_expday">
                            <option value="day" selected>month</option>
                            <option value="01">01</option>
                            <option value="02">02</option>
                            <option value="03">03</option>
                            <option value="04">04</option>
                            <option value="05">05</option>
                            <option value="06">06</option>
                            <option value="07">07</option>
                            <option value="08">08</option>
                            <option value="09">09</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                        </select>
                        <select id="sub_year">
                            <option value="year" selected>year</option>
                            <option value="2013">2013</option>
                            <option value="2014">2014</option>
                            <option value="2015">2015</option>
                            <option value="2016">2016</option>
                            <option value="2017">2017</option>
                            <option value="2018">2018</option>
                            <option value="2019">2019</option>
                            <option value="2020">2020</option>
                            <option value="2021">2021</option>
                        </select>
                    </td><td>&nbsp;&nbsp;Secure code<span class="tagged">*</span></td><td><input type="text" id="sub_securecode"></td></tr>
                <tr><td>Confirm</td><td><input type="checkbox" id="u_sign"></td><td id="u_selecturname">Select name</td><td><select id="u_signature">
                    <option value="syn">select your name</option>
                    <option value="John Wayne">Micheal Jackson</option>
                    <option value="Clint Eastwood">Barack Obama</option>
                    <option value="Al Pacino">Al Pacino</option>
                    <option value="Robert Duvall">Robert Duvall</option>
                    </select></td>
                 </tr>
                 </table>
                 </td>
                 </tr>
                 <tr><td colspan="4"><input type="button" id="save_newaccount" value="Submit">&nbsp;<input type="button" id="cancel_newaccount" value="Cancel"></td></tr>
            </table>
            </div>
            <section class="lsection"><div><span ><img src="../images/logo/satisfaction.png" alt="Satisfaction" style="width:70px; height: auto"></span><span><img src="../images/logo/nospam.png" alt="nospam"></span><span><img src="../images/logo/authorize.png" alt="Authorize"></span></div>
            </section>
        </div>
    </body>
</html>

