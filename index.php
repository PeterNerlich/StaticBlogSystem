<!DOCTYPE html>

<?php

/********** peter.nerlich4u.de/blog **********
 *  This is the source code of the "static"
 *  blog system invented by Peter Nerlich. Do
 *  not use if you have no understanding of
 *  HTML, PHP and CSS. This is not aimed at
 *  people afraid of code. Without warranty.
 *  Usage at own risk.
 */

# import core and helper functions
require_once './mgmt.php';

# determine whether search or article etc.
# from url query string
INIT_DISPLAY($_GET);

?>

<html>
<head>
<!--[if IE ]>
<meta http-equiv="refresh" content="0; url=http://abetterbrowser.org">
<![endif]-->

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="copyright" content="Peter Nerlich">
<?php head_title(); ?>
<meta name="wot-verification" content="34802f95eacd472223ee"/>
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php head_stylesheets(); ?>
<link rel='shortcut icon' type='image/x-icon' href='../favicon.ico' />

</head>
<body>

<header>
	<div class="border-wrapper">
		<?php header_content(); ?>
	</div>
</header>

<?php if ($DISPLAY == 'SEARCH') { ?>

	<section class="searchbar">
		<div class="border-wrapper">
			<?php search_form($SEARCH); ?>
		</div>
	</section>

	<section class="search">
		<div class="border-wrapper">

		<?php

		# import search algorithm
		require_once './search-algorithm.php';
		# import search index
		require_once './search-index.php';

		# search index for term 
		$dir = blogsearch($SEARCH, $INDEX, $LANG);
		$time = $dir['time'];

		# only get articles that really exist
		$dir = array_intersect_key(
			$dir['res'],
			array_flip( scandir('./article') )
		);

		# show articles
		flex_articles_loop(array_keys($dir), $CAT->slug);
		?>

		</div>
	</section>

<?php } elseif ($DISPLAY === 'ARTICLE') { ?>


	<section class="article">
		<?php
		# simply include the article
		include $THIS->root.'content.php';
		?>
	</section>


<?php } elseif ($DISPLAY === 'CATEGORY') { ?>


	<section class="articles">
		<div class="border-wrapper">

			<h1><?php echo $TL[$LANG]['h-catarticles']; ?></h1>

			<?php
			# show all articles in this category
			flex_articles_loop($CAT->known, $CAT->slug);
			?>
		</div>
	</section>


<?php } elseif ($DISPLAY === 'ALL_C') { ?>


	<section class="categories">
		<div class="border-wrapper">

			<h1><?php echo $TL[$LANG]['h-allcategories']; ?></h1>

			<?php
			# show all categories
			flex_categories_loop(scandir('./category', 1));?>
		</div>
	</section>


<?php } else { ?>


	<section class="articles">
		<div class="border-wrapper">
			<?php flex_catoverview(scandir('./category', 1), $TL[$LANG]['flex-gotoallc-title'], $TL[$LANG]['flex-gotoallc-description']); ?>
			<br>

			<h1><?php echo $TL[$LANG]['h-allarticles']; ?></h1>

			<?php
			# show all existing articles
			flex_articles_loop(scandir('./article', 1));
			?>
		</div>
	</section>

	<section>
		<div class="border-wrapper">
			<div class="bubble">
				<p>I am just building my own <b>Blog</b>, but it is different from other systems out there. I really like to write my websites completely by myself, with <b>100% of hand-written code</b>. In terms of the blog posts I would also like to have as much of this freedom as possible, while on the other hand I would like to have a search functionality. Also, I do not have another MYSQL database available at the moment.<br>
				So how do I make a blog system without a big overhead in PHP for searching and displaying static posts? We will see...</p>
			</div>
		</div>
	</section>


<?php } ?>


<div class="footerspacer clear"></div>
<footer>
	<?php footer_content(); ?>
</footer>

<!--<script async type="text/javascript" src="js/main.js"></script>-->

<script> // Google Analytics
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-57040104-1', 'auto');
  ga('require', 'linkid');
  ga('send', 'pageview');
</script>

</body>
</html>
