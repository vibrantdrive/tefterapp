<?php
class Login extends Controller {

	function Login()
	{
		parent::Controller();	

        $this->load->library('common');
        $this->load->library('Smarty_Wrapper');
        $this->load->library('messenger');
        $this->load->library('user');
        $this->load->library('thumbnail');
        
        $this->load->helper('url');
	}
	
	function index()
	{
        # init session
        $this->common->init();
        
        $language = $this->multilanguage->loadLanguage();

	    $installed = $this->config->item('app_installed');
	    
	    $enab = false;
	    
	    if ($installed)
	    {
        	if (file_exists($this->config->item('home_path') . 'install'))
        	{
        		$message = new Message();

		        $message->page = 'login';
		        $message->content = $language['message_install_folder_not_removed'];
		        $message->type = 'NOTICE';
		        $this->messenger->setMessage($message);
		        
		        $enab = false;
			}
			else
			{
				$enab = true;
				
				$automaticLogin = $this->user->doAutomaticLogin();

				if ($automaticLogin)
				{
					header("Location: " . $this->config->item('base_url') . 'dashboard');
					die;
				}
			}
		}

        $set = $this->settings->fetchBaseAll();
        $settings = $this->settings->transformToArray($set);

        $m = $this->messenger->getMessage('login');

        $this->smarty_wrapper->assign('title', 'Login');
        $this->smarty_wrapper->assign('title_fixed', $this->config->item('title_fixed'));
        $this->smarty_wrapper->assign('m', $m);
        $this->smarty_wrapper->assign('enab', $enab);
        $this->smarty_wrapper->assign('lang', $language);
        $this->smarty_wrapper->assign('settings', $settings);

        $this->smarty_wrapper->display("login/login.tpl");
	}

    function action()
    {
        # init session
        $this->common->init();

        $language = $this->multilanguage->loadLanguage();

        # get parameters
        $email = $this->common->getParameter('email');
        $password = $this->common->getParameter('password');
        $remember_me = $this->common->getParameter('remember-me');
        
        # check errors
        if (empty($email) || empty($password))
        {
            $message = new Message();
            
            $message->page = 'login';
            $message->content = $language['message_required_fields'];
            $message->type = 'PROBLEM';
            $this->messenger->setMessage($message);

            header("Location: " . $this->config->item('base_url') . 'login');
            die;
        }

        if (!$this->common->check_email_address($email))
        {
            $message = new Message();

            $message->page = 'login';
            $message->content = $language['message_email_format'];
            $message->type = 'PROBLEM';
            $this->messenger->setMessage($message);

            header("Location: " . $this->config->item('base_url') . 'login');
            die;
        }
        
        # login user
        $login = $this->user->login($email, $password);
        
        if ($login)
        {
            $userID = $this->common->getFromSession('TEFTER_USERID');
            $userObject = $this->user->fetchOne($userID);
            
            if ($userObject['level'] >= 0)
            {
            	# store cookie
            	if ($remember_me != null)
            	{
            		$this->user->storeLoginCookie($email, $password);
				}
            	
            	#redirect
                header("Location: " . $this->config->item('base_url') . 'dashboard');
                die;
            }
            else
            {
                $message = new Message();
                
	            $message->page = 'login';
	            $message->content = $language['message_error_during_process'];
	            $message->type = 'PROBLEM';
	            $this->messenger->setMessage($message);
                
                # redirect
                header("Location: " . $this->config->item('base_url') . 'login');
                die;
            }
        }
        else
        {
            $message = new Message();

            $message->page = 'login';
            $message->content = $language['message_user_not_exist'];
            $message->type = 'PROBLEM';
            $this->messenger->setMessage($message);

            header("Location: " . $this->config->item('base_url') . 'login');
            die;
        }
	}
}
?>