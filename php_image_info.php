<?php
/**
 * Created by PhpStorm.
 * User: liuhongdi
 * Date: 19-6-8
 * Time: 下午5:21
 */


class php_image_info
{

    //construct

    function __construct(){

    }

    /*
     *
     * get the image file main color
     *
     * param:file_path  full path of image file
     *
     * return :rgb color:such as :226,56,59
     *
     * comment:need install ImageMagick
     *
     * */
    function get_image_main_color($file_path){

        $cmdtmb = '/usr/bin/convert convert '.$file_path.' -resize 1x1\! -format \"%[fx:int(255*r+.5)],%[fx:int(255*g+.5)],%[fx:int(255*b+.5)]\" info:- 2>&1';
        $rettmb = shell_exec($cmdtmb);
        return $rettmb;

    }


    /*
    //check jpg/jpeg png gif is valid
    param:file_path  full path of image file
    return:bool
    */

    function is_a_valid_image($file_path) {

        if(!is_file($file_path)) {

           return false;
        }


            $source = file_get_contents($file_path);
            switch(bin2hex(substr($source,0,2)))
            {
                case 'ffd8' : return 'ffd9' === bin2hex(substr($source,-2));
                case '8950' : return '6082' === bin2hex(substr($source,-2));
                case '4749' : return '003b' === bin2hex(substr($source,-2));
                default : return false;
            }

    }

    /*
        get the width height for image file
        param: file: full path of image file
        return: array
        comment:need install ImageMagick
    */
    function get_image_width_height($file){

        $arr_img_size = array();
        $imgsize = GetImageSize($file);

        if (isset($imgsize[0]) && $imgsize[0]>0) {

            $arr_img_size['width'] = $imgsize[0];
            $arr_img_size['height'] = $imgsize[1];

        } else {

            $cmdiden="/usr/bin/identify '".$file."'";
            $retiden=shell_exec($cmdiden." 2>&1");
            $idenarr=explode(" ",$retiden);
            if (isset($idenarr['2']) && (strpos($idenarr['2'],"x")!==false)) {
                $arrsize=explode("x",$idenarr['2']);

                $arr_img_size['width'] = $arrsize[0];
                $arr_img_size['height'] = $arrsize[1];
            } else {
                $arr_img_size['width'] = "0";
                $arr_img_size['height'] = "0";
            }

        }

       return $arr_img_size;
    }

    /*
     * function: get the origin file type
     * param: file_path: full image file path
     * return : origin type of  file
    */

    function get_image_type($file_path){

        $origarr=explode("/",$file_path);
        $filename=$origarr[sizeof($origarr)-1];
        $arrtype=explode(".",$filename);

        $last_idx = sizeof($arrtype)-1;
        if (isset($arrtype[$last_idx])) {
            $origtype=strtolower($arrtype[$last_idx]);
        }else {
            $origtype="unknown";
        }

        return $origtype;
    }

    /*
     * function: download a image
     * param: file_path:full path of image file
     * down_name: default file name for download
    */

    function download_image($file_path,$down_name){

        if(!is_file($file_path))
        {
            $arr_ret = array("error"=>"1","msg"=>"file ".$file_path." not exist");
            return $arr_ret;
        }

        $file_size=filesize($file_path);

        header("Pragma: no-cache");
        header("Expires: -1" ); // set expiration time
        header( "Cache-Component: must-revalidate, post-check=0, pre-check=0" );
        header( "Cache-Control: no-cache, must-revalidate");
        header( "Content-type:application/download");
        header( "Content-Length: $file_size"  );
        header( "Content-Disposition: attachment; filename=\"image_".$down_name."\"");
        header( "Content-Transfer-Encoding: binary" );
        readfile( $file_path );

        $arr_ret = array("error"=>"0","msg"=>"");
        return $arr_ret;
    }

}
?>
