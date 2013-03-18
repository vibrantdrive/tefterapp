<?php
    if (!defined('BASEPATH')) exit('No direct script access allowed');
    
    class Uploads
    {
        private $table_key = 'file_id';
        private $table_name = 'uploaded_files';
        
        protected $fields = array(
            'file_id' => 'int',
            'item_id' => 'int',
            'user_id' => 'int',
            'title' => 'text',
            'name' => 'text',
            'original_name' => 'text',
            'type' => 'text',
            'upload_set_id' => 'int'
        );

        public function getFilesForItemId($itemId, $type, $uploadPath)
        {
            $obj =& get_instance();
            
            $sql = "SELECT *, CONCAT('$uploadPath', name) AS file_path FROM $this->table_name WHERE item_id = '$itemId' AND type = '$type' ";
            
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
        
        function getMaxUploadSetId() 
        {
            $obj =& get_instance();
            
            $sql = "SELECT IFNULL(MAX(upload_set_id), 0) AS upload_set_id FROM uploaded_files ";
            
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
                    return $records[0]['upload_set_id'];
                }
                else
                {
                    return 0;
                }
            }
            else
            {
                return 0;
            }
        }
          
        function getUploadSetIdForFileId($fileId)
        {
            $obj =& get_instance();
            
            $sql = "SELECT upload_set_id FROM uploaded_files WHERE file_id = '$fileId' ";
            
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
                    return $records[0]['upload_set_id'];
                }
                else
                {
                    return 0;
                }
            }
            else
            {
                return 0;
            }
        }
        
        public function insert($object)
        {
            $obj =& get_instance();
            
            if (isset($object)) 
            {
                
                $sql = $obj->common->buildInsertString($this->table_name, $this->table_key, $object, $this->fields, false);
                
                $result = $obj->adodb->Execute($sql);
                
                if ($result != false) 
                {
                    return $obj->adodb->Insert_ID();
;
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

        function removeOneFile($fileId, $itemId, $type)
        {
            $obj =& get_instance();

            # get all images for delete action
            $upload_set_id = $this->getUploadSetIdForFileId($fileId);
              
            $sql = "SELECT * FROM uploaded_files WHERE file_id = '$fileId' AND type = '$type' AND item_id = '$itemId' ";

            $recordSet = $obj->adodb->Execute($sql);
            $records = array();

            if ($recordSet != false) 
            {
                while (!$recordSet->EOF) 
                {
                    $records[$recordSet->fields['file_id']] = $obj->config->item('home_path') . $obj->config->item($type . '_upload_path') . $recordSet->fields['name'];
                    $recordSet->MoveNext();
                }
            }
            
            # unlink selected images
            foreach ($records as $key=>$value)
            {
                try
                {
                    $deleted = unlink($value);
                }
                catch (Exception $e)
                {
                    var_dump($e);
                }
            }
              
            # delete records from database
            $sql = "DELETE FROM uploaded_files WHERE file_id = '$fileId' AND type = '$type' AND item_id = '$itemId' ";

            $recordSet = $obj->adodb->Execute($sql);
            
            return $obj->adodb->Affected_Rows();
        }
        
        function remove($fileId, $type)
        {
            $obj =& get_instance();

            # get all images for delete action
            $upload_set_id = $this->getUploadSetIdForFileId($fileId);
              
            $sql = "SELECT * FROM uploaded_files WHERE upload_set_id = '$upload_set_id' ";

            $recordSet = $obj->adodb->Execute($sql);
            $records = array();

            if ($recordSet != false) 
            {
                while (!$recordSet->EOF) 
                {
                    $records[$recordSet->fields['file_id']] = $obj->config->item('home_path') . $obj->config->item($type . '_upload_path') . $recordSet->fields['name'];
                    $recordSet->MoveNext();
                }
            }
            
            # unlink selected images
            foreach ($records as $key=>$value)
            {
                try
                {
                    $deleted = unlink($value);
                }
                catch (Exception $e)
                {
                    var_dump($e);
                }
            }
              
            # delete records from database
            $sql = "DELETE FROM uploaded_files WHERE upload_set_id = '$upload_set_id' ";

            $recordSet = $obj->adodb->Execute($sql);
            
            return $obj->adodb->Affected_Rows();
        }

        function removeAllForItem($itemId, $type)
        {
            $obj =& get_instance();

            $sql = "SELECT * FROM uploaded_files WHERE item_id = '$itemId' AND type = '$type' ";

            $recordSet = $obj->adodb->Execute($sql);
            $records = array();

            if ($recordSet != false) 
            {
                while (!$recordSet->EOF) 
                {
                    $records[$recordSet->fields['file_id']] = $obj->config->item('home_path') . $obj->config->item($type . '_upload_path') . $recordSet->fields['name'];
                    $recordSet->MoveNext();
                }
            }
            
            # unlink selected images
            foreach ($records as $key=>$value)
            {
                try
                {
                    $deleted = unlink($value);
                }
                catch (Exception $e)
                {
                    var_dump($e);
                }
            }
              
            # delete records from database
            $sql = "DELETE FROM uploaded_files WHERE item_id = '$itemId' AND type = '$type' ";

            $result = $obj->adodb->Execute($sql);
        }

        function doUpload($type, $itemId, $userId, $files_upload_count, $upload_field_base, $title_field_base)
        {
            $obj =& get_instance();

            # get max upload_set
            $upload_set_id = $this->getMaxUploadSetId();
            $upload_set_id = $upload_set_id + 1;
            
            if (!is_dir($obj->config->item('home_path') . $obj->config->item($type . '_upload_path')))
            {
                if (!mkdir($obj->config->item('home_path') . $obj->config->item($type . '_upload_path'), 0700, true)) 
                {
                    die('Error 1');
                }
            }

            for ($a = 0; $a <= $files_upload_count; $a += 1)
            {
                if (isset($_FILES[$upload_field_base . $a]))
                {
                    if ($_FILES[$upload_field_base . $a]['tmp_name'] == null)
                    {
                        continue;
                    }
                    
                    if (!is_file($_FILES[$upload_field_base . $a]['tmp_name'])) 
                    {
                        die('not file uploaded! ->' . $_FILES[$upload_field_base . $a]['tmp_name']);
                    }

                    $original_name = basename($_FILES[$upload_field_base . $a]['name']);
                    $title = $obj->common->getParameter($title_field_base . $a);
                    $name = time() . '_' . $original_name;
                    $target_path = $obj->config->item('home_path') . $obj->config->item($type . '_upload_path') . $name;
                    
                    if (move_uploaded_file($_FILES[$upload_field_base . $a]['tmp_name'], $target_path))
                    {
                        $newfile = new Empty_Class();
                        
                        $newfile->item_id = $itemId;
                        $newfile->user_id = $userId;
                        $newfile->name = $name;
                        $newfile->type = $type;
                        $newfile->original_name = $original_name;
                        $newfile->title = $title;
                        $newfile->upload_set_id = $upload_set_id;
                        
                        $newFile = $this->insert($newfile);
                    }
                    else
                    {
                        die("Cannot upload file.");
                    }
                }
            }    
        }
    } 
?>