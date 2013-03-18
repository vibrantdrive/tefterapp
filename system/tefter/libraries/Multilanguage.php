<?php
    if (!defined('BASEPATH')) exit('No direct script access allowed');
    
    class Language
    {
    	public $title;
    	public $file;
	}
    
    class Multilanguage
    {
    	function getLanguages()
    	{
    		$obj =& get_instance();
    		
    		$result = array();
    		
    		$folder = APPPATH . $obj->config->item('language_path');
    		
			if ($handle = opendir($folder))
			{
			    while (false !== ($file = readdir($handle)))
			    {
					if (strpos($file, '.php', 1))
					{
                    	$language = $this->getLanguageByFileName($file);
                    	
                    	array_push($result, $language);
                	} 			        
			    }

			    closedir($handle);
			}    		
    		
    		return $result;
		}
		
		function getLanguageByFileName($filename)
		{
            $language = new Language();
            
            $language->file = $filename;
            $language->title = substr($filename, 0, strlen($filename) - 4);
    		
    		return $language;
		}
		
		function loadLanguage()
		{
    		$obj =& get_instance();
    		
            try
            {
    			$language = $obj->common->getFromSession('TEFTER_LANGUAGE');
    			
    			if ($language != null)
    			{
    				include(APPPATH . $obj->config->item('language_path') . '/' . $language->file);
				}
				else
				{
					# load language from settings
					$languageSet = $obj->settings->fetchBaseOne(1);
					
					include(APPPATH . $obj->config->item('language_path') . '/' . $languageSet['setting_value']);
				}
			}
			catch (Exception $e)
			{
				return null;
			}
			
			return $lang;
		}
		
		function setCurrentLanguage($filename)
		{
			$result = false;
			
			try
			{
				if (isset($filename))
				{
					$obj =& get_instance();
					
					$language = $this->getLanguageByFileName($filename);
					
					$setting = new Settings();
					$setting->setting_id = 1;
					$setting->setting_value = $language->file;
					
					$obj->settings->update($setting);
					
					$result = $obj->common->storeIntoSession('TEFTER_LANGUAGE', $language);
				}
			}
			catch (Exception $e)
			{
				$result = false;
			}
			
			return $result;
		}
	}
?>