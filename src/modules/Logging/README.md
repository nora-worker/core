ロガー
==============



使用方法（スタンドアローン)
----------------------------
```php
use Nora\Module\Logging\Logger;

// ログライタ群の設定
$writer_specs = [
    [
        'type' => 'stdout',
        'filter' => [
            'level' => 'ALL'
        ],
        'format' => '%(time) %(level) %(tag) %(msg) %(args)'
    ]
];

// ロガーのビルドアップ
$logger = Logger::build($writer_specs);

$logger->info('インフォメーション');

$logger->debug('デバッグメッセージ', [
        'file' => __FILE__,
        'line' => __LINE__
    ]);
```


使用方法（Nora)
----------------------------
```php
Nora::ModuleLoader( )->on('moduleloader.loadmodule',function($e) {

    if ($e->name === 'logging')
    {
        // ロガーの設定をする
        $e->module->configure([
            'loggers' => [
                '_default' => [
                    [
                        'type' => 'stdout',
                        'filter' => [
                            'level' => 'ALL'
                        ],
                        'format' => '%(time) %(level) %(tag) %(msg) %(args)'
                    ]
                ]
            ]
        ]);
    }
});
```

開発方法
---------

設定 type = ライタ名で読み込まれるので

class/Writer/ライタ名.php
を開発してください。


