<?php

namespace Inc\Claz;

/**
 * class Dbinfo
 * Contains the database connection information.
 * @author Rich Rowley
 */
class DbInfo
{
    private string $adapter;
    private string $dbname;
    private string $host;
    private string $password;
    private string $port;
    private string $prefix;
    private string $sectionName;
    private string $username;

    /**
     * Class constructor
     * @param string $filepath File path including name for the file that
     *        that contains the secured information.
     * @param string $sectionName Name of secured information file section
     *        with database information to be used.
     * @param string (Optional) $prefix Value that is at the first part of the field separated from the
     *        rest of the parameter name by a period. Ex: <i>database.adapter</i> is the <i>adapter</i>
     *        field with a prefix of <i>database</i>.
     * @throws PdoDbException
     */
    public function __construct(string $filepath, string $sectionName, string $prefix)
    {
        $this->adapter = "mysql";
        $this->dbname = "";
        $this->host = "";
        $this->password = "";
        $this->port = "3306";
        $this->username = "";
        $this->loadSectionInfo($filepath, $sectionName, $prefix);
    }

    /**
     * Load and decrypt the database parameters for the specified section.
     * @param string $filepath Path for file containing the encrypted information.
     * @param string $sectionName Name of section to load database parameters from. The section
     *        is in the form, <b>[sectionName]</b>.
     * @param string $prefix Value that is at the first part of the field separated from the
     *        rest of the parameter name by a period. Ex: <i>database.adapter</i> is the <i>adapter</i>
     *        field with a prefix of <i>database</i>.
     * @throws PdoDbException If unable to open the file and file the section name and data to decrypt.
     */
    public function loadSectionInfo(string $filepath, string $sectionName, string $prefix): void
    {
        $this->sectionName = $sectionName;
        $this->prefix = $prefix;
        if (($secureInfo = file($filepath, FILE_USE_INCLUDE_PATH)) === false) {
            throw new PdoDbException("DbInfo loadSectionInfo(): Unable to open the secure information file, $filepath");
        }

        $found = false;
        $lines = [];

        // Find the section to use and capture lines within that section
        $pattern = '/^\[[a-zA-Z0-9_ :\-\.]+\]/';
        foreach ($secureInfo as $line) {
            $line = trim($line);
            // Search for pattern (sans quotes): "   [xA0_ -.]". Ex: "   [Section_A 1]"
            if (preg_match($pattern, $line) === 1) {

                // If section found previously, we can terminate loop on load of another section.
                if ($found) {
                    break;
                }
                // This is a section line. Look to see if the one we are looking for.
                $beg = strpos($line, '[') + 1;
                $len = strpos($line, ']') - $beg;
                $section = substr($line, $beg, $len);
                $found = $section == $this->sectionName;
            }

            if ($found) {
                $lines[] = $line;
            }
        }

        if (!$found) {
            throw new PdoDbException("DbInfo loadSectionInfo(): Section, $this->sectionName, not found.");
        }

        // Loop through lines for the section and find the database info in it.
        foreach ($lines as $line) {
            if (strstr($line, "=") !== false) {
                $parts = explode("=", $line);
                if (count($parts) == 2) {
                    $pieces = self::unJoin($line, $prefix);
                    if (preg_match('/^(adapter|dbname|host|password|port|username)$/', $pieces[0])) {
                        if (strlen($pieces[1]) >= 60) {
                            throw new PdoDbException("DbInfo::loadSectionInfo - Attempt to use deleted MyCrypt class");
                        }
                        $value = preg_replace('/\'/', '', $pieces[1]);
                        if (!is_string($value)) {
                            throw new PdoDbException("DbInfo::loadSectionInfo() - Non-string type found in value");
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

                            default:
                                throw new PdoDbException("DbInfo loadSectionInfo(): Invalid key value[{$pieces[0]}");
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
     * Un-join line parts separated by an equal sign.
     * @param string $line Line to be broken apart.
     * @param string $prefix Value that is at the first part of the field separated from the
     *        rest of the parameter name by a period. Ex: <i>database.adapter</i> is the <i>adapter</i>
     *        field with a prefix of <i>database</i>.
     * @return array $pieces The two parts of the line previously joined by the equal sign.
     */
    private static function unJoin(string $line, string $prefix): array
    {
        $line = preg_replace('/^(.*);.*$/', '$1', $line);
        $pieces = explode("=", $line, 2);
        if (count($pieces) != 2) {
            return ["", ""];
        }

        $parts = explode(".", $pieces[0]);
        $ndx = count($parts) - 1;
        if (!empty($prefix) && ($ndx < 1 || trim($parts[0] != $prefix))) {
            return ["", ""];
        }

        $pieces[0] = trim($parts[$ndx]);
        $pieces[1] = trim($pieces[1]);
        return $pieces;
    }

    /**
     * getter for class property.
     * @return string $adapter
     */
    public function getAdapter(): string
    {
        return $this->adapter;
    }

    /**
     * getter for class property.
     * @return string $dbname.
     */
    public function getDbname(): string
    {
        return $this->dbname;
    }

    /**
     * getter for class property.
     * @return string $host.
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * getter for class property.
     * @return string $password.
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * getter for class property.
     * @return string $port.
     */
    public function getPort(): string
    {
        return $this->port;
    }

    /**
     * getter for class property.
     * @return string $sectionName.
     */
    public function getSectionName(): string
    {
        return $this->sectionName;
    }

    /**
     * getter for class property.
     * @return string $username.
     */
    public function getUsername(): string
    {
        return $this->username;
    }
}
