<?php
return array (
  'index' => 
  array (
    'name' => '全局',
    'list' => 
    array (
      'index' => 
      array (
        'act' => 
        array (
          'main' => '桌面',
          'help' => '帮助中心',
          'msg' => '意见反馈',
        ),
        'name' => '首页',
      ),
    ),
  ),
  'sys' => 
  array (
    'name' => '系统',
    'list' => 
    array (
      'config' => 
      array (
        'act' => 
        array (
          0 => 'add',
          1 => 'delete',
          2 => 'edit',
          3 => 'update',
        ),
        'name' => '模块设置',
      ),
      'common' => 
      array (
        'act' => 
        array (
          0 => 'clear_cache',
          1 => 'update_pwd',
        ),
        'name' => '通用',
      ),
      'log' => 
      array (
        'act' => 
        array (
          0 => 'delete',
        ),
        'name' => '系统日志',
      ),
      'user.log' => 
      array (
        'act' => 
        array (
          0 => 'delete',
        ),
        'name' => '管理日志',
      ),
      'verify' => 
      array (
        'act' => 
        array (
        ),
        'name' => '验证记录',
      ),
      'area' => 
      array (
        'act' => 
        array (
          0 => 'move',
          1 => 'save',
          2 => 'delete',
        ),
        'name' => '地区管理',
      ),
      'database' => 
      array (
        'act' => 
        array (
          0 => 'optimize',
          1 => 'repair',
          2 => 'backup',
          3 => 'reback',
          4 => 'del',
        ),
        'name' => '数据库',
      ),
      'user' => 
      array (
        'act' => 
        array (
          0 => 'add',
          1 => 'del',
          2 => 'delete',
          3 => 'edit',
          4 => 'dellist',
          5 => 'state',
          6 => 'clear_config',
		  'repayment' => '增减预付款',
 		  'score' => '增减积分',
        ),
        'name' => '注册用户',
      ),
      'user.group' => 
      array (
        'act' => 
        array (
          0 => 'move',
          1 => 'save',
          2 => 'limit',
          'menu' => '自定义菜单',
          'move' => '移动',
       ),
        'name' => '用户组',
      ),
      'user.depart' => 
      array (
        'act' => 
        array (
          0 => 'move',
          1 => 'save',
        ),
        'name' => '组织架构',
      ),
      'user.action' => 
      array (
        'act' => 
        array (
			'config' => '配置',
        ),
        'name' => '经验积分',
      ),
      'user.repayment' => 
      array (
        'act' => 
        array (
        ),
        'name' => '预付款记录',
      ),
      'user.address' => 
      array (
        'act' => 
        array (
          0 => 'add',
          2 => 'delete',
          3 => 'edit',
        ),
        'name' => '注册用户',
      ),
    ),
  ),
  'other' => 
  array (
    'name' => '组件',
    'list' => 
    array (
      'email' => 
      array (
        'act' => 
        array (
          0 => 'edit',
          1 => 'save',
          2 => 'delete',
          3 => 'send',
        ),
        'name' => '邮件管理',
      ),
      'sms' => 
      array (
        'act' => 
        array (
          0 => 'delete',
        ),
        'name' => '短信发送记录',
      ),
      'sms.re' => 
      array (
        'act' => 
        array (
          0 => 'delete',
        ),
        'name' => '短信回复记录',
      ),
      'ads' => 
      array (
        'act' => 
        array (
          0 => 'edit',
          1 => 'save',
          2 => 'delete',
        ),
        'name' => '广告管理',
      ),
      'msg' => 
      array (
        'act' => 
        array (
          0 => 'save',
          1 => 'delete',
          2 => 'return',
        ),
        'name' => '留言反馈',
      ),
      'pay' => 
      array (
        'act' => 
        array (
          'not' => '未安装页',
          'config' => '配置页',
          'save' => '保存配置',
          'down' => '下载新接口',
          'install' => '安装接口',
          'uninstall' => '卸载',
          'record' => '充值记录',
        ),
        'name' => '支付接口',
      ),
      'uc' => 
      array (
        'act' => 
        array (
          0 => 'save',
        ),
        'name' => 'UCenter',
      ),
      'share' => 
      array (
        'act' => 
        array (
        ),
        'name' => '分享记录',
      ),
    ),
  ),
  'article' => 
  array (
    'name' => '文章',
    'list' => 
    array (
      'article.channel' => 
      array (
        'act' => 
        array (
          0 => 'edit',
          1 => 'save',
          2 => 'state',
          3 => 'delete',
        ),
        'name' => '频道',
      ),
      'article.topic' => 
      array (
        'act' => 
        array (
          'showarticle' => '打开专题',
          1 => 'edit',
          2 => 'save',
          3 => 'state',
          4 => 'delete',
        ),
        'name' => '专题',
      ),
      'article' => 
      array (
        'act' => 
        array (
          'selectfolder' => '取目录列表',
          1 => 'paste_folder',
          2 => 'edit_folder',
          3 => 'save_folder',
          4 => 'del_folder',
          5 => 'delete_folder',
          6 => 'paste_article',
          7 => 'reback_article',
          8 => 'topic',
          9 => 'list',
          10 => 'dellist',
          11 => 'edit_article',
          12 => 'save_article',
          13 => 'state',
          14 => 'del_article',
          15 => 'delete_article',
        ),
        'name' => '文章',
      ),
    ),
  ),

  'report' => 
  array (
    'name' => '统计报表',
    'list' => 
    array (
      'user' => 
      array (
        'act' => 
        array (
        ),
        'name' => '用户统计',
      ),
    ),
  ),
  'act' => 
  array (
    'name' => '营销',
    'list' => 
    array (
      'gift' => 
      array (
        'act' => 
        array (
          0 => 'edit',
          1 => 'save',
          2 => 'delete',
        ),
        'name' => '积分兑换',
      ),
      'gift.record' => 
      array (
        'act' => 
        array (
          'send' => '发货',
        ),
        'name' => '兑换记录',
      ),
      'lottery' => 
      array (
        'act' => 
        array (
          0 => 'edit',
          1 => 'save',
          2 => 'delete',
        ),
        'name' => '抽奖活动',
      ),
      'lottery.record' => 
      array (
        'act' => 
        array (
        ),
        'name' => '抽奖记录',
      ),
      'lottery.log' => 
      array (
        'act' => 
        array (
			'receive' => '领取',
			'invalid' => '作废',
        ),
        'name' => '中奖记录',
      ),
       'voucher' => 
      array (
        'act' => 
        array (
			'create' => '生成',
        ),
        'name' => '优惠券',
      ),
   ),
  ),
  'weixin' => 
  array (
    'name' => '微信',
    'list' => 
    array (
      'message' => 
      array (
        'act' => 
        array (
          'keywords' => '关键词自动回复',
          1 => 'save',
          2 => 'delete',
        ),
        'name' => '消息管理',
      ),
      'user' => 
      array (
        'act' => 
        array (
          'sendmsg' => '发送消息',
        ),
        'name' => '关注者管理',
      ),
      'site' => 
      array (
        'act' => 
        array (
          0 => 'edit',
          1 => 'save',
          2 => 'delete',
        ),
        'name' => '微信站点',
      ),
      'menu' => 
      array (
        'act' => 
        array (
          0 => 'edit',
          1 => 'save',
        ),
        'name' => '自定义菜单',
      ),
      'media' => 
      array (
        'act' => 
        array (
          'upload' => '上传',
        ),
        'name' => '媒体列表',
      ),
    ),
  ),
);