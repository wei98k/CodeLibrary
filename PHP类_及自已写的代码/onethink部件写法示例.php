<?php

//----------------组件方法位置  Home/Widget

    /**
     * 猜你喜欢组件
     * @param str $uid 用户id
     * @author mjw | date:2015/10/16
     */
    public function maybeLike($uid)
    {
        $memberModel = D('member');
        $projectModel = D('project');

        //获取猜你喜欢数据
        $projectArr = $memberModel->getSimilarity($uid);
        $projectStr = arr2str($projectArr,',');
        $field = array('id','uid','project_name','director','create_time','pic_id','project_recommend');
        $projectMap = array('project_recommend'=>'1');
        $maybeLike = $projectModel->getProject($projectStr,1,$field,$projectMap,'hits DESC','5');
    

        $this->assign('maybeLike',$maybeLike);
        $this->display('Member:maybeLikeGroup');//Member模板目录下的maybeLikeGroup模板文件
    }

//----------------组件模板
/*
<ul>
<foreach name="maybeLike" item="v">
    <li>
        <dl>
            <dt><a href="{:U('Project/projectDetail','projectId='.$v['id'])}"><img src="<empty name="v.pic_id">__IMG__/xm_img01.jpg<else /> {:get_picture($v['pic_id'],'path')}</empty>" width="80" /></a></dt>
            <dd>
                <p><span style="margin-left:-9px;" class="font16 color l"><a href="{:U('Project/projectDetail','projectId='.$v['id'])}">《{$v.project_name}》</a></span><span style="line-height:30px;" class="r font12 color">角色数：<font class=" color3">{$v.role_num}</font></span></p>
                <p class="font12 color">导演：{$v.director}</p>
                <p class="font12 color2">发布日期：{$v.create_time|time_format='Y-m-d'}</p>
            </dd>
        </dl>
    </li>
</foreach>
</ul>
*/

//----------------组件调用：

//{:W('Audition/maybeLike',array($artistUid))} //传参数