yaf项目模板

改造思想：
1. 尽可能使用IDE的代码提示功能
2. 将项目中的常用组件集成
   
##顶级目录与文件
* application 应用主要代码目录，包括library(本地类库)、models(模型)、modules(对外服务，controller目录)、plugins(插件类库)等
* cli               命令行运行入口文件
* config            配置目录 - 将环境文件中的信息固化为配置信息
* database          数据迁移库
* env               环境文件目录，根据不同环境加载不同的文件
* log               本地日志目录
* public            入口文件
* vendor            composer安装类库
* common.php        项目基本定义
* composer.json     composer配置文件
* jobby.php         crontab运行定义文件
* README.md     
* supervisor.ini    supervisor配置文件，用于配合supervisor做常驻监控

## application目录结构
* controllers 默认控制器目录
    * Error.php catchException配置开启后的异常捕捉定义文件
* library 
    * Controllers   Controller类基类
    * Exceptions    自定义异常类目录
    * Extend        对第三方扩展的项目适配
    * ExtInterfaces 第三方接口服务
    * Messages      消息队列
        * Jobs      消息Job类库
        * Workers   消息Worker类库
    * Tools         辅助工具库 - 与框架无关
        * yaf.auto.complete-master 用于IDE的yaf函数自动补全
        * FastdfsHelper.php 用于IDE的fastdfs函数自动补全
        * Helpers 辅助函数与框架无关
    * Trais         Trait类库
        * SingletonTrait.php 单例模式Trait
    * Utils         应用相关工具库
        * Redis     自实现的PSR-16标准的Redis缓存工具
        * NewLog    日志库
* models            model目录，在业务较简单时候可以将Manager与Service合并为Bll层
    * Dao           数据访问层，负责与底层MySQL，Oracle等进行数据交互，数据模型类在此定义
    * Manager       通用业务处理层
    * Service       相对具体的业务逻辑服务层
* modules           对外模块目录
* plugins           插件目录
* Bootstrap.php     应用初始化文件

## 数据迁移组件 - doctrine\migration

database 迁移文件操作哦目录
* migrations 迁移文件目录
* migration  执行文件
* MyAbstractMigraton.php 迁移文件基类，生成的迁移文件会继承该类，以实现功能注入

使用方法：
* php migration migrations:generate --create table_name 
    * 根据传入创建的数据表名称生成迁移文件
    * 如创建issue_main数据表
    * php migration migrations:generate --create issue_main
    * database/migrations/Version20210716115528_create_table_issue_main.php
* php migration migrations:generate --alter action_describe
    * 进行非数据表的创建时候使用此选项，
    * 如在issue_main表中新增name字段，生成迁移文件如下
    * php migration migrations:generate --alter add_name_to_issue_main
    * 生成迁移文件为：database/migrations/Version20210716112914_alter_add_name_to_issue_main.php
    * 或者
    * php migration migrations:generate --alter add_columns_to_issue_main
* php migration migrations:generateModel --class migration_filename_without_suffix
    * 该命令用于根据新建表的迁移文件生成model文件，注意是用于新建
    * 根据传入的无后缀的迁移文件名称在Dao目录中生成对应的Model文件
    * php migration migrations:generateModel --class Version20210716115528_create_table_issue_main
    * 会在Dao下生成Model文件：application/models/Dao/IssueMain.php


## ORM - Eloquent
Eloquent是一款较为优秀的orm组件，laravel框架便深度集成了Eloquent。因此关于Eloquent的教程文档较多
可以直接查看laravel文档 https://laravelacademy.org/post/8194.html 不过需要注意版本

使用Eloquent是为了将对数据库的访问隔离在Dao层，避免在Manager或Service层中直接对数据库的操作导致的重复代码与维护性的降低

与migration的集成：在迁移文件命令 php migration migrations:generateModel 能够自动生成Model文件会自动集成预先写好的Eloquent Model基类，MysqlModel或者MongodbModel，分别对应model类对MySQL、MongoDB的访问

## 消息队列与持久化 - resque+supervisor
使用resque是为了替代cli运行模式，相比较使用popen来运行后台任务或者较为耗时的操作，使用resque做任务队列能够做到对后台任务的运行、重试、失败等做全方面的监控。

resque是基于redis做的消息队列，较为轻量级

supervisor负责监控需要常驻的任务进程，如日志消费、企业微信通知发送等。
在主目录的supervisor.ini文件就可以指定监控任务

## HTTP访问 - guzzlehttp
替换掉对原生Curl的直接使用，避免对curl的学习门槛导致的代码问题。
GuzzleHttp提供了异步http等较为先进的特性。
同时GuzzleHttp支持所有实现了PSR-7标准的请求与响应，也提高了扩展性。

ExtInterface目录下的文件都需要继承AbstractApi，该类实现了对http访问的封装

## crontab周期任务管理 - jobby
将crontab的任务监控纳入到代码跟踪，避免需要操作生产机器以及可能存在的忘记在crontab添加新增或删除过时任务

## 配置分级 env -> config
现有配置分为env环境变量配置与config系统配置

env目录为环境变量目录，依据不同环境进行配置分割，开发、测试环境加载develop.ini文件，生产环境加载product.ini文件
* develop.ini   个人开发用配置，不纳入git版本管理
* sample.ini    配置样例，在下载项目后，复制文件更名为develop.ini
* product.ini   生产环境用配置

config目录下为系统配置文件
* database：数据库配置
* cache：   缓存配置
*constant： 常量配置

新的配置系统将生产配置文件纳入管理，方便生产配置的变更，同时使用文件划分的方式来进行环境分割，尽可能避免误操作

## composer类加载 - psr-7
yaf类很多都需要带默认后缀Model，如models/Bll/Data/Amt.php 中定义的类为 AmtModel
个人不太喜欢，因此使用composer支持的psr-7类加载机制替换掉yaf默认的类加载方法

同时使用composer的files加载选项载入复制函数文件Helper.php

## 错误与异常处理
在Bootstrap文件中将捕捉到的Error转换为Exception抛出后由Error.php文件的errorActio函数统一处理
通过异常code或者所属异常类进行不同的处理

常量的加载是在应用的初始化时候做的
在配置与应用代码之间加入常量目录是为了使用IDE的自动补全功能，避免拼写错误等问题

## 数据库使用配置化
系统集成Eloquent作为ORM框架，该框架支持两种数据库使用模式，一种为Model类继承后的表模块类的使用方式，一种是直接的SQL运行方式

Model继承方式的使用可以参考Eloquent文档

直接的SQL运行方式：\DB::connection()->select($rawSql);
返回结果为stdClass对象数组

## 缓存标准化
PSR-16标准：https://learnku.com/docs/psr/psr-16-simple-cache/1628
