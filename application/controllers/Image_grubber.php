<?php
/**
 * Created by PhpStorm.
 * User: salov
 * Date: 02.09.16
 * Time: 18:52
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Image_grubber extends CI_Controller
{

    const IMAGES_QUANTITY = 10;

    const MAX_TAG_LENGTH = 50;

    public function index()
    {
        $this->load->view('image_grubber/index');
    }

    public function grub_images_by_tag()
    {
        try {
            $tag = $this->input->post('tag');

            $tag_length = mb_strlen($tag);

            if ($tag_length < 1 || $tag_length > self::MAX_TAG_LENGTH) {
                throw new Exception(
                    'Tag length must be between 1 and ' . self::MAX_TAG_LENGTH
                );
            }

            $this->load->helper('filesystem');

            $sanitized_tag = sanitize_directory_name($tag);

            if (!$sanitized_tag) {
                throw new Exception('Please specify correct tag');
            }

            $does_images_exist = $this->check_tagged_images_exist(
                $sanitized_tag
            );

            $this->load->helper('url');

            if ($does_images_exist) {
                redirect('image_grubber/show_images_by_tag/' . $tag);
            }

            $google_api_key = $this->config->item('google_api_key');
            $google_search_engine_id = $this->config->item(
                'google_search_engine_id'
            );

            $this->load->library(
                'image_grubber/google_image_grubber',
                ['search_engine_id' => $google_search_engine_id,
                 'api_key'          => $google_api_key], 'image_grubber'
            );

            $this->image_grubber->grub_images($tag, self::IMAGES_QUANTITY);
        } catch (Exception $e) {
            $this->load->view(
                'image_grubber/partials/error', [
                    'errors' => [
                        $e->getMessage()
                    ]
                ]
            );

            return;
        }
        redirect('image_grubber/show_images_by_tag/' . $tag);
        return;
    }

    public function show_images_by_tag($tag)
    {
        try {
            $this->load->helper('filesystem');

            $sanitized_tag = sanitize_directory_name($tag);

            if (!$sanitized_tag) {
                throw new Exception('Please specify correct tag');
            }

            $this->load->helper('directory');

            $images_directory_path = $this->config->item(
                    'images_directory_path'
                ) . $sanitized_tag;

            $images = directory_map($images_directory_path);

            $images_directory = $this->config->item('images_directory')
                . $sanitized_tag;

            $this->load->view(
                'image_grubber/partials/gallery',
                ['images_directory' => $images_directory, 'images' => $images]
            );
        } catch (Exception $e) {

            $this->load->view(
                'image_grubber/partials/error', [
                    'errors' => [
                        $e->getMessage()
                    ]
                ]
            );

            return;
        }
        return;
    }

    private function check_tagged_images_exist($sanitized_tag): bool
    {
        $this->load->helper('directory');

        $images_directory = $this->config->item('images_directory_path')
            . $sanitized_tag;

        return directory_map($images_directory) ? true : false;
    }
}