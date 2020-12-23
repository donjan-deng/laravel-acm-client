<?php

return [
    //accesskey
    'access_key' => env('ALIYUN_ACM_AK'),
    //secretkey
    'secret_key' => env('ALIYUN_ACM_SK'),
    //namespace
    'namespace' => env('ALIYUN_ACM_NAMESPACE'),
    //data_id
    'data_id' => env('ALIYUN_ACM_DATA_ID'),
    //group
    'group' => env('ALIYUN_ACM_GROUP', 'DEFAULT_GROUP'),
    //acm config file path
    'path' => base_path('acm.json')
];
