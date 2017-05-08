<?php

namespace Addons\SyncDevData;
use Common\Controller\Addon;

/**
 * 同步开发系统数据插件
 * @author 鬼谷子
 */

    class SyncDevDataAddon extends Addon{

        public $info = array(
            'name'=>'SyncDevData',
            'title'=>'同步开发系统数据',
            'description'=>'同步开发的系统级数据',
            'status'=>1,
            'author'=>'鬼谷子',
            'version'=>'0.1'
        );

        public function install(){
            return true;
        }

        public function uninstall(){
            return true;
        }

        //实现的syncDevData钩子方法
        public function syncDevData($param){
            $model  = D('Addons://SyncDevData/SyncDevData');
            $config = $this->getConfig();
            echo '<h1>执行中...</h1>';
            $model->sync($config);
            
            //$this->display('index');
        }


        // 读两个库

    }