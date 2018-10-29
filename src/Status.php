<?php

namespace scy\HiLink;

use Psr\Http\Message\ResponseInterface;

class Status
{
    protected $xml;

    const NETWORK_TYPE_NAMES = [
        0   => 'No Service',
        1   => 'GSM',
        2   => 'GPRS (2.5G)',
        3   => 'EDGE (2.75G)',
        4   => 'WCDMA (3G)',
        5   => 'HSDPA (3G)',
        6   => 'HSUPA (3G)',
        7   => 'HSPA (3G)',
        8   => 'TD-SCDMA (3G)',
        9   => 'HSPA+ (4G)',
        10  => 'EV-DO rev. 0',
        11  => 'EV-DO rev. A',
        12  => 'EV-DO rev. B',
        13  => '1xRTT',
        14  => 'UMB',
        15  => '1xEVDV',
        16  => '3xRTT',
        17  => 'HSPA+ 64QAM',
        18  => 'HSPA+ MIMO',
        19  => 'LTE (4G)',
        41  => 'UMTS (3G)',
        44  => 'HSPA (3G)',
        45  => 'HSPA+ (3G)',
        46  => 'DC-HSPA+ (3G)',
        64  => 'HSPA (3G)',
        65  => 'HSPA+ (3G)',
        101 => 'LTE (4G)',
    ];

    public function __construct(ResponseInterface $response)
    {
        $this->xml = new \SimpleXMLElement($response->getBody()->getContents());
    }

    public function __toString(): string
    {
        return sprintf('%s, %d%%',
            $this->getNetworkTypeName(),
            $this->getQuality() * 100
        );
    }

    public function getBars(): int
    {
        return (int)$this->xml->SignalIcon;
    }

    public function getNetworkType(): int
    {
        return (int)$this->xml->CurrentNetworkType;
    }

    public function getNetworkTypeName(): string
    {
        $type = $this->getNetworkType();
        return static::NETWORK_TYPE_NAMES[$type] ?? "Unknown Type $type";
    }

    public function getMaxBars(): int
    {
        return (int)$this->xml->maxsignal;
    }

    public function getQuality(): float
    {
        return (float)$this->getBars() / (float)$this->getMaxBars();
    }
}