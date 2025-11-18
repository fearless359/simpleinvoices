<?php

use Inc\Claz\CustomFlags;

/**
 * Setup for custom flag template.
 * @param array $params
 * @param object $template
 * @noinspection PhpParameterByRefIsNotUsedAsReferenceInspection
 */
function smarty_function_custom_flag_template(array $params, object &$template) {
    global $smarty;
    $inv = $params['invoice'];
    $cf = $params['customFieldLabels'];
    $customer = $params['customer'];

    try {
        $invDate = new DateTime($inv['date_original']);
        $template->assign('inv_dt', $invDate->format('m/d/Y'));

        $cust = [];
        if (!empty($customer['attention'])) {
            $cust[] = $customer['attention'];
        }

        if (!empty($customer['street_address'])) {
            $cust[] = $customer['street_address'];
        }

        if (!empty($customer['street_address2'])) {
            $cust[] = $customer['street_address2'];
        }

        $tmp = '';
        if (!empty($customer['city'])) {
            $tmp .= $customer['city'] . ', ';
        }

        if (!empty($customer['state'])) {
            $tmp .= $customer['state'] . ' ';
        }

        if (!empty($customer['zip_code'])) {
            $tmp .= $customer['zip_code'];
        }
        $cust[] = $tmp;

        if (empty($customer['mobile_phone'])) {
            if (!empty($customer['phone'])) {
                $cust[] = $customer['phone'];
            }
        } else {
            $cust[] = $customer['mobile_phone'];
        }

        if (!empty($customer['email'])) {
            $cust[] = $customer['email'];
        }

        if (!empty($customer['custom_field1'])) {
            if (!CustomFlags::isCustomFlagField($cf['customer_cf1'])) {
                $tmp = '';
                if (isset($smarty->useIt) && $smarty->useIt) {
                    $tmp = $cf['customer_cf1'] . ': ';
                }
                $tmp .= $customer['custom_field1'];
                $cust[] = $tmp;
            }
        }

        if (!empty($customer['custom_field2'])) {
            if (!CustomFlags::isCustomFlagField($cf['customer_cf2'])) {
                $tmp = '';
                if (isset($smarty->useIt) && $smarty->useIt) {
                    $tmp = $cf['customer_cf2'] . ': ';
                }
                $tmp .= $customer['custom_field2'];
                $cust[] = $tmp;
            }
        }

        if (!empty($customer['custom_field3'])) {
            if (!CustomFlags::isCustomFlagField($cf['customer_cf3'])) {
                $tmp = '';
                if (isset($smarty->useIt) && $smarty->useIt) {
                    $tmp = $cf['customer_cf3'] . ': ';
                }
                $tmp .= $customer['custom_field3'];
                $cust[] = $tmp;
            }
        }

        if (!empty($customer['custom_field4'])) {
            if (CustomFlags::isCustomFlagField($cf['customer_cf4'])) {
                $tmp = '';
                if (isset($smarty->useIt) && $smarty->useIt) {
                    $tmp = $cf['customer_cf4'] . ': ';
                }
                $tmp .= $customer['custom_field4'];
                $cust[] = $tmp;
            }
        }

        for ($custCnt = count($cust); $custCnt < 5; $custCnt++) {
            $cust[] = '';
        }

        $template->assign('cust', $cust);
        $template->assign('cust_cnt', $custCnt);
        $template->assign('ndx', 0);
    } catch (Exception $exp) {
        exit("templates/invoices/ftcc/plugins/function_custom_flag_template.php Unexpected error: {$exp->getMessage()}");
    }
}
