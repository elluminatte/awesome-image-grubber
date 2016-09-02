<?php
/**
 * Created by PhpStorm.
 * User: salov
 * Date: 02.09.16
 * Time: 21:25
 */
defined('BASEPATH') OR exit('No direct script access allowed');

interface Image_grubber_interface {
    public function grub_images(string $key_word, int $images_quantity): bool;
}