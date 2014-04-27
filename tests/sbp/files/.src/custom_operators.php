<?

@f __sbp_in $needle, $haystack = null
	if func_num_args() is 1
		< array_search(true, $neelde)
	< is_array($haystack) ?
		in_array($needle, $haystack) :
		strpos($haystack, $needle) !== false

if 5 in [3, 5, 7, 11]
	echo "5 is in [3, 5, 7, 11]"
else
	echo "5 isn't in [3, 5, 7, 11]"

if "BA" in "tagaBAduru"
	echo "BA is in tagaBAduru"
else
	echo "BA isn't in tagaBAduru"

if in [false, true, true, false]
	echo "There is true values"
else
	echo "There is no true value"