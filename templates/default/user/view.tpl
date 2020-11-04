{*
 *  Script: details.tpl
 *      User detail template
 *
 *  Last edited:
 *      2018-09-26 by Richard Rowley
 *
 *  License:
 *      GPL v3 or above
 *}
<div class="si_form">
    <table>
        <tr>
            <th class="details_screen">{$LANG.username}:</th>
            <td>{$user.username|htmlSafe}</td>
        </tr>
        <tr>
            <th class="details_screen">{$LANG.password}:</th>
            <td>**********</td>
        </tr>
        <tr>
            <th class="details_screen">{$LANG.role}:</th>
            <td>{$user.role_name|htmlSafe}</td>
        </tr>
        <tr>
            <th class="details_screen">{$LANG.email}:</th>
            <td>{$user.email|htmlSafe}</td>
        </tr>
        <tr>
            <th class="details_screen">{$LANG.enabled}:</th>
            <td>{$user.enabled_text|htmlSafe}</td>
        </tr>
        <tr>
            <th class="details_screen">{$LANG.userId}:</th>
            <td>{$user_id_desc|htmlSafe}</td>
        </tr>
    </table>
</div>
<div class="si_toolbar si_toolbar_form">
    <a href="index.php?module=user&amp;view=edit&amp;id={$user.id|urlencode}" class="positive">
        <img src="images/report_edit.png" alt=""/>
        {$LANG.edit}
    </a>
    <a href="index.php?module=user&amp;view=manage" class="negative">
        <img src="images/cross.png" alt=""/>
        {$LANG.cancel}
    </a>
</div>
