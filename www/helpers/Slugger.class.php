<?php

namespace App\Helpers;

class Slugger
{

    public static function sluggify($title_article)
    {
        $chars = [
            'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e', 'Œ' => 'oe', 'Ē' => 'e',
            'À' => 'a', 'à' => 'a', 'â' => 'a', 'ä' => 'a', 'ì' => 'i', 'î' => 'i', 'ï' => 'i',
            'ô' => 'o', 'ö' => 'o', 'ò' => 'o', 'û' => 'u', 'ù' => 'u', 'û' => 'u',
            '&' => '-', '\"' => '', '\'' => '', '§' => '', '!' => '', '?' => '', '%' => '',
            '$' => '', '€' => '', '£' => '', '/' => '-', '(' => '-', ')' => '-', '_' => '-',
            '`' => '-', '+' => '-', '=' => '-', ',' => '-', ';' => '-', ':' => '-', '^' => '',
            '<' => '', '>' => '', '@' => '', '#' => '', '°' => '', '*' => '', ' ' => '-'
        ];

        // supprime le html, mets en minuscule et supprime les espaces en debut et fin de chaine
        $title_article = strtolower(trim(htmlspecialchars($title_article)));
        // on eclate la chaine en tableau
        $article_chars = str_split($title_article);

        $expected_chars = [];
        for ($i = 0; $i < count($article_chars); $i++) {
            foreach ($chars as $key => $value) {
                if ($article_chars[$i] == $key) {
                    $expected_chars[] = $value;
                }
            }
            $expected_chars[] = $article_chars[$i];
        }

        $slug =  implode('', $expected_chars);
        $slug = str_replace(' ', '', $slug);
        return $slug;
    }
}