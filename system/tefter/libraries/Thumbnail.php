<?php
    if (!defined('BASEPATH')) exit('No direct script access allowed');
    
    class Thumbnail extends Basedata
    {
        function Thumbnail()
        {
            $obj =& get_instance();

    		$this->table_name = $obj->config->item('table_prefix') . 'uploaded_images';
    		$this->table_key = 'image_id';
    		$this->fields = array(
	            'image_id' => 'int',
	            'item_id' => 'int',
	            'user_id' => 'int',
	            'name' => 'text',
	            'type' => 'text',
	            'w' => 'text',
	            'h' => 'text',
	            'upload_set_id' => 'int'
			);            

            if ( !class_exists('phpthumb') )
            {
                require_once(APPPATH . "libraries/phpthumb/phpthumb.class.php");
            }
            
            $this->_init_phpthumb_library($obj);
        }
        
        function _init_phpthumb_library(&$ci)
        {
            $ci->thumbnail = new phpThumb();
        }
        
        function getMaxUploadSetId() 
        {
            $obj =& get_instance();
            
            $sql = "SELECT IFNULL(MAX(upload_set_id), 0) AS upload_set_id FROM $this->table_name ";
            
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
          
        function getUploadSetIdForImageId($imageId)
        {
            $obj =& get_instance();
            
            $sql = "SELECT upload_set_id FROM $this->table_name WHERE $this->table_key = '$imageId' ";
            
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

        function doUpload($type, $itemId, $userId, $images_upload_count, $upload_field_base)
        {
            $obj =& get_instance();

           // global $_FILES;
           
            for ($a = 0; $a <= $images_upload_count; $a += 1)
        //    foreach ($_FILES as $param=>$arr)
            {
                if (isset($_FILES[$upload_field_base . $a]))
                {
                    if ($_FILES[$upload_field_base . $a]['tmp_name'] == null)
                    {
                        continue;
                    }
                    
                    # get max upload_set
                    $upload_set_id = $this->getMaxUploadSetId();
                    $upload_set_id = $upload_set_id + 1;
                
                    foreach ($obj->config->item($type . '_thumbs') as $size_params) 
                    {
                        if (!is_dir($obj->config->item('home_path') . $obj->config->item($type . '_upload_path')))
                        {
                            if (!mkdir($obj->config->item('home_path') . $obj->config->item($type . '_upload_path'), 0700, true)) 
                            {
                                die('Error 1');
                            }
                        }

                        $phpThumb = new phpThumb();
                        
                        $width = $size_params['width'];
                        $height = $size_params['height'];
                        $crop = $size_params['crop'];
                        $top_left_x = $size_params['top_left_x'];
                        $top_left_y = $size_params['top_left_y'];
                        
                        $img = $width . "-" . $height . "-" . time();
                        $phpThumb->setSourceFilename($_FILES[$upload_field_base . $a]['tmp_name']);
                        
                        if (!is_file($_FILES[$upload_field_base . $a]['tmp_name'])) 
                        {
                            die('not file uploaded! ->' . $_FILES[$upload_field_base . $a]['tmp_name']);
                        }
                        
                        $output_filename = '';
                        $output_filename = $obj->config->item('home_path') . $obj->config->item($type . '_upload_path') . $img;
                        
                        if (!$crop)
                        {
                            if ($width != 0)
                            {
                                $phpThumb->setParameter('w', $width);
                            }
                            
                            if ($height != 0)
                            {
                                $phpThumb->setParameter('h', $height);
                            }
                        }
                        else
                        {
                            $phpThumb->setParameter('sx', $top_left_x);
                            $phpThumb->setParameter('sy', $top_left_y);
                            
                            if ($width != 0)
                            {
                                $phpThumb->setParameter('sw', $width);
                            }
                            
                            if ($height != 0)
                            {
                                $phpThumb->setParameter('sh', $height);
                            }
                        }
    /*                    
                        if ($height != 0 && $width != 0)
                        {
                            $phpThumb->setParameter('zc', 1);
                        }
    */                    
                        $phpThumb->GenerateThumbnail();
                        
                        if ($phpThumb->RenderToFile($output_filename)) 
                        {
                            # insert image details into database
                            $newimage = new Empty_Class();
                            
                            $newimage->item_id = $itemId;
                            $newimage->user_id = $userId;
                            $newimage->name = $img;
                            $newimage->type = $type;
                            $newimage->w = $width;
                            $newimage->h = $height;
                            $newimage->upload_set_id = $upload_set_id;
                            
                            $newImage = $this->insert($newimage);
    /*                        
                            if ($newImage > 0)
                            {
                                return true;
                            }
                            else
                            {
                                return false;
                            }
    */                        
                        }
                        else
                        {
                            print_r($phpThumb->fatalerror);
                            print_r($phpThumb->debugmessages);
                            die("Error 2");
                        }
                    }
                }
            }
        }

        function removeImage($imageId, $type)
        {
            $obj =& get_instance();

            # get all images for delete action
            $upload_set_id = $this->getUploadSetIdForImageId($imageId);
              
            $sql = "SELECT * FROM $this->table_name WHERE upload_set_id = '$upload_set_id' ";

            $recordSet = $obj->adodb->Execute($sql);
            $records = array();

            if ($recordSet != false) 
            {
                while (!$recordSet->EOF) 
                {
                    $records[$recordSet->fields['image_id']] = $obj->config->item('home_path') . $obj->config->item($type . '_upload_path') . $recordSet->fields['name'];
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
            $sql = "DELETE FROM $this->table_name WHERE upload_set_id = '$upload_set_id' ";

            $recordSet = $obj->adodb->Execute($sql);
            
            return $obj->adodb->Affected_Rows();
        }

        function removeAllForItem($itemId, $type)
        {
            $obj =& get_instance();

            $sql = "SELECT * FROM $this->table_name WHERE item_id = '$itemId' AND type = '$type' ";

            $recordSet = $obj->adodb->Execute($sql);
            $records = array();

            if ($recordSet != false) 
            {
                while (!$recordSet->EOF) 
                {
                    $records[$recordSet->fields['image_id']] = $obj->config->item('home_path') . $obj->config->item($type . '_upload_path') . $recordSet->fields['name'];
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
            $sql = "DELETE FROM $this->table_name WHERE item_id = '$itemId' AND type = '$type' ";

            $result = $obj->adodb->Execute($sql);
        }
    }
?>