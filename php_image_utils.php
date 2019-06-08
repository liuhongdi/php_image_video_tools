<?php
/**
 * Created by PhpStorm.
 * php modify image utils
 * User: liuhongdi
 * Date: 19-6-8
 * Time: 下午5:02
 */
class php_image_utils
{
    //convert... command location
    //this class need install ImageMagick

    var $ImageMagick_DIR = "/usr/bin";

    function __construct(){
        //$this->ImageMagick_DIR = "/usr/bin";
    }


    /*
     * //auto fix the orient of photo
     * origpic:origin image  file
     * dest_pic:destination image file
     * return:if =="",is success
     * */


    function auto_orient($origpic,$destpic) {

        $cmdtmb = $this->ImageMagick_DIR."/convert -auto-orient " . $origpic . " " . $destpic . " 2>&1";
        //echo "cmd auto_orient:".$cmdtmb.":\n";
        $rettmb = shell_exec($cmdtmb);
        return $rettmb;
    }

    /*
    * add a water mark to image
    * origpic:origin image  file
    * water_mark_pic:the watermake image file
    * return:if =="",is success
    * */


    function add_warter_mark($orig_pic,$water_mark_pic)
    {
        $cmdtmb=$this->ImageMagick_DIR."/composite -gravity Center -compose plus ".$water_mark_pic." ".$orig_pic." ".$orig_pic." 2>&1";
        $rettmb=shell_exec($cmdtmb);
        return $rettmb;
    }

    /*
    * convert other image format to jpg
    * origpic:origin image  file
    * dest_pic:destination image file
    * return:if =="",is success
    * */

    function convert_to_jpg($origpic,$destpic)
    {
        $cmdtmb=$this->ImageMagick_DIR."/convert ".$origpic." ".$destpic." 2>&1";
        $rettmb=shell_exec($cmdtmb);
        return $rettmb;
    }

    /*
     * flip a image file
     * origpic:origin image  file
     * dest_pic:destination image file
     * $orient: orient: horizon or vertical
     * return:if =="",is success
     */

    function flip_pic_by_orient($origpic,$destpic,$orient)
    {
        if ($orient == 'horizon')
        {
            $op = '-flop';
        }
        else if ($orient == 'vertical')
        {
            $op = '-flip';
        }
        else
        {
            $op = '';
        }

        if ($op != '')
        {
            $cmdtmb=$this->ImageMagick_DIR."/convert ".$op." ".$origpic." ".$destpic." 2>&1";
            $rettmb=shell_exec($cmdtmb);
            return $rettmb;
        } else {
            return "orient is not horizon or vertical";
        }

    }

    /*
     * crop_pic_by_rect
     * origpic:origin image  file
     * dest_pic:destination image file
     * $x,$y,$w,$h: x point,y point,width,height
     * return:if =="",is success
     */

    function crop_pic_by_rect($origpic,$destpic,$x,$y,$w,$h)
    {
        $cmdtmb=$this->ImageMagick_DIR."/convert -crop ".$w."x".$h."+".$x."+".$y." ".$origpic." ".$destpic." 2>&1";
        $rettmb=shell_exec($cmdtmb);
        // convert -crop 300x400+10+10 src.jpg dest.jpg 从src.jpg坐标为x:10 y:10截取300x400的图片存为dest.jpg
        return $rettmb;
    }

    /*
     * rotate a image by degree;
     * origpic:origin image  file
     * dest_pic:destination image file
     * $degree: the degree of rotation
     * return:if =="",is success
     */

    function rotate_pic_by_degree($origpic,$destpic,$degree)
    {
        //  convert -rotate +90 1.jpg 1.jpg
        $cmdtmb=$this->ImageMagick_DIR."/convert -rotate ".$degree." ".$origpic." ".$destpic." 2>&1";

        //echo "cmdtmb:".$cmdtmb."\n";
        $rettmb=shell_exec($cmdtmb);

        return $rettmb;
    }

    /*
     * crop a image to square image
     * origpic:origin image  file
     * dest_pic:destination image file
     * $size: destination square image width or height
     * return:if =="",is success
     */
    function crop_pic_to_square($origpic,$destpic,$size)
    {
        $i_f = new php_image_info();
        $arr_size = $i_f->get_image_width_height($origpic);
        $width = $arr_size['width'];
        $height = $arr_size['height'];

        if ($width > $height)
        {
            $max = $width;
            $crop=($height*$max)/$width;
            $left=($max-$crop)/2;
            $top=0;
            $syssqu=$this->ImageMagick_DIR."/convert -crop ".$crop."x".$crop."+".$left."+".$top." ".$origpic." ".$destpic." 2>&1";
        }
        else if ($height > $width)
        {
            $max = $height;
            $crop=($width*$max)/$height;
            $left=0;
            $top=($max-$crop)/2;
            $syssqu=$this->ImageMagick_DIR."/convert -crop ".$crop."x".$crop."+".$left."+".$top." ".$origpic." ".$destpic." 2>&1";
        }
        else
        {
            $syssqu=$this->ImageMagick_DIR."/convert -size ".$size."x".$size." -resize ".$size."x".$size." ".$origpic." ".$destpic." 2>&1";
        }

        $retrs5=shell_exec($syssqu);
        //if ()
        if ($width<>$height) {
            $syssiz=$this->ImageMagick_DIR."/mogrify -size ".$size."x".$size." -resize ".$size."x".$size." ".$destpic."  2>&1";
            $retrs6=shell_exec($syssiz);
            return $retrs6;
        }
    }


