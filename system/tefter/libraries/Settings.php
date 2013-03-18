<?php
    if (!defined('BASEPATH')) exit('No direct script access allowed');
    
    class Settings extends Basedata
    {
    	function Settings()
    	{
            $obj =& get_instance();

    		$this->table_name = $obj->config->item('table_prefix') . 'settings';
    		$this->table_key = 'setting_id';
    		$this->fields = array(
		        'setting_id' => 'int',
		        'setting_key' => 'text',
		        'setting_value' => 'text',
		        'setting_description' => 'text'
			);
		}

		public function getValueForKey($set, $settingKey)
		{
			$setting_value = null;
			
			foreach ($set as $item)
			{
				if ($item['setting_key'] == $settingKey)
				{
					$setting_value = $item['setting_value'];
				}
			}
			
			return $setting_value;
		}
		
		public function transformToArray($set)
		{
			$result = array();
			
			foreach ($set as $item)
			{
				$result[$item['setting_key']] = $item['setting_value'];
			}
			
			return $result;
		}
		
		public function loadEmailParameters()
		{
            $set = $this->fetchBaseAll();
            
            $protocol = $this->getValueForKey($set, 'MAIL_PROTOCOL');

            $config = array();

            switch ($protocol)
            {
/*
            	case 'sendmail':
            		$config['protocol'] = 'sendmail';
					$config['mailpath'] = $this->getValueForKey($set, 'MAIL_PATH');
					$config['charset'] = $this->getValueForKey($set, 'MAIL_CHARSET');
					$config['mailtype'] = $this->getValueForKey($set, 'MAIL_TYPE');
            	break;
*/            	
            	case 'mail':
            		$config['protocol'] = 'mail';
					$config['charset'] = $this->getValueForKey($set, 'MAIL_CHARSET');
					$config['mailtype'] = $this->getValueForKey($set, 'MAIL_TYPE');
            	break;
            	
            	case 'smtp':
					$config['protocol'] = 'smtp';
					$config['smtp_host'] = $this->getValueForKey($set, 'MAIL_SMTP_HOST');
					$config['smtp_user'] = $this->getValueForKey($set, 'MAIL_SMTP_USER');
					$config['smtp_pass'] = $this->getValueForKey($set, 'MAIL_SMTP_PASS');
					$config['smtp_port'] = $this->getValueForKey($set, 'MAIL_SMTP_PORT');
					$config['smtp_timeout'] = $this->getValueForKey($set, 'MAIL_SMTP_TIMEOUT');
					$config['mailtype'] = $this->getValueForKey($set, 'MAIL_TYPE');
            	break;
			}	
            
            return $config;
		}
	}
?>