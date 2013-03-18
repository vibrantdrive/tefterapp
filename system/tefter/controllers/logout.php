<?php
class Logout extends Controller {

    function Logout()
    {
        parent::Controller();    
        
        $this->load->library('common');
        $this->load->library('user');
        $this->load->library('messenger');
        
        $this->load->helper('url');
    }
    
    function index()
    {
        $this->common->init();
        
        $this->user->logout();
        
        $this->common->init();

        $language = $this->multilanguage->loadLanguage();

        $message = new Message();
        
        $message->page = 'login';
        $message->content = $language['message_logged_out'];
        $message->type = 'SUCCESS';
        
        $this->messenger->setMessage($message);

        header("Location: " . $this->config->item('base_url') . 'login');
        die;
    }
}
?>