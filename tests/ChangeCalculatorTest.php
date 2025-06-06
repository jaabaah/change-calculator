<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/ChangeCalculator.php';

class ChangeCalculatorTest extends TestCase
{
    public function testEncaissementEuroAvecExempleFournis()
    {
        $calculator = new ChangeCalculator();

        $resultat = $calculator->encaissementEuro([100, 150, 30, 80]);

        // On tolère un écart de 0.01 à cause de l'arrondi flottant
        $this->assertEquals(313.35, $resultat);
    }

    public function testEncaissementEuroAvecTableauVide()
    {
        $calculator = new ChangeCalculator();

        $resultat = $calculator->encaissementEuro([]);

        $this->assertEquals(0.0, $resultat);
    }

    public function testEncaissementEuroAvecMontantsSansCommission()
    {
        $calculator = new ChangeCalculator();

        $resultat = $calculator->encaissementEuro([200, 300]);

        $tauxConversion = 1 / $calculator->getTauxChange();
        $expected = (200 * $tauxConversion) + (300 * $tauxConversion);

        $this->assertEqualsWithDelta($expected, $resultat, 0.01);
    }

    public function testEncaissementEuroLanceExceptionSiParametreNonTableau()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Le paramètre doit être un tableau de montants en dollars.');

        $calculator = new ChangeCalculator();
        $calculator->encaissementEuro('je ne suis pas un tableau');
    }

    public function testEncaissementEuroLanceExceptionSiElementNonNumerique()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Chaque montant du tableau doit être un nombre.');

        $calculator = new ChangeCalculator();
        $calculator->encaissementEuro([100, 'abc', 50]);
    }

    public function testEncaissementEuroLanceExceptionSiMontantNegatif()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Les montants doivent être positifs ou nuls.');

        $calculator = new ChangeCalculator();
        $calculator->encaissementEuro([100, -20, 50]);
    }

    public function testSetTauxChangeAccepteValeurValide()
    {
        $calculator = new ChangeCalculator();
        $calculator->setTauxChange(1.20);

        $this->assertEquals(1.20, $calculator->getTauxChange());
    }

    public function testSetTauxChangeLanceExceptionSiValeurInvalide()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Le taux de change doit être strictement positif.');

        $calculator = new ChangeCalculator();
        $calculator->setTauxChange(0);
    }
}
