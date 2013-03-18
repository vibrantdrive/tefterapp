<?php
    if (!defined('BASEPATH')) exit('No direct script access allowed');
    
    class Group_Account extends Basedata
    {
    	function Group_Account()
    	{
            $obj =& get_instance();

    		$this->table_name = $obj->config->item('table_prefix') . 'group_accounts';
    		$this->table_key = 'group_account_id';
    		$this->fields = array(
		        'group_id' => 'int',
		        'account_id' => 'int'
			);
		}
		
		function fetchAll($groupID = null)
		{
            $obj =& get_instance();
            
            $sql = "SELECT * FROM $this->table_name ";
            
            if ($groupID != null)
            {
                $sql = $sql . " WHERE group_id = '$groupID' ";
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

		function removeAllForGroupID($groupID)
		{
            $obj =& get_instance();
            
            $sql = "DELETE FROM $this->table_name WHERE group_id = '$groupID' ";
            
            $recordSet = $obj->adodb->Execute($sql);
            
            return $obj->adodb->Affected_Rows();
		}

		function removeAllForAccountID($accountID)
		{
            $obj =& get_instance();
            
            $sql = "DELETE FROM $this->table_name WHERE account_id = '$accountID' ";
            
            $recordSet = $obj->adodb->Execute($sql);
            
            return $obj->adodb->Affected_Rows();
		}
	}
?>