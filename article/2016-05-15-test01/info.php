<?php

# self explanatory

$THIS->title = array(
	'EN' => "Blog Test 01",
	'DE' => "Blog Test 01"
);
$THIS->author	=	"Peter Nerlich";
$THIS->published	=	mktime(20,05,00, 05,15,2016);	#	h, m, s,  m, d, y
$THIS->categories	=	array("programming");
$THIS->description = array(
	'EN' => "Description",
	'DE' => "Beschreibung"
);
$THIS->keywords	=	array(
	'EN' => array('test','php','html','css','hack'),
	'DE' => array('test','php','html','css','hack')
);
# this will be shown if no `preview.php` is present
$THIS->preview = array(
	'EN' => "<div class='bubble'><p>I am just building my own <b>Blog</b>, but it is different from other systems out there. I really like to write my websites completely by myself, with <b>100% of hand-written code</b>. In terms of the blog posts I would also like to have as much of this freedom as possible, while on the other hand I would like to have a search functionality. Also, I do not have another MYSQL database available at the moment.</p></div>",
	'DE' => "<div class='bubble'><p>Ich baue gerade meinen eigenen <b>Blog</b>, der anders wird als bekannte bestehende Systeme. Ich bevorzuge es, meine Internetseiten selbst zu schreiben, m <b>100% handgeschriebenem Code</b>. Für die Blog Posts würde ich auch gerne so frei wie möglich sein, während ich auch gerne eine Suchfunktion hätte. Außerdem ist für mich gerade keine MYSQL-Datenbank verfügbar.</p></div>"
);

# returning true to test wether everything loaded correctly
return TRUE;

?>