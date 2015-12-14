
package com.jetty.maven1;

import com.mvn.aircraft.AircraftTab;
import org.openqa.selenium.WebDriver;
import com.mvn.tabs.Tabs;
import java.io.IOException;

/**
 *
 * @author def
 */
public class PageProvider {
    
    private static WebDriver _driver;
    
    public static Tabs getTabObject(){
        
          return new Tabs(_driver);
    }
    
    public static AircraftTab getAircraftObject(){
        
          return new AircraftTab(_driver);
    }
    
    public static void initialize(WebDriver driver){
        
        _driver = driver;
        
    }
}
