/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.model;

import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.ObjectInputStream;
import java.io.ObjectOutputStream;
import java.util.HashMap;

/**
 *
 * @author farad
 * this line is added for testing pull action
 */
public class UserImpl {

    private HashMap<String, User> userlist = new HashMap();

    public UserImpl() {
       
    }

    public HashMap<String, User> getUser() {

        return userlist;
    }

    /*
     *  new user into collection and 
     *  serialazing User object 
     * 
     */
    public boolean addUser(User user) {

        if(user != null && !user.getEmail().equals("")){
        this.userlist.put(user.getEmail(), user);

        try {
            FileOutputStream fos = new FileOutputStream("hashmap.ser");
            ObjectOutputStream oos = new ObjectOutputStream(fos);
            oos.writeObject(this.userlist);
            oos.close();
            fos.close();
        } catch (IOException ioe) {
            ioe.printStackTrace();
        }
          return true;
        }else{
          return false;}
    }
    
    /*
     *  Deserializing User object into HashMap
     *  This method each time refresh the HashMap list
     *  for cllient side request
     */
     public void readfile() {
        try {
            FileInputStream fis = new FileInputStream("hashmap.ser");
            ObjectInputStream ois = new ObjectInputStream(fis);
            this.userlist = (HashMap) ois.readObject();
            ois.close();
            fis.close();
        } catch (IOException ioe) {
            System.err.println("An IOException was caught!");
            ioe.printStackTrace();
            return;
        } catch (ClassNotFoundException c) {
            System.out.println("Class not found");
            c.printStackTrace();
            return;
        }
    }
     
     /**
      *  if User object exist in the hashmap list
      *  then it removes from list
      * 
      */
     public boolean removeuser(String email){
         readfile();
         if(this.userlist.get(email) instanceof User){
            this.userlist.remove(email);
            try {
            FileOutputStream fos = new FileOutputStream("hashmap.ser");
            ObjectOutputStream oos = new ObjectOutputStream(fos);
            oos.writeObject(this.userlist);
            oos.close();
            fos.close();
        } catch (IOException ioe) {
            ioe.printStackTrace();
        }
            return true;
        }else{return false;}
     }

}
