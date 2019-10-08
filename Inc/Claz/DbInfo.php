<?php
namespace Inc\Claz;

/**
 * class Dbinfo
 * Contains the database connection information.
 * @author Rich Rowley
 */
class DbInfo {
    private $dbname;
    private $adapter;
    private $filepath;
    private $host;
    private $password;
    private $port;
    private $prefix;
    private $sectionname;
    private $username;

    /**
     * Class constructor
     * @param string $filepath File path including name for the file that
     *        that contains the secured information.
     * @param string $sectionname Name of secured information file section
     *        with database information to be used.
     * @param string (Optional) $prefix Value that is at the first part of the field separated from the
     *        rest of the parameter name by a period. Ex: <i>database.adapter</i> is the <i>adapter</i>
     *        field with a prefix of <i>database</i>.
     * @throws PdoDbException
     */
    public function __construct($filepath, $sectionname, $prefix) {
        $this->adapter  = "mysql";
        $this->dbname   = null;
        $this->host     = null;
        $this->password = null;
        $this->port     = "3306";
        $this->username = null;
        $this->loadSectionInfo($filepath, $sectionname, $prefix);
    }

    /**
     * Load and decrypt the database parameters for the specified section.
     * @param string $filepath Path for file containing the encrypted information.
     * @param string $sectionname Name of section to load database parameters from. The section
     *        is in the form, <b>[sectionname]</b>.
     * @param string $prefix Value that is at the first part of the field separated from the
     *        rest of the parameter name by a period. Ex: <i>database.adapter</i> is the <i>adapter</i>
     *        field with a prefix of <i>database</i>.
     * @throws PdoDbException If unable to open the file and file the section name and data to decrypt.
     */
    public function loadSectionInfo($filepath, $sectionname, $prefix) {
        $this->filepath = $filepath;
        $this->sectionname = $sectionname;
        $this->prefix = $prefix;
        if (($secure_info = file($this->filepath, FILE_USE_INCLUDE_PATH)) === false) {
            throw new PdoDbException("DbInfo loadSectionInfo(): Unable to open the secure information file, $this->filepath");
        }

        $found = false;
        $lines = array();

        // Find the section to use and capture lines within that section
        foreach($secure_info as $line) {
            $line = trim($line);
            // Search for pattern (sans quotes): "   [xA0_ -.]". Ex: "   [Section_A 1]"
            if (preg_match('/^\[[a-zA-Z0-9_ :\-\.]+\]/', $line) === 1) {

                // If section found previously, we can terminate loop on load of another section.
                if ($found) {
                    break;
                }
                // This is a section line. Look to see if the one we are looking for.
                $beg = strpos($line, '[') + 1;
                $len = strpos($line, ']') - $beg;
                $section = substr($line, $beg, $len);
                $found = ($section == $this->sectionname);
            }

            if ($found) {
                $lines[] = $line;
            }
        }

        if (!$found) {
            throw new PdoDbException("DbInfo loadSectionInfo(): Section, $this->sectionname, not found.");
        }

        // Loop through lines for the section and find the database info in it.
        foreach ($lines as $line) {
            if (strstr($line,"=") !== false) {
                $parts = explode("=", $line);
                if (count($parts) == 2) {
                    $pieces = self::unjoin($line, $prefix);
                    if (preg_match('/^(adapter|dbname|host|password|port|username)$/', $pieces[0])) {
                        if (strlen($pieces[1]) < 60) {
                            $value = preg_replace('/\'/', '', $pieces[1]);
                        } else {
                            throw new PdoDbException("DbInfo::loadSectionInfo - Attempt to use deleted MyCrypt class");
                        }

                        switch ($pieces[0]) {
                            case 'adapter':
                                if (preg_match('/^pdo_/', $value) == 1) {
                                    $this->adapter = substr($value, 4);
                                } else {
                                    $this->adapter = $value;
                                }
                                break;

                            case 'dbname':
                                $this->dbname = $value;
                                break;

                            case 'host':
                                $this->host = $value;
                                break;

                            case 'password':
                                $this->password = $value;
                                break;

                            case 'port':
                                $this->port = $value;
                                break;

                            case 'username':
                                $this->username = $value;
                                break;
                        }
                    }
                }
            }
        }

        // Make sure we got the minimum info
        if (empty($this->host) || empty($this->dbname) || empty($this->password) || empty($this->username)) {
            throw new PdoDbException("DbInfo loadSectionInfo(): Missing one or more of host, dbname, password and username.");
        }
    }

    /**
     * Unjoin line parts separated by an equal sign.
     * @param string $line Line to be broken apart.
     * @param string $prefix Value that is at the first part of the field separated from the
     *        rest of the parameter name by a period. Ex: <i>database.adapter</i> is the <i>adapter</i>
     *        field with a prefix of <i>database</i>.
     * @return array $pieces The two parts of the line previously joined by the equal sign.
     */
    private static function unjoin($line, $prefix) {
        $line = preg_replace('/^(.*);.*$/', '$1', $line);
        $pieces = explode("=", $line, 2);
        if (count($pieces) != 2) return array("","");

        $parts = explode(".", $pieces[0]);
        $ndx = count($parts) - 1;
        if (!empty($prefix) && ($ndx < 1 || trim($parts[0] != $prefix))) return array("","");

        $pieces[0] = trim($parts[$ndx]);
        $pieces[1] = trim($pieces[1]);
        return $pieces;
    }

    /**
     * getter for class property.
     * @return string $adapter
     */
    public function getAdapter() {
        return $this->adapter;
    }

    /**
     * getter for class property.
     * @return string $dbname.
     */
    public function getDbname() {
        return $this->dbname;
    }

    /**
     * getter for class property.
     * @return string $host.
     */
    public function getHost() {
        return $this->host;
    }

    /**
     * getter for class property.
     * @return string $password.
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * getter for class property.
     * @return string $port.
     */
    public function getPort() {
        return $this->port;
    }

    /**
     * getter for class property.
     * @return string $sectionname.
     */
    public function getSectionname() {
        return $this->sectionname;
    }

    /**
     * getter for class property.
     * @return string $username.
     */
    public function getUsername() {
        return $this->username;
    }
}
