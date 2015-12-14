package com.mvn.util;

import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.interactions.Actions;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.WebDriverWait;

/**
 *
 * @author def
 */
public class AppUtil {

    public AppUtil() {
    }

    public static void mouseOverAction(WebDriver wd, WebElement we, Actions action) {

        action.moveToElement(we).build().perform();
    }

    public WebElement isElementLoaded(WebElement elementToBeLoaded, WebDriver driver) {
        WebDriverWait wait = new WebDriverWait(driver, 15);
        WebElement element = wait.until(ExpectedConditions.visibilityOf(elementToBeLoaded));
        return element;
    }
}
