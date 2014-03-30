
- $privateAttribute = 42
* $protectedAttribute = "foo"
+ $publicAttribute = "bar"

- privateMethod
	<>privateAttribute;

* protectedMethod $val
	>privateAttribute = $val

+ publicMethod
	echo >privateAttribute
	< true