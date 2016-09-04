<?php
/**
 * Created by PhpStorm.
 * User: salov
 * Date: 02.09.16
 * Time: 21:28
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'Image_grubber_interface.php';

class Google_image_grubber implements Image_grubber_interface
{

    /**
     * address of google search api
     */
    const GOOGLE_SEARCH_URL = 'https://www.googleapis.com/customsearch/v1';

    /**
     * google max images per request
     */
    const MAX_RESULTS_PER_REQUEST = 10;

    /**
     * google search type
     */
    const SEARCH_TYPE = 'image';

    /**
     * images size
     */
    const IMAGE_SIZE = 'large';

    /**
     * @var mixed engine id of google custom search
     */
    private $search_engine_id;

    /**
     * @var mixed google developer api key
     */
    private $api_key;

    /**
     * @var int search result start from
     */
    private $start_index;

    /**
     * @var string api search string
     */
    private $query_string;

    /**
     * @var string tag to search
     */
    private $key_word;

    /**
     * @var string folder for image saving
     */
    private $images_folder;

    /**
     * Google_image_grubber constructor.
     *
     * @param array $params constructor parameters
     */
    public function __construct(array $params)
    {
        $CI =& get_instance();

        $this->images_folder = $CI->config->item('images_directory_path');

        $this->start_index = 1;

        $this->search_engine_id = $params['search_engine_id'];

        $this->api_key = $params['api_key'];
    }

    /**
     * @param string $key_word tag to search
     * @param int    $images_quantity images quantity to search
     *
     * @return bool - grub results
     * @throws Exception
     */
    public function grub_images(string $key_word, int $images_quantity): bool
    {
        $this->key_word = $key_word;

        $this->construct_query_string();

        $requestsQuantity = $images_quantity / self::MAX_RESULTS_PER_REQUEST;

        for ($i = 1; $i <= $requestsQuantity; $i++) {

            $grub_query_result = $this->send_query_request();

            if (!$grub_query_result) {
                throw new Exception(
                    'An error has occurred while request sending'
                );
            }

            if (!empty($grub_query_result['error'])) {
                throw new Exception($grub_query_result['error']['message']);
            }


            if (empty($grub_query_result['items'])) {
                continue;
            }

            if (empty($grub_query_result['queries']['nextPage'])) {
                break;
            }

            if (!empty($grub_query_result['items'])) {
                foreach ($grub_query_result['items'] as $item) {
                    $this->download_image_by_url($item['link']);

                    $this->start_index++;
                }
                $this->start_index++;
            }

            $this->construct_query_string();
        }
        return true;
    }

    /**
     * @return bool query string construction result
     */
    private function construct_query_string(): bool
    {
        $this->query_string = self::GOOGLE_SEARCH_URL . '?'
            . 'key=' . $this->api_key
            . '&cx=' . $this->search_engine_id
            . '&q=' . $this->key_word
            . '&searchType=' . self::SEARCH_TYPE
            . '&imgSize=' . self::IMAGE_SIZE
            . '&num=' . self::MAX_RESULTS_PER_REQUEST
            . '&start=' . $this->start_index;

        return true;
    }

    /**
     * @return array array of server json answer
     */
    private function send_query_request(): array
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->query_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $body = curl_exec($ch);
        curl_close($ch);

        return json_decode($body, true);
    }

    /**
     * @param string $url - url for download
     *
     * @return bool - download result
     * @throws Exception
     */
    public function download_image_by_url(string $url): bool
    {
        $ch = curl_init($url);

        $file_name = uniqid() . $this->get_remote_file_extension($url);

        $save_directory_name = $this->images_folder
            . $this->get_save_directory_name() . '/';

        if (!is_dir($save_directory_name)) {
            if(!mkdir($save_directory_name, 0775, true)) {
                throw new Exception('An error has occured while folder creation');
            }
        }

        $fp = fopen($save_directory_name . $file_name, 'wb');

        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        curl_exec($ch);

        curl_close($ch);

        fclose($fp);

        return true;
    }

    /**
     * @param string $url - url of image
     *
     * @return string - extension of image
     */
    private function get_remote_file_extension(string $url): string
    {
        return '.' . pathinfo($url, PATHINFO_EXTENSION);
    }

    /**
     * @return string - sanitized directory name for image saving
     */
    private function get_save_directory_name(): string
    {
        $CI =& get_instance();
        $CI->load->helper('filesystem');

        return sanitize_directory_name($this->key_word);
    }

}