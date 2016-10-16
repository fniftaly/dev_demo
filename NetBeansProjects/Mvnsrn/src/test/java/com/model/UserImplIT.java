
package com.model;

import java.util.HashMap;
import org.junit.After;
import org.junit.AfterClass;
import org.junit.Before;
import org.junit.BeforeClass;
import org.junit.Test;
import static org.junit.Assert.*;

/**
 *
 * @author def
   added remote file
 */
public class UserImplIT {
    
    private User _user;
    private String _email; 
    private HashMap<String, User> _map;
    private int _size;
    
    public UserImplIT() {
    }
    
    @BeforeClass
    public static void setUpClass() {
       
    }
    
    @AfterClass
    public static void tearDownClass() {
    }
    
    @Before
    public void setUp() {
         _user = new User("Nick", "Hackker", "glob@abadaba.com","5/30/2015");
         _email = _user.getEmail();
         _map = new HashMap();
         _size = 0;
    }
    
    @After
    public void tearDown() {
    }

    /**
     * Test of getUser method, of class UserImpl.
     */
    @Test
    public void testGetUser() {
        System.out.println("getUser");
        UserImpl instance = new UserImpl();
        HashMap<String, User> result = instance.getUser();
        assertTrue(result instanceof HashMap);
    }

    /**
     * Test of addUser method, of class UserImpl.
     */
    @Test
    public void testAddUser() {
        System.out.println("addUser");
        UserImpl instance = new UserImpl();
        boolean expResult = true;
        boolean result = instance.addUser(_user);
        assertEquals(expResult, result);
    }

    /**
     * Test of readfile method, of class UserImpl.
     */
    @Test
    public void testReadfile() {
        System.out.println("readfile");
        UserImpl instance = new UserImpl();
        instance.readfile();
        _map = instance.getUser();
        _size = _map.size();
        assertTrue(_size > 0);
        
    }

    /**
     * Test of removeuser method, of class UserImpl.
     */
    @Test
    public void testRemoveuser() {
        System.out.println("removeuser");
        UserImpl instance = new UserImpl();
        boolean expResult = true;
        boolean result = instance.removeuser(_email);
        assertEquals(expResult, result);
    }
    
}
