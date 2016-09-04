<?php
/**
 * Created by PhpStorm.
 * User: salov
 * Date: 03.09.16
 * Time: 0:07
 */
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('sanitize_directory_name'))
{
    /**
     * @param string $directory_name - direactory name string
     *
     * @return string - lowercased trimed hashed directory name
     */
    function sanitize_directory_name(string $directory_name): string
    {
        return md5(trim(mb_strtolower($directory_name)));
    }
}