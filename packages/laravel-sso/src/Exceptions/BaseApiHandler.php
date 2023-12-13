<?php

namespace TuoiTre\SSO\Exceptions;

if (class_exists('\App\Exceptions\Handler')) {
    class BaseApiHandler extends \App\Exceptions\Handler
    {

    }
} else {
    class BaseApiHandler extends \Illuminate\Foundation\Exceptions\Handler
    {

    }
}
