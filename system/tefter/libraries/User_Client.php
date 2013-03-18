<?php
    if (!defined('BASEPATH')) exit('No direct script access allowed');
    
    class User_Client extends Basedata
    {
    	function User_Client()
    	{
            $obj =& get_instance();

    		$this->table_name = $obj->config->item('table_prefix') . 'user_clients';
    		$this->table_key = 'user_client_id';
    		$this->fields = array(
		        'user_id' => 'int',
		        'client_id' => 'int'
			);
		}
		
		function fetchAll($userID = null)
		{
            $obj =& get_instance();
            
            $sql = "SELECT * FROM $this->table_name ";
            
            if ($userID != null)
            {
                $sql = $sql . " WHERE user_id = '$userID' ";
            }
            
            $recordSet = $obj->adodb->Execute($sql);
            $records = array();

            if ($recordSet != false) 
            {
                while (!$recordSet->EOF) 
                {
                    $records[] = $recordSet->fields;
                    $recordSet->MoveNext();
                }
                
                return $records;
            }
            else
            {
                return NULL;
            }
		}
		
		function countAllForUserIDAndClientID($userID, $clientID)
		{
            $obj =& get_instance();
            
            $sql = "SELECT COUNT(*) AS total FROM $this->table_name WHERE user_id = '$userID' AND client_id = '$clientID' ";
            
            $recordSet = $obj->adodb->Execute($sql);

            if ($recordSet != false) 
            {
                while (!$recordSet->EOF) 
                {
                    $records[] = $recordSet->fields;
                    $recordSet->MoveNext();
                }
                
                return $records[0]['total'];
            }
            else
            {
                return 0;
            }
		}

		function removeAllForUserID($userID)
		{
            $obj =& get_instance();
            
            $sql = "DELETE FROM $this->table_name WHERE user_id = '$userID' ";
            
            $recordSet = $obj->adodb->Execute($sql);
            
            return $obj->adodb->Affected_Rows();
		}

		function removeAllForClientID($clientID)
		{
            $obj =& get_instance();
            
            $sql = "DELETE FROM $this->table_name WHERE client_id = '$clientID' ";
            
            $recordSet = $obj->adodb->Execute($sql);
            
            return $obj->adodb->Affected_Rows();
		}
	}
?>