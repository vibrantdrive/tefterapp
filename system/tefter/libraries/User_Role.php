<?php
    if (!defined('BASEPATH')) exit('No direct script access allowed');
    
    class User_Role extends Basedata
    {
    	function User_Role()
    	{
            $obj =& get_instance();

    		$this->table_name = $obj->config->item('table_prefix') . 'user_roles';
    		$this->table_key = 'role_id';
    		$this->fields = array(
		        'role_id' => 'int',
		        'role_title' => 'text',
		        'level' => 'int'
			);
		}
	}
?>
