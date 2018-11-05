<?php
namespace Inc\Claz;

/**
 * Requests class
 * @author Rich
 * Apr 28, 2016
 */
class Requests {
    /**
     * @var PdoDb
     */
    private $pdoDb;
    private $requests;
    private $addIds;

    /**
     * Class constructor.
     * Opens database and initializes class properties.
     * @throws PdoDbException
     */
    public function __construct() {
        global $environment;
        $this->pdoDb = new PdoDb(new DbInfo(Config::CUSTOM_CONFIG_FILE, $environment, "database"));
        $this->reset();
    }

    /**
     * Class destructor
     */
    public function __destruct() {
        $this->pdoDb = null;
    }

    /**
     * Reset class properties.
     */
    public function reset() {
        $this->requests = array();
        $this->addIds = array();
        try {
            $this->pdoDb->clearAll(true);
        } catch (PdoDbException $pde) {
            error_log("Requests::reset() - Error: " . $pde->getMessage());
        }
    }

    /**
     * Turn database debug on.
     */
    public function debugOn() {
        $this->pdoDb->debugOn();
    }

    /**
     * Turn database debug off.
     */
    public function debugOff() {
        $this->pdoDb->debugOff();
    }

    /**
     * Add a <b>Request</b> to be processed.
     * @param Request $request
     * @return integer Number of request. Keep for retrieval of the
     *         record ID automatically assigned to new records assuming
     *         the table the record was added to has an auto assign field.
     */
    public function add(Request $request) {
        $this->requests[] = $request;
        return count($this->requests);
    }

    /**
     * Process all requests.
     * Note: This performs a PDO transaction. If an error is thrown, all changes
     *       will be rolled back and the database will not be changed. Only upon
     *       success will all changes be applied.
     * @throws PdoDbException If an error occurs while processing requests.
     */
    public function process() {
            $idx = 0;
            $this->pdoDb->begin();
            /**
             * @var Request $request
             */
            foreach ($this->requests as $request) {
                try {
                    $result = $request->performRequest($this->pdoDb);
                    if ($request->isAdd()) {
                        $this->addIds[$idx] = $result;
                    }
                    $idx++;
                } catch (PdoDbException $pde) {
                    $this->pdoDb->rollback();
                    $str = "Requests process(): " . $request->describe() . ". Error: " . $pde->getMessage();
                    error_log($str);
                    throw new PdoDbException($str);
                }
            }
            $this->pdoDb->commit();
    }

    /**
     * Get the ID value assigned to a new record.
     * @param integer $idx Number of the request to get the ID associated with it.
     * @return integer ID assigned or 0 if no ID exists.
     */
    public function getAddId($idx) {
        if (array_key_exists($idx, $this->addIds)) return $this->addIds[$idx];
        return 0;
    }
}
