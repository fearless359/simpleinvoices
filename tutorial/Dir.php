<?php

class Dir
{
	public static function get($path, $findext='')
	{
		if (!isset($path) || !$path || !file_exists($path))
			return false;
		if (substr($path, -1) != '/')		$path .= '/';
		$dir = opendir($path);
		$list = array();
		while($file = readdir($dir))
			if ($file[0] != '.')
				if (empty($findext) || pathinfo($file, PATHINFO_EXTENSION)==$findext)
				{
					$list[] = count(stat($path. $file)) ?
						array_merge(array('name' => $file, 'path' => $path), stat($path. $file)) :
						array('name' => $file, 'path' => $path);
				}
		closedir($dir);
		return $list;
	}
}