<?php /* Generated By SBP *//*:C:\wamp\www\sbp/tests/sbp/files/.src/super_methods.php:*/ 

echo (new \Sbp\Handler(5))->sqrt() . "\n";
echo (new \Sbp\Handler(5))->pow(4) . "\n";

$array = array(
	 'toto' => 4,
	 'tata' => 5,
	 'lulu' => 6
);
echo (new \Sbp\Handler((new \Sbp\Handler($array))->keys()))->implode(', ') . "\n";

echo (new \Sbp\Handler((new \Sbp\Handler(array(
	 'toto' => 4,
	 'tata' => 5,
	 'lulu' => 6
)))->keys()))->implode(', ') . "\n";

echo (new \Sbp\Handler("AbcdEF"))->replace('/[a-z]/', '#') . "\n";

echo (new \Sbp\Handler([1, 3, 7]))->sum() . "\n";

function array_custom($array, $a, $b){
    $array[] = $a+$b;
    return  $array;
}
function str_custom($string, $a, $b){
    $string .= $a+$b;
    return  $string;
}
print_r((new \Sbp\Handler([5]))->custom(1, 2));

$matches = [];

echo (new \Sbp\Handler("yoh-toh-pouf-paf"))->match_all('/-\\w/', &$matches, PREG_PATTERN_ORDER, 5) . "\n";

print_r($matches);

echo (new \Sbp\Handler("abc"))->custom(1, 2) . "\n";
