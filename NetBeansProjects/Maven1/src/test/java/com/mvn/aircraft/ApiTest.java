/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mvn.aircraft;

import java.io.IOException;
import org.apache.http.HttpEntity;
import org.apache.http.HttpStatus;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.client.methods.HttpUriRequest;
import org.apache.http.entity.ContentType;
import org.apache.http.impl.client.HttpClientBuilder;
import org.jboss.netty.handler.codec.http.HttpResponse;
import org.testng.Assert;
import org.testng.annotations.Test;

/**
 *
 * @author def
 */
public class ApiTest {
    
    @Test(enabled=false)
    public void testStatusCode() throws ClientProtocolException, IOException {
 
    HttpUriRequest request = new HttpGet("http://riiwards.com?login=bassan");
    
    HttpResponse httpResponse = (HttpResponse) HttpClientBuilder.create().build().execute(request);
    
        System.out.println("BOX: " +httpResponse.getStatus());
         
    Assert.assertEquals(httpResponse.getStatus(),HttpStatus.SC_OK);
//    Assert.assertEquals("200","200");
}
     @Test
    public static void testMimeType(String restURL, String expectedMimeType) throws ClientProtocolException, IOException {
         
    HttpUriRequest request = new HttpGet(restURL);
    HttpResponse httpResponse = (HttpResponse) HttpClientBuilder.create().build().execute(request);
         
    Assert.assertEquals(expectedMimeType,ContentType.getOrDefault((HttpEntity) httpResponse.getStatus()).getMimeType());
}
    
}
