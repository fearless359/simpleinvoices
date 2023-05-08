<?php

namespace Inc\Claz;

/**
 * Class ApiAuth
 * @package Inc\Claz
 */
class ApiAuth
{
    public static function authenticate(string $module, string $view): void
    {
        // API calls don't use the auth module
        if ($module != 'api') {
            Log::out("ApiAuth::authenticate - _SESSION: " . print_r($_SESSION, true));
            $sessionId = $_SESSION['id'] ?? 0;
            $getId = $_GET['id'] ?? 0;
            Log::out("ApiAuth::authenticate() - module[$module] view[$view] sessionId[$sessionId] getId[$getId]");
            // If we don't have an active session, force login screen.
            if ($sessionId <= 0) {
                // If this is not an "auth" module request, then force login screen.
                if ($module !== "auth" && $module !== "install") {
                    Log::out("ApiAuth::authenticate() - Forcing redirect to login screen.");
                    header('Location: index.php?module=auth&view=login');
                    exit();
                }
            }

//            if ($module != "auth" && $view != "logout" && $module != 'errorPages') {
//                 // If the role is biller or customer, the available options are restricted.
//                $userId = $_SESSION['user_id'];
//                $id = $getId;
//                $role = $_SESSION['role_name'];
//                $viewInArray = in_array($view, ['edit', 'manage', 'save', 'view']);
//                $userAccess = $module == 'user' && $viewInArray && ($id == 0 || $sessionId == $id);
//                $invoiceAccess = $module == 'invoices';
//                $paymentAccess = $module == 'payments';
//                $moduleViewOk = $userAccess || $invoiceAccess || $paymentAccess;
//                Log::out("ApiAuth::authenticate() - role[$role] viewInArray[$viewInArray] userAccess[$userAccess] " .
//                    "moduleViewOk[$moduleViewOk] userId[$userId] id[$id]");
//                if (!$moduleViewOk) {
//                    header('Location: index.php?module=errorPages&view=e401');
//                    exit();
//                }
//            }
        }
    }
}
