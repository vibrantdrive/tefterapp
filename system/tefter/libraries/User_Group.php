<?php
    if (!defined('BASEPATH')) exit('No direct script access allowed');
    
    class User_Group extends Basedata
    {
    	function User_Group()
    	{
			$obj =& get_instance();
			
    		$this->table_name = $obj->config->item('table_prefix') . 'user_groups';
    		$this->table_key = 'user_group_id';
    		$this->fields = array(
		        'user_id' => 'int',
		        'group_id' => 'int'
			);
		}
		
		function fetchAll($groupID = null)
		{
            $obj =& get_instance();
            
            $sql = "SELECT ug.user_group_id, ug.user_id, ug.group_id, u.name_first, u.name_last, u.email, u.role_id, ur.role_title, ur.level 
            		FROM $this->table_name ug 
            			JOIN " . $obj->config->item('table_prefix') . "users u ON ug.user_id = u.user_id 
            			JOIN " . $obj->config->item('table_prefix') . "user_roles ur ON u.role_id = ur.role_id ";
            
            if ($groupID != null)
            {
                $sql = $sql . " WHERE group_id = '$groupID' ";
            }
            
            $sql = $sql . " ORDER BY u.name_first, u.name_last ";
            
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

		function fetchAllForUserID($userID)
		{
            $obj =& get_instance();
            
            $sql = "SELECT ug.user_group_id, ug.user_id, ug.group_id, u.name_first, u.name_last, u.email, u.role_id, ur.role_title, ur.level 
            		FROM $this->table_name ug 
            			JOIN " . $obj->config->item('table_prefix') . "users u ON ug.user_id = u.user_id 
            			JOIN " . $obj->config->item('table_prefix') . "user_roles ur ON u.role_id = ur.role_id 
            		WHERE user_id = '$userID' ";
            
            
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

		function countGroupsForUser($userID, $groupID = null)
		{
            $obj =& get_instance();
            
            $sql = "SELECT COUNT(*) AS total 
            		FROM $this->table_name 
            		WHERE user_id = '$userID' ";

            if ($groupID != null)
            {
            	$sql = $sql . " AND group_id = '$groupID' ";
            }
            
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

		function removeAllForGroupID($groupID)
		{
            $obj =& get_instance();
            
            $sql = "DELETE FROM $this->table_name WHERE group_id = '$groupID' ";
            
            $recordSet = $obj->adodb->Execute($sql);
            
            return $obj->adodb->Affected_Rows();
		}

		function removeAllForUserID($userID)
		{
            $obj =& get_instance();
            
            $sql = "DELETE FROM $this->table_name WHERE user_id = '$userID' ";
            
            $recordSet = $obj->adodb->Execute($sql);
            
            return $obj->adodb->Affected_Rows();
		}

		function removeLink($userID, $groupID)
		{
            $obj =& get_instance();
            
            $sql = "DELETE FROM $this->table_name WHERE user_id = '$userID' AND group_id = '$groupID' ";
            
            $recordSet = $obj->adodb->Execute($sql);
            
            return $obj->adodb->Affected_Rows();
		}
	}
?>