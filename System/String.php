<?php

namespace KF\Lib\System;

class String {

    public static function camelToDash($string) {
        return self::camelToSeparator('-', $string);
    }

    public static function camelToUnderscore($string) {
        return self::camelToSeparator('_', $string);
    }

    public static function camelToSpace($string) {
        return self::camelToSeparator(' ', $string);
    }

    public static function dashToCamel($string) {
        return self::separatorToCamel('-', $string);
    }

    public static function underscoreToCamel($string) {
        return self::separatorToCamel('_', $string);
    }

    public static function spaceToCamel($string) {
        return self::separatorToCamel(' ', $string);
    }

    public static function separatorToCamel($separator, $string) {
        $pregQuotedSeparator = preg_quote($separator, '#');

        $patterns = array(
            '#(' . $pregQuotedSeparator . ')([A-Za-z]{1})#',
            '#(^[A-Za-z]{1})#',
        );
        $replacements = array(
            function ($matches) {
                return strtoupper($matches[2]);
            },
            function ($matches) {
                return strtoupper($matches[1]);
            },
        );

        foreach ($patterns as $index => $pattern) {
            $string = preg_replace_callback($pattern, $replacements[$index], $string);
        }

        return lcfirst($string);
    }

    public static function camelToSeparator($separator, $string) {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $string, $matches);
        $ret = $matches[0];

        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }

        return implode($separator, $ret);
    }

}
