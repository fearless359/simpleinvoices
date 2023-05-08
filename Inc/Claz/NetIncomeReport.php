<?php
namespace Inc\Claz;

/**
 * Class NetIncomeReport
 * @package Inc\Claz
 */
class NetIncomeReport
{

    public static function selectRptItems(string $startDate, string $stopDate, int $customerId, int|string|null $excludeCustomFlagItems): array
    {
        global $pdoDb;

        $domainId = DomainId::get();

        if (isset($excludeCustomFlagItems) && $excludeCustomFlagItems > 0) {
            // Make a regex string that Tests for "0" in the specified position
            $cFlags = ['.', '.', '.', '.', '.', '.', '.', '.', '.', '.'];
            $cFlags[$excludeCustomFlagItems - 1] = '0';
            $pattern = '^' . implode('', $cFlags);
        } else {
            $pattern = '.*'; // Basically ignores custom flag setting
        }

        // Find all invoices that received payments in this reporting period.
        $ivIds = [];
        $pvRecs = [];
        try {
            $pdoDb->setOrderBy("ac_inv_id");
            $pdoDb->addSimpleWhere("domain_id", $domainId, "AND");
            $pdoDb->addToWhere(new WhereItem(false, "ac_date", "BETWEEN", [$startDate, $stopDate], false));

            $pvRecs = $pdoDb->request("SELECT", "payment");
        } catch (PdoDbException $pde) {
            error_log("Inc/Claz/NetIncomeReport.php - error(1): " . $pde->getMessage());
        }

        $lastInvId = 0;
        foreach ($pvRecs as $row) {
            $currInvId = intval($row['ac_inv_id']);
            if ($lastInvId != $currInvId) {
                $lastInvId = $currInvId;
                $ivIds[] = $currInvId;
            }
        }

        // Get all invoices that had payments made in the current reporting period.
        $netIncInvs = [];
        foreach ($ivIds as $id) {
            $ivRecs = [];
            try {
                $jn = new Join("INNER", "customers", "cu");
                $jn->addSimpleItem("cu.id", new DbField("iv.customer_id"), "AND");
                $jn->addSimpleItem("cu.domain_id", new DbField("iv.domain_id"));
                $pdoDb->addToJoins($jn);

                $pdoDb->addSimpleWhere("iv.id", $id, "AND");
                $pdoDb->addSimpleWhere("iv.domain_id", $domainId);
                $pdoDb->setSelectList(["iv.id", "iv.index_id AS iv_number", "iv.date AS iv_date", "cu.name AS customer", 'iv.customer_id']);

                $ivRecs = $pdoDb->request("SELECT", "invoices", "iv");
            } catch (PdoDbException $pde) {
                error_log("Inc/Claz/NetIncomeReport.php - error(3): " . $pde->getMessage());
            }

            foreach ($ivRecs as $iv) {
                $cuId = intval($iv['customer_id']);

                if ($customerId > 0 && $cuId != $customerId) {
                    continue;
                }

                $ivNumber = intval($iv['iv_number']);
                $ivCustomer = $iv['customer'];

                // Create an invoice object for the report. This object holds the payments and
                // invoice items for the invoice. We know that a payment to this invoice was
                // made in this reporting period. However, it is possible that not all payments
                // were made in this reporting period. So we will keep the payment info, so we can
                // report only the payment that were made in this period.
                $netIncInv = new NetIncomeInvoice($id, $ivNumber, $iv['iv_date'], $ivCustomer);

                // Get all the payments made for this invoice. We do this, so we can calculate what
                // if any payments are left for the invoice, as well as have payment detail to
                // include in the report.
                $pyRecs = [];
                try {
                    $pdoDb->setOrderBy("ac_date");
                    $pdoDb->addSimpleWhere("ac_inv_id", $id, "AND");
                    $pdoDb->addSimpleWhere("domain_id", $domainId);
                    $pdoDb->setSelectList(["ac_amount", "ac_date"]);
                    $pyRecs = $pdoDb->request("SELECT", "payment");
                } catch (PdoDbException $pde) {
                    error_log("Inc/Claz/NetIncomeReport.php - error(4): " . $pde->getMessage());
                }

                foreach ($pyRecs as $py) {
                    $inPeriod = $startDate <= $py['ac_date'] && $stopDate >= $py['ac_date'];
                    $netIncInv->addPayment($py['ac_amount'], $py['ac_date'], $inPeriod);
                }

                // Now get all the invoice items except those flagged as non-income items
                // provided the option to exclude them was specified.
                $iiRecs = [];
                try {
                    $pdoDb->setOrderBy("ii.invoice_id");
                    $pdoDb->setOrderBy("pr.description");
                    $pdoDb->addSimpleWhere("ii.invoice_id", $id, "AND");
                    $pdoDb->addSimpleWhere("ii.domain_id", $domainId, "AND");
                    $list = ["ii.total AS amount", "pr.description AS description"];
                    $pdoDb->addToWhere(new WhereItem(false, "pr.custom_flags", "REGEXP", $pattern, false));
                    $list[] = "pr.custom_flags";

                    $join = new Join("INNER", "products", "pr");
                    $join->addSimpleItem("pr.id", new DbField("ii.product_id"), "AND");
                    $join->addSimpleItem("pr.domain_id", new DbField("ii.domain_id"));
                    $pdoDb->addToJoins($join);
                    $pdoDb->setSelectList($list);

                    $iiRecs = $pdoDb->request("SELECT", "invoice_items", "ii");
                } catch (PdoDbException $pde) {
                    error_log("Inc/Claz/NetIncomeReport.php - error(5): " . $pde->getMessage());
                }

                foreach ($iiRecs as $py) {
                    $netIncInv->addItem($py['amount'], $py['description'], $py['custom_flags']);
                }

                if ($netIncInv->totalAmount < $netIncInv->totalPayments) {
                    $netIncInv->totalPayments = $netIncInv->totalAmount;
                }
                if ($netIncInv->totalAmount < $netIncInv->totalPeriodPayments) {
                    $netIncInv->totalPeriodPayments = $netIncInv->totalAmount;
                }
                if ($netIncInv->totalAmount != 0) {
                    $netIncInvs[] = $netIncInv;
                }
            }
        }

        return $netIncInvs;
    }
}
