<?php
/**
 * Class Install
 * @package Inc\Claz
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20221018
 */

namespace Inc\Claz;

/**
 *
 */
class Install
{
    /**
     * Check to see if the install_completed status is set.
     * @return bool true if set. false if not.
     * @noinspection PhpUnused
     */
    public static function isComplete(): bool
    {
        global $pdoDb;

        $result = false;
        try {
            $rows = $pdoDb->request('SELECT', 'install_complete');
            $result = !empty($rows) && $rows[0]['completed'] == ENABLED;
        } catch (PdoDbException $pdo) {
            error_log("Install::isComplete() - Error: " . $pdo->getMessage());
        }
        return $result;
    }

    /**
     * Set the install_completed status in database.
     * @return bool true if it was necessary to set the complete status. false if not.
     */
    public static function setComplete(): bool
    {
        global $pdoDb;

        $result = false;
        try {
            $rows = $pdoDb->request('SELECT', 'install_complete');
            if (empty($rows) || $rows[0]['completed'] != ENABLED) {
                $pdoDb->setFauxPost(['completed' => ENABLED]);
                if (empty($rows)) {
                    $pdoDb->request('INSERT', 'install_complete');
                } else {
                    $pdoDb->request('UPDATE', 'install_complete');
                }
                $result = true;
            }
        } catch (PdoDbException $pde) {
            error_log("Install::setComplete() - Error: " . $pde->getMessage());
        }

        return $result;
    }
}
