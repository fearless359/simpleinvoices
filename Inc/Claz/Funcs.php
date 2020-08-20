<?php

namespace Inc\Claz;

use Exception;

/**
 * General functions class
 * @author Rich Rowley
 */
class Funcs
{
    /**
     * Break the <b>menu.tpl</b> into sections.
     * @param string $menutpl <b>menu.tpl</b> file contents.
     * @param array $lines Lines from <b>menu.tpl</b> broken by <i>&lt;!-- SECTION:</i> tag.
     * @param array $sections Associative array with the index of each <i>&lt;!-- SECTION:</i> tag name.
     *        Ex: <i>&lt;!-- SECTION:tax_rates&gt;</i> makes <b>tax_rates</b> the <i>key</i> and the <i>values</i> is the
     *            offset in the <b>$lines</b> array for the <b>tax_rates</b> section.
     */
    public static function menuSections(string $menutpl, array &$lines, array &$sections): void
    {
        $divs = preg_split('/(< *div *id=|< *div *class=)/', $menutpl, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $idx = 0;
        $sections = [];
        $lines = [];
        foreach ($divs as $dsec) {
            $parts = preg_split('/(<!-- *SECTION:)/', $dsec, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            $hitSection = false;
            foreach ($parts as $part) {
                if ($hitSection) {
                    $sects = preg_split('/ *-->/', $part, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
                    $sections[$sects[0]] = $idx;
                    $hitSection = false;
                    $lines[] = $sects[1];
                    $idx++;
                } elseif (preg_match('/^<!-- *SECTION:/', $part)) {
                    $hitSection = true;
                } else {
                    $lines[] = $part;
                    $idx++;
                }
            }
        }
    }

    /**
     * Merge extension sections with the main <b>menu.tpl<b> file.
     * @param array $extNames Extension names.
     * @param array $lines Lines from <b>menu.tpl</b> broken by <i>&lt;!-- SECTION:</i> tag.
     * @param array $sections Associative array with the index of each <i>&lt;!-- SECTION:</i> tag name.
     * @return string <b>menu.tpl</b> file content with active extension menus merged.
     */
    public static function mergeMenuSections(array $extNames, array $lines, array $sections): string
    {
        global $smarty;

        try {
            foreach ($extNames as $extName) {
                if (file_exists("extensions/$extName/templates/default/menu.tpl")) {
                    $menuExtension = $smarty->fetch("extensions/$extName/templates/default/menu.tpl");
                    $pattern = '/<!\-\- BEFORE:/';
                    $extSects = preg_split($pattern, $menuExtension, -1, PREG_SPLIT_NO_EMPTY);
                    foreach ($extSects as $sect) {
                        $pattern = '/ *-->/';
                        $parts = preg_split($pattern, $sect);
                        $secNdx = trim($parts[0]);
                        $pattern = '/^ *-->/';
                        $pieces = preg_split($pattern, $parts[1]);
                        $ndx = $sections[$secNdx];
                        $lines[$ndx] = $pieces[0] . $lines[$ndx];
                    }
                }
            }
        } catch (Exception $exp) {
            error_log("Funcs::mergeMenuSections() - Error: " . $exp->getMessage());
        }
        $menutpl = "";
        foreach ($lines as $line) {
            $menutpl .= $line;
        }
        return $menutpl;
    }
}
