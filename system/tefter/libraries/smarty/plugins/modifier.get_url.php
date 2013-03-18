<?php
function smarty_modifier_get_url($params) {
    if (!function_exists('get_url')) {
        //return error message in case we can't get CI instance
        if (!function_exists('get_instance')) return "Can't get CI instance";
        $CI= &get_instance();
        $CI->load->library('pages');
        $CI->load->helper('url');
    }
    
    $pag = $CI->pages->fetchOneByUrl($params);
    
    if ($pag != null)
    {
        return '<a href="' . base_url() . 'page/' . $params . '">' . $pag['title'] . '</a>';
    }
    else
    {
        return null;
    }
}
?>