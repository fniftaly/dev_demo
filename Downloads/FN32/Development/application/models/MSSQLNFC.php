<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MSSQL
 *
 * @author farad
 */

class Application_Model_MSSQLNFC extends Zend_Application_Resource_ResourceAbstract
{
 
   public function connect()
   {
//       $dt = date('Y-m-d H:i:s');
//       echo "<br>Connection: $dt<br>";
       //10.104.35.53
       //208.96.41.53 external
       $this->_connection = @mssql_connect("208.96.41.53", "Textmunication", "ympb72d6");
       if(!$this->_connection)
       {
           die('<font color="red">Error: Unable to connect to database host.</font>');
       }
       $this->_dbselect = @mssql_select_db("FitCompanySNFC", $this->_connection);
       if(!$this->_dbselect)
       {
           die('<font color="red">Error: Unable to select database.</font>');
       }
   }
   public function query($sql)
   {
//        echo "<br>Query:<br>";
       $this->_result = @mssql_query($sql);
       
       if(!$this->_result)
       {
           die('<font color="red">Error: Could not run query.</font>');
       }
       return $this->_result;
   }

   public function free()
   {
       mssql_free_result($this->_result);
   }

   public function disconnect()
   {
       mssql_close($this->_connection);
   }

    public function init()
    {
//        return self::getConnection();
    }

}

?>
