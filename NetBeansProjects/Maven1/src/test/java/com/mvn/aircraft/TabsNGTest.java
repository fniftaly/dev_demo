
package com.mvn.aircraft;

import com.jetty.maven1.PageProvider;
import com.mvn.tabs.Tabs;
import java.util.logging.Level;
import java.util.logging.Logger;
import org.openqa.selenium.WebDriver;
//import org.openqa.selenium.chrome.ChromeDriver;
import org.openqa.selenium.firefox.FirefoxDriver;
import static org.testng.Assert.*;
import org.testng.annotations.AfterClass;
import org.testng.annotations.AfterMethod;
import org.testng.annotations.BeforeClass;
import org.testng.annotations.BeforeMethod;
import org.testng.annotations.Test;

/**
 *
 * @author def
 */
public class TabsNGTest {
    
    private String _baseUrl;
    
    private WebDriver _wd;
    
    private Tabs tbs;
    
    private AircraftTab links;
    
    public TabsNGTest() {
        
    }

    @BeforeClass
    public void setUpClass() throws Exception {
        
        this._wd = new FirefoxDriver();
        
        this._baseUrl = "http://www.faa.gov/";
        
        this._wd.get(_baseUrl);
        
        this._wd.manage().window().maximize();
        
        PageProvider.initialize(_wd);
        
        tbs = PageProvider.getTabObject();
        
        links = PageProvider.getAircraftObject();
        
//        driver.manage().timeouts().implicitlyWait(10, TimeUnit.SECONDS);
    }

    @AfterClass
    public void tearDownClass() throws Exception {
        this._wd.close();
        
    }

    @BeforeMethod
    public void setUpMethod() throws Exception {
        Thread.sleep(1000);
    }

    @AfterMethod
    public void tearDownMethod() throws Exception {
//        _wd.quit();
    }

   
    @Test(priority=1)
    public void aircrafttab() {
        
        String tabtext = tbs.aircraftTabExist();
        
        if(tabtext.equals(Tabs.AIRCRAFTTABTEXT))
            
        assertTrue(true);
    }
    @Test(priority=2)
    public void airporttab() {
        
        String tabtext = tbs.airportTabExist();
        
        if(tabtext.equals(Tabs.AIRPORTTABTEXT)){
        assert(true);
        }else{
        assert(false);
        }
    }
    
    @Test(priority=3)
    public void airtraffictab() {
        
        String tabtext = tbs.airtrafficTabExist();
        
        if(tabtext.equals(Tabs.AIRTRAFFIC))
            
        assertTrue(true);
    }
    
    @Test(priority=4)
    
    public void aircraftTabLinks() {
        
        try {
            links.validateLinks();
        } catch (Exception ex) {
            Logger.getLogger(TabsNGTest.class.getName()).log(Level.SEVERE, null, ex);
        }
       
    }
   
}
