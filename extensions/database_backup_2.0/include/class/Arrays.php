<?php

class Arrays
{
	public static function sortBy($field, &$array, $direction = 'asc')
	{
		usort($array, create_function('$a, $b', '
			$a = $a["' . $field . '"];
			$b = $b["' . $field . '"];
			if ($a == $b)
			{
				return 0;
			}
			return ($a ' . ($direction == 'desc' ? '>' : '<') .' $b) ? -1 : 1;
		'));
		return true;
	}
	public static function sortByName($a, $b)
	{
		$a = $a['name'];
		$b = $b['name'];
		if ($a == $b)
		{
			return 0;
		}
		return ($a < $b) ? -1 : 1;
	}
	public static function sortByCtime($a, $b)
	{
		$a = $a['ctime'];
		$b = $b['ctime'];
		if ($a == $b)
		{
			return 0;
		}
		return ($a < $b) ? -1 : 1;
	}
}
