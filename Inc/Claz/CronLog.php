<?php

namespace Inc\Claz;

/**
 * Class CronLog
 * @package Inc\Claz
 * CronLog runs outside of sessions and triggered by Cron
 */
class CronLog
{

    /**
     * Get all cron_log records for the specified domain.
     * @param PdoDb $pdoDb
     * @param int $domainId
     * @return array
     */
    public static function getAll(PdoDb $pdoDb, int $domainId): array
    {
        $rows = [];
        try {
            $pdoDb->addSimpleWhere("domain_id", $domainId);
            $pdoDb->setOrderBy([["run_date", "DESC"], ["id", "DESC"]]);
            $rows = $pdoDb->request("SELECT", "cron_log");
        } catch (PdoDbException $pde) {
            error_log("CronLog::getOne() - Error: " . $pde->getMessage());
        }
        return $rows;
    }

    /**
     * Insert a new log record for run history.
     * @param PdoDb $pdoDb
     * @param int $domainId
     * @param int $cron_id
     * @param string $run_date
     */
    public static function insert(PdoDb $pdoDb, int $domainId, int $cron_id, string $run_date): void
    {
        try {
            $pdoDb->setFauxPost([
                "domain_id" => $domainId,
                "cron_id" => $cron_id,
                "run_date" => $run_date
            ]);
            $pdoDb->request("INSERT", "cron_log");
        } catch (PdoDbException $pde) {
            error_log("CronLog::insert() - Error: " . $pde->getMessage());
        }
    }

    /**
     * Check to see if any cron_log records exist for specified parameters.
     * @param PdoDb $pdoDb
     * @param int $domainId
     * @param int $cron_id
     * @param string $run_date
     * @return bool true if records exist; false if not.
     */
    public static function check(PdoDb $pdoDb, int $domainId, int $cron_id, string $run_date): bool
    {
        $rows = [];
        try {
            $pdoDb->addSimpleWhere('cron_id', $cron_id, "AND");
            $pdoDb->addSimpleWhere("run_date", $run_date, "AND");
            $pdoDb->addSimpleWhere("domain_id", $domainId);
            $pdoDb->addToFunctions("COUNT(*) AS count");
            $rows = $pdoDb->request("SELECT", "cron_log");
        } catch (PdoDbException $pde) {
            error_log("CronLog::check() - Error: " . $pde->getMessage());
        }
        return !empty($rows);
    }
}
