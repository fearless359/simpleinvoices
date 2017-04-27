<?php
// replace getExtensions() function
class Extensions
{
	public static function get_all()
	{
		$domain_id = class_exists('DomainId') ? DomainId:get() : class_exists('domain_id') ? domain_id::get() : 1;

		$sql = "SELECT * FROM ".TB_PREFIX."extensions WHERE domain_id = 0 OR domain_id = :domain_id ORDER BY name";
		$sth = dbQuery($sql, ':domain_id', $domain_id);

		$exts = null;

		for($i=0; $ext = $sth->fetch(); $i++) {
			$exts[$i] = $ext;
		}
		return $exts;
	}
}
