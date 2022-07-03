<?php

namespace App\Helpers;

class DateHelper
{
    public static function dateConverter(string $format)
    {
        $date_souhaite = date('d/m/Y');
        $date_explode = explode("/", $date_souhaite);
        $jour = $date_explode[0];
        $mois = $date_explode[1];
        $annee = $date_explode[2];

        $newTimestamp = mktime(12, 0, 0, $mois, $jour, $annee); // Créé le timestamp pour ta date (a midi)

        // Ensuite tu fais un tableau avec les jours en Français
        $Jour = array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");


        // Pareil pour les mois en FR
        $Mois = array("", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");


        if ($format == 'fullDate')
            return $Jour[date("w", $newTimestamp)] . ' ' . date('d') . ' ' . $Mois[date("n", $newTimestamp)] . ' ' . date('Y');

        if ($format == 'monthDate')
            return  $Mois[date("n", $newTimestamp)] . ' ' . date('Y');

        if ($format == 'dayDate')
            return $Jour[date("w", $newTimestamp)] . ' ' . date('d') . ' ' . $Mois[date("n", $newTimestamp)];
    }
}