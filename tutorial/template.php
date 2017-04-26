<?php
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="description" content="">
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta name="viewport" content="initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<title><?php if (isset($insert['title'])) echo $insert['title']; ?></title>
	<link rel="alternate" type="application/rss+xml" title="frittt.com" href="feed/index.html">
	<link href="http://fonts.googleapis.com/css?family=Raleway:700,300" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/prettify.css">
<?php if (isset($insert['inline.css'])) echo '<style type="text/css">'. "\n". $insert['inline.css']. '</style>'. "\n"; ?>
</head>
<body>
	<div class="wrapper">
		<nav class="top_nav">
<?php if (isset($insert['top_nav.html'])) echo $insert['top_nav.html']; ?>
		</nav>
		<header>
<?php if (isset($insert['header.html'])) echo $insert['header.html']; ?>
		</header>
		<section>
			<div class="container">
<?php
foreach($insert['nav'] as $nav)
{
	echo $nav;
}
?>

				<div class="docs-content">
<?php
foreach($insert['contents'] as $cont)
{
	echo $cont;
}
?>
				</div>
			</div>
		</section>
		<section class="foot_sec vibrant centered">
<?php if (isset($insert['foot_sec.html'])) echo $insert['foot_sec.html']; ?>
		</section>
		<footer>
<?php if (isset($insert['footer.html'])) echo $insert['footer.html']; ?>
		</footer>
	</div>
	<script type="text/javascript" src="js/jquery.min.js"></script> 
	<script type="text/javascript" src="js/prettify/prettify.js"></script>
	<!--script src="https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js?lang=css&skin=sunburst"></script-->
	<!--script src="https://github.com/google/code-prettify/blob/master/src/run_prettify.js"></script-->
	<script type="text/javascript" src="js/prettify/run_prettify.js"></script>
	<script type="text/javascript" src="js/layout.js"></script>
<?php if (isset($insert['inline.js'])) echo '<script type="text/javascript">'. "\n". $insert['inline.js']. '</script>'. "\n"; ?>
</body>
</html>
