<?

f triple($number)
	< $number * 3;

$score = 4;
$score **= triple();

echo $score; // Affiche 12

?>