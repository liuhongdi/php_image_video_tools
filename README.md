# php_image_tools
a php class,to resize/rotate/crop/composite/draw text...,some use ImageMagick
//对图片的操作功能的封装，用的是php语言，其他语言可以参考相应的做法
//php_image_info类的功能包括:

  判断图片是否完整
  得到图片的高宽
  得到图片的类型
  下载图片
  
 //php_image_utils类的功能包括:（说明：调用了ImageMagick,大家使用时也可自行切换为GM）
 
  自动校对方向（因拍照时方向调换）
  加水印
  转换格式为jpg
  图片翻转(flip)
  图片剪切(crop)
  图片旋转(rotate)
  生成方形预览图
  
  按长边/按宽/按高/按指定的宽高 对图片进行缩放
  (用于生成thumb预览图)
  
  组合两张图片
  在图片上指定位置写文字
 
其他常用的功能，也会添加进来
大家有宝贵意见请留言。
