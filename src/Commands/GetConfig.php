<?php

namespace Donjan\AcmClient\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Donjan\AcmClient\AcmClient;

class GetConfig extends Command
{

    protected $signature = 'acm:get-config';
    protected $description = 'get config from aliyun acm';

    public function handle(Filesystem $filesystem)
    {
        $client = new AcmClient('acm.aliyun.com', '8080');
        $client->setAccessKey(env('ALIYUN_ACM_AK'));
        $client->setSecretKey(env('ALIYUN_ACM_SK'));
        $client->setNameSpace(env('ALIYUN_ACM_NAMESPACE'));
        $client->refreshServerList();
        $dataId = env('ALIYUN_ACM_DATA_ID');
        $group = env('ALIYUN_ACM_GROUP');
        $config = $client->getConfig($dataId, $group);
        $filesystem->put(base_path() . DIRECTORY_SEPARATOR . 'acm.json', $config);
        $this->info('success');
    }

}
