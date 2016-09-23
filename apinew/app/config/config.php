<?php

defined('APP_PATH') || define('APP_PATH', realpath('.'));

return new \Phalcon\Config(array(
    'database' => array(
        'adapter'    => 'Mysql',
        'host'       => 'mysql.mid.host',
        'username'   => 'ranking',
        'password'   => 'ranking_W0rld',
        'dbname'     => 'mid_ranking',
        'charset'    => 'utf8',
    ),


    'mysql_mid_ranking' => array(
        'adapter'    => 'Mysql',
        'host'       => 'mysql.mid.host',
        'username'   => 'ranking',
        'password'   => 'ranking_W0rld',
        'dbname'     => 'mid_ranking',
        'charset'    => 'utf8',
    ),

    'mysql_ranking' => array(
        'adapter'    => 'Mysql',
        'host'       => 'mysql.cms.host',
        'username'   => 'ranking',
        'password'   => 'ranking_W0rld',
        'dbname'     => 'ranking',
        'charset'    => 'utf8',
    ),


    'application' => array(
        'controllersDir' => APP_PATH . '/app/controllers/',
        'modelsDir'      => APP_PATH . '/app/models/',
        'dtoDir'         => APP_PATH . '/app/dtos/',
        'migrationsDir'  => APP_PATH . '/app/migrations/',
        'viewsDir'       => APP_PATH . '/app/views/',
        'pluginsDir'     => APP_PATH . '/app/plugins/',
        'libraryDir'     => APP_PATH . '/app/library/',
        'cacheDir'       => APP_PATH . '/app/cache/',
        'rankingDir'     => APP_PATH . '/lib/',
        'baseUri'        => '/',
    ),

    //配置文件，定义变量 es_server 和，imageUrl   
     // 调用 'http://es.ranking.chinalaw.com:9200/' 的时候，输出 $this->config->es_server
     'es_server' =>'http://es.ranking.chinalaw.com:9200/' ,
      
    'imagesUrl'=>'http://api.ranking.chinalaw.com/images/view/'
));
