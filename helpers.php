<?php

if (!function_exists('getCeByCtype')) {
    /**
     * Generates an identifier by the provided $CType-string.
     * Turns a 'ce_rte' to 'Rte' - optional also definable whether upper- or lowercase.
     * 
     * @param string $CType The CType of the content-element. Could be e.g. 'ce_rte'.
     * @param string $upperCase Optional. Default true - determines whether the first letter should be upperCase or not.
     * 
     * @return string
     */
    function getCeByCtype($CType, $upperCase = true)
    {
        $CType = str_replace('ce_', '', $CType);

        return ($upperCase ? ucfirst($CType) : lcfirst($CType));
    }
}
