<?php /* Generated By SBP */

use PHPUnit\Framework\TestCase;

class MathTest extends TestCase {

	public function testSomeCalcs () {

		// should suivi d'un opérateur vérifie qu'une comparaison est vraie
		$this->assertTrue(sqrt(25) == 5, 'sqrt(25) should == 5');
		$this->assertTrue(6 > 4, '6 should > 4');
		// vérifier qu'une fonction renvoie true
		$this->assertTrue(class_exists('MathTest'), 'should class_exists(\'MathTest\')');
		// be après should devient is pour une conjugaison correcte dans le code
		$this->assertTrue(strtolower('ABC') === 'abc', 'strtolower(\'ABC\') should be \'abc\'');
		// message personnalisé
		$this->assertTrue(strtolower('ABC') === 'abc', "strtolower devrait transformer ABC en abc", 'strtolower(\'ABC\') should be \'abc\', "strtolower devrait transformer ABC en abc"');
		// be, is et === sont sensibles aux types
		$this->assertFalse(7 === "7", '7 should not be "7"');
		// should return = should be
		$this->assertTrue(gettype(7) === "int", 'gettype(7) should return "int"');
		$this->assertFalse(gettype(7) === "string", 'gettype(7) should not return "string"');
}}
