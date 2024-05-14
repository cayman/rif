<?php
define('UTF','UTF-8');
define('WIN','WINDOWS-1251');
define('LAQUO',"&#171;");
define('RAQUO',"&#187;");

define('REG_NAME','/^[\p{L}\p{N}\p{Pd}\p{Cyrillic}\s@_:]+$/u');//Для Имен и Заголовков
define('REG_TEXT','/^[\p{L}\p{N}\p{P}\p{Sm}\p{Sc}\p{Cyrillic}\s*]+$/u');
define('REG_WORDS','/^[,\p{Cyrillic}\p{Pd}\s]+$/u'); //Для Слов

define('HEAD_CLASS_ID','1');
define('LEVEL_CLASS_ID','2');
define('TAG_CLASS_ID','4');
define('RANK_TERM_ID','50');
define('CAPTURE_TERM_ID','80');
define('AUTHOR_NODE_ID','1');
define('VERSE_TYPE_ID','1');


define('SPACE',' ');
define('ACTION','action');
define('ACTIVE','active');
define('ACCESS','access');
define('ALL','all');
define('AUTOCOMPLETE','autocomplete');
define('ADMIN','admin');
define('AUTH','auth');
define('AUTHOR','author');
define('BLOCK','block');
define('CODE','code');
define('CALL','call');
define('CONTROLLER','controller');
define('COMMENTS','comments');
define('COMMENT','comment');
define('CLASSIFIER','classifier');
define('DATE','date');
define('DESC','description');
define('ERROR','error');
define('EDITOR','editor');
define('FIND','find');
define('FOOTER','footer');
define('FORM','form');
define('GUEST','guest');
define('HEADS','heads');
define('HEAD','head');
define('HIDE','hide');
define('HTTP_REFERER','HTTP_REFERER');
define('ID','id');

define('KEY','key');
define('KEYS','keys');
define('LABEL','label');
define('LEVEL','level');
define('LEVELS','levels');


define('LOGIN','login');
define('MAIN','main');
define('MENU','menu');
define('MULTIPLE','multiple');
define('NAME','name');
define('NAVIGATION','navigation');
define('NODE','node');
define('NODES','nodes');
define('ORDER','order');
define('PAGE','page');
define('PARAM','param');
define('PARAMS','params');
define('PASSWORD','password');

define('RANK','rank');
define('RANKS','ranks');
define('READER','reader');
define('RELATION','relation');
define('ROUTE','route');
define('ROLE','role');
define('SEARCH','search');
define('SERVICE','service');
define('SITEMAP','sitemap');
define('SOURCES','sources');
define('SOURCE','source');
define('SORT','sort');
define('TAXONOMY','taxonomy');
define('TAGS','tags');
define('TAG','tag');
define('TASK','task');
define('TEXT','text');
define('TERM','term');
define('TERMS','terms');
define('TITLE','title');
define('TYPE','type');
define('VERSE','verse');
define('VALUE','value');
define('VISIBILITY','visibility');
define('USER','user');
define('WORD','word');
define('WORDS','words');
define('WSDL','wsdl');

define('_OPEN','open');
define('_LIST','list');
define('_HEAD','head');
define('_ADD','add');
define('_COPY','copy');
define('_EDIT','edit');
define('_DELETE','delete');
define('_UP','up');
define('_DOWN','down');
define('_LOGIN','login');
define('_LOGOUT','logout');
define('_INFO','info');
define('_PRINT','print');


define('CACHE_CORE','cache_core');

define('SITE_ACL','Site_Acl'); //Registry ACL
define('SITE_HEADS','Site_Heads'); //Registry Heads array
define('SITE_TAGS','Site_Tags'); //Registry Tags array

define('SITE_SOURCES','Site_Source'); //Registry Verse Sources array
define('SITE_ORDERS','Site_Order'); //Registry Verse order items array
define('SITE_SEARCH','Site_Search'); //Session verse search
define('SITE_FORM','Site_Form');    //Session verse form
define('SITE_HISTORY','Site_History'); //Session navigation history


define('SITE_SELECT','Site_Select'); //Session verse select params
define('SITE_ACCESS','Site_Access'); //Registry Verse Levels array
define('SITE_RANK','Site_Rank'); //Session verse user rank
define('SITE_REGIONS','Site_Regions'); //Session verse user rank