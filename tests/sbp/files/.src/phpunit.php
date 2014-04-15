<?

MathTest:\PHPUnit_Framework_TestCase

	+testSomeCalcs

		// should suivi d'un opérateur vérifie qu'une comparaison est vraie
		sqrt(25) should == 5
		6 should > 4
		// vérifier qu'une fonction renvoie true
		should class_exists('MathTest')
		// be après should devient is pour une conjugaison correcte dans le code
		strtolower('ABC') should be 'abc'
		// message personnalisé
		strtolower('ABC') should be 'abc', "strtolower devrait transformer ABC en abc"
		// be, is et === sont sensibles aux types
		7 should not be "7"
		// should return = should be
		gettype(7) should return "int"
		gettype(7) should not return "string"