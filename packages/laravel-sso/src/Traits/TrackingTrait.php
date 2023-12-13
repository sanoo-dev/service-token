<?php

namespace TuoiTre\SSO\Traits;

trait TrackingTrait
{
    protected ?array $trackingData = null;

    public function setTrackingData(?array $data): void
    {
        $this->trackingData = $data;
    }

    public function getTrackingData(): ?array
    {
        return $this->trackingData;
    }
}