<?php
namespace Addons\SyncDevData\Model;
use Think\Model;

class SyncDevDataModel extends Model{




    //获取远程数据库配置
    public function config($config)
    {
        if( isset($config['remote_config']) ) {
            $dataBaseConfig = array();
            $confCate = explode(',', $config['remote_config']);
            foreach ($confCate as $key => $value) {
                $conf = explode( ':', trim($value) );
                //dump($conf);
                $dataBaseConfig[$conf[0]] = $conf[1];
            }
            return $dataBaseConfig;
        }
        return null;
    }

    //对比指定数据库的表
    //执行同步
    public function sync($config)
    {
        G('begin');
        $remoteDataBaseConfig = $this->config($config);
        //哪些表需要对比的
        $talbe = array(
            array('t' => 'action', 'f' => 'name'),
            array('t' => 'menu', 'f' => 'url'),
            array('t' => 'config', 'f' => 'name'),
            array('t' => 'category', 'f' => 'name'),
        );
        echo '========= 同步的表有 action, menu, config, category ===================<br/>';
        echo '========= 系统表一般不能随意更改 ===================<br/>';
        echo '========= 如果有数据同步失败，请手动添加 然后导出远程数据库对应的表同步到本地 ===================<br/>';
        
        foreach ($talbe as $key => $value) {
            $localData = M($value['t'])->order('id ASC')->select();
            echo '本地数据表' . $value['t'] . ' 同步至' . $remoteDataBaseConfig['db_host'];
            echo '<br>';
            foreach ($localData as $k => $v) {
                //dump($v);
                $remoteModel = M($value['t'],'ks_', $remoteDataBaseConfig);
                if(!$remoteModel->where(array($value['f'] => $v[$value['f']]))->find()){
                    $syncStatus = $remoteModel->add($v) ? '成功' : '<span style="color:red">失败</span>';
                    echo '状态：' . $syncStatus . '<br>';
                    dump($v);
                }
            }
        }
        
        G('end');
        echo '<h1>执行完毕!</h1>';
        echo '<br/>运行时间:'. G('begin','end',6);
        echo '<br/>消耗内存:'.G('begin','end','m');
    }

}