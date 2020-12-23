<?php

namespace Donjan\AcmClient\Test;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Donjan\AcmClient\Providers\AcmServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('acm.access_key', env('ALIYUN_ACM_AK'));
        $app['config']->set('acm.secret_key', env('ALIYUN_ACM_SK'));
        $app['config']->set('acm.namespace', env('ALIYUN_ACM_NAMESPACE'));
        $app['config']->set('acm.data_id', env('ALIYUN_ACM_DATA_ID'));
        $app['config']->set('acm.group', env('ALIYUN_ACM_GROUP', 'DEFAULT_GROUP'));
        $app['config']->set('acm.path', base_path('acm.json'));
        $app['config']->set('filesystems.default', 'local');
        $app['config']->set('filesystems.disks', ['local' => [
                'driver' => 'local',
                'root' => public_path('upload'),
                'url' => '/upload',
                'visibility' => 'public',
        ]]);
    }

}
