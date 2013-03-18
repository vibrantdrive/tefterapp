<?php
    if (!defined('BASEPATH')) exit('No direct script access allowed');
    
    class Account_Types_Groups extends Basedata
    {
    	function Account_Types_Groups()
    	{
    		$obj =& get_instance();
    		
    		$this->table_name = $obj->config->item('table_prefix') . 'account_types_groups';
    		$this->table_key = 'account_type_group_id';
    		$this->fields = array(
		        'account_type_group_id' => 'int',
		        'account_type_group_name' => 'text'
			);
		}
	}
?>