yaf项目模板

改造思想：
1. 尽可能使用IDE的代码提示功能
2. 将项目中的常用组件集成

目录结构：
.
├── application
│   ├── Bootstrap.php
│   ├── controllers
│   │   ├── Error.php
│   │   └── Index.php
│   ├── library
│   │   ├── Controllers
│   │   ├── Exceptions
│   │   ├── Extend
│   │   ├── ExtInterfaces
│   │   ├── Message
│   │   ├── Tools
│   │   ├── Traits
│   │   └── Utils
│   ├── models
│   │   ├── Dao
│   │   ├── Manager
│   │   └── Service
│   ├── modules
│   └── plugins
│       └── User.php
├── cli
│   └── cli.php
├── Common.php
├── composer.json
├── composer.lock
├── conf
│   ├── develop.ini
│   ├── product.ini
│   └── sample.ini
├── constant
│   ├── app.php
│   ├── mongodb.php
│   ├── mysql.php
│   └── redis.php
├── database
│   ├── migration
│   ├── migrations
│   │   └── Version20210707090521_create_table_failure_job.php
│   └── MyAbstractMigration.php
├── jobby.php
├── log
│   ├── 2021-07-12.log
│   └── 2021-07-14.log
├── public
│   └── index.php
├── README.md
├── supervisor.ini
└── vendor
    ├── autoload.php
    ├── bin
    │   ├── carbon
    │   ├── doctrine-dbal
    │   ├── doctrine-migrations
    │   ├── resque
    │   └── resque-scheduler
    ├── colinmollenhour
    │   └── credis
    ├── composer
    │   ├── autoload_classmap.php
    │   ├── autoload_files.php
    │   ├── autoload_namespaces.php
    │   ├── autoload_psr4.php
    │   ├── autoload_real.php
    │   ├── autoload_static.php
    │   ├── ClassLoader.php
    │   ├── installed.json
    │   ├── installed.php
    │   ├── InstalledVersions.php
    │   ├── LICENSE
    │   ├── package-versions-deprecated
    │   └── platform_check.php
    ├── doctrine
    │   ├── cache
    │   ├── dbal
    │   ├── deprecations
    │   ├── event-manager
    │   ├── inflector
    │   ├── lexer
    │   └── migrations
    ├── dragonmantank
    │   └── cron-expression
    ├── egulias
    │   └── email-validator
    ├── friendsofphp
    │   └── proxy-manager-lts
    ├── guzzlehttp
    │   ├── guzzle
    │   ├── promises
    │   └── psr7
    ├── hellogerard
    │   └── jobby
    ├── illuminate
    │   ├── container
    │   ├── contracts
    │   ├── database
    │   ├── events
    │   ├── pagination
    │   └── support
    ├── intervention
    │   └── image
    ├── jenssegers
    │   └── mongodb
    ├── laminas
    │   ├── laminas-code
    │   ├── laminas-eventmanager
    │   └── laminas-zendframework-bridge
    ├── mongodb
    │   └── mongodb
    ├── monolog
    │   └── monolog
    ├── nesbot
    │   └── carbon
    ├── opis
    │   └── closure
    ├── predis
    │   └── predis
    ├── psr
    │   ├── container
    │   ├── http-message
    │   ├── log
    │   └── simple-cache
    ├── ralouphie
    │   └── getallheaders
    ├── resque
    │   └── php-resque
    ├── swiftmailer
    │   └── swiftmailer
    └── symfony
        ├── console
        ├── filesystem
        ├── mime
        ├── polyfill-ctype
        ├── polyfill-iconv
        ├── polyfill-intl-idn
        ├── polyfill-intl-normalizer
        ├── polyfill-mbstring
        ├── polyfill-php72
        ├── polyfill-php73
        ├── polyfill-php80
        ├── process
        ├── service-contracts
        ├── stopwatch
        ├── translation
        └── translation-contracts

结构分层：Controller => Service => Manager => Dao

Dao: 与数据库交互
Manager：负责数据库对象到业务对象的转换，由于项目较为简单，取消改层
Service：检验输入、控制业务逻辑
Controller：控制器

jobby引入，所有的crontab的定时任务卸载jobby.php文件中
用法：在系统的crontab表中添加对jobby文件的执行任务
'* * * * * cd APPLICATION_PATH/application && php jobby.php 1>> /dev/null 2>&1'
APPLICATION_PATH替换为真实项目地址

migration引入，将所有的数据库迁移行为纳入项目管理中
目录：database 
 