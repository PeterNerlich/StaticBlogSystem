<?php

# local timezone
date_default_timezone_set('CET');

# most important variables
global $DISPLAY, $SEARCH, $THIS, $CAT, $TL, $LANG;



/********** core functions **********
 * 
 */


function INIT_DISPLAY($args=array()) {
	# determine page type from arguments (e.g. url query string)
	# setup either search, article context ($THIS), or category context $CAT

	global $DISPLAY, $SEARCH, $THIS, $CAT, $TL, $LANG;

	# load translations
	require_once './lang.php';

	# set current language
	if (isset($args['lang']) && array_key_exists(strtoupper(trim($args['lang'])), $TL)) {
		$LANG = strtoupper($args['lang']);
	}

	if (isset($args['search']) && trim($args['search']) != '') {
		$DISPLAY = 'SEARCH';
		$SEARCH = trim_all($args['search']);

		# keep category context if present
		if (isset($args['c']) && trim($args['c']) != '' && trim($args['c']) !== 'all') {
			$CAT = new Category(trim($args['c']));
			if (is_file('./category/'.$CAT->slug.'.php') && (include './category/'.$CAT->slug.'.php') === TRUE) {
				#
			} else {
				#die('ERROR 404: Category not found');
			}
		} else {
			$CAT = new Category('ALL');
		}
	} elseif (isset($args['a']) && trim($args['a']) != '') {
		$DISPLAY = 'ARTICLE';
		$THIS = new Article(trim($args['a']));
		if (is_file($THIS->root.'info.php') && (include $THIS->root.'info.php') === TRUE) {
			#
		} else {
			# TODO: don't abort, sjhow a proper error page
			die('ERROR 404: Article not found');
		}
	} elseif (isset($args['c']) && trim($args['c']) != '') {
		if (trim($args['c']) === 'all') {
			$DISPLAY = 'ALL_C';
		} else {
			$DISPLAY = 'CATEGORY';
			$CAT = new Category(trim($args['c']));
			if (is_file('./category/'.$CAT->slug.'.php') && (include './category/'.$CAT->slug.'.php') === TRUE) {
				#
			} else {
				# TODO: don't abort, sjhow a proper error page
				die('ERROR 404: Category not found');
			}
		}
	} else {
		$DISPLAY = 'ALL';
	}
}



/********** helper functions **********
 * 
 */


function getargs() {
	# reconstruct array of arguments from page type etc.

	global $DISPLAY, $SEARCH, $THIS, $CAT, $TL, $LANG;
	$args = array();

	if ($DISPLAY === 'SEARCH') {
		$args['search'] = $SEARCH;
		if (isset($_GET['c']) && trim($_GET['c'])) {
			$args['c'] = trim($_GET['c']);
		}
	} elseif ($DISPLAY === 'ARTICLE') {
		$args['a'] = $THIS->slug;
		if (isset($_GET['c']) && trim($_GET['c'])) {
			$args['c'] = trim($_GET['c']);
		}
	} elseif ($DISPLAY === 'ALL_C') {
		$args['c'] = 'all';
	} elseif ($DISPLAY === 'CATEGORY') {
		$args['c'] = $CAT->slug;
	}
	if (isset($LANG)) {
		$args['lang'] = $LANG;
	}
	return $args;
}

function queryURI($overwrite=array()) {
	# build query string

	global $DISPLAY, $SEARCH, $THIS, $CAT, $TL, $LANG;

	# use current page type etc. as base
	$args = getargs();

	foreach ($overwrite as $key => $value) {
		$args[$key] = $value;
	}

	$uri = '';
	foreach ($args as $key => $value) {
		if ($value != '') {
			if ($uri === '') {
				$uri .= '?';
			} else {
				$uri .= '&';
			}
			$uri .= $key.'='.$value;
		}
	}
	return $uri;
}

function trim_all( $str , $what = NULL , $with = ' ' ) {
	# remove leading, ending and multiple spaces and similar

	if($what === NULL) {
		//	Character	Decimal		Use
		//	"\0"		0			Null Character
		//	"\t"		9			Tab
		//	"\n"		10			New line
		//	"\x0B"		11			Vertical Tab
		//	"\r"		13			New Line in Mac
		//	" "			32			Space

		$what = "\\x00-\\x20"; //all white-spaces and control chars
	}

	return trim(preg_replace("/[".$what."]+/", $with, $str), $what);
}

function console_log($data) {
	# facilitate debugging
	echo '<script>console.log('.json_encode($data).');</script>';
	return $data;
}



/********** page part functions **********
 * 
 */


require_once './pageparts.php';



/********** classes **********
 * 
 */


class Article {
	public $slug = "";
	public $title = array();
	public $author = "";
	public $published = 0;
	public $description = array();
	public $categories = array();
	public $preview = array();
	public $root = "";

	function __construct($slug="", $root="") {
		$this->slug = $slug;
		if ($root === "") {
			$root = "./article/".$slug."/";
		}
		$this->root = $root;
	}
}

class Category {
	public $slug = "";
	public $title = array();
	public $description = array();
	public $known = array();
	public $color = '#b88';
	public $background = "repeating-linear-gradient(-45deg, rgba(255,255,255,.4), rgba(255,255,255,.4) 30px, rgba(0,0,0,.2) 30px, rgba(0,0,0,.2) 60px);";

	function __construct($slug="") {
		$this->slug = $slug;
	}
}

?>