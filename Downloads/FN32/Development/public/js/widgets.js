(function($){
	$.fn.accordion = function(options) {
	
		var defaults = 
		{
			
		};
	
		var options = $.extend(defaults, options);
	
		return this.each( function() 
		{		
			var $container = $(this);
			var $triggers = $container.find ('.accordion_panel');
			var $panels = $container.find ('.accordion_content');
			
			
			$panels.hide ();			
			$triggers.eq (0).addClass ('active').next ().show ();			
			
			// Set min-height to prevent content from jumping as much
			$container.css ('min-height' , $container.height ()  + 10 + 'px');
			
			$triggers.live ('click' , function () 
			{
				if ( $(this).next ().is (':hidden') )
				{
					$triggers.removeClass ('active').next ().slideUp ();
					$(this).toggleClass ('active').next ().slideDown ();
				}					
				return false;
			});
		});		
	};

})(jQuery);


(function($){
	$.fn.tabs = function(options) {
	
		var defaults = 
		{
			
		};
	
		var options = $.extend(defaults, options);
	
		return this.each( function() {
	   		
			var $tabContainer = $(this);
			var $tabLi = $tabContainer.find ('.tabs li');
			var $tabContent = $tabContainer.find ('.tab_content');
            var $tabButtons = $tabContainer.find ('.tab_container .btn');
			
			$tabContent.hide ();
			$tabLi.eq (0).addClass ('active').show ();
			$tabContent.eq (0).show ();
			
			$tabLi.live ('click' , function () 
			{
				var activeTab = $(this).find ('a').attr ('href');
				
				$tabLi.removeClass("active");
				$(this).addClass("active");
				$tabContent.hide ();
				
				$tabContainer.find (activeTab).fadeIn ('slow');
				return false;
			});	
            
            $tabButtons.live ('click' , function () 
			{
				var activeTab = $(this).attr ('href');
				$tabLi.removeClass("active");
                
                // Find the correct tab
                $tabContainer.find ('.tabs li a[href='+activeTab+']').parent().addClass("active");
                
                $tabContent.hide ();
				
				$tabContainer.find (activeTab).fadeIn ('slow');
				return false;
			});	
		});		
	};

})(jQuery);

$(document).ready(function(){
//    $('.portlet-content .form div.create-user div.user-right div.field #industry').eComboBox();
      $('.portlet-content .form div.create-user div.user-left div.field #billing-info').click(function(e){
//        e.preventDefault();  
       if ($(this).is(":checked")){
        $('.biling-address').css('display','block');
    } else {
            $('.biling-address').css('display','none');
    }
    });
});
/**
 * add a new industry
 */
$(function(){
    
   $('.portlet-content .form div.create-user div.user-right div.addindustry #industry').change(function(){
        var $this = $(this); 
       if($(this).val() == 'newelement'){
          $this.parent().css('display','none').hide();
          $('.portlet-content .form div.create-user div.user-right div.addindustry #addind').css('display','inline').show();
       }
   });
   $('.portlet-content .form div.create-user div.user-right div.addindustry #addind').blur(function(){
       var selectAdd = $('.portlet-content .form div.create-user div.user-right div.addindustry #industry');
        var $this = $(this); 
         if($this.val() != ""){
             var path = '/users/addindustry/';
             var data = 'name='+$this.val();
             $.ajax({
               type: "POST",
                  url: path,
                  data:data,
                  datatype:'json',
                  success: function(data){
                      var option = $('<option></option>').attr("value", "newelement").text("NEW INDUSTRY");
                      selectAdd.empty().append(option);
                      data = JSON.parse(data);
                     $.each(data, function(id) {
                       selectAdd.append('<option value='+id+'>'+data[id]+'</option>'); 
                     });
                  }
                 });
         } // end of if
         
         $this.css('display','none').hide();
         $('.portlet-content .form div.create-user div.user-right div.addindustry #industry').parent().css('display','inline').show();
       
   });

});


