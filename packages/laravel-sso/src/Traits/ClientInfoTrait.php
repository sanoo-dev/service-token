<?php

namespace TuoiTre\SSO\Traits;

use Illuminate\Http\Request;

trait ClientInfoTrait
{
    public function getClientInfoFromRequest(Request $request)
    {
        if (!empty($state = $request->get('state'))
            || !empty($state = $request->header('X-Client-Track-Content'))
        ) {
            return @json_decode(base64_decode($state), true);
        } else {
            $clientUrlData = parse_url($this->getClientUrl($request));
            return [
                'ip' => $request->getClientIp(),
                'user_agent' => $request->userAgent(),
                'fingerprint' => $request->fingerprint(),
                'client_domain' => $clientUrlData['host'] ?? $request->getHost()
            ];
        }
    }

    public function getClientUrl(Request $request): string|null
    {
        if (!empty($headerReferer = $request->header('referer'))) {
            $clientUrl = $headerReferer;
        } elseif (!empty($serverReferer = $request->server('HTTP_REFERER'))) {
            $clientUrl = $serverReferer;
        } elseif (!empty($origin = $request->header('origin'))) {
            $clientUrl = $origin;
        } else {
            $clientUrl = $request->getSchemeAndHttpHost();
        }
        return $clientUrl;
    }
}