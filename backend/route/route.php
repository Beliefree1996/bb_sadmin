<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::any('login','index/index/login');
Route::any('logout','index/index/logout');
Route::any('userinfo','index/index/userinfo');
Route::any('uploadexl','index/index/uploadexl');
Route::any('transformexl','index/Api/transformexl');
Route::any('mobilelist','index/index/mobilelist');
Route::any('userlist','index/index/userlist');
Route::any('downchange','index/index/downchange');
Route::any('showchange','index/index/showchange');
Route::any('exltomysql','index/index/exltomysql');
Route::any('delnumpc','index/index/delnumpc');
Route::any('ywuserlist','index/index/ywuserlist');
Route::any('yeuserlist','index/index/yeuserlist');
Route::any('hfuserlist','index/index/hfuserlist');
Route::any('creckpc','index/index/creckpc');
Route::any('lefthf','index/index/lefthf');
Route::any('mobilefp','index/index/mobilefp');
Route::any('chargefp','index/index/chargefp');
Route::any('countdatalist','index/index/countdatalist');
Route::any('exlcountdata','index/Api/exlcountdata');
Route::any('extoexl','index/Api/extoexl');
Route::any('deleteRecord','index/Api/deleteRecord');
Route::any('deleteTest','index/Api/deleteTest');
Route::any('setremarks','index/index/setremarks');
Route::any('voicelist','index/index/voicelist');
Route::any('addvoice','index/index/addvoice');
Route::any('editvoice','index/index/editvoice');
Route::any('delvoice','index/index/delvoice');
Route::any('patterlist','index/index/patterlist');
Route::any('addpatter','index/index/addpatter');
Route::any('editpatter','index/index/editpatter');
Route::any('patterwordlist','index/index/patterwordlist');
Route::any('delPatterWord','index/index/delPatterWord');
Route::any('allvoice','index/index/allvoice');
Route::any('wordlist','index/index/wordlist');
Route::any('addpatterword','index/index/addpatterword');
Route::any('editpatterword','index/index/editpatterword');
Route::any('getPatterWord','index/index/getPatterWord');
Route::any('getAllPatter','index/index/getAllPatter');
Route::any('getCurQueue','index/index/getCurQueue');
Route::any('startCall','index/index/startCall');
Route::any('getInformations','index/index/getInformations');
Route::any('getMenus','index/index/getMenus');
Route::any('editMenu','index/index/editMenu');
Route::any('getMenuer','index/index/getMenuer');
Route::any('sceneList','index/index/sceneList');
Route::any('addScene','index/index/addScene');
Route::any('editScene','index/index/editScene');
Route::any('adminindex','index/index/adminindex');
Route::any('getAllWordBase','index/index/getAllWordBase');
Route::any('getAllScene','index/index/getAllScene');
Route::any('getwordBaseInfo','index/index/getwordBaseInfo');
Route::any('getSceneInfo','index/index/getSceneInfo');
Route::any('getTaskState','index/index/getTaskState');
Route::any('changeOpenState','index/index/changeOpenState');
Route::any('changeCloseState','index/index/changeCloseState');
Route::any('getTaskState','index/index/getTaskState');
Route::any('changeOpenState','index/index/changeOpenState');
Route::any('changeCloseState','index/index/changeCloseState');

//测试话术接口
Route::any('patter1list','index/index/patter1list');
Route::any('addpatter1','index/index/addpatter1');
Route::any('editpatter1','index/index/editpatter1');
Route::any('patter1wordlist','index/index/patter1wordlist');
Route::any('delPatter1Word','index/index/delPatter1Word');
Route::any('addpatter1word','index/index/addpatter1word');
Route::any('getPatter1Info','index/index/getPatter1Info');
Route::any('editpatter1word','index/index/editpatter1word');
Route::any('getPatter1Word','index/index/getPatter1Word');
Route::any('word1list','index/index/word1list');
Route::any('rolemanagelist','index/index/rolemanagelist');
Route::any('allrole','index/index/allrole');
Route::any('changespeechrole','index/index/changespeechrole');

//客户使用统计数据
Route::any('company_analyse','index/index/company_analyse');
Route::any('company_analyse_info','index/index/company_analyse_info');

Route::any('crmListApi','index/CrmApi/crmListApi');
Route::any('getCrmDetails','index/CrmApi/getCrmDetails');
Route::any('approvalApi','index/CrmApi/approvalApi');
Route::any('wordBaseList','index/CrmApi/wordBaseList');
Route::any('wordBaseAdd','index/CrmApi/wordBaseAdd');
Route::any('wordBaseChange','index/CrmApi/wordBaseChange');
Route::any('wordBaseDelete','index/CrmApi/wordBaseDelete');
Route::any('wordListApi','index/CrmApi/wordListApi');
Route::any('wordAdd','index/CrmApi/wordAdd');
Route::any('wordChange','index/CrmApi/wordChange');
Route::any('wordDelete','index/CrmApi/wordDelete');
Route::any('sceneListApi','index/CrmApi/sceneListApi');
Route::any('sceneAdd','index/CrmApi/sceneAdd');
Route::any('sceneChange','index/CrmApi/sceneChange');
Route::any('sceneDelete','index/CrmApi/sceneDelete');
Route::any('sceneKeyList','index/CrmApi/sceneKeyList');
Route::any('sceneKeyAdd','index/CrmApi/sceneKeyAdd');
Route::any('sceneKeyChange','index/CrmApi/sceneKeyChange');
Route::any('sceneKeyDelete','index/CrmApi/sceneKeyDelete');
Route::any('changePatter','index/CrmApi/changePatter');

Route::any('linshi','index/index/linshi');

return [

];
