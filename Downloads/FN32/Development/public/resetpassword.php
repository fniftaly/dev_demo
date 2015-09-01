<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <title>Reset password</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <style>
             #wrap{
                margin: 20px auto;
                border: 1px solid blue;
                background: #bbc;
                width: 400px;
                height: 100px;
            }
        </style>
        <script type="text/javascript" src="js/jquery/jquery.1.4.2.min.js"></script>
        
        <script type="text/javascript">
            var qs = (function(a) {
                if (a == "") return {};
                var b = {};
                for (var i = 0; i < a.length; ++i)
                {
                    var p=a[i].split('=');
                    if (p.length != 2) continue;
                    b[p[0]] = decodeURIComponent(p[1].replace(/\+/g, " "));
                }
                return b;
            })(window.location.search.substr(1).split('&'));
            
        $(function(){
        $('#resetbtn').click(function(){
          var path='http://50.57.107.160/login/updatepswd/';
//          alert(qs['item']); return;
          var val1 = $('#pswd1').val();
          var val2 = $('#pswd2').val();
          
            var data ="";
            
          var setemail = false;
          if(val1 !=="" && val2 !==""){
              if(val1!==val2){
                 $('#rstset').text('Your entries do not match, please re-enter');
                  setemail = false;
              }
              if(val1===val2){
                 setemail = true;
                 data ='newemail='+val1+'&userid='+qs['item'];
              }
              if(setemail){
                $.ajax({
                    type:'POST',
                    url :path,
                    data:data,
                    dataType:'text',
                    success: function(data){
                      if(data == "true")
                      {
                          $('#rstset').text('Your password has been successfully reset');
                      }
                     $('#pswd1').val("");
                     $('#pswd2').val("");
                     $('#resetbtn').css('visibility','hidden')
              } // end of success
          });
              }
          }
      });
    });
 </script>
        </script>
    </head>
    <body>
        <div id="wrap">
            <table>
                <tr><td>Enter new password</td><td><input type="password" id="pswd1" value=""/></td></tr>
                <tr><td>Reenter new password</td><td><input type="password" id="pswd2" value=""/></td></tr>
                <tr><td></td><td><input type="button" id="resetbtn" value ="Reset" style="cursor: pointer"/></td></tr>
            </table>
            <div id="rstset"></div>
        </div>
    </body>
</html>