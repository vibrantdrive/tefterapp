<?php
    include 'lib/smarty/Smarty.class.php';
    include 'lib/classes.php';
    
    class Smarty_Instance extends Smarty
    {
        function Smarty_Instance()
        {
            $this->Smarty();

            $this->template_dir = 'view/views';
            $this->compile_dir = 'view/cache';
            $this->config_dir = 'view/configs';
            $this->cache_dir = 'view/cache_files';
		}
	}
?>