/*this code need to be move to js library*/
$(document).ready(function(){
    var textarea_msg = $('div.portlet-content form.form.label-top table.clear.no-pad tbody tr td div.optinlife '+
              'select.#optin_life');
    var msg_body_alt = $('div.portlet-content form.form.label-top table.clear.no-pad tbody tr td div.field '+
              'textarea.#msg_body_alt');
    var checkAlternative = $('div.portlet-content form.form.label-top table.clear.no-pad tbody tr td div.key-msgbody');//'+
              //'div.#uniform-usealt.checker span');//div.#uniform-usealt span input.#usealt
       textarea_msg.change(function(){
            if($(this).val() != 'na'){
             checkAlternative.find('span').addClass('checked');   
             msg_body_alt.attr('placeholder','ADD ALTERNATIVE MESSAGE BODY');
            }else{
               msg_body_alt.removeAttr('placeholder');
               checkAlternative.find('span').removeClass('checked');
            }
         }); 
});

function setOfferdate(fday){
    var d = new Date();
    var day = d.getDate();
    var mon = d.getMonth()+1;
     var offerexp = day + parseInt(fday);
        if(offerexp > 31)
          {
              if(mon >=12)
               mon = 0;    
              mon = mon + 1;
              day = offerexp - 31;
           return mon + "/" + day;   
          } 
     return mon + "/" + (day+parseInt(fday));     
}

