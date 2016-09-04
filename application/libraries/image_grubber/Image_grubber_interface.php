<?php
/**
 * Created by PhpStorm.
 * User: salov
 * Date: 02.09.16
 * Time: 21:25
 */
defined('BASEPATH') OR exit('No direct script access allowed');

interface Image_grubber_interface {
    /**
     * @param string $key_word - tag to grub
     * @param int    $images_quantity - quantity of images to grub
     *
     * @return bool - grub result
     */
    public function grub_images(string $key_word, int $images_quantity): bool;
}