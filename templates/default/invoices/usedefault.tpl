{*
* Script: usedefault.tpl
* 	Template used to redirect to the correct page based on dynamic specification.
*   Note that this is used by the default_invoice logic when adding a new invoice
*   based on the specified default.
*
* Authors:
*	 Marcel van Dorp
*
* Last edited:
* 	 2018-10-23 by Richard Rowley.
*
* License:
*	 GPL v2 or above
*}
<meta http-equiv="refresh" content="0;URL=index.php?module=invoices&amp;view={$view}&amp;{$spec}={$id}{if $spec2}&amp;{$spec2}={$CID}{/if}"/>
