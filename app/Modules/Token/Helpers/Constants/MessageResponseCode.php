<?php

namespace App\Modules\Token\Helpers\Constants;


class MessageResponseCode
{
    const MESSAGE_NO_ERROR = 0;
    const MESSAGE_SIGNATURE_FAILED = 40;
    const MESSAGE_GENERAL_ERROR = 42;
    const MESSAGE_REQUEST_TIMEOUT = 48;
    const MESSAGE_SERVICES_OFFLINE = 49;
    const MESSAGE_SUCCESS = 100;
    const MESSAGE_DATA_NOT_SAVED = 45;
    const MESSAGE_DUPLICATE_DATA = 55;
    const MESSAGE_NOT_FOUND = 44;

    const MESSAGE_SYNTAX_ERROR = 50;
}
