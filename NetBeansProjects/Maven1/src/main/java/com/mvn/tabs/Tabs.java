
package com.mvn.tabs;

import com.mvn.util.AppUtil;
import java.util.concurrent.TimeUnit;
import org.apache.log4j.Logger;
import org.apache.log4j.PropertyConfigurator;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.interactions.Actions;

/**
 *
 * @author def
 */
public class Tabs {

    private static final Logger LOG = Logger.getLogger(Tabs.class.getName());

    private WebDriver driver;
    
    private Actions action;
    
    public static final String AIRCRAFTTABTEXT = "Aircraft";

    public static final By AIRCRAFT_TAB_LINK = By.xpath("//html/body/div[1]/div[2]/ul/li[1]/a");

    public static final String AIRPORTTABTEXT = "Airports";

    public static final By AIRPORTS_TAB_LINK = By.xpath("//html/body/div[1]/div[2]/ul/li[2]/a");

    public static final String AIRTRAFFIC = "Air Traffic";

    public static final By AIRTRAFFIC_TAB_LINK = By.xpath("//html/body/div[1]/div[2]/ul/li[3]/a");

    public Tabs(WebDriver driver){

        this.driver = driver;
        
        action = new Actions(this.driver);
        
        PropertyConfigurator.configure("/Users/def/NetBeansProjects/Maven1/src/main/java/Log4j.properties");

        LOG.info("LOGGER NAME: ".concat(LOG.getName()));

        LOG.info("CHECKING ALL TABS EXISTENCE");

        driver.manage().timeouts().implicitlyWait(10, TimeUnit.SECONDS);
    }

    public String aircraftTabExist() {
        if (!driver.findElements(AIRCRAFT_TAB_LINK).isEmpty()) {
            String tabtext = driver.findElement(AIRCRAFT_TAB_LINK).getText();
            String win1 = driver.getWindowHandle();
            LOG.info("AIRCRAFT_TAB_ID:".concat(win1));
            WebElement we = driver.findElement(AIRCRAFT_TAB_LINK);
            AppUtil.mouseOverAction(driver,we,action);
            LOG.info("AIRCRAFT_TAB_LINK - PASSED");
            return tabtext;
        } else {
            LOG.info("AIRCRAFT_TAB_LINK IS - FAILED");
            return null;
        }
    }

    public String airportTabExist() {
        if (!driver.findElements(AIRPORTS_TAB_LINK).isEmpty()) {
            String tabtext = driver.findElement(AIRPORTS_TAB_LINK).getText();
            String win1 = driver.getWindowHandle();
            LOG.info("AIRPORTS_TAB_ID:".concat(win1));
            WebElement we = driver.findElement(AIRPORTS_TAB_LINK);
            AppUtil.mouseOverAction(driver,we,action);
            LOG.info("AIRPORT_TAB_LINK - PASSED");
            return tabtext;
        } else {
            LOG.info("AIRPORT_TAB_LINK - FAILED");
            return null;
        }
    }

    public String airtrafficTabExist() {
        if (!driver.findElements(AIRTRAFFIC_TAB_LINK).isEmpty()) {
            String tabtext = driver.findElement(AIRTRAFFIC_TAB_LINK).getText();
            String win1 = driver.getWindowHandle();
            LOG.info("AIRTRAFFIC_TAB_ID:".concat(win1));
            WebElement we = driver.findElement(AIRTRAFFIC_TAB_LINK);
            AppUtil.mouseOverAction(driver,we,action);
            LOG.info("AIRTRAFFIC_TAB_LINK - PASSED");
            return tabtext;
        } else {
            LOG.info("AIRTRAFFIC_TAB_LINK - FAILED");
            return null;
        }
    }
}
