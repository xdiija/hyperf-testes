<?php

namespace App\Trait;

trait Country
{
    function getCountryLanguage($country)
    {

        $countries = array(
            "AR" => "es",
            "BO" => "es",
            "CL" => "es",
            "CO" => "es",
            "CR" => "es",
            "CU" => "es",
            "EC" => "es",
            "SV" => "es",
            "ES" => "es",
            "GT" => "es",
            "GQ" => "es",
            "HN" => "es",
            "MX" => "es",
            "NI" => "es",
            "PA" => "es",
            "PY" => "es",
            "PE" => "es",
            "PR" => "es",
            "DO" => "es",
            "UY" => "es",
            "VE" => "es",
            "BR" => "pt",
            "PT" => "pt",
            "AO" => "pt",
            "MZ" => "pt",
            "CV" => "pt",
            "GW" => "pt",
            "TL" => "pt",
            "ST" => "pt",
            "US" => "en",
            "GB" => "en",
            "CA" => "en",
            "AU" => "en",
            "NZ" => "en",
            "IE" => "en",
            "ZA" => "en",
            "JM" => "en",
            "BS" => "en",
            "TT" => "en",
            "NP" => "hi",
            "FJ" => "hi",
            "MU" => "hi",
            "SG" => "hi",
            "LK" => "hi",
            "IN" => "hi",
            "OM" => "hi",
            "AE" => "hi",
            "SA" => "hi",
            "QA" => "hi",
            "BH" => "hi",
            "KW" => "hi",
            "PK" => "hi",
        );

        if (isset($countries[strtoupper($country)])) {
            return $countries[strtoupper($country)];
        } else {
            return "en";
        }
    }

    function getCountryRegion($country)
    {

        $countries = array(
            "NP" => "IN",
            "FJ" => "IN",
            "MU" => "IN",
            "SG" => "IN",
            "LK" => "IN",
            "IN" => "IN",
            "OM" => "IN",
            "AE" => "IN",
            "SA" => "IN",
            "QA" => "IN",
            "BH" => "IN",
            "KW" => "IN",
            "PK" => "IN",
        );

        if (isset($countries[strtoupper($country)])) {
            return $countries[strtoupper($country)];
        } else {
            return null;
        }
    }

}

