<?php
    if (!defined('BASEPATH')) exit('No direct script access allowed');
    
    class User_IMs extends Basedata
    {
    	function User_IMs()
    	{
            $obj =& get_instance();

    		$this->table_name = $obj->config->item('table_prefix') . 'user_ims';
    		$this->table_key = 'user_im_id';
    		$this->fields = array(
		        'user_id' => 'int',
		        'im_id' => 'int'
			);
		}
		
        public function removeAllForUser($userId)
        {
            $obj =& get_instance();
            
            $sql = "DELETE FROM $this->table_name WHERE user_id = '$userId' ";
            
            $recordSet = $obj->adodb->Execute($sql);
            
            return $obj->adodb->Affected_Rows();
        }
	}
?>