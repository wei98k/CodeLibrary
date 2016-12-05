<?php
/**
 * 检测用户只允许英文大小写_中文不能包含空格
 * @author 七秒の记忆 <Majw998@gmail.com>
 * @datetime 2016-12-05T14:50:34+0800
 * @param    string                   $username 用户名
 * @return   bool                               符合 true 不符合 false
 */
protected function checkCharacter($username) {
    if (preg_match('/^[0-9a-zA-Z_\x{4e00}-\x{9fa5}]+$/u',$username)){
        return true;
    }else {
        return false;
    }
}