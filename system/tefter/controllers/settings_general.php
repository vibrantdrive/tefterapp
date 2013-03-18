<?php
class Settings_General extends Controller {

    function Settings_General()
    {
        parent::Controller();    
        
        $this->load->library('common');
        $this->load->library('Smarty_Wrapper');
        $this->load->library('user');
        $this->load->library('email');
        $this->load->library('messenger');
        
        $this->load->helper('url');
    }
    
    function index()
    {
        # init session
        $this->common->init();

        $language = $this->multilanguage->loadLanguage();

        if (!$this->user->isAuthenticated())
        {
            $message = new Message();
            
            $message->page = 'login';
            $message->content = $language['message_login_required'];
            $message->type = 'PROBLEM';
            $this->messenger->setMessage($message);

            header("Location: " . $this->config->item('base_url') . 'login');
            die;
		}
        
        $userID = $this->common->getFromSession('TEFTER_USERID');
        $user = $this->user->fetchOne($userID);
        
        if (!$this->user->isMinimumLevel($user, 99))
        {
        	show_404('page');
        	die;
		}

        $set = $this->settings->fetchBaseAll();
        $settings = $this->settings->transformToArray($set);
        
        $languages = $this->multilanguage->getLanguages();
        
        $posted = $this->common->getPostedFromSession('settings_general');

        $m = $this->messenger->getMessage('settings_general');
        
        $this->smarty_wrapper->assign('title', 'Settings');
        $this->smarty_wrapper->assign('title_fixed', $this->config->item('title_fixed'));
        $this->smarty_wrapper->assign('m', $m);
        $this->smarty_wrapper->assign('posted', $posted);
        $this->smarty_wrapper->assign('lang', $language);
        $this->smarty_wrapper->assign('languages', $languages);
        $this->smarty_wrapper->assign('settings', $settings);
        $this->smarty_wrapper->assign('user', $user);
        $this->smarty_wrapper->assign('selectMenuItem', 'settings');

        $this->common->removePostedFromSession('settings_general');

        $this->smarty_wrapper->display("settings/settings_general.tpl");
    }
    
