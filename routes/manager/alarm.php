<?php

use \App\Http\Response;
use \App\Controller\Manager;

$obRouter->get('/manager/alarm',[
    'middlewares' => [
        'required-manager-login'
    ],
    function($request) {
        return new Response(200, Manager\Alarm::getAlarm($request));
    }
]);

$obRouter->get('/manager/alarm_form',[
    'middlewares' => [
        'required-manager-login'
    ],
    function($request) {
        return new Response(200, Manager\Alarm::Alarm_Form($request));
    }
]);

$obRouter->post('/manager/alarm_form_create',[
    'middlewares' => [
        'required-manager-login'
    ],
    function($request) {
        return new Response(200, Manager\Alarm::Alarm_Create($request));
    }
]);

$obRouter->get('/manager/alarm_form/{idx}/edit',[
    'middlewares' => [
        'required-manager-login'
    ],
    function($request, $idx) {
        return new Response(200, Manager\Alarm::Alarm_Form($request, $idx));
    }
]);

$obRouter->post('/manager/alarm_form/{idx}/edit',[
    'middlewares' => [
        'required-manager-login'
    ],
    function($request, $idx) {
        return new Response(200, Manager\Alarm::Alarm_Edit($request, $idx));
    }
]);