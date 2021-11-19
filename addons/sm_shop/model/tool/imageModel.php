<?php
//namespace model\tool;
//
//use library\image;

namespace sm_shop\model\tool;
use sm_shop\model;
use sm_shop\lib\image;

class imageModel {
	public static function resize($filename, $width = 100, $height = 100, $placeholder = true) {

		if (!is_file(ROOT_DIR . $filename)
            || substr(str_replace('\\', '/', realpath(ROOT_DIR . $filename)), 0, strlen(ROOT_DIR))
            != str_replace('\\', '/', ROOT_DIR) ) {
			if ($placeholder) {
				$filename = '/image/placeholder.png';
			} else {
				return;
			}
		}

		$extension = pathinfo($filename, PATHINFO_EXTENSION);

		$image_old = $filename;
		$image_new = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . (int)$width . 'x' . (int)$height . '.' . $extension;

		if (!is_file(DIR_IMAGE . $image_new) || (filemtime(ROOT_DIR . $image_old) > filemtime(DIR_IMAGE . $image_new))) {
			list($width_orig, $height_orig, $image_type) = getimagesize(ROOT_DIR . '/' . $image_old);

			if (!in_array($image_type, array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF))) {
				return ROOT_DIR . $image_old;
			}

			$path = '';

			$directories = explode('/', dirname($image_new));

			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;

				if (!is_dir(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				}
			}

			if ($width_orig != $width || $height_orig != $height) {
				$image = new image(ROOT_DIR . $image_old);
				$image->resize($width, $height);
				$image->save(DIR_IMAGE . $image_new);
			} else {
				copy(ROOT_DIR . $image_old, DIR_IMAGE . $image_new);
			}
		}

		$image_new = str_replace(' ', '%20', $image_new);  // fix bug when attach image on email (gmail.com). it is automatic changing space " " to +

		return WEB_URL . '/image/' . $image_new;
	}

    public static function resize2($filename, $width = 100, $height = 100, $placeholder = true) {

	    global $_W;

        $DIR_IMAGE = IA_ROOT . '/' . $_W['config']['upload']['attachdir'] . '/';

        if (!is_file( $DIR_IMAGE . $filename )
            || substr(str_replace('\\', '/', realpath($DIR_IMAGE . $filename)), 0, strlen($DIR_IMAGE))
            != str_replace('\\', '/', $DIR_IMAGE) ) {
            return tomedia( $filename );
            if ($placeholder) {
                $filename = '/images/global/nopic-small.jpg';
            } else {
                return;
            }
        }

        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $image_old = $filename;

        $image_new = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . (int)$width . 'x' . (int)$height . '.' . $extension;

        if (!is_file($DIR_IMAGE . $image_new) || (filemtime($DIR_IMAGE . $image_old) > filemtime($DIR_IMAGE . $image_new))) {
            list($width_orig, $height_orig, $image_type) = getimagesize($DIR_IMAGE . '/' . $image_old);

            if (!in_array($image_type, array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF))) {
                return $DIR_IMAGE . $image_old;
            }

            $path = '';

            $directories = explode('/', dirname($image_new));

            foreach ($directories as $directory) {
                $path = $path . '/' . $directory;

                if (!is_dir($DIR_IMAGE . $path)) {
                    @mkdir($DIR_IMAGE . $path, 0777);
                }
            }

            if ($width_orig != $width || $height_orig != $height) {
                $image = new image($DIR_IMAGE . $image_old);
                $image->resize($width, $height);
                $image->save($DIR_IMAGE . $image_new);
            } else {
                copy($DIR_IMAGE . $image_old, $DIR_IMAGE . $image_new);
            }
        }

        $image_new = str_replace(' ', '%20', $image_new);  // fix bug when attach image on email (gmail.com). it is automatic changing space " " to +

//        return WEB_URL . '/image/' . $image_new;
        return tomedia( $image_new );
    }

    public static function merge( $bg_img, $merge_img, $x = 0, $y = 0, $opacity = 100 ){

        global $_W;

        $extension = pathinfo($bg_img, PATHINFO_EXTENSION);
        $image_new = ltrim( $bg_img, 'cache/' );
        $image_new = 'cache/' . utf8_substr($image_new, 0, utf8_strrpos($image_new, '.')) . '-poster.' . time() . '.' . $extension;

        $DIR_IMAGE = IA_ROOT . '/' . $_W['config']['upload']['attachdir'] . '/';
        $bg_image = new image($DIR_IMAGE . $bg_img);
        $merge_image = new image($DIR_IMAGE . $merge_img);
        imagecopymerge($bg_image->getImage(), $merge_image->getImage(), $x, $y, 0, 0, $merge_image->getWidth(), $merge_image->getHeight(), 100);
        $bg_image->save( $DIR_IMAGE . $image_new );

//        return $bg_img ;
        return $image_new ;
    }

    public static function text($bg_img, $text, $x = 0, $y = 0, $size = 5, $color = '000000') {

        global $_W;

        $extension = pathinfo($bg_img, PATHINFO_EXTENSION);
//        $image_new = 'cache/' . utf8_substr($bg_img, 0, utf8_strrpos($bg_img, '.')) . '-poster.' . time() . '.' . $extension;
        $image_new = utf8_substr($bg_img, 0, utf8_strrpos($bg_img, '.')) . '-poster-' . time() . '.' . $extension;

        $DIR_IMAGE = IA_ROOT . '/' . $_W['config']['upload']['attachdir'] . '/';

        $bg_image = new image($DIR_IMAGE . $bg_img );

        $bg_image->addText( $text, $x , $y , $size , $color );
        $bg_image->save( $DIR_IMAGE . $image_new );
        return $image_new ;

    }

    public static function download($url, $path )
    {

        global $_W;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        $file = curl_exec($ch);
        curl_close($ch);
//        $filename = pathinfo($url, PATHINFO_BASENAME);
        $filename = time() . '.jpg';
        $image_new = $path . '/' . $filename;
//
        $DIR_IMAGE = IA_ROOT . '/' . $_W['config']['upload']['attachdir'] . '/';

        $resource = fopen( $DIR_IMAGE . $image_new, 'a+');
        fwrite($resource, $file);
        fclose($resource);

        return $image_new;
    }

    public static function is_base64( $base64 ){

        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64, $result)) {

            return 1;

        }
        return 0;
    }

    public static function save_base64( $base64, $file_name ){

        global $_W;
        $base64_image_content = $base64;

        //匹配出图片的格式

        $DIR_IMAGE = IA_ROOT . '/' . $_W['config']['upload']['attachdir'] . '/';

        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {

            $type = $result[2];

            $new_file =  'comment/' . date('Ymd', time()) . "/";
            $new_file = $new_file . ($file_name?$file_name:time()) . ".{$type}";
//            if (!file_exists($DIR_IMAGE .$new_file)) {
//
//                //检查是否有该文件夹，如果没有就创建，并给予最高权限
//                mkdir($DIR_IMAGE .$new_file, 0700);
//
//            }
            $directories = explode('/', dirname( $new_file ));
            $path = '';
            foreach ($directories as $directory) {
                $path = $path . '/' . $directory;
                if (!is_dir($DIR_IMAGE . $path)) {
                    @mkdir($DIR_IMAGE . $path, 0777);

                }
            }




            if (file_put_contents($DIR_IMAGE .$new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                return $new_file;
            }else{
                return 0;
            }
        }
        return 0;
    }

}
