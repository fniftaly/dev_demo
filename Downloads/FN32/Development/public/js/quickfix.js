/* 
 *  page campaign.phtml
 *  sending international campaign
 */
 $(function(){
     $('#intrsend').click(function(){
         if($(this).is(':checked')){
         $('#sendintr').css('display','inline');
         $('#finish').css('display','none');
         }else{
             $('#sendintr').css('display','none');
             $('#finish').css('display','inline');
         }
     });
 });

$(function(){
 $('#sendintr').click(function(){
   var arr = new Array();  
   var jarr = new Array();  
   var msg = $('#msg_body').val();
   var $this = $('#intrsend');
    $("div.checker span.checker input[type='checkbox']:checked").each(function(){
     arr.push($(this).val());
});
    jarr = JSON.stringify(arr);
    /**lets create ajax action to send request and get
     *  response from server
     */
           var path = '/messages/intrsms/';
             var data = 'msg='+msg+'&data='+jarr;
             if(msg !="" && arr.length != 0){
             $.ajax({
               type: "POST",
                  url: path,
                  data:data,
//                  datatype:'json',
                  success: function(data){
//                      alert(data);
                      if(data){
                            $("div.checker  span.checker input[type='checkbox']:checked").each(function(){
                               {
                                     $(this).parent().removeClass('checked');
                                     $(this).removeAttr('checked');
                               }
                           });
                      $('#msg_body').val(null);
                      $('#description').val(null);
                      $('#msg_head').val(null);
                      $this.removeAttr('checked');
                      $this.parent().removeClass('checked');
                      $this.trigger('click');
                      $this.removeAttr('checked');
                  }}
                 });
             }else{
//                 alert("Campaign is not completed properly!");
                 csscody.alert('Campaign is not properly set!');
             }
 });
});
// end of page campaign.phtml

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

/*******************************/
 function remainingCharacters(obj, obj1){
        var total = 160;
        obj.keyup(function() {
            var cs = $(this).val().length;
            var res = total - cs;
            var $textarea = obj1;
            if(res < 0)
            {
                $textarea.css('color','red');
            }  else{ $textarea.css('color','black');}
            $textarea.text(res);
        });
 }
/**********************/

 function fadeinandout(obj){
        obj.fadeIn('3000');
        setTimeout(function(){ obj.fadeOut() }, 3000);
    }
    
  