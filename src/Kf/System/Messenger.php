<?php

namespace Kf\System;

class Messenger {

    public static function success($message) {
        $session = new Session('__KuestionsMessenger__');
        $success = $session->success;
        $success[] = $message;
        $session->success = $success;
    }

    public static function error($message) {
        $session = new Session('__KuestionsMessenger__');
        $error = $session->error;
        $error[] = $message;
        $session->error = $error;
    }

    public static function successNow($message) {
        $success = \Kf\System::$layout->success;
        $success[] = $message;
        \Kf\System::$layout->success = $success;
    }

    public static function errorNow($message) {
        $error = \Kf\System::$layout->error;
        $error[] = $message;
        \Kf\System::$layout->error = $error;
    }

    public static function getSuccess() {
        $session = new Session('__KuestionsMessenger__');
        $success = $session->success ? $session->success : [];
        $session->success = [];
        return $success;
    }

    public static function getError() {
        $session = new Session('__KuestionsMessenger__');
        $error = $session->error ? $session->error : [];
        $session->error = [];
        return $error;
    }

}
