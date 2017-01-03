


<?php

$underkat = $_GET['lnk'];
include "db.ink.php";
$idag	= date ("Y-m-d", mktime (0,0,0,date("m"),date("d"),date("Y")));

$finn_minmax 	= "select max(liste_a_id) MAX, min(liste_a_id) MIN from liste_ab";
$result 	= mysql_query($finn_minmax);
$row       	= mysql_fetch_assoc($result);
$mini 		= $row["MIN"];
$maxi		= $row["MAX"];
$kake		=  $_COOKIE["UnderKat"];

if (($underkat < $mini) OR ($underkat > $maxi)) {
	// hent cookie
	$underkat = $kake;
} else {
	if ($underkat <> $kake) {
		setcookie("UnderKat", $underkat, time()+10800);
	}
}


if ($art_nr > 1) {
	$og_artikkel = "&amp;id=$art_nr";
} else {
	$og_artikkel = "";
}

$rod_liste = array();
$teller    = 0;
$sql_retrive    = mysql_query("
select
        a.id,
        a.navn
from
        liste_a a,
	liste_ab ab
where
	a.id = ab.liste_a_id
group by
	a.id
order by 
	a.rangering
");
?>
<article class="enkel"><div class="littrom">
	<ul>
<?php
// ..............................................................................
while ($row = mysql_fetch_array($sql_retrive, MYSQL_ASSOC)) {
        $id             = $row["id"];
        $beskrivelse    = $row["navn"];

        print "<li><a href=\"&#63;lnk=$id$og_artikkel\">$beskrivelse</a></li>";
	$rod_liste[$teller] = $id;$teller++;
}

?>		
	</ul>
</div></article>

<article class="enkel"><div class="littrom">
	<ul>
<?php
if (in_array($underkat, $rod_liste)) {} else {$underkat = 296;}

$finn_b ="
select
        b.navn		navn,
        b.lenke		lenke,
	ab.rangering	rang
from
	liste_b b,
	liste_ab ab
where
	ab.liste_a_id = $underkat
	and
	ab.liste_b_id = b.id
order by
	rang
";
$sql_retrive    = mysql_query($finn_b);

// ..............................................................................
while ($row = mysql_fetch_array($sql_retrive, MYSQL_ASSOC)) {
        $navn           = $row["navn"];
        $lenke          = $row["lenke"];
        print "<li><a href=\"$lenke\" title=\"$navn\" class=\"exempt\">$navn</a></li>";
}
// ..............................................................................
?>		
	</ul>
</div></article>
