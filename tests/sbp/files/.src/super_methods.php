<?

Foo
	- $bar = '1s7zz05'
	- $baz = 'abcd'

	+match($suffix)
		< preg_match('`^[a-z]+'.$suffix.'$`', >bar) or preg_match('`^[a-z]+'.$suffix.'$`', >baz)

	+match_all($suffix)
		< preg_match('`^[a-z]+'.$suffix.'$`', >bar) and preg_match('`^[a-z]+'.$suffix.'$`', >baz)

echo 5-->sqrt() . "\n"
echo 5-->pow(4) . "\n"

$array = {
	toto = 4
	tata = 5
	lulu = 6
}
echo $array-->keys()-->implode(', ') . "\n"

echo {
	toto = 4
	tata = 5
	lulu = 6
}-->keys()-->implode(', ') . "\n"

echo "AbcdEF"-->replace(/[a-z]/, '#') . "\n"

echo "AbcdEF"-->replace_callback(/[a-z]/, f° ($match)
	< ord($match[0])
) . "\n"

echo "AbcdEF"-->filter(/[a-z]/, '?') . "\n"

echo [1, 3, 7]-->sum() . "\n"

f array_custom($array, $a, $b)
    $array[] = $a+$b
    < $array

f str_custom($string, $a, $b)
    $string .= $a+$b
    < $string

print_r([5]-->custom(1, 2))

echo "yoh-toh-pouf-paf"-->match(/-\w/, $matches) . "\n"

echo "yoh-toh-pouf-paf"-->match_all(/-\w/, $matches, PREG_PATTERN_ORDER, 5) . "\n"

echo "yoh-toh-pouf-paf"-->split(/-\w/, 2)-->implode(':') . "\n"

echo $matches[0]-->implode(', ') . "\n"

$foo = new Foo

echo $foo-->match("[a-z]"-->quote()) ? 'true' : 'false'
echo "\n"

echo $foo-->match("[a-z]") ? 'true' : 'false'
echo "\n"

echo $foo-->match_all("[a-z]") ? 'true' : 'false'
echo "\n"

echo "abc"-->custom(1, 2) . "\n"
