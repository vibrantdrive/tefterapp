<?php
    if (!defined('BASEPATH')) exit('No direct script access allowed');
    
    class IMs extends Basedata
    {
    	function IMs()
    	{
            $obj =& get_instance();

    		$this->table_name = $obj->config->item('table_prefix') . 'ims';
    		$this->table_key = 'im_id';
    		$this->fields = array(
		        'im_id' => 'int',
		        'im_title' => 'text'
			);
		}
		
		function fetchAll()
		{
            $obj =& get_instance();
            
            $sql = "SELECT * FROM $this->table_name ORDER BY im_title ";
            
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