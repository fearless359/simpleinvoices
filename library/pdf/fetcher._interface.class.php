<?php
class Fetcher {
    /**
     * Fetches the data identified by $data_id, wraps it into FetchedData object together with
     * any auxiliary information (like HTTP response headers, number of redirect, fetched file
     * information or something else) and returns this object.
     *
     * @param String $data_id unique identifier of the data to be fetched (URI, file path,
     *        primary key of the database record or something else)
     */
    function get_data($data_id) {
        // Eliminates unused warning
        if ($data_id == null) {
            $data_id = null;
        }
        die("Oops. In overridden 'get_data' method called in " . get_class($this));
    }

    /**
     *
     * @return String value of base URL to use for resolving relative links inside the document
     */
    function get_base_url() {
        die("Oops. In overridden 'get_base_url' method called in " . get_class($this));
    }

    function error_message() {
        die("Oops. In overridden 'error_message' method called in " . get_class($this));
    }
}
