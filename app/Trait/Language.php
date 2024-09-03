<?php

namespace App\Trait;

trait Language
{
    private function getPrimaryLanguage($acceptLanguageHeader) {

        $acceptedLanguages =array('pt', 'en', 'es', 'hi');

        $languages = explode(',', $acceptLanguageHeader);

        $preferredLanguage = 'en';
        $maxQValue = 0;

        foreach ($languages as $language) {
            $parts = explode(';', $language);
            $tag = trim($parts[0]);

            $qValue = isset($parts[1]) ? (float) explode('=', $parts[1])[1] : 1;

            $primaryLanguage = explode('-', $tag)[0];
            if ($qValue > $maxQValue && in_array($primaryLanguage, $acceptedLanguages)) {
            $preferredLanguage = $primaryLanguage;
            $maxQValue = $qValue;
            }
        }
        return $preferredLanguage;

      }

}
