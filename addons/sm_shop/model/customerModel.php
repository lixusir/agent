<?php
namespace sm_shop\model;
use sm_shop\model;
class customerModel extends model{

    public static function login( $name, $password ){

        $sql = "select * from sh_customer where name='". $name
            ."' and password='". $password ."' ";
//        $result = self::$db->query( $sql );
//        return !empty($result->row)?$result->row:false;

        $result = pdo_fetch( $sql );

        return !empty($result) ? $result : false;

    }

    public static function removeCustomerXcx( $openid ){

        $sql = 'delete from sh_customer_xcx where open_id="' . $openid . '" ';
        pdo_query( $sql );
    }

    public static function getCustomerXcx( $openid ){

        $sql = 'select cx.open_id as xcx_open_id, c.* from sh_customer_xcx cx inner join sh_customer c on cx.telephone=c.telephone where cx.open_id="' . $openid . '" ';
        $result = pdo_fetch( $sql );
        return !empty($result) ? $result : false;
    }
    public static function getCustomer( $openid ){

        $sql = 'select * from sh_customer  where open_id="' . $openid . '" ';
        $result = pdo_fetch( $sql );
        return !empty($result) ? $result : false;
    }

    public static function setCustomerXcx( $openid, $phone ){

        $sql = 'insert into sh_customer_xcx set open_id="' . $openid
            . '", telephone="' . $phone . '"';
        return pdo_query( $sql );

    }

    public static function registerByXcx( $openid, $telephone, $referee = 0, $data = [] ){

        global $_W;
//        customerModel::removeCustomerXcx( $openid );
//        self::setCustomerXcx( $openid, $telephone );

        $customer = self::getCustomerByPhone( $telephone );


        if( empty( $customer ) ){
            $date = Date("Y-m-d H:i:s");
            $nickname = !empty( $data['nickName'] ) ?$data['nickName']:'';
            $headUrl = !empty( $data['avatarUrl'] ) ?$data['avatarUrl']:'';
//            $referee = !empty( $data['referee'] ) ? $data['referee'] : 0;
            $info = !empty( $data ) ? json_encode( $data ) :'';


            $sql = "insert into sh_customer set "
                . " uniacid='" . $_W['uniacid']
                . "', referee='" . $referee
                . "', open_id='" . $openid
                . "', telephone='" . $telephone
                . "', `name`='" . $nickname
                . "', headUrl='" . $headUrl
                . "', info='" . $info
                . "', create_time='" . $date
                . "', customer_group_id='1'"
                . " , status=1 ";

            pdo_query( $sql );

            $customer_id = pdo_insertid();

            // todo 添加用户链
            if( $referee ){
                self::setCustomerChain( $referee, $customer_id );
            }


            return $customer_id;
        }else{

            if( !empty( $data ) ){
                self::update( $telephone, $data );
            }


            return $customer['id'];
        }

    }

    public static function update( $telephone, $data ){

        $nickname = !empty( $data['nickName'] ) ?$data['nickName']:'';
        $headUrl = !empty( $data['avatarUrl'] ) ?$data['avatarUrl']:'';
        $info = is_array( $data ) ? json_encode( $data ) : '';

        $sql = " update sh_customer set "
            . " `name`='" . $nickname
            . "', headUrl='" . $headUrl
            . "', info='" . $info
            . "' where telephone='" . $telephone . "'";

        return pdo_query( $sql );
    }

    public static function getCustomerByPhone( $telephone ){

        global $_W;
        $sql = 'select * from sh_customer where '
            . ' telephone="' . $telephone . '"'
            . ' and uniacid="' . $_W['uniacid'] . '"'
        ;

        return pdo_fetch( $sql );

    }

    public static function getCustomerQrcode( $customer_id ){

        global $_W;
        $sql = 'select * from sh_customer_qrcode where '
            . ' customer_id="' . $customer_id . '"'
            . ' and uniacid= ' . $_W['uniacid']
        ;

        return pdo_fetch( $sql );

    }

    public static function setCustomerQrcode( $data ){
        global $_W;

        $sql = 'insert into sh_customer_qrcode set '
            . ' customer_id="' . $data['customer_id'] . '",'
            . ' scene="' . $data['scene'] . '",'
            . ' qrcode_path="' . $data['qrcode_path'] . '",'
            . ' uniacid= ' . $_W['uniacid']
        ;

        pdo_query( $sql );

        return pdo_insertid();
    }

    public static function setCustomerChain(){

    }


}