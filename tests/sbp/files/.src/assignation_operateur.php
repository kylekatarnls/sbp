<?
$a &&= true
$a ||= true
$a (and= true)
$a (or= true)
$a (xor= false)
$a is= true
$a not= false
$a <>= "abc"
$a lt= 25
$a gt= -12.5/2
$a <== 25
$a >== 0
$a ==== "foo"
$a !=== "bar"
$a ?:= "default value"
// Équivalent pour PHP < 5.3
$a !?= "default value"

/*
 * is, not, lt et gt sont aussi
 * disponibles dans le contexte normal
 */

if $a is 23
	echo "a is 23"
if $a not 23
	echo "a is not 23"
if $a lt 23
	echo "a is lesser than 23"
if $a gt 23
	echo "a is greater than 23"
?>