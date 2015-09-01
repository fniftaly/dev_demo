<?php
$req ='
<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
xmlns:xsd="http://www.w3.org/2001/XMLSchema" 
xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <GetContactInf xmlns="http://asfint.com/asfwebservices">
      <clubid>4000</clubid>
      <loginName>farad</loginName>
      <loginPW>a1111</loginPW>
      <CanceledSince></CanceledSince>
      <NewSince>2014-04-01</NewSince>
    </GetContactInf>
  </soap:Body>
</soap:Envelope>';

$req1 ="<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
$req1 .="<soap:Envelope xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:soap=\"http://schemas.xmlsoap.org/soap/envelope/\">";
$req1 .="<soap:Body>";    
$req1 .="<GetContactInf xmlns=\"http://asfint.com/asfwebservices\">"; 
$req1 .="<clubid>4000</clubid>"; 
$req1 .="<loginName>farad</loginName>"; 
$req1 .="<loginPW>a1111</loginPW>"; 
$req1 .="<CanceledSince></CanceledSince>"; 
$req1 .="<NewSince>2014-04-01</NewSince>"; 
$req1 .="</GetContactInf>"; 
$req1 .="</soap:Body>"; 
$req1 .="</soap:Envelope>"; 

//<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
//xmlns:xsd="http://www.w3.org/2001/XMLSchema" 
//xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
//  <soap:Body>
//    <GetContactInf xmlns="http://asfint.com/asfwebservices">
//      <clubid>4000</clubid>
//      <loginName>farad</loginName>
//      <loginPW>a1111</loginPW>
//      <CanceledSince>sometext</CanceledSince>
//      <NewSince>textm</NewSince>
//    </GetContactInf>
//  </soap:Body>
//</soap:Envelope>';

 $soap_request  = "<?xml version=\"1.0\"?>\n";
  $soap_request .= "<soap:Envelope xmlns:soap=\"http://www.w3.org/2001/12/soap-envelope\" soap:encodingStyle=\"http://www.w3.org/2001/12/soap-encoding\">\n";
  $soap_request .= "  <soap:Body xmlns:m=\"http://www.example.org/stock\">\n";
  $soap_request .= "    <m:GetStockPrice>\n";
  $soap_request .= "      <m:StockName>IBM</m:StockName>\n";
  $soap_request .= "    </m:GetStockPrice>\n";
  $soap_request .= "  </soap:Body>\n";
  $soap_request .= "</soap:Envelope>";
 
  $header = array(
    "Content-type: text/xml;charset=\"utf-8\"",
    "Accept: text/xml",
    "Cache-Control: no-cache",
    "Pragma: no-cache",
    "SOAPAction: \"run\"",
    "Content-length: ".strlen($req1),
  );
 
  $soap_do = curl_init();
  curl_setopt($soap_do, CURLOPT_URL, "http://asfint.com/asfwebservices/GetContactInf" );
  curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 10);
  curl_setopt($soap_do, CURLOPT_TIMEOUT,        10);
  curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true );
  curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($soap_do, CURLOPT_HTTPHEADER,     $header);
  curl_setopt($soap_do, CURLOPT_POST,           true );
  curl_setopt($soap_do, CURLOPT_POSTFIELDS,     $req);
 echo curl_exec($soap_do);
  curl_close($soap_do);
  
//  if(curl_exec($soap_do) === false) {
//    $err = 'Curl error: ' . curl_error($soap_do);
//    curl_close($soap_do);
//    print $err;
//  } else {
//    curl_close($soap_do);
//    print 'Operation completed without any errors';
//  }
?>
