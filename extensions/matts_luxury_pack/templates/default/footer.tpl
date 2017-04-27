{* 
/*
 * Script: extensions/matts_luxury_pack/templates/default/footer.tpl
 * 	page footer template
 *
 * Authors:
 *	 git0matt@gmail.com
 *
 * Last edited:
 * 	 2016-09-14
 *
 * License:
 *	 GPL v2 or above
 *
 * Website:
 * 	http://www.simpleinvoices.org
 */
*}
	</div>

	<div id="si_footer">
		<div class="si_wrap">
			{$LANG.thank_you_inv}<a href="http://www.simpleinvoices.org">{$LANG.simple_invoices}</a> | 
			<a href="http://www.simpleinvoices.org/+">{$LANG.forum}</a> | 
			<a href="http://www.simpleinvoices.org/blog">{$LANG.blog}</a> |
			{$version_name|htmlsafe}
		</div>
	</div>

{$smarty.capture.hook_body_end}
</body>
</html>