    function action()
    {
        # init session
        $this->common->init();

        $language = $this->multilanguage->loadLanguage();
    	
    	if (!$this->user->isAuthenticated())
        {
            $message = new Message();
            
            $message->page = 'login';
            $message->content = $language['message_login_required'];
            $message->type = 'PROBLEM';
            $this->messenger->setMessage($message);

            header("Location: " . $this->config->item('base_url') . 'login');
            die;
		}

        $userID = $this->common->getFromSession('TEFTER_USERID');
        $user = $this->user->fetchOne($userID);

        if (!$this->user->isMinimumLevel($user, 99))
        {
        	show_404('page');
        	die;
		}

        switch (htmlspecialchars($this->uri->segment(4 + $this->config->item('segment_addition'))))
        {
            case 'save':
                $license_number = $this->common->getParameter('license', 'text');
//                $application_url = $this->common->getParameter('url', 'text');
//                $absolute_path = $this->common->getParameter('absolute_path', 'text');
                $company_name = $this->common->getParameter('company', 'text');
                $default_lang = $this->common->getParameter('default_lang', 'text');
                $date_time_format = $this->common->getParameter('date_time_format', 'text');
                $items_per_page = $this->common->getParameter('items_per_page', 'text');
                $server_timezone = $this->common->getParameter('server_timezone', 'text');
            	
                $posted = $_POST;
                
                if (empty($license_number))
                {
                    $message = new Message();
                    
                    $message->page = 'settings_general';
                    $message->content = $language['message_license_number_required'];
                    $message->type = 'PROBLEM';
                    $this->messenger->setMessage($message);
                    
                    $this->common->storePostedIntoSession('settings_general', $posted, 'license');

                    header("Location: " . $this->config->item('base_url') . 'settings/general');
                    die;
                }

                if (!empty($items_per_page))
                {
	                if (!$this->common->checkInteger($items_per_page))
	                {
	                    $message = new Message();
	                    
	                    $message->page = 'settings_general';
	                    $message->content = $language['message_field_not_integer'];
	                    $message->type = 'PROBLEM';
	                    $this->messenger->setMessage($message);
	                    
	                    $this->common->storePostedIntoSession('settings_general', $posted, 'items_per_page');

	                    header("Location: " . $this->config->item('base_url') . 'settings/general');
	                    die;
					}
				}
				
				if (!empty($license_number))
				{
					$setting = new Settings();
					
					$setting->setting_id = 13;
					$setting->setting_value = $license_number;
					
					$updated = $this->settings->update($setting);

	                if (!$updated)
	                {
	                    $message = new Message();
	                    
	                    $message->page = 'settings_general';
	                    $message->content = $language['message_error_during_process'] . ' ("' . $language['label_license_number'] . '")';
	                    $message->type = 'PROBLEM';
	                    $this->messenger->setMessage($message);
	                    
	                    $this->common->storePostedIntoSession('settings_general', $posted, 'license');

	                    header("Location: " . $this->config->item('base_url') . 'settings/general');
	                    die;
	                }
				}

				$setting = new Settings();
				
				$setting->setting_id = 16;
				$setting->setting_value = $company_name;
				
				$updated = $this->settings->update($setting);

	            if (!$updated)
	            {
	                $message = new Message();
	                
	                $message->page = 'settings_general';
	                $message->content = $language['message_error_during_process'] . ' ("' . $language['label_name_company'] . '")';
	                $message->type = 'PROBLEM';
	                $this->messenger->setMessage($message);
	                
	                $this->common->storePostedIntoSession('settings_general', $posted, 'company');

	                header("Location: " . $this->config->item('base_url') . 'settings/general');
	                die;
	            }

				if (!empty($date_time_format))
				{
					$setting = new Settings();
					
					$setting->setting_id = 18;
					$setting->setting_value = $date_time_format;
					
					$updated = $this->settings->update($setting);

	                if (!$updated)
	                {
	                    $message = new Message();
	                    
	                    $message->page = 'settings_general';
	                    $message->content = $language['message_error_during_process'] . ' ("' . $language['label_default_date_formatting'] . '")';
	                    $message->type = 'PROBLEM';
	                    $this->messenger->setMessage($message);
	                    
	                    $this->common->storePostedIntoSession('settings_general', $posted, 'date_time_format');

	                    header("Location: " . $this->config->item('base_url') . 'settings/general');
	                    die;
	                }
				}

				if (!empty($items_per_page))
				{
					$setting = new Settings();
					
					$setting->setting_id = 17;
					$setting->setting_value = $items_per_page;
					
					$updated = $this->settings->update($setting);

	                if (!$updated)
	                {
	                    $message = new Message();
	                    
	                    $message->page = 'settings_general';
	                    $message->content = $language['message_error_during_process'] . ' ("' . $language['label_number_items_per_page'] . '")';
	                    $message->type = 'PROBLEM';
	                    $this->messenger->setMessage($message);
	                    
	                    $this->common->storePostedIntoSession('settings_general', $posted, 'items_per_page');

	                    header("Location: " . $this->config->item('base_url') . 'settings/general');
	                    die;
	                }
				}
				
				if (!empty($default_lang))
				{
					$updated = $this->multilanguage->setCurrentLanguage($default_lang);
					
					$language = $this->multilanguage->loadLanguage();
					
	                if (!$updated)
	                {
	                    $message = new Message();
	                    
	                    $message->page = 'settings_general';
	                    $message->content = $language['message_error_during_process'] . ' ("' . $language['label_default_language'] . '")';
	                    $message->type = 'PROBLEM';
	                    $this->messenger->setMessage($message);
	                    
	                    $this->common->storePostedIntoSession('settings_general', $posted, 'default_lang');

	                    header("Location: " . $this->config->item('base_url') . 'settings/general');
	                    die;
	                }
				}

				if (!empty($server_timezone))
				{
					$setting = new Settings();
					
					$setting->setting_id = 19;
					$setting->setting_value = $server_timezone;
					
					$updated = $this->settings->update($setting);

	                if (!$updated)
	                {
	                    $message = new Message();
	                    
	                    $message->page = 'settings_general';
	                    $message->content = $language['message_error_during_process'] . ' ("' . $language['label_default_date_formatting'] . '")';
	                    $message->type = 'PROBLEM';
	                    $this->messenger->setMessage($message);
	                    
	                    $this->common->storePostedIntoSession('settings_general', $posted, 'server_timezone');

	                    header("Location: " . $this->config->item('base_url') . 'settings/general');
	                    die;
	                }
				}

	            $message = new Message();
	            
	            $message->page = 'settings_general';
	            $message->content = $language['message_settings_general_success'];
	            $message->type = 'SUCCESS';
	            $this->messenger->setMessage($message);

	            header("Location: " . $this->config->item('base_url') . 'settings/general');
	            die;
            break;
		}
	}
}    
?>