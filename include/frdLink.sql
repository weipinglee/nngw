create table if not exists `dou_frdlink`(
  `id` int(11) unsigned not null auto_increment,
  `name` varchar(20) not null default '',
  `url` varchar(100) not null default '',
  `img` varchar(100) not null default '',
  `status` tinyint(2) not null default 1 comment '状态：1启用 0禁用',
  primary key(`id`)
)engine=innodb charset=utf8;
#插入一条配置信息（微信二维码）
insert into dou_config values('weixin','','file','','main',16);
#添加文章分类：
#名称：视频  别名：shipin
#名称：工程案例 别名：project