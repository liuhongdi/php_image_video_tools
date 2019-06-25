<?php
/**
 * Created by PhpStorm.
 * User: liuhongdi
 * Date: 19-6-9
 * Time: 上午9:43
 */

class php_video_file
{
    //construct

    function __construct(){

    }

    /*

    screenshot one frame to image from video
    return true:success
           false:fail
    time_str format:hours:minutes:seconds

    */

    function screenshot_one_image_from_video($video_file,$image_file,$time_str="00:00:00") {

        if (!is_file($video_file)) {

            return array("status"=>false,"error_msg"=>"source video file ".$video_file." not exist");

        }

        $cmd = "/usr/bin/ffmpeg -y -ss ".$time_str." -i ".$video_file." -vframes 1 ".$image_file;
        $ret = shell_exec($cmd." 2>&1");

        if (is_file($image_file)) {

            return array("status"=>true,"error_msg"=>"");

        } else {

            return array("status"=>false,"error_msg"=>"screenshot error");

        }

    }

    /*
     *
     * get length of video file
     *
     * need install ffmpeg
     *
     * param: video_file: full path of video file
     * return: if ==0 ,error,
     *         if > 0 ,success
     *
     *         unit: seconds
     *
     * */
    function get_video_length($video_file)
    {

        $cmd_duration = "/usr/bin/ffmpeg -i ".$video_file." 2>&1 | grep 'Duration' | cut -d ' ' -f 4 | sed s/,// ";
        $ret_duration = shell_exec($cmd_duration." 2>&1");

        if (strpos($ret_duration, ":") === false) {
            return 0;
        }

        $arr_dur = explode(":", $ret_duration);

        if (sizeof($arr_dur) != 3 ) {
            return 0;
        }

        $hours = (int)$arr_dur[0];
        $minutes = (int)$arr_dur[1];
        $seconds = (int)$arr_dur[2];

        $total = $hours*3600+$minutes*60+$seconds;

        return $total;
    }



    /*
     * function: download a video
     * param: file_path:full path of video file
     * down_name: default file name for download
    */

    function download_video($file_path,$down_name){

        if(!is_file($file_path))
        {
            $arr_ret = array("error"=>"1","msg"=>"file ".$file_path." not exist");
            return $arr_ret;
        }

        $file_size=filesize($file_path);

        header( "Pragma: public" );
        header( "Expires: 0" ); // set expiration time
        header( "Cache-Component: must-revalidate, post-check=0, pre-check=0" );
        header( "Content-type:application/download");
        header( "Content-Length: $file_size"  );
        header( "Content-Disposition: attachment; filename=\"video_".$down_name."\"");
        header( 'Content-Transfer-Encoding: binary' );
        readfile( $file_path );

        $arr_ret = array("error"=>"0","msg"=>"");
        return $arr_ret;
    }


}