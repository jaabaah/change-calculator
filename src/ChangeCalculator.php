<?php

/**
 * ChangeCalculator
 *
 * Classe permettant de calculer le montant en euros encaissé à partir de montants en dollars,
 * en tenant compte d'une commission et d'un seuil.
 */
class ChangeCalculator
{
    /**
     * Taux de change : 1 euro = X dollars (modifiable)
     *
     * @var float
     */
    private float $tauxChange;

    /** Commission en euros pour les transactions en dessous du seuil */
    public const COMMISSION_EURO = 1.0;

    /** Seuil en euros en dessous duquel la commission s'applique */
    public const SEUIL_COMMISSION_EURO = 100.0;

    /**
     * Constructeur
     *
     * @param float|null $tauxChange Si fourni, définit le taux de change initial. Sinon, valeur par défaut (1.16).
     */
    public function __construct(?float $tauxChange = null)
    {
        // On passe par le setter → DRY
        $this->setTauxChange($tauxChange ?? 1.16);
    }

    /**
     * Définit le taux de change
     *
     * @param float $tauxChange
     * @return self
     *
     * @throws InvalidArgumentException si le taux est invalide
     */
    public function setTauxChange(float $tauxChange): self
    {
        if ($tauxChange <= 0) {
            throw new InvalidArgumentException('Le taux de change doit être strictement positif.');
        }

        $this->tauxChange = $tauxChange;
        return $this;
    }

    /**
     * Retourne le taux de change courant
     *
     * @return float
     */
    public function getTauxChange(): float
    {
        return $this->tauxChange;
    }

    /**
     * Calcule le total des euros encaissés à partir d'un tableau de montants en dollars
     *
     * @param array $montantsDollars Tableau de montants en dollars
     * @return float Total des euros encaissés, arrondi à 2 décimales
     *
     * @throws InvalidArgumentException si le paramètre n'est pas un tableau ou si les valeurs ne sont pas valides
     */
    public function encaissementEuro($montantsDollars): float
    {
        // Validation du type du paramètre
        if (!is_array($montantsDollars)) {
            throw new InvalidArgumentException('Le paramètre doit être un tableau de montants en dollars.');
        }

        $tauxConversion = 1 / $this->tauxChange;
        $totalEuros = 0.0;

        foreach ($montantsDollars as $montantDollar) {
            // Validation de chaque montant
            if (!is_numeric($montantDollar)) {
                throw new InvalidArgumentException('Chaque montant du tableau doit être un nombre.');
            }

            if ($montantDollar < 0) {
                throw new InvalidArgumentException('Les montants doivent être positifs ou nuls.');
            }

            $montantEuro = $montantDollar * $tauxConversion;

            if ($montantEuro < self::SEUIL_COMMISSION_EURO) {
                $montantEuro += self::COMMISSION_EURO;
            }

            $totalEuros += $montantEuro;
        }

        // On arrondit à 2 décimales pour correspondre à l'exemple, via la fonction dédiée
        return $this->roundUp($totalEuros, 2);
    }

    /**
     * Arrondi supérieur à N décimales
     *
     * @param float $number
     * @param int $precision
     * @return float
     */
    private function roundUp(float $number, int $precision = 2): float
    {
        $fig = pow(10, $precision);
        return (ceil($number * $fig) / $fig);
    }
}