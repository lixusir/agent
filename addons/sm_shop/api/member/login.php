<?php


namespace sm_shop\api\member;
use sm_shop\controller;


use sm_shop\model\customerModel;

class login extends controller{

    public function index(){

        $res = [
            'status'=>1,
            'description'=>'登录失败'
        ];
        global $_GPC;
        $name = !empty( $_GPC['name']) ? $_GPC['name'] : '';
        $password = $_GPC['password'] ? $_GPC['password'] : '';
        if( !empty( $name ) && !empty( $password ) ){

            if( $user = customerModel::login( $name, $password ) ){
                $res['status'] = 0;
                $res['description'] = '登录成功';
//                $_SESSION['user']['name'] = $user['name'];
//                $_SESSION['user']['id'] = $user['id'];
                $_SESSION['customer_id'] = $user['id'];
                $res['customer_id'] = $user['id'];
            }
        }else{
            $res['description'] = '账户或者密码不能为空';
        }

        echo json_encode( $res );

    }

    public function index_w7(){

        global $_GPC,$_W;
//        print_r( $_SESSION );
        $res = [
            'status'=>1,
            'description'=>'登录失败'
        ];

        $username = !empty( $_GPC['name']) ? $_GPC['name'] : '';
        $password = $_GPC['password'] ? $_GPC['password'] : '';
        $where['uniacid'] = $_W['uniacid'];

        if (preg_match(REGULAR_MOBILE, $username)) {
            $where['mobile'] = $username;
        } else {
            $where['email'] = $username;
        }
        $user = table('mc_members')
            ->select(array('uid', 'salt', 'password'))
            ->where($where)
            ->get();

        if ( empty( $user ) ) {
            $res['description'] = '该帐号尚未注册';
        }else{
            $hash = md5($password . $user['salt'] . $_W['config']['setting']['authkey']);
            if ($user['password'] != $hash) {
                $res['description'] = '密码错误';
            }else{
                if (_mc_login($user)) {
                    $res['status'] = 0;
                    $res['description'] = '登录成功';
                }else{
                    $res['description'] = '未知错误导致登录失败';
                }
            }
        }
//        print_r( $_SESSION );
        echo json_encode( $res );

    }

    /**
     * 退出登录
     */
    public function logout(){

        $_SESSION['customer_id'] = '';
        $res = [
            'status'=>0,
            'description'=>'您已退出登录',
        ];
        echo json_encode( $res );
    }

    /**
     * 获取登录状态
     */
    public function status(){


        $res= [
            'status'=>0
        ];
        if( !empty($_SESSION['customer_id']) ){
            $res['customer_id'] = $_SESSION['customer_id'];
        }else{
            $res['status'] = 1;
            $res['description'] = '没有登录';
        }

        echo json_encode( $res );
    }

}