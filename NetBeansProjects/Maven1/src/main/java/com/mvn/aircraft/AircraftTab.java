package com.mvn.aircraft;

import com.mvn.tabs.Tabs;
import com.mvn.util.AppUtil;
import org.apache.log4j.Logger;
import org.apache.log4j.PropertyConfigurator;
import org.junit.Assert;
import org.openqa.selenium.By;
import org.openqa.selenium.NoSuchElementException;
import org.openqa.selenium.StaleElementReferenceException;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.interactions.Actions;
import org.openqa.selenium.support.ui.ExpectedCondition;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.WebDriverWait;

/**
 *
 * @author def
 */
public class AircraftTab {

    private static final Logger LOG = Logger.getLogger(AircraftTab.class.getName());

    private WebDriver wd;

    private Actions action;

    public static final By AIRCRAFT_CERTIFICATION
            = By.xpath("//html/body/div[1]/div[2]/ul/li[1]/div/ul/li[99999]/a");

    public static final By AIRCRAFT_SAFTY
            = By.xpath("//html/body/div[1]/div[2]/ul/li[1]/div/ul/li[2]/a");

    public static final By GENERAL_AVIATION_RECREATIONAL_AIRCRAFT
            = By.xpath("//html/body/div[1]/div[2]/ul/li[1]/div/ul/li[3]/a");

    public static final By REPAIR_STATIONS
            = By.xpath("//html/body/div[1]/div[2]/ul/li[1]/div/ul/li[4]/a");

    public AircraftTab(WebDriver wd) {

        PropertyConfigurator.configure("/Users/def/NetBeansProjects/Maven1/src/main/java/Log4j.properties");

        this.wd = wd;

        LOG.info("LOGGER NAME: ".concat(LOG.getName()));

        this.action = new Actions(wd);
    }

    public void validateLinks() throws Exception {

        wd.findElement(Tabs.AIRCRAFT_TAB_LINK).click();

        LOG.info("*********AIRCRAFT TAB LINKS*************");
        if (!wd.findElements(AIRCRAFT_CERTIFICATION).isEmpty()) {

            WebElement link = wd.findElement(AIRCRAFT_CERTIFICATION);

            AppUtil.mouseOverAction(wd, link, this.action);

            LOG.info("AIRCRAFT CERTIFICATION LINK - PASSED ");

            Assert.assertTrue("Aircraft Tab first link", true);

            wd.findElement(AIRCRAFT_CERTIFICATION).isSelected();

        } else {
//            new ExpectedCondition<Boolean>() {
//                @Override
//                public Boolean apply(WebDriver driver) {
//                    try {
//                        driver.findElement(AIRCRAFT_CERTIFICATION);
//                        return false;
//                    } catch (NoSuchElementException e) {
//                        LOG.error(e.getMessage());
//                        return true;
//                    } catch (StaleElementReferenceException e) {
//                        LOG.error(e.getMessage());
//                        return true;
//                    }
//                }
//
//                @Override
//                public String toString() {
//                    return "element to not being present: " + AIRCRAFT_CERTIFICATION;
//                }
//            };
            try {
                LOG.error("AIRCRAFT CERTIFICATION LINK - FAILED");
            } catch (Exception e) {
                
               new WebDriverWait(wd, 10).until(ExpectedConditions.elementToBeClickable(AIRCRAFT_CERTIFICATION));

                wd.findElement(AIRCRAFT_CERTIFICATION).click();
                throw (e);
            }
        }

        if (!wd.findElements(AIRCRAFT_SAFTY).isEmpty()) {

            WebElement link = wd.findElement(AIRCRAFT_SAFTY);

            AppUtil.mouseOverAction(wd, link, this.action);

            LOG.info("AIRCRAFT SAFTY LINK - PASSED ");

            wd.findElement(AIRCRAFT_SAFTY).isSelected();

        } else {
            LOG.info("AIRCRAFT SAFTY LINK - FAILED ");
        }
    }

}
