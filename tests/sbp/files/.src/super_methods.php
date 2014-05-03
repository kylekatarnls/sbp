<?

echo 5-->sqrt() . "\n"
echo 5-->pow(4) . "\n"

$array = {
	toto = 4
	tata = 5
	lulu = 6
}
echo $array-->keys()-->implode(', ') . "\n"

echo ({
	toto = 4
	tata = 5
	lulu = 6
}-->keys())-->implode(', ') . "\n"