<?php
function smarty_modifier_smart_truncate($params) {
    if (!function_exists('smart_truncate')) {
        //return error message in case we can't get CI instance
        if (!function_exists('get_instance')) return "Can't get CI instance";
        $CI= &get_instance();
        $CI->load->library('common');
    }
    
    return $CI->common->smart_truncate($params);
}
?>