    /*
     * resize a image by long side
     * origpic:origin image  file
     * dest_pic:destination image file
     * $size: destination image long side length;
     * return:if =="",is success
     */
    function resize_image_by_long_side($origpic,$destpic,$size)
    {
        $cmdtmb=$this->ImageMagick_DIR."/convert -size ".$size."x".$size.
            " -resize ".$size."x".$size." +profile '*' ".$origpic." ".$destpic." 2>&1";
        //echo "cmdtmb:".$cmdtmb."\n";
        $rettmb=shell_exec($cmdtmb);

        return $rettmb;
    }

    /*
     * resize a image by width and height
     * origpic:origin image  file
     * dest_pic:destination image file
     * $width: destination image long width;
     * $height: destination image height;
     * return:if =="",is success
     */

    function resize_image_by_width_and_height($origpic,$destpic,$width,$height)
    {
        $cmdtmb=$this->ImageMagick_DIR."/convert -size ".$width."x".$height."! -resize ".$width."x".$height."! +profile '*' ".$origpic." ".$destpic." 2>&1";
        //echo "cmdtmb:".$cmdtmb."\n";
        $rettmb=shell_exec($cmdtmb);
        return $rettmb;
    }

    /*
     * resize a image by height
     * origpic:origin image  file
     * dest_pic:destination image file
     * $height: destination image height;
     * return:if =="",is success
     */

    function resize_image_by_height($origpic,$destpic,$height)
    {
        $cmdtmb=$this->ImageMagick_DIR."/convert -resize x".$height." +profile '*' ".$origpic." ".$destpic." 2>&1";
        //echo "cmdtmb:".$cmdtmb."\n";
        $rettmb=shell_exec($cmdtmb);

        return $rettmb;
    }

    /*
     * resize a image by width
     * origpic:origin image  file
     * dest_pic:destination image file
     * $width: destination image width;
     * return:if =="",is success
     */

    function resize_image_by_width($origpic,$destpic,$width)
    {

        $cmdtmb=$this->ImageMagick_DIR."/convert -resize ".$width." +profile '*' ".$origpic." ".$destpic." 2>&1";
        //echo "cmdtmb:".$cmdtmb."\n";
        $rettmb=shell_exec($cmdtmb);

        return $rettmb;
    }


    /*
     * composite two images by left and top
     * orig_pic:origin image  file
     * dest_pic:destination image file
     * background_pic: the background image file
     * left: x point
     * top: y point
     * return:if =="",is success
     */


    function composite_two_pic_by_left_and_top($orig_pic,$background_pic,$dest_pic,$left,$top)
    {
        $cmd_composite = $this->ImageMagick_DIR."/composite -geometry +".$left."+".$top." ".$orig_pic." ".$background_pic." ".$dest_pic."  2>&1";
        //echo $cmd_composite."<br/>";
        $ret_composite=shell_exec($cmd_composite);
        return $ret_composite;
    }

    /*
     * draw text on a imgage file
     * orig_pic:origin image  file
     * dest_pic:destination image file
     * text: the text to draw
     * left: draw text x point
     * top: draw text y point
     * return:if =="",is success
     * */
    function draw_text_on_pic_by_left_and_top($orig_pic,$dest_pic,$text,$left,$top)
    {
        $cmd_draw_text=$this->ImageMagick_DIR."/convert ".$orig_pic." -fill black -pointsize 16 -draw \"text ".$left.",".$top." '".$text."'\" ".$dest_pic."  2>&1";
        //echo $cmd_draw_text."<br/>";
        $ret_draw_text=shell_exec($cmd_draw_text);
        return $ret_draw_text;
    }

    /*
    function horizon_join_multi_pic_by_arr($arr_pic,$dest_pic)
    {
        $str_file = implode(" ",$arr_pic);
        $cmd_horizon = $this->ImageMagick_DIR."/convert +append ".$str_file." ".$dest_pic." 2>&1";
        //echo $cmd_horizon."<br/>";
        $ret_horizon=shell_exec($cmd_horizon);
    }

    function vertical_join_multi_pic_by_arr($arr_pic,$dest_pic)
    {
        $str_file = implode(" ",$arr_pic);
        $cmd_vertical = $this->ImageMagick_DIR."/convert -append ".$str_file." ".$dest_pic." 2>&1";
        //echo $cmd_vertical."<br/>";
        $ret_vertical=shell_exec($cmd_vertical);
    }

    function horizon_join_multi_pic_by_str($str_pic,$dest_pic)
    {
        //$str_file = implode(" ",$arr_pic);
        $cmd_horizon = $this->ImageMagick_DIR."/convert +append ".$str_pic." ".$dest_pic." 2>&1";
        //echo $cmd_horizon."<br/>";
        $ret_horizon=shell_exec($cmd_horizon);
    }

    function vertical_join_multi_pic_by_str($str_pic,$dest_pic)
    {
        //$str_file = implode(" ",$arr_pic);
        $cmd_vertical = $this->ImageMagick_DIR."/convert -append ".$str_pic." ".$dest_pic." 2>&1";
        //echo $cmd_vertical."<br/>";
        $ret_vertical=shell_exec($cmd_vertical);
    }
    */

}