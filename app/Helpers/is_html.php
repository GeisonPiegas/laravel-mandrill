<?php

if (!function_exists('is_html')) {
    function is_HTML($data){
        return $data != strip_tags($data) ? true : false;
    }
}
