<?php

namespace Inc\Claz;

/**
 * Requests class
 * @author Rich
 * Apr 28, 2016
 */
class Requests
{
    private PdoDb $pdoDb;
    private array $requests;
    private array $addIds;

    /**
     * Class constructor.
     * Opens database and initializes class properties.
     * @param array $config
     * @throws PdoDbException
     */
    public function __construct(array $config)
    {
        $this->pdoDb = new PdoDb($config);
        $this->reset();
    }

    /**
     * Reset class properties.
     */
    public function reset(): void
    {
        $this->requests = [];
        $this->addIds = [];
        $this->pdoDb->clearAll();
    }

    /**
     * Turn database debug on.
     * @noinspection PhpUnused
     */
    public function debugOn(): void
    {
        $this->pdoDb->debugOn();
    }

    /**
     * Turn database debug off.
     * @noinspection PhpUnused
     */
    public function debugOff(): void
    {
        $this->pdoDb->debugOff();
    }

    /**
     * Add a <b>Request</b> to be processed.
     * @param Request $request
     * @return int Number of request. Keep for retrieval of the record ID automatically assigned
     *             to new records assuming the table the record was added to, has an auto assign field.
     */
    public function add(Request $request): int
    {
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
    public function process(): void
    {
        $idx = 0;
        $this->pdoDb->begin();
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
     * @param int $idx Number of the request to get the ID associated with it.
     * @return int ID assigned or 0 if no ID exists.
     * @noinspection PhpUnused
     */
    public function getAddId(int $idx): int
    {
        if (array_key_exists($idx, $this->addIds)) {
            return $this->addIds[$idx];
        }
        return 0;
    }
}
