<?php

namespace app\api\controller\v1;

use app\api\validate\IDCollection;

use app\api\model\Theme as ThemeModel;
use app\api\validate\IDMustBePositiveInt;
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

        if($result->isEmpty()){
            throw new ThemeMissException();
        }

        return $result;

    }

    /*
     * 获得专题详情
     * @url /theme/:id
     */
    public function getComplexOne($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $theme = ThemeModel::getThemeWithProducts($id);
        if(!$theme){
            throw new ThemeMissException();
        }
        return $theme;
    }
}
