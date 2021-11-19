<?php
namespace sm_shop\admin\api;
use sm_shop\controller;
use sm_shop\model\tool\imageModel;

class huodong extends controller
{

    public function page_list()
    {

        $this->template('marketing/huodong/list');

    }

}