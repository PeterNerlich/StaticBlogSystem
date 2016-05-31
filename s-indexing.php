<!DOCTYPE html>

<?php

require_once './mgmt.php';

?>

<html>
<head>
<!--[if IE ]>
<meta http-equiv="refresh" content="0; url=http://abetterbrowser.org">
<![endif]-->

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="copyright" content="Peter Nerlich">
<title>Keyword Indexing – <?php echo $TL[$LANG]['title-blogname']; ?></title>
<link rel='shortcut icon' type='image/x-icon' href='../favicon.ico' />
<style>
	html, body {
		margin: 0;
		padding: 5px;
		background-color: #272822;
		color: #F8F8F2;
	}
	a {
		color: #66D9EF;
	}
	a:hover, a:active {
		color: #AE81FF;
	}
	pre {
		margin: 0;
		white-space: pre-wrap;
	}
	.good {
		color: #A6E22E;
	}
	.bad {
		color: #F92672;
	}
</style>

</head>
<body>

<pre><a href="./search.php">SEARCH</a></pre><br>

<pre>Indexing...
========

<?php

$time = microtime(true);

$dump = array(
	"TITLES"	=>	array(),
	"DESCRIPTIONS"	=>	array(),
	"KEYWORDS"	=>	array()
);

$dir = scandir('./article', 1);
foreach ($dir as $THIS) {
	if ($THIS !== '.' && $THIS !== '..' && $THIS !== 'index.php') {
		$THIS = new Article($THIS);
		echo $THIS->slug."\t...";
		if ((include $THIS->root.'info.php') === TRUE) {

			/* TITLES */
			foreach ($THIS->title as $lang => $title) {
				if (!array_key_exists(strtoupper($lang), $dump['TITLES'])) {
					$dump['TITLES'][strtoupper($lang)] = array();
				}
				if (!array_key_exists(strtolower(trim_all($title)), $dump['TITLES'][strtoupper($lang)])) {
					$dump['TITLES'][strtoupper($lang)][strtolower(trim_all($title))] = array();
				}
				array_push($dump['TITLES'][strtoupper($lang)][strtolower(trim_all($title))], $THIS->slug);
			}

			/* DESCRIPTIONS */
			foreach ($THIS->description as $lang => $description) {
				if (!array_key_exists(strtoupper($lang), $dump['DESCRIPTIONS'])) {
					$dump['DESCRIPTIONS'][strtoupper($lang)] = array();
				}
				if (!array_key_exists(strtolower(trim_all($description)), $dump['DESCRIPTIONS'][strtoupper($lang)])) {
					$dump['DESCRIPTIONS'][strtoupper($lang)][strtolower(trim_all($description))] = array();
				}
				array_push($dump['DESCRIPTIONS'][strtoupper($lang)][strtolower(trim_all($description))], $THIS->slug);
			}

			/* KEYWORDS */
			foreach ($THIS->keywords as $lang => $keys) {
				if (!array_key_exists(strtoupper($lang), $dump['KEYWORDS'])) {
					$dump['KEYWORDS'][strtoupper($lang)] = array();
				}
				foreach ($keys as $key => $value) {
					if (!array_key_exists(strtolower(trim_all($value)), $dump['KEYWORDS'][strtoupper($lang)])) {
						$dump['KEYWORDS'][strtoupper($lang)][strtolower(trim_all($value))] = array();
					}
					array_push($dump['KEYWORDS'][strtoupper($lang)][strtolower(trim_all($value))], $THIS->slug);
				}
			}
			
			echo "\t[<span class='good'>DONE</span>]\n";
		} else {
			echo "\t[<span class='bad'>FAIL</span>]\n";
		}
	}
}

echo "\n========\n\n<span class='good'>";

foreach ($dump['TITLES'] as $lang => $keys) {
	ksort($dump['TITLES'][$lang]);
	foreach ($dump['TITLES'][$lang] as $key => $slug) {
		sort($dump['TITLES'][$lang][$key]);
	}
}
foreach ($dump['DESCRIPTIONS'] as $lang => $keys) {
	ksort($dump['DESCRIPTIONS'][$lang]);
	foreach ($dump['DESCRIPTIONS'][$lang] as $key => $slug) {
		sort($dump['DESCRIPTIONS'][$lang][$key]);
	}
}
foreach ($dump['KEYWORDS'] as $lang => $keys) {
	ksort($dump['KEYWORDS'][$lang]);
	foreach ($dump['KEYWORDS'][$lang] as $key => $slug) {
		sort($dump['KEYWORDS'][$lang][$key]);
	}
}

var_export($dump);
echo "</span>\n\nWriting to file...";

$time = microtime(true)-$time;

file_put_contents('./search-index.php', "<?php\n\n/* indexing time: ".$time."s */\n\n\$INDEX = ".var_export($dump, true).";\n\n?>");

echo "\t[<span class='good'>DONE</span>]\n";

echo "\n[<span class='good'>FINISHED</span> in <span style='color:#AE81FF;'>".$time."</span>s]";

?>
</pre>

</body>
</html>
