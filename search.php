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
<title>Search – <?php echo $TL[$LANG]['title-blogname']; ?></title>
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

<pre><?php
echo 'articles: '.date("m/d/Y H:i:s", filemtime('./article/.'))."\n";
echo 'categories: '.date("m/d/Y H:i:s", filemtime('./category/.'))."\n";
if (need_index() === TRUE) {
	echo 'Need new index! ('.date("m/d/Y H:i:s", filemtime('./search-index.php')).') | ';
} else {
	echo 'Index up to date ('.date("m/d/Y H:i:s", filemtime('./search-index.php')).') | ';
} ?><a href="./s-indexing.php">INDEXING</a></pre><br>

<form method="get" action="?"  id="searchform">
	<input type="text" name="search" placeholder="search string" <?php if (isset($_GET['search'])) { echo 'value="'.trim_all($_GET['search']).'"'; } ?>>
	<button type="submit">Search</button>
</form>
<br>

<?php if (isset($_GET['search']) && trim_all($_GET['search']) != '') { ?>

<pre>Searching...
========

<?php

$LANG = 'all';
$INDEX = array();
$res = array();
$search = strtolower(trim_all($_GET['search']));

function strpartmatch($search='',$key='') {
	$res = 0;
	$searchptr = 0;
	while (strlen($search) > 0 && strlen($key) > 0) {
		$str = $key;
		$i = stripos($str, $search[$searchptr]);
		if ($i === FALSE) {
			break 1;
		} else {
			#echo "found '".substr($str, $i)."' at ".$i."\n";
			$str = substr($str, $i+1);
			$i = 1;
			if ($i === strlen($search)) {
				$res = 1;
			}

			$searchptr++;
			while (TRUE) {
				if ($searchptr >= strlen($search)) {
					break 2;
				} elseif (strlen($str) == 0) {
					break 1;
				} elseif ($str[0] !== $search[$searchptr]) {
					/* single mismatch, no score for you! */
					if ($str[1] === $search[$searchptr]) {
						#echo " wrong char in string! (".$str[0].")\n";
						$str = substr($str, 1);
					} elseif ($str[0] === $search[$searchptr+1]) {
						#echo " char in search omitted! (".$search[$searchptr].")\n";
						$searchptr += 2;
					} elseif ($str[1] === $search[$searchptr+1]) {
						#echo " char mismatch! (".$str[0]." - ".$search[$searchptr].")\n";
						$str = substr($str, 1);
						$searchptr += 2;
					} else {
						break 1;
					}
				} else {
					$str = substr($str, 1);
					if (++$i > 2 || $i === strlen($search)) {
						$res++;
					}
					$searchptr++;
				}
			}
		}
	}
	#console_log("strpartmatch('".$search."', '".$key."') = ".$res);
	return $res;
}

$time = microtime(true);

require_once './search-index.php';

console_log("seaching titles...");
foreach ($INDEX['TITLES'] as $lang => $keys) {
	if ($LANG === 'all' || $LANG === $lang) {
		$res['TMP'] = array();
		foreach ($keys as $key => $arts) {
			$score = strpartmatch($search, $key);
			if ($score > 0) {
				foreach ($arts as $art) {
					if (!array_key_exists($art, $res['TMP'])) {
						$res['TMP'][$art] = 0;
					}
					$res['TMP'][$art] += log($score) * 2 + 1;
				}
			}
		}
		foreach ($res['TMP'] as $art => $score) {
			if (!array_key_exists($art, $res) || $res[$art] < $score) {
				$res[$art] = $score;
			}
		}
	}
}

console_log("seaching description...");
foreach ($INDEX['DESCRIPTIONS'] as $lang => $keys) {
	if ($LANG === 'all' || $LANG === $lang) {
		$res['TMP'] = array();
		foreach ($keys as $key => $arts) {
			$score = strpartmatch($search, $key);
			if ($score > 0) {
				foreach ($arts as $art) {
					if (!array_key_exists($art, $res['TMP'])) {
						$res['TMP'][$art] = 0;
					}
					$res['TMP'][$art] += log($score) * 1.7 + 1;
				}
			}
		}
		foreach ($res['TMP'] as $art => $score) {
			if (!array_key_exists($art, $res) || $res[$art] < $score) {
				$res[$art] = $score;
			}
		}
	}
}

console_log("seaching keywords...");
foreach ($INDEX['KEYWORDS'] as $lang => $keys) {
	if ($LANG === 'all' || $LANG === $lang) {
		$res['TMP'] = array();
		foreach ($keys as $key => $arts) {
			if (stristr($search, $key)) {
				foreach ($arts as $art) {
					if (!array_key_exists($art, $res['TMP'])) {
						$res['TMP'][$art] = 0;
					}
					$res['TMP'][$art] += 1;
				}
			}
		}
		foreach ($res['TMP'] as $art => $score) {
			if (!array_key_exists($art, $res) || $res[$art] < $score) {
				$res[$art] = $score;
			}
		}
	}
}

unset($res['TMP']);

arsort($res);

echo "<span class='good'>";
var_export($res);
echo "</span>\n\n";

$time = microtime(true)-$time;

echo console_log("[FINISHED in ".$time."s]");

?>
</pre>

<?php } ?>

</body>
</html>
