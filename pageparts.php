<?php


/********** head section **********
 *
 */


function head_title() {
	global $DISPLAY, $SEARCH, $THIS, $CAT, $TL, $LANG;

	if ($DISPLAY === 'SEARCH') { ?>
		<title><?php echo $SEARCH; ?> – <?php echo $TL[$LANG]['title-blogname']; ?></title>
	<?php } elseif ($DISPLAY === 'ARTICLE') { ?>
		<title><?php echo $THIS->title[$LANG]; ?> – <?php echo $TL[$LANG]['title-blogname']; ?></title>
	<?php } elseif ($DISPLAY === 'CATEGORY') { ?>
		<title><?php echo $CAT->title[$LANG]; ?> – <?php echo $TL[$LANG]['title-blogname']; ?></title>
	<?php } elseif ($DISPLAY === 'ALL_C') { ?>
		<title><?php echo $TL[$LANG]['title-categories']; ?> – <?php echo $TL[$LANG]['title-blogname']; ?></title>
	<?php } else { ?>
		<title><?php echo $TL[$LANG]['title-blogname']; ?></title>
	<?php }
}


function head_stylesheets() {
	global $DISPLAY, $SEARCH, $THIS, $CAT, $TL, $LANG;
	?>

	<link rel="stylesheet" type="text/css" href="./style.css" />
	<?php
	# if article has custom stylesheet, embed
	if ($DISPLAY === 'ARTICLE' && is_file($THIS->root.'article.css')) { ?>
		<link rel="stylesheet" type="text/css" href="<?php echo $THIS->root; ?>article.css">
	<?php }
}



/********** header and footer **********
 * 
 */


function heafoo_overview($args=array()) {
	# give overview link
	global $TL, $LANG;
	?>
	<a class="overview" href="<?php echo queryURI($args); ?>" title="<?php echo $TL[$LANG]['a-overview']; ?>"></a>
	<?php
}


function header_content() {
	global $DISPLAY, $SEARCH, $THIS, $CAT, $TL, $LANG;
	
	if ($DISPLAY === 'SEARCH') {
		heafoo_overview(array('search'=>'')); ?>
		<h1><?php echo $TL[$LANG]['title-search'] ?> – <?php echo $TL[$LANG]['title-blogname']; ?></h1>
		<?php
		if ($CAT->slug !== 'ALL') { ?>
			<p class="description"><?php echo $TL[$LANG]['search-filter-cat'][0].$CAT->title[$LANG].$TL[$LANG]['search-filter-cat'][1]; ?></p>
		<?php }
		lang_menu($TL, $LANG); ?>
	<?php } elseif ($DISPLAY === 'ARTICLE') {
		heafoo_overview(array('a'=>'')); ?>
		<h1><?php echo $THIS->title[$LANG]; ?></h1>
		<div class="article-info">
			<p class="author"><?php echo $THIS->author; ?></p>
			<p class="published"><?php echo date($TL[$LANG]['date-format'], $THIS->published); ?></p>
		</div>
		<?php lang_menu($THIS->title, $LANG); ?>
		<form method="get" action="<?php if(queryURI() != ''){echo queryURI();}else{echo '?';} ?>"  id="searchform">
			<span><input type="text" name="search" placeholder="<?php echo $TL[$LANG]['searchbar-placeholder']; ?>" <?php if ($DISPLAY === 'SEARCH') { echo 'value="'.trim_all($SEARCH).'"'; } ?>></span>
			<?php
			$args = getargs();
			if (isset($args['search'])) {
				unset($args['search']);
			}
			foreach ($args as $key => $value) { ?>
				<input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
			<?php } ?>
			<button type="submit"><?php echo $TL[$LANG]['searchbar-submit']; ?></button>
		</form>
	<?php } elseif ($DISPLAY === 'CATEGORY') {
		heafoo_overview(array("c"=>'all')); ?>
		<img src="./category/img/icon-<?php echo (is_file('./category/img/icon-'.$CAT->slug.'.svg')?$CAT->slug:'unknown'); ?>.svg">
		<h1><?php echo $CAT->title[$LANG]; ?></h1>
		<p class="description"><?php echo $CAT->description[$LANG]; ?></p>
		<?php lang_menu($TL, $LANG); ?>
		<!--<form method="get" action="<?php if(queryURI() != ''){echo queryURI();}else{echo '?';} ?>"  id="searchform">
			<span><input type="text" name="search" placeholder="<?php echo $TL[$LANG]['searchbar-placeholder']; ?>" <?php if ($DISPLAY === 'SEARCH') { echo 'value="'.trim_all($SEARCH).'"'; } ?>></span>
			<?php
			$args = getargs();
			if (isset($args['search'])) {
				unset($args['search']);
			}
			foreach ($args as $key => $value) { ?>
				<input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
			<?php } ?>
			<button type="submit"><?php echo $TL[$LANG]['searchbar-submit']; ?></button>
		</form>-->
		<?php search_form($SEARCH); ?>
	<?php } elseif ($DISPLAY === 'ALL_C') {
		heafoo_overview(array('c'=>'')); ?>
		<h1><?php echo $TL[$LANG]['title-categories']; ?> – <?php echo $TL[$LANG]['title-blogname']; ?></h1>
		<?php lang_menu($TL, $LANG); ?>
		<p style="float: right; line-height: 34px;"><a href="/aboutme"><?php echo $TL[$LANG]['a-aboutme']; ?></a></p>
		<?php search_form(); ?>
	<?php } else { ?>
		<h1><?php echo $TL[$LANG]['title-blogname']; ?></h1>
		<?php lang_menu($TL, $LANG); ?>
		<p style="float: right; line-height: 34px;"><a href="/aboutme"><?php echo $TL[$LANG]['a-aboutme']; ?></a></p>
		<?php search_form(); ?>
	<?php } ?>
	<div class="clear"></div>

	<?
}