$(function(){
    if($('div#login h1 img').attr('src') == "/images/logo/salontouch.png"){
        $('div#login h1').css('background','black');
        $('body').css('background','#303030'); //#282828 383838	
    }
//    if($('div#login h1 img').attr('src') == "/images/logo/musmarketing.png"){
//        $('div#login h1').css('background','#263849');
////        $('body').css('background','#303030'); //#282828 383838	
//    }
    if($('div#login h1 img').attr('src') == "/images/logo/abcfin.png"){
        $('div#login h1').css('height','151px');
        $('div#login h1 img').css('width','100%').css('height','150px');
    }
    if($('div#login h1 img').attr('src') == "/images/logo/imarketing.png"){
        $('div#login h1').css('height','151px');
        $('div#login h1 img').css('width','90%').css('height','150px');
    }
    if($('#wrapper div.#header  h1 img').attr('src') == "/images/logo/salontouch.png")
    {
       $('#wrapper div.#header  h1 img').css('width','auto').css('height','115px');
       $('#wrapper div.#header').css('background','black');
       $('#wrapper div.#top div.#nav').css('background','black');
    }
    if($('#wrapper div.#header  h1 img').attr('src') == "/images/logo/anytime.png")
    {
//       $('div#login h1').css('background','white');
       $('#wrapper div.#top div.#header').css('background','white');
    }
    if($('#wrapper div.#header  h1 img').attr('src') == "/images/logo/mxs.jpg")
    {
       $('#wrapper div.#header  h1 img').css('height','100px').css('width','auto').css('margin-top','2px'); 
    }
    if($('#wrapper div.#header  h1 img').attr('src') == "/images/logo/prosolutions.png")
    {
       $('#wrapper div.#header  h1 img').css('height','100px').css('width','auto').css('margin-top','2px'); 
    }
    if($('#wrapper div.#header  h1 img').attr('src') == "/images/logo/overstreet.jpg")
    {
       $('#wrapper div.#header  h1 img').css('height','100px').css('width','auto').css('margin-top','2px'); 
    }
    if($('#wrapper div.#header  h1 img').attr('src') == "/images/logo/relylocal.jpg")
    {
       $('#wrapper div.#header  h1 img').css('height','100px').css('width','auto').css('margin-top','2px'); 
    }
    if($('#wrapper div.#header  h1 img').attr('src') == "/images/logo/IDT.png")
    {
       $('#wrapper div.#header  h1 img').css('height','90px').css('width','auto').css('margin-top','2px');
       $('#wrapper div.#header  h1 img').css('background', 'white');
    }
     if($('#wrapper div.#header  h1 img').attr('src') == "/images/logo/tipnow.png")
    {
       $('#wrapper div.#header  h1 img').css('height','90px').css('width','auto').css('margin-top','2px');
       $('#wrapper div.#header  h1 img').css('background', 'white');
    }
    if($('#wrapper div.#header  h1 img').attr('src') == "/images/logo/dkadlec.png")
    {
       $('#wrapper div.#header  h1 img').css('height','90px').css('width','auto').css('margin-top','2px');
       $('#wrapper div.#header  h1 img').css('background', 'white');
    }
    if($('#wrapper div.#header  h1 img').attr('src') == "/images/logo/leadbox.jpg")
    {
       $('#wrapper div.#header  h1 img').css('height','90px').css('width','auto').css('margin-top','2px');
       $('#wrapper div.#header  h1 img').css('background', 'white');
    }
    if($('#wrapper div.#header  h1 img').attr('src') == "/images/logo/imarketing.png")
    {
       $('#wrapper div.#header  h1 img').css('height','90px').css('width','auto').css('margin-top','2px');
       $('#wrapper div.#header').css('background', 'white');
    }
    if($('#wrapper div.#header  h1 img').attr('src') == "/images/logo/abcfin.png")
    {
       $('#wrapper div.#header  h1 img').css('height','90px').css('width','auto').css('margin-top','2px');
       $('#wrapper div.#header').css('background', 'black');
    }
    if($('#wrapper div.#header  h1 img').attr('src') == "/images/logo/aspenmarketing.png")
    {
       $('#wrapper div.#header  h1 img').css('height','90px').css('width','auto').css('margin-top','2px');
       $('#wrapper div.#header').css('background', 'white');
    }
    if($('#wrapper div.#header  h1 img').attr('src') == "/images/logo/musmarketing.png")
    {
       $('#wrapper div.#header  h1 img').css('height','90px').css('width','auto').css('margin-top','2px');
       $('#wrapper div.#header').css('background','white');
//       $('#wrapper div.#header  h1 img').css('background', 'blue');
    }
    if($('div#login h1 img').attr('src') == "/images/logo/leadbox.jpg"){
        $('div#login h1').css('min-height','2px');
    }
    if($('#wrapper div.#header  h1 img').attr('src') == "/images/logo/asf.png")
    {
       $('#wrapper div.#header  h1 img').css('height','90px').css('width','auto').css('margin-top','2px');
       $('#wrapper div.#header').css('background', 'white');
    }
    if($('#wrapper div.#header  h1 img').attr('src') == "/images/logo/tapla.png")
    {
       $('#wrapper div.#header  h1 img').css('height','90px').css('width','auto').css('margin-top','2px');
       $('#wrapper div.#header').css('background', 'white');
    }
    if($('#wrapper div.#header  h1 img').attr('src') == "/images/logo/moneymovers.png")
    {
       $('#wrapper div.#header  h1 img').css('height','90px').css('width','auto').css('margin-top','2px');
       $('#wrapper div.#header').css('background', 'white');
    }
    if($('#wrapper div.#header  h1 img').attr('src') == "/images/logo/legen.png")
    {
       $('#wrapper div.#header  h1 img').css('height','90px').css('width','auto').css('margin-top','2px');
       $('#wrapper div.#header').css('background', 'white');
    }
});



/*jquery manually opt out from inbox*/
function getId(id){

         var phone = 'phone='+id;
         var path = "/folder/subcriberoptout/";
   $.ajax({
               type: "POST",
                  url: path,
                  data:phone,
                  success: function(data){
                      alert(data);
                  }
                  
                 });
} 
/*###################### tooltip function */
 function createTooltip(event,text){          
         $('<div class="tooltip"></div>').appendTo('body').fadeIn('slow');
         $('.tooltip').text(text);
         positionTooltip(event);        
}

function positionTooltip(e){
    var tPosX = e.pageX +30;
    var tPosY = e.pageY - 40;
    $('div.tooltip').css({'position': 'absolute', 'top': tPosY, 'left': tPosX, 'border': '1px solid green',
           'z-index':'100','height':'auto','width':'200px','background':'#272727','color':'white','border-radius':'4px'});
}

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