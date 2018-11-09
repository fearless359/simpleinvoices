<?php
namespace Inc\Claz;

/**
 * Class CronLog
 * @package Inc\Claz
 * Cronlog runs outside of sessions and triggered by Cron
 * Manually set the domain_id class member before using class methods
 */
class CronLog
{
    /**
     * @param PdoDb $pdoDb
     * @param $domain_id
     * @param $cron_id
     * @param $run_date
     * @return mixed|string
     */
    public static function insert(PdoDb $pdoDb, $domain_id, $cron_id, $run_date) {
        $id = '';
        try {
            $pdoDb->setFauxPost(array("domain_id" => $domain_id,
                "cron_id" => $cron_id,
                "run_date" => $run_date));
            $id = $pdoDb->request("INSERT", "cron_log");
        } catch (PdoDbException $pde) {
            error_log("CronLog::insert() - Error: " . $pde->getMessage());
        }
        return $id;
    }

    /**
     * @param PdoDb $pdoDb
     * @param $domain_id
     * @param $cron_id
     * @param $run_date
     * @return int
     */
    public static function check(PdoDb $pdoDb, $domain_id, $cron_id, $run_date) {
        $rows = array();
        try {
            $pdoDb->addSimpleWhere('cron_id', $cron_id, "AND");
            $pdoDb->addSimpleWhere("run_date", $run_date, "AND");
            $pdoDb->addSimpleWhere("domain_id", $domain_id);
            $pdoDb->addToFunctions("COUNT(*) AS count");
            $rows = $pdoDb->request("SELECT", "cron_log");
        } catch (PdoDbException $pde) {
            error_log("CronLog::check() - Error: " . $pde->getMessage());
        }
        return (empty($rows) ? 0 : $rows[0]['count']);
    }

    /**
     * @param PdoDb $pdoDb
     * @param $domain_id
     * @return array|mixed
     */
    public static function select(PdoDb $pdoDb, $domain_id) {
        $rows = array();
        try {
            $pdoDb->addSimpleWhere("domain_id", $domain_id);
            $pdoDb->setOrderBy(array(array("run_date", "DESC"), array("id", "DESC")));
            $rows = $pdoDb->request("SELECT", "cron_log");
        } catch (PdoDbException $pde) {
            error_log("CronLog::select() - Error: " . $pde->getMessage());
        }
        return $rows;
    }
}
