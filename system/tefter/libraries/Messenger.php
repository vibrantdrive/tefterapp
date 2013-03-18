<?php
    if (!defined('BASEPATH')) exit('No direct script access allowed');

    class Message
    {
    	public $page;
    	public $type;
    	public $content;
	}
    
    class Messenger
    {
        public function setMessage($message)
        {
            $obj =& get_instance();

            if(isset($message))
            {
                $obj->common->storeIntoSession('TEFTER_MESSAGE', $message);
            }
        }

        public function getMessage($page)
        {
            $obj =& get_instance();

            if(isset($page))
            {
                if(isset($_SESSION['TEFTER_MESSAGE']))
                {
                    $message = $obj->common->getFromSession('TEFTER_MESSAGE');
                    if(strcasecmp($message->page, $page) == 0)
                    {
                        switch ($message->type){
                            case 'SUCCESS':
                                $message->content = '<div class="message success"><p>' . $message->content . '</p></div>';
                            break;
                            case 'NOTICE':
                                $message->content = '<div class="message notice"><p>' . $message->content . '</p></div>';
                            break;
                            case 'PROBLEM':
                                $message->content = '<div class="message problem"><p>' . $message->content . '</p></div>';
                            break;
                        }    
                        
                        $obj->common->removeFromSession('TEFTER_MESSAGE');
                        
                        return $message;
                    }
                }
            }
        }
    }
?>