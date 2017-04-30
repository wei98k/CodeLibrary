<?php 
   public function bulkSave($data)
    {
        //开启事务
        M()->startTrans();
        //实例对象
        $orderBulkObj        = new OrderBulktradeModel();
        $orderBulkGoodsObj   = new OrderBulktradeGoodsModel();
        $bulkGoodsObj        = new BulkGoodsModel();
        $regionObj           = new RegionLogic();
        //====参数处理=====
        //其它物品
        $goods = $data ['goods'];
        if(array_key_exists('other', $goods)) {
            $otherGoods = array();
            $otherGoods ['amount'] = $goods ['other'];
            if(array_key_exists('other_remark', $data) && !empty($data ['other_remark'])) {
                $otherGoods ['remark'] = $data ['other_remark'];
            }
            $otherGoods = serialize( $otherGoods );
            unset($goods ['other']);
        }
        //总价
        if(is_array($goods)) {
            $bulkGoods = array();
            $total     = 0;
            foreach ($goods as $bulkid => $num) {
                $temData = $bulkGoodsObj->find($bulkid);
                $bulkGoods [$bulkid]['goods_name'] = $temData ['goods_name'];
                $bulkGoods [$bulkid]['num']         = $num;
                $bulkGoods [$bulkid]['goods_unit'] = $temData ['unit'];
                $bulkGoods [$bulkid]['price']       = $temData ['price'];
                $total =+ $bulkGoods [$bulkid]['price'] * $num;
            }
        }
        //地址
        $region = $regionObj->getOpenIdArea($data ['open_id'], 2);
        //文件关联ID
        $file = $data ['ks_file'];
        if(is_array($file)) {
            $file = implode(',', $file);
        }
        //=========数据入库及数据验证======
        //主表数据
        $saveData = array();
        $saveData ['uid']               = $data ['uid'];
        $saveData ['evaluate_price']  = $total;
        $saveData ['order_region_id'] = $data ['open_id'];
        $saveData ['province']         = $region ['province'];
        $saveData ['city']              = $region ['city'];
        $saveData ['area']              = $region ['area'];
        $saveData ['address']          = $data ['address'];
        $saveData ['attachment']       = $file;
        $saveData ['other_goods']     = $otherGoods;
        $res = $orderBulkObj->add($saveData);
        if($res < 0) {
            $message = $orderBulkObj->getMessage($res);//错误消息
            $this->result ['bool']      = false;
            $this->result ['message']   =  $message;
            M()->rollback();
            return $this->result;
        }
        $oid = array('oid' => $res);
        //副表数据
         $saveGoodsData = array();
         $saveGoodsData = $bulkGoods;
         $this->addUnit($saveGoodsData, $oid);
         $allres = $orderBulkGoodsObj->addAll($saveGoodsData);
         if($allres == false) {
             M()->rollback();
             $this->result ['bool']      = false;
             $this->result ['message']   =  '系统错误请重新下单';
             return $this->result;
         }
         M()->commit();
         //======错误消息处理或结果集
         $this->result ['message'] = '成功下单';
         return $this->result;
    }
    /**
     * 为二维数组增加一个单元
     * 描述:主要给addall方法准备的,用于在下单的时候有多个商品,主表生成OID后给副表关联
     * @param  array    &$data 二维数组
     * @param  array    $unit 准备加入数组的单元
     * @return void
     */
    private function addUnit(&$data, $unit)
    {
        if(!is_array($unit) || !is_array($data)) {
            return false;
        }
        $unitKey = key($unit);
        $unitVal = current($unit);
        foreach ($data as $k => $val) {
            $data [$k][$unitKey] = $unitVal;
        }
        $data = array_values($data);
    }