<?php
class Settings_Email extends Controller {

    function Settings_Email()
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
        
        $posted = $this->common->getPostedFromSession('settings_email');

        $m = $this->messenger->getMessage('settings_email');
        
        $this->smarty_wrapper->assign('title', 'Settings');
        $this->smarty_wrapper->assign('title_fixed', $this->config->item('title_fixed'));
        $this->smarty_wrapper->assign('m', $m);
        $this->smarty_wrapper->assign('posted', $posted);
        $this->smarty_wrapper->assign('lang', $language);
        $this->smarty_wrapper->assign('languages', $languages);
        $this->smarty_wrapper->assign('settings', $settings);
        $this->smarty_wrapper->assign('user', $user);
        $this->smarty_wrapper->assign('selectMenuItem', 'settings');

        $this->common->removePostedFromSession('settings_email');

        $this->smarty_wrapper->display("settings/settings_email.tpl");
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

        $set = $this->settings->fetchBaseAll();
        $settings = $this->settings->transformToArray($set);

        switch (htmlspecialchars($this->uri->segment(4 + $this->config->item('segment_addition'))))
        {
            case 'save':
                $return_email = $this->common->getParameter('return_email', 'text');
                $from_name = $this->common->getParameter('from_name', 'text');
                $email_protocol = $this->common->getParameter('email_protocol', 'text');
                $smtp_host = $this->common->getParameter('smtp_host', 'text');
                $smtp_username = $this->common->getParameter('smtp_username', 'text');
                $smtp_password = $this->common->getParameter('smtp_password', 'text');
                
                $posted = $_POST;
                
                if (!empty($return_email))
                {
                	if (!$this->common->check_email_address($return_email))
                	{
	                    $message = new Message();
	                    
	                    $message->page = 'settings_email';
	                    $message->content = $language['message_email_format'];
	                    $message->type = 'PROBLEM';
	                    $this->messenger->setMessage($message);
	                    
	                    $this->common->storePostedIntoSession('settings_email', $posted, 'return_email');

	                    header("Location: " . $this->config->item('base_url') . 'settings/email');
	                    die;
					}
				}
                
                if ($email_protocol == 'smtp')
                {
	                if (empty($smtp_username))
	                {
	                    $message = new Message();
	                    
	                    $message->page = 'settings_email';
	                    $message->content = $language['message_username_required'];
	                    $message->type = 'PROBLEM';
	                    $this->messenger->setMessage($message);
	                    
	                    $this->common->storePostedIntoSession('settings_email', $posted, 'smtp_username');

	                    header("Location: " . $this->config->item('base_url') . 'settings/email');
	                    die;
	                }
                	
	                if (empty($smtp_password))
	                {
	                    $message = new Message();
	                    
	                    $message->page = 'settings_email';
	                    $message->content = $language['message_password_required'];
	                    $message->type = 'PROBLEM';
	                    $this->messenger->setMessage($message);
	                    
	                    $this->common->storePostedIntoSession('settings_email', $posted, 'smtp_password');

	                    header("Location: " . $this->config->item('base_url') . 'settings/email');
	                    die;
	                }
				}
				
				if (!empty($return_email))
				{
					$setting = new Settings();
					
					$setting->setting_id = 3;
					$setting->setting_value = $return_email;
					
					$updated = $this->settings->update($setting);

	                if (!$updated)
	                {
	                    $message = new Message();
	                    
	                    $message->page = 'settings_email';
	                    $message->content = $language['message_error_during_process'] . ' ("' . $language['label_return_email_address'] . '")';
	                    $message->type = 'PROBLEM';
	                    $this->messenger->setMessage($message);
	                    
	                    $this->common->storePostedIntoSession('settings_email', $posted, 'return_email');

	                    header("Location: " . $this->config->item('base_url') . 'settings/email');
	                    die;
	                }
				}
				
				if (!empty($from_name))
				{
					$setting = new Settings();
					
					$setting->setting_id = 4;
					$setting->setting_value = $from_name;
					
					$updated = $this->settings->update($setting);

	                if (!$updated)
	                {
	                    $message = new Message();
	                    
	                    $message->page = 'settings_email';
	                    $message->content = $language['message_error_during_process'] . ' ("' . $language['label_from_email_name'] . '")';
	                    $message->type = 'PROBLEM';
	                    $this->messenger->setMessage($message);
	                    
	                    $this->common->storePostedIntoSession('settings_email', $posted, 'from_name');

	                    header("Location: " . $this->config->item('base_url') . 'settings/email');
	                    die;
	                }
				}

				if (!empty($email_protocol))
				{
					$setting = new Settings();
					
					$setting->setting_id = 6;
					$setting->setting_value = $email_protocol;
					
					$updated = $this->settings->update($setting);

	                if (!$updated)
	                {
	                    $message = new Message();
	                    
	                    $message->page = 'settings_email';
	                    $message->content = $language['message_error_during_process'] . ' ("' . $language['label_email_protocol'] . '")';
	                    $message->type = 'PROBLEM';
	                    $this->messenger->setMessage($message);
	                    
	                    $this->common->storePostedIntoSession('settings_email', $posted, 'email_protocol');

	                    header("Location: " . $this->config->item('base_url') . 'settings/email');
	                    die;
	                }
				}

				if (!empty($smtp_host))
				{
					$setting = new Settings();
					
					$setting->setting_id = 7;
					$setting->setting_value = $smtp_host;
					
					$updated = $this->settings->update($setting);

	                if (!$updated)
	                {
	                    $message = new Message();
	                    
	                    $message->page = 'settings_email';
	                    $message->content = $language['message_error_during_process'] . ' ("' . $language['label_smtp_host'] . '")';
	                    $message->type = 'PROBLEM';
	                    $this->messenger->setMessage($message);
	                    
	                    $this->common->storePostedIntoSession('settings_email', $posted, 'smtp_host');

	                    header("Location: " . $this->config->item('base_url') . 'settings/email');
	                    die;
	                }
				}

				if (!empty($smtp_username))
				{
					$setting = new Settings();
					
					$setting->setting_id = 11;
					$setting->setting_value = $smtp_username;
					
					$updated = $this->settings->update($setting);

	                if (!$updated)
	                {
	                    $message = new Message();
	                    
	                    $message->page = 'settings_email';
	                    $message->content = $language['message_error_during_process'] . ' ("' . $language['label_username'] . '")';
	                    $message->type = 'PROBLEM';
	                    $this->messenger->setMessage($message);
	                    
	                    $this->common->storePostedIntoSession('settings_email', $posted, 'smtp_username');

	                    header("Location: " . $this->config->item('base_url') . 'settings/email');
	                    die;
	                }
				}

				if (!empty($smtp_password))
				{
					$setting = new Settings();
					
					$setting->setting_id = 8;
					$setting->setting_value = $smtp_password;
					
					$updated = $this->settings->update($setting);

	                if (!$updated)
	                {
	                    $message = new Message();
	                    
	                    $message->page = 'settings_email';
	                    $message->content = $language['message_error_during_process'] . ' ("' . $language['label_password'] . '")';
	                    $message->type = 'PROBLEM';
	                    $this->messenger->setMessage($message);
	                    
	                    $this->common->storePostedIntoSession('settings_email', $posted, 'smtp_password');

	                    header("Location: " . $this->config->item('base_url') . 'settings/email');
	                    die;
	                }
				}
				
	            $message = new Message();
	            
	            $message->page = 'settings_email';
	            $message->content = $language['message_settings_email_success'];
	            $message->type = 'SUCCESS';
	            $this->messenger->setMessage($message);

	            header("Location: " . $this->config->item('base_url') . 'settings/email');
	            die;
            break;
            
            case 'sendtestemail':
            	$config = $this->settings->loadEmailParameters();
            	
            	$this->email->initialize($config);
            	
            	$this->email->to($user['email']);
            	$tempmailname = $this->settings->getValueForKey($set, 'MAIL_NAME');
                if ($tempmailname != '')
                {
                    $this->email->from($this->settings->getValueForKey($set, 'MAIL_FROM'), $this->settings->getValueForKey($set, 'MAIL_NAME'));
                }
                else
                {
                    $this->email->from($this->settings->getValueForKey($set, 'MAIL_FROM'), 'Tefter');
                }
            	$this->email->subject($language['text_test_email_subject']);
            	$this->email->message($language['text_test_email_message']);
            	
/*
            	echo '<pre>';
            	var_dump($this->email);
            	echo '</pre>';
            	die;
*/            	
				error_reporting(0);

            	if (!$this->email->send())
            	{
		            $message = new Message();
		            
		            $message->page = 'settings_email';
		            $message->content = $language['message_settings_email_test_fail'] /*. ' ' . $this->email->print_debugger()*/;
		            $message->type = 'PROBLEM';
		            $this->messenger->setMessage($message);

					error_reporting(E_ALL);
					
		            header("Location: " . $this->config->item('base_url') . 'settings/email');
		            die;
				}
				else
				{
		            $message = new Message();
		            
		            $message->page = 'settings_email';
		            $message->content = $language['message_settings_email_test_success'] . ' ' . $language['message_to'] . ' ' . $user['email'];
		            $message->type = 'SUCCESS';
		            $this->messenger->setMessage($message);

					error_reporting(E_ALL);

		            header("Location: " . $this->config->item('base_url') . 'settings/email');
		            die;
				}
            break;
		}
	}
}    
?>