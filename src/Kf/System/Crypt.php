<?php

namespace Kf\System;

class Crypt {

    public static function encode($text, $key = "E4HD9h4DhS23DYfhHemkS3Nf", $iv = "fYfhHeDm", $bit_check = 8) {
        $text_num = str_split($text, $bit_check);
        $text_num = $bit_check - strlen($text_num[count($text_num) - 1]);
        for ($i = 0; $i < $text_num; $i++) {
            $text = $text . chr($text_num);
        }
        $cipher = mcrypt_module_open(MCRYPT_TRIPLEDES, '', 'cbc', '');
        mcrypt_generic_init($cipher, $key, $iv);
        $decrypted = mcrypt_generic($cipher, $text);
        mcrypt_generic_deinit($cipher);
        return base64_encode($decrypted);
    }

    public static function decode($encryptedText, $key = "E4HD9h4DhS23DYfhHemkS3Nf", $iv = "fYfhHeDm", $bit_check = 8) {
        $cipher = mcrypt_module_open(MCRYPT_TRIPLEDES, '', 'cbc', '');
        mcrypt_generic_init($cipher, $key, $iv);
        $decrypted = mdecrypt_generic($cipher, base64_decode($encryptedText));
        mcrypt_generic_deinit($cipher);
        $last_char = substr($decrypted, -1);
        for ($i = 0; $i < $bit_check - 1; $i++) {
            if (chr($i) == $last_char) {
                $decrypted = substr($decrypted, 0, strlen($decrypted) - $i);
                break;
            }
        }
        return $decrypted;
    }

}
