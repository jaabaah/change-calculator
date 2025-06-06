# ChangeCalculator

Petit projet PHP permettant de calculer le montant en euros encaissé à partir de montants en dollars, en tenant compte :

- d'un taux de change configurable  
- d'une commission fixe pour les transactions inférieures à un seuil donné

## Fonctionnement

La classe `ChangeCalculator` permet de :

✅ définir un taux de change  
✅ calculer le total en euros à partir d'un tableau de montants en dollars  
✅ appliquer une commission de `1€` sur les transactions < `100€`  
✅ modifier dynamiquement le taux de change si besoin

## Installation

1. Cloner le projet  
2. Installer les dépendances via Composer :

```bash
composer install
```

## Utilisation

```php
<?php

require_once 'src/ChangeCalculator.php';

$calculator = new ChangeCalculator(); // Taux par défaut 1.16

echo $calculator->encaissementEuro([100, 150, 30, 80]) . "\n"; // → 313.35

$calculator->setTauxChange(1.10);

echo $calculator->encaissementEuro([100, 150, 30, 80]) . "\n";
```

## Tests unitaires

Le projet inclut des tests unitaires avec PHPUnit.

Lancer les tests :

```bash
composer test
```

## Structure du projet

```
src/
└── ChangeCalculator.php
tests/
└── ChangeCalculatorTest.php
composer.json
README.md
```

## Auteurs

Réalisé dans le cadre d'un test technique Developer.

---
