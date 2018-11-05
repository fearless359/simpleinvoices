<?php
namespace Inc\Claz;

/**
 * Script: CustomFieldDeprecated_deprecated.php
 *
 * Abstract class that should extend each custom field.
 * It defines basic function for all plugins that should be used.
 * So we can change CustomFieldDeprecated a bit without changing each CustomFieldDeprecated.
 * Some function need to be overwritten otherwise their is no output.
 *
 * The $id of a CustomFieldDeprecated have to be unique. It's hardcoded into the plugin,
 * so before setting an id, please check the other plugins.
 * The id's from 0-99 are reserved for core plug-ins. So please use an id > 100.
 *
 * Authors:
 *     Nicolas Ruflin
 *
 * Last edited:
 *      2007-09-10
 *
 * License:
 *     GPL v2 or above
 */
abstract class CustomFieldDeprecated {

    var $name;
    var $id;
    var $description;
    var $fieldId;    //to differentiate between the instances of a plug-in

    /* Constructor: name and id for each CustomFieldDeprecated needed. */
    public function __construct($id,$name) {
        $this->id = $id;
        $this->name = $name;
    }

    /***** Please override the following functions *****/
    function installPlugin() {
    }

    function updatePlugin() {
    }

    function printInputField() {
    }
    /***** Please overwrite the above functions *****/

    /**
     * Updates the custom field value
     * @param $value
     * @param $itemId
     */
    function updateInput($value, $itemId) {
        global $dbh;

        $sth = $dbh->prepare('SELECT * FROM '.TB_PREFIX.'customFieldValues WHERE customFieldID = :field AND itemID = :item');
        $sth->execute(':field', $this->fieldId, ':item', $itemId);
        $result = $sth->fetch();

        if($result == null) {
            $this->saveInput($value,$itemId);
        }
        else {
            $sql = "UPDATE ".TB_PREFIX."customFieldValues SET value = :value WHERE customFieldId = :field AND itemId = :item" ;
            dbQuery($sql, ':value', $value, ':field', $this->fieldId, ':item', $itemId);
        }
    }

    /* Returns the value for a choosen field and item. Should be unique, because the itemId for each categorie is unique. */
    function getFieldValue($customFieldId, $itemId) {
        $sql = "SELECT * FROM ".TB_PREFIX."customFieldValues WHERE (customFieldId = :field AND itemId = :item)";
        $sth = dbQuery($sql, ':field', $customFieldId, ':item', $itemId);

        if($sth) {
            $value = $sth->fetch();
            return $value['value'];
        }

        return "";
    }

    function getValue($id) {
        $sql = "SELECT * FROM ".TB_PREFIX."customFieldValues WHERE id = :id";
        $sth = dbQuery($sql, ':id', $id);

        if($sth) {
            $value = $sth->fetch();
            return $value['value'];
        }

        return "";
    }

    /* Stores the input into the database */
    function saveInput($value,$itemId) {
        $sql = "INSERT INTO ".TB_PREFIX."customFieldValues (customFieldId,itemId,value) VALUES(:field, :item, :value);";
        dbQuery($sql, ':field', $this->fieldId, ':item', $itemId, ':value', $value);
    }

    function showField() {
    }

    function setFieldId($id) {
        $this->fieldId = $id;
    }

    function getFormName($id) {
        return "cf".$id;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    /**
     * @param $id
     * @return mixed
     * @throws PdoDbException
     */
    function getDescription($id) {
        global $pdoDb;

        $pdoDb->setSelectList('description');
        $pdoDb->addSimpleWhere('id', $id);
        $rows = $pdoDb->request('SELECT', 'custom_fields');
        $sql = "SELECT description FROM ".TB_PREFIX."custom_fields WHERE id = :id";
        $sth = dbQuery($sql, ':id', $id);
        $field = $sth->fetch();

        return eval('return "'.$field['description'].'";');
    }

    //TODO: activate and deactivate plugins... What happens if you delete a plug-in?
    /*function setActiveCategories($categories) {
        $this->categories = $categories;
    }?*/

    function getCustomFieldValues($id) {
        global $pdoDb;

        $pdoDb->setSelectAll(true);
        $pdoDb->addSimpleWhere('id', $id);
        $rows = $pdoDb->request('SELECT', 'custom_field_values');
        return $rows;

//        $sql = "SELECT * FROM ".TB_PREFIX."customFieldValues WHERE id = ?";
//        $sth = $dbh->prepare($sql);
//        $sth->execute(array($id));
//        return $sth->fetch();
    }
}