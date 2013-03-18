<?php
    if (!defined('BASEPATH')) exit('No direct script access allowed');
    
    class Basedata
    {
        protected $table_name;
        protected $table_key;
        protected $fields;
        
		public function fetchBaseAll()
		{
            $obj =& get_instance();
            
            $sql = "SELECT * FROM $this->table_name ";
            
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

        public function fetchBaseOne($id)
        {
            $obj =& get_instance();
            
            $sql = "SELECT * FROM $this->table_name WHERE $this->table_key = '$id' ";

            $recordSet = $obj->adodb->Execute($sql);
            $records = array();
            
            if ($recordSet != false) 
            {
                while (!$recordSet->EOF) 
                {
                    $records[] = $recordSet->fields;
                    $recordSet->MoveNext();
                }
                
                if (count($records) > 0)
                {
                    return $records[0];
                }
                else
                {
                    return NULL;
                }
            }
            else
            {
                return NULL;
            }
        }

        public function countItems()
        {
            $obj =& get_instance();
            
            $sql = "SELECT COUNT(*) AS total FROM $this->table_name ";
            
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

        public function insert($object, $includeTableKey = false)
        {
            $obj =& get_instance();
            
            if (isset($object)) 
            {
                
                $sql = $obj->common->buildInsertString($this->table_name, $this->table_key, $object, $this->fields, $includeTableKey);
                
                $result = $obj->adodb->Execute($sql);
                
                if ($result != false) 
                {
                    return $obj->adodb->Insert_ID();
                } 
                else 
                {
                    return -1;
                }
                
            } 
            else 
            {
                return -1;
            }
        }
        
        public function update($object) 
        {
            $obj =& get_instance();
            $key_value = $this->table_key;
            
            if (isset($object)) 
            {
                
                $sql = $obj->common->buildUpdateString($this->table_name, $this->table_key, $object, $this->fields, $object->$key_value);
                
                $result = $obj->adodb->Execute($sql);
                
                if ($result != false) 
                {
                    return true;
                } 
                else 
                {
                    return false;
                }
            } 
            else 
            {
                return false;
            }
        }
        
        public function remove($id)
        {
            $obj =& get_instance();
            
            $sql = "DELETE FROM $this->table_name WHERE $this->table_key = '$id'";
            
            $recordSet = $obj->adodb->Execute($sql);
            
            return $obj->adodb->Affected_Rows();
        }

        public function removeAll()
        {
            $obj =& get_instance();
            
            $sql = "DELETE FROM $this->table_name ";
            
            $recordSet = $obj->adodb->Execute($sql);
            
            return $obj->adodb->Affected_Rows();
        }
	}
?>