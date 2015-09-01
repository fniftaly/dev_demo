<?php
//header("Content-type:text/octect-stream");
//header("Content-Disposition:attachment;filename=data.csv");
$filename = 'myreport';
header("Content-type: application/vnd.ms-excel");
header("Content-disposition: csv" . date("Y-m-d") . ".csv");
header( "Content-disposition: filename=".$filename.".csv");
echo "ID,Name,Age\r\n"; //header 
//while($row = mysql_fetch_row($result)){ 
echo "abc,xyz,nop\r\n"; //data 
//}  

?>