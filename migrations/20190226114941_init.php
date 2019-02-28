<?php

use Phpmig\Migration\Migration;

class Init extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $this->getContainer()['db']->exec( "
            CREATE TABLE `a_setting` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `type` varchar(100) DEFAULT NULL,
                `val` text,
                `create_time` datetime DEFAULT NULL,
                `update_time` datetime DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='设置表';

            CREATE TABLE `a_admin` (
                `adminid` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
                `username` varchar(40) NOT NULL DEFAULT '' COMMENT '用户名',
                `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
                `name` varchar(40) NOT NULL DEFAULT '' COMMENT '姓名',
                `code` varchar(20) DEFAULT NULL COMMENT '编号',
                `del` tinyint(1) DEFAULT '0',
                `remark` varchar(255) DEFAULT NULL,
                `role` tinyint(1) DEFAULT '1',
                `create_time` datetime DEFAULT NULL,
                `update_time` datetime DEFAULT NULL,
                PRIMARY KEY (`adminid`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员表';

            CREATE TABLE `a_order` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `admin_id` int(11) DEFAULT NULL COMMENT '管理员id',
                `village` varchar(100) DEFAULT NULL COMMENT '小区',
                `name` varchar(100) DEFAULT NULL COMMENT '姓名',
                `phone` varchar(20) DEFAULT NULL COMMENT '手机号',
                `address` varchar(100) DEFAULT NULL COMMENT '地址',
                `amount` varchar(20) DEFAULT NULL COMMENT '订金',
                `date` varchar(20) DEFAULT NULL COMMENT '测量日期',
                `content` text COMMENT '测量内容',
                `scqg` int(11) DEFAULT NULL COMMENT '石材切割',
                `dcmb` int(11) DEFAULT NULL COMMENT '单层磨边',
                `scmb` int(11) DEFAULT NULL COMMENT '双层磨边',
                `gjjg` int(11) DEFAULT NULL COMMENT '钢筋加固',
                `create_time` datetime DEFAULT NULL,
                `update_time` datetime DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

            INSERT INTO `a_admin` (`adminid`, `username`, `password`, `name`, `code`, `del`, `remark`, `role`, `create_time`, `update_time`) VALUES (1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', '管理员', '001', 0, '', 0, '2018-12-01 00:00:00', '2018-12-01 00:00:00');
        ");
    }

    /**
     * Undo the migration
     */
    public function down()
    {

    }
}
