<?php
require_once 'src/ChangeCalculator.php';

$calculator = new ChangeCalculator(); // Taux par défaut 1.16

echo $calculator->encaissementEuro([100, 150, 30, 80]) . "\n"; // → 313.35

$calculator->setTauxChange(1.10);

echo $calculator->encaissementEuro([100, 150, 30, 80]) . "\n";