function footer_content() {
	global $DISPLAY, $SEARCH, $THIS, $CAT, $TL, $LANG;
	?>

	<div class="border-wrapper">
	<?php if ($DISPLAY === 'SEARCH') {
		heafoo_overview(array("search"=>'')); ?>
	<?php } elseif ($DISPLAY === 'ARTICLE') {
		heafoo_overview(array("a"=>'')); ?>
	<?php } elseif ($DISPLAY === 'CATEGORY') {
		heafoo_overview(array("c"=>'all')); ?>
	<?php } elseif ($DISPLAY === 'ALL_C') {
		heafoo_overview(array("c"=>'')); ?>
	<?php } ?>
		<p><?php echo $TL[$LANG]['copyrightby'][0]."2016".$TL[$LANG]['copyrightby'][1]; ?><a href="/aboutme">Peter Nerlich</a><?php echo $TL[$LANG]['copyrightby'][2]; ?></p>
	</div>

	<?php
}



/********** search **********
 * 
 */


function search_form($SEARCH='') {
	global $DISPLAY, $TL, $LANG;
	?>

	<form method="get" action="<?php if(queryURI() != ''){echo queryURI();}else{echo '?';} ?>" id="searchform">
		<span><input type="text" name="search" placeholder="<?php echo $TL[$LANG]['searchbar-placeholder']; ?>" <?php echo 'value="'.trim_all($SEARCH).'"'; ?>></span>
		<?php
		$args = getargs();
		if (isset($args['search'])) {
			unset($args['search']);
		}
		# don't lose the other settings!
		foreach ($args as $key => $value) { ?>
			<input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
		<?php } ?>
		<button type="submit"><?php echo $TL[$LANG]['searchbar-submit']; ?></button>
	</form>

	<?php
}



/********** flexbox loops **********
 * 
 */


function flex_articles_loop($dir=array(),$cat='ALL') {
	global $DISPLAY, $TL, $LANG;
	?>

	<div class="flexbox-grid">

	<?php
	$i = 0;
	foreach ($dir as $THIS) {
		if (flex_article($THIS, $cat) === TRUE) {
			$i++;
		}
	}

	if ($i === 0) { ?>
		<h3 class="noarticles"><?php echo $TL[$LANG]['flex-search-noarticles']; ?></h3>
	<?php }
}


function flex_article($THIS, $cat='ALL') {
	global $DISPLAY, $TL, $LANG;

	if ($THIS !== '.' && $THIS !== '..' && $THIS !== 'index.php') {
		$THIS = new Article($THIS);
		if ((include $THIS->root.'info.php') === TRUE && array_key_exists($LANG, $THIS->title) && $THIS->published < time() && ($cat === 'ALL' || in_array($cat, $THIS->categories))) {
			?>

			<figure class="article">
				<a id="<?php echo $THIS->slug; ?>" href="<?php echo queryURI(array("a"=>$THIS->slug)); ?>">
					<div class="prev">
						<?php if (is_file($THIS->root.'preview.php') && (include $THIS->root.'preview.php') === TRUE) {
							#
						} elseif ($THIS->preview[$LANG] != "") {
							echo $THIS->preview[$LANG];
						} else { ?>
							<div class="emptypreview"></div>
						<?php } ?>
					</div>
					<div class="shadow">
						<div class="placeholder"></div>
						<figcaption>
							<p class="title"><?php echo $THIS->title[$LANG]; ?></p>
							<p class="article-info"><?php echo $THIS->author; ?> – <?php echo date($TL[$LANG]['date-format'], $THIS->published); ?></p>
						</figcaption>
						<p class="description"><?php echo $THIS->description[$LANG]; ?></p>
					</div>
				</a>
				<?php flex_article_categories($THIS->categories, scandir('./category', 1)); ?>
			</figure>

			<?php
			return TRUE;
		}
	}

	return FALSE;
}


