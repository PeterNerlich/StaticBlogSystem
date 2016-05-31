<?php

#require_once './search-index.php';

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

function blogsearch($search='', $INDEX=array(), $LANG='all') {
	$search = strtolower(trim_all($search));
	$res = array();

	$time = microtime(true);

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

	$time = microtime(true)-$time;

	return array("res"=>$res, "time"=>$time);
}

?>