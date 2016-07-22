create table if not exists `dou_frdlink`(
  `id` int(11) unsigned not null auto_increment,
  `name` varchar(20) not null default '',
  `url` varchar(100) not null default '',
  `img` varchar(100) not null default '',
  `status` tinyint(2) not null default 1 comment '状态：1启用 0禁用',
  primary key(`id`)
)engine=innodb charset=utf8;
insert into dou_config values('weixin','','file','','main',16);