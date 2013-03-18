<?php
class Forgot_Password extends Controller {

    function Forgot_Password()
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
        
        $set = $this->settings->fetchBaseAll();
        $settings = $this->settings->transformToArray($set);

        $m = $this->messenger->getMessage('forgot_password');
        
        $this->smarty_wrapper->assign('title', 'Forgot password');
        $this->smarty_wrapper->assign('title_fixed', $this->config->item('title_fixed'));
        $this->smarty_wrapper->assign('m', $m);
        $this->smarty_wrapper->assign('lang', $language);
        $this->smarty_wrapper->assign('settings', $settings);

        $this->smarty_wrapper->display("login/forgot_password.tpl");
    }
    
    function sent()
    {
        # init session
        $this->common->init();

        $language = $this->multilanguage->loadLanguage();
        
        $set = $this->settings->fetchBaseAll();
        $settings = $this->settings->transformToArray($set);

        $m = $this->messenger->getMessage('request_sent');
        
        $this->smarty_wrapper->assign('title', 'Forgot password');
        $this->smarty_wrapper->assign('title_fixed', $this->config->item('title_fixed'));
        $this->smarty_wrapper->assign('m', $m);
        $this->smarty_wrapper->assign('lang', $language);
        $this->smarty_wrapper->assign('settings', $settings);

        $this->smarty_wrapper->display("login/request-sent.tpl");
	}
    
    function actions()
    {
        # init session
        $this->common->init();
        
        $language = $this->multilanguage->loadLanguage();

        switch (htmlspecialchars($this->uri->segment(3 + $this->config->item('segment_addition'), '')))
        {
            case 'send':
                $email_address = $this->common->getParameter('email');
                
                if (empty($email_address))
                {
                    $message = new Message();
                    
                    $message->page = 'forgot_password';
                    $message->content = $language['message_required_fields'];
                    $message->type = 'PROBLEM';
                    $this->messenger->setMessage($message);

                    header("Location: " . $this->config->item('base_url') . 'amnesia');
                    die;
                }

                if (!$this->common->check_email_address($email_address))
                {
                    $message = new Message();
                    
                    $message->page = 'forgot_password';
                    $message->content = $language['message_email_format'];
                    $message->type = 'PROBLEM';
                    $this->messenger->setMessage($message);

                    header("Location: " . $this->config->item('base_url') . 'amnesia');
                    die;
                }
                
                if (!$this->user->isEmailExist($email_address))
                {
                    $message = new Message();
                    
                    $message->page = 'forgot_password';
                    $message->content = $language['message_email_not_exist'];
                    $message->type = 'PROBLEM';
                    $this->messenger->setMessage($message);

                    header("Location: " . $this->config->item('base_url') . 'amnesia');
                    die;
                }
              
                # fetch a user with this email address
                $user = $this->user->fetchOneByEmailAddress($email_address);
                
                $editUser = new User();
                
                $editUser->user_id = $user['user_id'];
                $editUser->password_change = 2;
                
                $updated = $this->user->update($editUser);

                if ($updated)
                {
                    header("Location: " . $this->config->item('base_url') . 'amnesia/sent');
                    die;
                }
                else
                {
                    $message = new Message();

                    $message->page = 'forgot_password';
                    $message->content = $language['message_error_during_process'];
                    $message->type = 'PROBLEM';
                    $this->messenger->setMessage($message);

                    header("Location: " . $this->config->item('base_url') . 'amnesia');
                    die;
                }
            break;
            
            default:
                header("Location: " . $this->config->item('base_url') . 'amnesia');
                die;
            break;
        }
    }
}
?>