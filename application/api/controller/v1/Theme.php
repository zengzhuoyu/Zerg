<?php

namespace app\api\controller\v1;

use app\api\validate\IDCollection;

use app\api\model\Theme as ThemeModel;
use app\lib\exception\ThemeMissException;

class Theme
{
    /**
     * @url /theme?ids=id1,id1,id3.......
     * @return 一组theme模型
     */
    public function getSimpleList($ids = '')
    {
        (new IDCollection())->goCheck();
        $ids = explode(',',$ids);

        $result = ThemeModel::with('topicImg,headImg')
            ->select($ids);

        if(!$result){
            throw new ThemeMissException();
        }
        return $result;

    }
}
