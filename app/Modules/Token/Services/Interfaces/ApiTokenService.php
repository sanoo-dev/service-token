<?php

namespace App\Modules\Token\Services\Interfaces;

interface ApiTokenService
{
    public function createToken_cache($data);

    public function verifyToken_cache($data);

    public function saveInfoService($data);

    public function saveInfoTransfer($data);

    public function getListEndPoint($data);
    public function getListAllEndPoint($data);
    public function getListService($data);
    public function updateService($data);

    public function updateTransfer($data);

    public function createNewKey($data);

    public function acceptKey($data);
    public function acceptEndPoint($data);
    public function acceptService($data);
    public function changeNewKey($data);
    public function extendKey($data);
    public function delData($data);
    public function serviceaddED($data);

}
