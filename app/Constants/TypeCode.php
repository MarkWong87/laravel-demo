<?php
namespace App\Constants;
class TypeCode
{
    const TYPE_BANNER = 1;           //1:代表banner
    const TYPE_BUOY = 2;             //2:代表浮标
    const TYPE_DEFAULT_WORD = 3;     //2:代表搜索默认词

    const TYPE_NOT_DELETE = 0;       //不删除
    const TYPE_IS_DELETE = 1;        //已经删除

    const TYPE_DISPLAY_STATUS = 1;   //表示显示
    const TYPE_HIDE_STATUS = 0;      //表示隐藏

    const TYPE_A_DISPLAY_STATUS = 0;  //表示显示
    const TYPE_A_HIDE_STATUS = 1;     //表示隐藏

    const TYPE_B_DISPLAY_STATUS = 1;  //表示启用
    const TYPE_B_HIDE_STATUS = 0;     //表示停用
    const TYPE_B_STATUS = 2;          //表示停用并隐藏

    const TYPE_C_DISPLAY_STATUS = 0;  //表示启用
    const TYPE_C_HIDE_STATUS = 1;     //表示停用
    const TYPE_C_STATUS = 2;          //表示停用并隐藏

    const TYPE_HOTWORD = 1;          //热门搜索词
    const TYPE_SEATEXT = 2;          //默认文本内容
    const TYPE_BANNERS = 3;          //默认轮播图
    const TYPE_NAVIGATIONS = 4;      //获取导航
    const TYPE_PENDANT = 5;          //获取挂件
    const TYPE_GLOBAL = 6;           //获取头部所有内容聚合

    const TYPE_HOTWORD_TYPE = ['WEB','H5','MOBILE'];
    const TYPE_RESOURCE = 'mysql';
}