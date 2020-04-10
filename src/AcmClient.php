<?php

namespace Donjan\AcmClient;

use GuzzleHttp\Client;
use Donjan\AcmClient\Models\Server;
use Donjan\AcmClient\Exceptions\AcmException;

class AcmClient
{

    protected $accessKey;
    protected $secretKey;
    protected $endPoint;
    protected $nameSpace;
    protected $port;
    protected $appName;
    public $serverList = array();

    public function __construct($endpoint, $port)
    {
        $this->endPoint = $endpoint;
        $this->port = $port;
        $this->serverList[$endpoint] = new Server($endpoint, $port, Helpers::isIpv4($endpoint));
    }

    public function setAccessKey($accessKey)
    {
        $this->accessKey = $accessKey;
    }

    public function setSecretKey($secretKey)
    {
        $this->secretKey = $secretKey;
    }

    public function setNameSpace($nameSpace)
    {
        $this->nameSpace = $nameSpace;
    }

    public function setAppName($appName)
    {
        $this->appName = $appName;
    }

    public function getConfig($dataId, $group)
    {
        if (!is_string($this->secretKey) ||
                !is_string($this->accessKey)) {
            throw new AcmException('Invalid auth string', "invalid auth info for dataId: $dataId");
        }

        Helpers::checkDataId($dataId);
        $group = Helpers::checkGroup($group);

        $servers = $this->serverList;
        $singleServer = $servers[array_rand($servers)];

        $host = str_replace(array('host', 'port'), array($singleServer->url, $singleServer->port), 'http://host:port/diamond-server/config.co');

        $host .= "?dataId=" . urlencode($dataId) . "&group=" . urlencode($group)
                . "&tenant=" . urlencode($this->nameSpace);
        $headers = $this->getHeader($group);
        $client = new Client(['verify' => false, 'headers' => $this->getHeader($group)]);
        $res = $client->get($host);
        $code = $res->getStatusCode();
        $rawData = $res->getBody()->getContents();
        return $rawData;
    }

    public function refreshServerList()
    {
        $client = new Client();
        $url = str_replace(array('host', 'port'), array($this->endPoint, $this->port), 'http://host:port/diamond-server/diamond');
        $res = $client->get($url);
        $body = $res->getBody()->getContents();
        $this->serverList = array();
        if (is_string($body)) {
            $serverArray = explode("\n", $body);
            $serverArray = array_filter($serverArray);
            foreach ($serverArray as $value) {
                $value = trim($value);
                $singleServerList = explode(':', $value);
                $singleServer = null;
                if (count($singleServerList) == 1) {
                    $singleServer = new Server($value,8080, Helpers::isIpv4($value));
                } else {
                    $singleServer = new Server($singleServerList[0], $singleServerList[1], Helpers::isIpv4($value));
                }
                $this->serverList[$singleServer->url] = $singleServer;
            }
        }
    }

    private function getHeader($group)
    {
        $headers = array();
        $headers['Diamond-Client-AppName'] = 'LARAVEL-ACM-CLIENT';
        $headers['Client-Version'] = '1.0.0';
        $headers['Content-Type'] = 'application/x-www-form-urlencoded; charset=utf-8';
        $headers['exConfigInfo'] = 'true';
        $headers['Spas-AccessKey'] = $this->accessKey;
        $ts = round(microtime(true) * 1000);
        $headers['timeStamp'] = $ts;
        $signStr = $this->nameSpace . '+';
        if (is_string($group)) {
            $signStr .= $group . "+";
        }
        $signStr = $signStr . $ts;
        $headers['Spas-Signature'] = base64_encode(hash_hmac('sha1', $signStr, $this->secretKey, true));
        return $headers;
    }

}
