<?php
    if (!defined('BASEPATH')) exit('No direct script access allowed');
    
    class Account_Type extends Basedata
    {
    	function Account_Type()
    	{
    		$obj =& get_instance();
    		
    		$this->table_name = $obj->config->item('table_prefix') . 'account_types';
    		$this->table_key = 'account_type_id';
    		$this->fields = array(
		        'account_type_id' => 'int',
		        'account_type_name' => 'text',
		        'account_type_group_id' => 'int',
		        'account_type_template_name' => 'text',
		        'account_type_login_url' => 'text'
			);
		}
		
		function fetchAll()
		{
            $obj =& get_instance();
            
            $sql = "SELECT t.*, g.account_type_group_name 
            		FROM $this->table_name t 
            			JOIN " . $obj->config->item('table_prefix') . "account_types_groups g ON t.account_type_group_id = g.account_type_group_id 
            		ORDER BY g.account_type_group_name, t.account_type_name ASC ";

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
                return null;
            }
		}
	}
?>