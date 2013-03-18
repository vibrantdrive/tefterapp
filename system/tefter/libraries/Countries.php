<?php
    if (!defined('BASEPATH')) exit('No direct script access allowed');
    
    class Countries extends Basedata
    {
    	function Countries()
    	{
            $obj =& get_instance();

    		$this->table_name = $obj->config->item('table_prefix') . 'countries';
    		$this->table_key = 'country_code';
    		$this->fields = array(
		        'country_code' => 'text',
		        'country_name' => 'text'
			);
		}
		
		function fetchAll()
		{
            $obj =& get_instance();
            
            $sql = "SELECT * FROM $this->table_name ORDER BY country_name ";
            
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