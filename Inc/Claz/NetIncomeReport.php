<?php
namespace Inc\Claz;

/**
 * Class NetIncomeReport
 * @package Inc\Claz
 */
class NetIncomeReport
{
    public $domain_id;

    /**
     * @param $start_date
     * @param $stop_date
     * @param $exclude_custom_flag_items
     * @return array
     */
    public function select_rpt_items($start_date, $stop_date, $customer_id, $exclude_custom_flag_items)
    {
        global $pdoDb;

        $domain_id = DomainId::get($this->domain_id);

        if (isset($exclude_custom_flag_items) && $exclude_custom_flag_items > 0) {
            // Make a regex string that Tests for "0" in the specified position
            $cFlags = array('.', '.', '.', '.', '.', '.', '.', '.', '.', '.');
            $cFlags[$exclude_custom_flag_items - 1] = '0';
            $pattern = '^';
            foreach ($cFlags as $cFlag) {
                $pattern .= $cFlag;
            }
        } else {
            $pattern = '.*'; // Basically ignores custom flag setting
        }

        // Find all invoices that received payments in this reporting period.
        $iv_ids = array();

        $pv_recs = array();
        try {
            $pdoDb->setOrderBy("ac_inv_id");
            $pdoDb->addSimpleWhere("domain_id", $domain_id, "AND");
            $pdoDb->addToWhere(new WhereItem(false, "ac_date", "BETWEEN", array($start_date, $stop_date), false));
            $pv_recs = $pdoDb->request("SELECT", "payment");
        } catch (PdoDbException $pde) {
            error_log("Inc/Claz/NetIncomeReport.php - error(1): " . $pde->getMessage());
        }
        $last_inv_id = 0;
        foreach ($pv_recs as $row) {
            $curr_inv_id = $row['ac_inv_id'];
            if ($last_inv_id != $curr_inv_id) {
                $last_inv_id = $curr_inv_id;
                $iv_ids[] = $curr_inv_id;
            }
        }

        // Get all invoices that had payments made in the current reporting period.
        $invoices = array();
        foreach ($iv_ids as $id) {
            $iv_recs = array();
            try {
                $jn = new Join("INNER", "customers", "cu");
                $jn->addSimpleItem("cu.id", new DbField("iv.customer_id"), "AND");
                $jn->addSimpleItem("cu.domain_id", new DbField("iv.domain_id"));
                $pdoDb->addToJoins($jn);

                $pdoDb->addSimpleWhere("iv.id", $id, "AND");
                $pdoDb->addSimpleWhere("iv.domain_id", $domain_id);
                $pdoDb->setSelectList(array("iv.id", "iv.index_id AS iv_number", "iv.date AS iv_date", "cu.name AS customer", 'iv.customer_id'));

                $iv_recs = $pdoDb->request("SELECT", "invoices", "iv");
            } catch (PdoDbException $pde) {
                error_log("Inc/Claz/NetIncomeReport.php - error(3): " . $pde->getMessage());
            }

            foreach ($iv_recs as $iv) {
                if ($customer_id > '0' && $iv['customer_id'] != $customer_id) {
                    continue;
                }

                // Create an invoice object for the report. This object holds the payments and
                // invoice items for the invoice. We know that a payment to this invoice was
                // made in this reporting period. However, it is possible that not all payments
                // were made in this reporting period. So we will keep the payment info so we can
                // report only the payment that were made in this period.
                $invoice = new NetIncomeInvoice($id, $iv['iv_number'], $iv['iv_date'], $iv['customer']);

                // Get all the payments made for this invoice. We do this so we can calculate what
                // if any payments are left for the invoice, as well as have payment detail to
                // include in the report.
                // @formatter:off
                $py_recs = array();
                try {
                    $pdoDb->setOrderBy("ac_date");
                    $pdoDb->addSimpleWhere("ac_inv_id", $id, "AND");
                    $pdoDb->addSimpleWhere("domain_id", $domain_id);
                    $pdoDb->setSelectList(array("ac_amount", "ac_date"));
                    $py_recs = $pdoDb->request("SELECT", "payment");
                } catch (PdoDbException $pde) {
                    error_log("Inc/Claz/NetIncomeReport.php - error(4): " . $pde->getMessage());
                }
                // @formatter:on

                foreach ($py_recs as $py) {
                    $in_period = ($start_date <= $py['ac_date'] && $stop_date >= $py['ac_date']);
                    $invoice->addPayment($py['ac_amount'], $py['ac_date'], $in_period);
                }

                // Now get all the invoice items with the exception of those flagged
                // as non-income items provided the option to exclude them was specified.
                // @formatter:off
                $ii_recs = array();
                try {
                    $pdoDb->setOrderBy("ii.invoice_id");
                    $pdoDb->setOrderBy("pr.description");
                    $pdoDb->addSimpleWhere("ii.invoice_id", $id, "AND");
                    $pdoDb->addSimpleWhere("ii.domain_id", $domain_id, "AND");
                    $list = array("ii.total AS amount", "pr.description AS description");
                    $pdoDb->addToWhere(new WhereItem(false, "pr.custom_flags", "REGEXP", $pattern, false));
                    $list[] = "pr.custom_flags";

                    $join = new Join("INNER", "products", "pr");
                    $join->addSimpleItem("pr.id", new DbField("ii.product_id"), "AND");
                    $join->addSimpleItem("pr.domain_id", new DbField("ii.domain_id"));
                    $pdoDb->addToJoins($join);
                    $pdoDb->setSelectList($list);

                    $ii_recs = $pdoDb->request("SELECT", "invoice_items", "ii");
                } catch (PdoDbException $pde) {
                    error_log("Inc/Claz/NetIncomeReport.php - error(5): " . $pde->getMessage());
                }

                foreach ($ii_recs as $py) {
                    $invoice->addItem($py['amount'], $py['description'], $py['custom_flags']);
                }

                if ($invoice->total_amount < $invoice->total_payments) $invoice->total_payments = $invoice->total_amount;
                if ($invoice->total_amount < $invoice->total_period_payments) $invoice->total_period_payments = $invoice->total_amount;
                if ($invoice->total_amount != 0) $invoices[] = $invoice;
            }
        }
        return $invoices;
    }
}