function flex_article_categories($categories=array(), $d=array()) {
	global $DISPLAY, $TL, $LANG;
	?>

	<ul class="categories">
		<?php
		$d = scandir('./category', 1);
		foreach ($categories as $category) {
			if (in_array($category.'.php', $d)) {
				$CAT = new Category($category);
				if ((include './category/'.$d[array_search($category.'.php', $d)]) === TRUE) { ?>
					<a href="<?php echo queryURI(array("c"=>$CAT->slug)); ?>" title="<?php echo $TL[$LANG]['flex-all-cattitle'][0].$CAT->title[$LANG].$TL[$LANG]['flex-all-cattitle'][1]; ?>">
						<li data-category="<?php echo $CAT->slug; ?>" style="background-color: <?php echo $CAT->color; ?>; border-color: <?php echo $CAT->color; ?>">
							<img src="./category/img/icon-<?php echo (is_file('./category/img/icon-'.$CAT->slug.'.svg')?$CAT->slug:'unknown'); ?>.svg">
							<p><?php echo $CAT->title[$LANG]; ?></p>
						</li>
					</a>
				<?php } else { ?>
					<li class="failed" data-category="<?php echo $CAT->slug; ?>">
						<img src="./category/img/icon-unknown.svg">
						<p><?php echo $TL[$LANG]['flex-cat-unknown'][0].$CAT->slug.$TL[$LANG]['flex-cat-unknown'][1]; ?></p>
					</li>
				<?php }
			} else { ?>
				<li class="unknown" data-category="<?php echo $category; ?>">
					<img src="./category/img/icon-unknown.svg">
					<p><?php echo $TL[$LANG]['flex-cat-unknown'][0].$category.$TL[$LANG]['flex-cat-unknown'][1]; ?></p>
				</li>
			<?php }
		} ?>
	</ul>

	<?php
}


function flex_categories_loop ($dir=array()) {
	global $DISPLAY, $TL, $LANG;
	?>

	<div class="flexbox-grid">

	<?php
	foreach ($dir as $CAT) {
		flex_category($CAT);
	}

	?>

	</div>

	<?php
}


function flex_category($CAT='') {
	global $DISPLAY, $TL, $LANG;

	if (is_file('./category/'.$CAT) && $CAT !== '.' && $CAT !== '..' && $CAT !== 'index.php') {
		$CAT = new Category(rtrim($CAT,'.php'));
		if ((include './category/'.$CAT->slug.'.php') === TRUE) {
			?>

			<figure class="category">
				<a id="<?php echo $CAT->slug; ?>" href="<?php echo queryURI(array("c"=>$CAT->slug)); ?>">
					<div class="prev" style="background: <?php echo $CAT->background; ?>;">
					</div>
					<div class="shadow">
						<div class="placeholder"></div>
						<figcaption>
							<img src="./category/img/icon-<?php echo (is_file('./category/img/icon-'.$CAT->slug.'.svg')?$CAT->slug:'unknown'); ?>.svg">
							<p class="title"><?php echo $CAT->title[$LANG]; ?></p>
							<p class="category-info"><?php echo (count($CAT->known)===1)?$TL[$LANG]['flex-allc-articlecount'][0][0].count($CAT->known).$TL[$LANG]['flex-allc-articlecount'][0][1]:$TL[$LANG]['flex-allc-articlecount'][1][0].count($CAT->known).$TL[$LANG]['flex-allc-articlecount'][1][1]; ?></p>
						</figcaption>
						<p class="description"><?php echo $CAT->description[$LANG]; ?></p>
					</div>
				</a>
			</figure>

			<?php
		}
	}

}


function flex_catoverview($dir=array(), $title='', $description='') {
	global $DISPLAY, $TL, $LANG;
	?>

	<div class="flexbox-grid">
		<figure class="category all_c">
			<a id="all_c" href="<?php echo queryURI(array("c"=>'all',"a"=>'')); ?>">
				<div class="prev" style="background: repeating-linear-gradient(-45deg, <?php
					$px = 0;
					# do the stripes!
					foreach ($dir as $CAT) {
						if (is_file('./category/'.$CAT) && $CAT !== '.' && $CAT !== '..' && $CAT !== 'index.php' && substr($CAT,0,3) !== 'bg-') {
							$CAT = new Category(rtrim($CAT,'.php'));
							if ((include './category/'.$CAT->slug.'.php') === TRUE) {
								if ($px > 0) {
									echo ', ';
								}
								echo $CAT->color.' '.$px.'px, ';
								$px += 30;
								echo $CAT->color.' '.$px.'px';
							}
						}
					}
					?>); opacity: .75;">
				</div>
				<div class="shadow">
					<div class="placeholder"></div>
					<figcaption>
						<p class="title"><?php echo $title; ?></p>
					</figcaption>
					<p class="description"><?php echo $description; ?></p>
				</div>
			</a>
		</figure>
	</div>

	<?php
}


?>