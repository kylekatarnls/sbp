<?
if $a
	for $i=1; $i<10; $i++
		if $i%2 is 0
			echo $i." est paire"

		elseif $i%3 is 0
			echo $i." est un multiple de 3"

		else
			echo $i." n'a rien de spécial"

else
	echo "a est faux"
?>