<?php

namespace Common\App\Traits;

use Exception;
use Illuminate\Support\Facades\Log;

trait WriteLogTrait
{
    public function writeMessage($message)
    {
        echo "\n" . date('d/m/Y H:i:s', time()) . ' [M] Class ' . get_called_class() . ' - Message: ' . strval($message) . "\n";
    }

    public function writeError($message)
    {
        $str = date('d/m/Y H:i:s', time()) . ' [E] Class ' . get_called_class() . ' - Message: ' . strval($message);
        echo "\n $str \n";
        Log::error($str);
    }

    public function writeException(Exception $exception)
    {
        $str = date('d/m/Y H:i:s', time()) . " [Exception] Class " . get_called_class()
            . " - Message:" . $exception->getMessage() . " - Line: " . $exception->getLine() . " - File: " . $exception->getFile();
        echo "\n $str \n";
        Log::error($str);
    }
}
