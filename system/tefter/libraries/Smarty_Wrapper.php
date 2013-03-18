<?php
    if (!defined('BASEPATH')) exit('No direct script access allowed');

    require "smarty/Smarty.class.php";
    
    class Smarty_Wrapper extends Smarty
    {

        function Smarty_Wrapper()
        {
    
			$this->Smarty();

            $this->compile_dir = APPPATH . "views/cache";
            $this->template_dir = APPPATH . "views/";
            $this->cache_dir = APPPATH . 'views/cache_files/';
            $this->config_dir = APPPATH . 'views/configs/';
            
            log_message('debug', "Smarty Class Initialized");
    
        }

    }
?>