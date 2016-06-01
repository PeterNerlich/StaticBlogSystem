<?php

# the global $TL array provides the general translations of
# website labels
$TL = array(
	"EN" => array(
		"date-format"	=>	'd/m/Y',
		"title-blogname"	=>	"Peter Nerlich's Blog",
		"title-categories"	=>	"Categories",
		"title-search"	=>	"Search",
		"a-overview"	=>	"Overview",
		"a-aboutme"	=>	"Who am I?",
		"searchbar-placeholder"	=>	"Search...",
		"searchbar-submit"	=>	"Search",
		"search-filter-cat"	=>	array("Only displaying articles from category ", ""),
		"h-allarticles"	=>	"All articles",
		"h-catarticles"	=>	"All articles in this category",
		"h-allcategories"	=>	"All categories",
		"flex-all-cattitle"	=>	array("Category: ", ""),
		"flex-gotoallc-title"	=>	"Category overview",
		"flex-gotoallc-description"	=>	"View all categories to browse articles of a specific topic",
		"flex-allc-articlecount"	=>	array(array("", " article"),array("", " articles")),
		"flex-cat-noarticles"	=>	"There are no articles in this language available yet.",
		"flex-cat-unknown"	=>	array("Unknown: ",""),
		"flex-search-noarticles"	=>	"No articles in this language have been found.",
		"copyrightby"	=>	array("&copy; ", " by ", "")
	),
	"DE" => array(
		"date-format"	=>	'd. m. Y',
		"title-blogname"	=>	"Peter Nerlichs Blog",
		"title-categories"	=>	"Kategorien",
		"title-search"	=>	"Suche",
		"search-filter-cat"	=>	array("Es werden nur Artikel der Kategorie ", " angezeigt"),
		"a-overview"	=>	"Übersicht",
		"a-aboutme"	=>	"Wer bin ich?",
		"searchbar-placeholder"	=>	"Suchen...",
		"searchbar-submit"	=>	"Suchen",
		"search-filter-cat"	=>	array("Zeige nur Artikel der Kategorie ", " an"),
		"h-allarticles"	=>	"Alle Artikel",
		"h-catarticles"	=>	"Alle artikel in dieser Kategorie",
		"h-allcategories"	=>	"Alle Kategorien",
		"flex-all-cattitle"	=>	array("Kategorie: ", ""),
		"flex-gotoallc-title"	=>	"Kategorieübersicht",
		"flex-gotoallc-description"	=>	"Zeige alle Kategorien, um Artikel zu einem bestimmten Thema zu finden",
		"flex-allc-articlecount"	=>	array(array("", " Artikel"),array("", " Artikel")),
		"flex-cat-unknown"	=>	array("Unbekannt: ",""),
		"flex-cat-noarticles"	=>	"In dieser Sprache gibt es noch keine Artikel.",
		"flex-search-noarticles"	=>	"Es konnten keine Artikel in dieser Sprache gefunden werden.",
		"copyrightby"	=>	array("&copy; ", " durch ", "")
	)
);

# default is the first language (EN)
$LANG = key($TL);

function lang_menu($a=array(), $exclude='') {
	?>
	
	<div class="lang">
	
	<?php
	foreach ($a as $l => $val) {
		if (is_int($l)) {
			$l = $val;
		}
		if ($l !== $exclude) { ?>
			<a id="a-lang<?php echo strtoupper($l); ?>" class="<?php echo strtolower($l); ?>" href="<?php echo queryURI(array("lang"=>strtoupper($l))); ?>"><div></div></a>
		<?php }
	} ?>

	</div>

	<?php
}

?>