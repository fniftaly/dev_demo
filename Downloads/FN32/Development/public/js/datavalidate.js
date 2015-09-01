function cardvalidate(num){
var ten = 10;
var numlength = num.length;
var sh_num = num.substring(0,numlength-1)
var last = num.substring(numlength-1);
var short_rev =sh_num.split('').reverse().join('');
 var i = 2;
 var n =1; 
 var total = 0;
for(i= 0; i < numlength-1; i++){
    if(n % 2 == 0 ){           
        total =total+ parseInt(short_rev.substring(i,i+1));
    }else{
         if((parseInt(short_rev.substring(i,i+1)*2)) > 9)
        {
             total = total + ((parseInt(short_rev.substring(i,i+1)*2)) - 9);
        }else
            {
             total = total + parseInt(short_rev.substring(i,i+1)*2);
        }
    }
    n++;
}
var mod = total.toString();
mod = parseInt(mod.substring(mod.length-1));

if((ten - mod) == last){
   return true;
}else if(mod == 0){
return true;
}
else{
return false;
}
}

function dateFormat(date, format) {
    // Calculate date parts and replace instances in format string accordingly
    format = format.replace("DD", (date.getDate() < 10 ? '0' : '') + date.getDate()); // Pad with '0' if needed
    format = format.replace("MM", (date.getMonth() < 9 ? '0' : '') + (date.getMonth() + 1)); // Months are zero-based
    format = format.replace("YYYY", date.getFullYear());
    return format;
}