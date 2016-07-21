create table `frdLink`(
  `id` int(11) unsigned not null auto_increment comment '主键id',
  `name` varchar(20) not null default '' comment '名称';
  `url` varchar(100) not null default '' comment '链接地址',
  `img` varchar(100) not null default '' comment '链接图片',
  `status` tinyint(2) not null default 1 comment '状态:1启用 0禁用',
  primary key(`id`)
)engine=innodb charset=utf8;
create table `frdlink`(
  `id` int(11) unsigned not null auto_increment,
  `name` varchar(20) not null default '',
  `url` varchar(100) not null default '',
  `img` varchar(100) not null default '',
  `status` tinyint(2) not null default 1 comment '状态：1启用 0禁用',
  primary key(`id`)
)engine=innodb charset=utf8
create table if not exists `dou_frdlink`(
  `id` int(11) unsigned not null auto_increment,
  `name` varchar(20) not null default '',
  `url` varchar(100) not null default '',
  `img` varchar(100) not null default '',
  `status` tinyint(2) not null default 1 comment '状态：1启用 0禁用',
  primary key(`id`)
)engine=innodb charset=utf8;
create table if not exists `dou_article_project`(
  `id` int(11) unsigned not null auto_increment,
  `title` varchar(100) not null default '',
  `big_img` varchar(50) not null DEFAULT '',
  `small_img` varchar(50) not null default '',
  `add_time`
)engine=mysiam charset=utf8;