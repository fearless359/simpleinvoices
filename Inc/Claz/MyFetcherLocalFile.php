<?php
namespace Inc\Claz;

use Fetcher;
use FetchedDataURL;

require_once ('library/pdf/fetcher._interface.class.php');

/**
 * Class MyFetcherLocalFile
 * @package Inc\Claz
 */
class MyFetcherLocalFile extends Fetcher {
    public $_content;

    /**
     * MyFetcherLocalFile constructor.
     * @param $html_to_pdf
     */
    public function __construct($html_to_pdf) {
        $this->_content = $html_to_pdf;
    }

    /**
     * @param String $dummy1
     * @return \FetchedDataURL
     */
    public function get_data($dummy1) {
        return new FetchedDataURL($this->_content, array(), "");
    }

    /**
     * @return string
     */
    public function get_base_url() {
        return "";
    }
}
