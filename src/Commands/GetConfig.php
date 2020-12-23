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
        $client->setAccessKey(config('acm.access_key'));
        $client->setSecretKey(config('acm.secret_key'));
        $client->setNameSpace(config('acm.namespace'));
        $client->refreshServerList();
        $dataId = config('acm.data_id');
        $group = config('acm.group');
        $config = $client->getConfig($dataId, $group);
        $filesystem->put(config('acm.path'), $config);
        $this->info('success');
    }

}
