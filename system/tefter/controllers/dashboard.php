<?php
class Dashboard extends Controller {

    function Dashboard()
    {
        parent::Controller();    
        
        $this->load->library('common');
        $this->load->library('Smarty_Wrapper');
        $this->load->library('user');
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
        
        if (!$this->user->isMinimumLevel($user, 20))
        {
        	show_404('page');
        	die;
		}
        
        $set = $this->settings->fetchBaseAll();
        $settings = $this->settings->transformToArray($set);
        
        if ($user['level'] > 20)
        {
        	$usersPasswordReset = $this->user->fetchAllPasswordReset($user['user_id'], $user['level']);

        	$this->smarty_wrapper->assign('usersPasswordReset', $usersPasswordReset);
        	
        	if (count($usersPasswordReset) > 0)
        	{
        		$this->smarty_wrapper->assign('hasCount', true);
			}
			else
			{
        		$this->smarty_wrapper->assign('hasCount', false);
			}
		}
        
        $m = $this->messenger->getMessage('dashboard');
        
        $this->smarty_wrapper->assign('title', 'Dashboard');
        $this->smarty_wrapper->assign('title_fixed', $this->config->item('title_fixed'));
        $this->smarty_wrapper->assign('m', $m);
        $this->smarty_wrapper->assign('lang', $language);
        $this->smarty_wrapper->assign('settings', $settings);
        $this->smarty_wrapper->assign('user', $user);
        $this->smarty_wrapper->assign('selectMenuItem', 'dashboard');
        
        $this->smarty_wrapper->display("dashboard/dashboard.tpl");
    }
    
    function actions()
    {
        # init session
        $this->common->init();

        $language = $this->multilanguage->loadLanguage();

        $userID = $this->common->getFromSession('TEFTER_USERID');
        $user = $this->user->fetchOne($userID);

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

        if (!$this->user->isMinimumLevel($user, 20))
        {
        	show_404('page');
        	die;
		}

        $set = $this->settings->fetchBaseAll();
        $settings = $this->settings->transformToArray($set);

        switch (htmlspecialchars($this->uri->segment(3 + $this->config->item('segment_addition'))))
        {
            case 'changepassword':
            	$new_password = $this->common->getParameter('new_password', 'text');
            	$confirm_new_password = $this->common->getParameter('confirm_new_password', 'text');
            	
            	if (empty($new_password) || empty($confirm_new_password))
            	{
		            $message = new Message();
		            
		            $message->page = 'dashboard';
		            $message->content = $language['message_required_fields'];
		            $message->type = 'PROBLEM';
		            $this->messenger->setMessage($message);

		            header("Location: " . $this->config->item('base_url') . 'dashboard');
		            die;
				}
				
				if ($new_password != $confirm_new_password)
				{
		            $message = new Message();
		            
		            $message->page = 'dashboard';
		            $message->content = $language['message_passwords_do_not_match'];
		            $message->type = 'PROBLEM';
		            $this->messenger->setMessage($message);

		            header("Location: " . $this->config->item('base_url') . 'dashboard');
		            die;
				}
				
				$userObject = new User();
				
				$userObject->user_id = $user['user_id'];
				$userObject->password = $new_password;
				$userObject->accpassword = $new_password;
				$userObject->password_change = 0;
				
				$result = $this->user->update($userObject);
				
				if ($result)
				{
                	$this->common->storeIntoSession('TEFTER_UP', $new_password);

		            $message = new Message();
		            
		            $message->page = 'dashboard';
		            $message->content = $language['message_password_successfully_changed'];
		            $message->type = 'SUCCESS';
		            $this->messenger->setMessage($message);

		            header("Location: " . $this->config->item('base_url') . 'dashboard');
		            die;
				}
				else
				{
		            $message = new Message();
		            
		            $message->page = 'dashboard';
		            $message->content = $language['message_error_during_process'];
		            $message->type = 'PROBLEM';
		            $this->messenger->setMessage($message);

		            header("Location: " . $this->config->item('base_url') . 'dashboard');
		            die;
				}
            break;
            
            case 'dontchangepassword':
            	$userObject = new User();
            	
            	$userObject->user_id = $user['user_id'];
            	$userObject->password_change = 0;
            	
            	$result = $this->user->update($userObject);
            	
            	if ($result)
            	{
		            $message = new Message();
		            
		            $message->page = 'dashboard';
		            $message->content = $language['message_decided_not_to_change_password'];
		            $message->type = 'SUCCESS';
		            $this->messenger->setMessage($message);

		            header("Location: " . $this->config->item('base_url') . 'dashboard');
		            die;
				}
				else
				{
		            $message = new Message();
		            
		            $message->page = 'dashboard';
		            $message->content = $language['message_error_during_process'];
		            $message->type = 'PROBLEM';
		            $this->messenger->setMessage($message);

		            header("Location: " . $this->config->item('base_url') . 'dashboard');
		            die;
				}
            break;
		}
	}
}
?>