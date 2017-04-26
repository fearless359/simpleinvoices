<?php

defined('TUTABS') || define('TUTABS', realpath(dirname(__FILE__)));
//defined('TUTREL') || define('TUTREL', str_replace(dirname($_SERVER['PHP_SELF']), ".", dirname(strstr(TUTABS, dirname($_SERVER['PHP_SELF'])))). '/');
defined('TUTREL') || define('TUTREL', dirname($_SERVER['PHP_SELF']));
include TUTABS. '/Dir.php';
//include TUTREL. '/Dir.php';
//defined('TUT_CONTENTS') || define('TUT_CONTENTS', TUTREL. '/contents');
//defined('TUT_SECTIONS') || define('TUT_SECTIONS', TUTREL. '/sections');
defined('TUT_CONTENTS') || define('TUT_CONTENTS', TUTABS. '/contents');
defined('TUT_SECTIONS') || define('TUT_SECTIONS', TUTABS. '/sections');

$insert = array();
$sections = Dir::get(TUT_SECTIONS, 'html');
foreach($sections as $file)
{
	$insert[$file['name']] = file_get_contents(TUT_SECTIONS. '/'. $file['name']);
}

$fill = Dir::get(TUT_CONTENTS, 'html');
$all_headings = array();
foreach($fill as $file)
{
	$contents = file_get_contents(TUT_CONTENTS. '/'. $file['name']);

	$headings = explode('<h', $contents);
	unset ($headings[0]);
	$blocks = array();
	$string = '';
	foreach($headings as $block)
	{
		$above = isset($level) ? $level : 2;
		$level = (int)$block[0];
		$first_step = explode('>', $block);
		if ($level==1)
		{
			if (!isset($insert['title']))
				$insert['title'] = $first_step[1];
		} else
		{
			$head = substr($first_step[1], 0, -4);
			$name = strtok($head, ' ');
			if (!empty($string))
				if ($level > $above)
					$string .= "\n". str_repeat("\t", $above). '<ul>'. "\n";
				else 
					$string .= "</li>\n";
			if ($level < $above)
				$string .= "</li>\n". str_repeat("\t", $level). "</ul>";
			else
				$string .= str_repeat("\t", $level);
			$string .= '<li><a href="#'. $name. '" class="cc-active level'. $level. '">'. $head. '</a>';
			$block = str_replace('{$path}', TUTREL, $block);
			$blocks[] = '<h'. $block;
		}
	}
	$all_heads[] = '<ul class="docs-nav">'. "\n". $string. "\n</ul></li>\n</ul>";
	$all_headings[] = implode("\n", $blocks);
}
$insert['contents'] = $all_headings;
$insert['nav'] = $all_heads;

$file = TUTABS. '/js/inline.js';
if (file_exists($file))
	$insert['inline.js'] = file_get_contents($file);

$file = TUTABS. '/css/inline.css';
if (file_exists($file))
	$insert['inline.css'] = file_get_contents($file);

include TUTABS. '/template.php';

//echo 'insert: '. print_r($insert, true);
