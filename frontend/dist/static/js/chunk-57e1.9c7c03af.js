(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-57e1"],{"1v8p":function(t,e,n){},"9lIA":function(t,e,n){"use strict";var r=n("1v8p");n.n(r).a},nokV:function(t,e,n){"use strict";n.r(e);n("X4fA");var r=n("yybV"),a=n("pZP+"),o={name:"DocDetails",components:{tool:a.a},data:function(){return{CountDataList:[],tableLoading:!1,total:0,titledate:"",pickerOptions:{shortcuts:[{text:"今天",onClick:function(t){t.$emit("pick",new Date)}},{text:"昨天",onClick:function(t){var e=new Date;e.setTime(e.getTime()-864e5),t.$emit("pick",e)}},{text:"一周前",onClick:function(t){var e=new Date;e.setTime(e.getTime()-6048e5),t.$emit("pick",e)}}],disabledDate:function(t){return t.getTime()>=Date.now()||t.getTime()<=Date.now()-6912e5}},CallDateTime:""}},created:function(){this.getCountDataList()},methods:{formatDate:function(t){if(t){var e=new Date(t);return a.a.formatDate(e,"yyyy-MM-dd")}return""},timeNow:function(){var t=this.formatDate(this.CallDateTime);if(0==t.length){var e=new Date,n=e.getFullYear(),r=e.getMonth()+1,a=e.getDate();r>=1&&r<=9&&(r="0"+r),a>=0&&a<=9&&(a="0"+a),t=n+"-"+r+"-"+a}this.titledate="白宾机器人数据("+t+")"},getCountDataList:function(){var t=this;this.timeNow();var e=this.formatDate(this.CallDateTime);if(0==e.length){var n=new Date,a=n.getFullYear(),o=n.getMonth()+1,i=n.getDate();o>=1&&o<=9&&(o="0"+o),i>=0&&i<=9&&(i="0"+i),e=a+"-"+o+"-"+i}var u={aid:this.$route.params.id,time:e};this.tableLoading=!0,Object(r.i)(u).then(function(e){t.tableLoading=!1;var n=e.data;"0000"==n.code&&(t.CountDataList=n.data.rows,t.total=n.data.total)}).catch(function(t){})},clickExportTabel:function(){this.timeNow();var t=this.formatDate(this.CallDateTime);if(0==t.length){var e=new Date,n=e.getFullYear(),r=e.getMonth()+1,a=e.getDate();r>=1&&r<=9&&(r="0"+r),a>=0&&a<=9&&(a="0"+a),t=n+"-"+r+"-"+a}window.location.href="https://sai.bbxxjs.com/exlcountdata?aid="+this.$route.params.id+"&time="+t},objectSpanMethod:function(t){t.row,t.column;var e=t.rowIndex,n=t.columnIndex;return 1===n?e%(this.total-1)==0?{rowspan:this.total-1,colspan:1}:{rowspan:0,colspan:0}:e+1===this.total&&0===n?[1,3]:void 0},tableHeaderColor:function(t){t.row,t.column;var e=t.rowIndex;t.columnIndex;if(0===e)return"background-color: #f7f7f7;color: #363636;font-weight: 500;"},cellColor:function(t){t.row,t.column;var e=t.rowIndex;t.columnIndex;if(e+1===this.total)return"background-color: #A4DDA9;color: #D52114;font-weight: 500;"}}},i=(n("9lIA"),n("KHd+")),u=Object(i.a)(o,function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"app-container"},[n("div",{staticClass:"table_view"},[n("el-row",[n("el-col",{attrs:{span:24}},[n("el-date-picker",{attrs:{editable:"false",size:"small",type:"date",placeholder:"选择日期",align:"right","picker-options":t.pickerOptions},model:{value:t.CallDateTime,callback:function(e){t.CallDateTime=e},expression:"CallDateTime"}}),t._v(" "),n("el-button",{staticStyle:{"margin-left":"30px"},attrs:{type:"primary"},on:{click:function(e){t.getCountDataList()}}},[t._v("查询")])],1)],1),t._v(" "),n("el-row",[n("br")]),t._v(" "),n("el-row",[n("el-col",[n("el-button",{attrs:{type:"primary",icon:"el-icon-download"},on:{click:t.clickExportTabel}},[t._v("导出表格")])],1)],1),t._v(" "),n("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.tableLoading,expression:"tableLoading"}],staticClass:"tableListView",staticStyle:{width:"100%"},attrs:{data:t.CountDataList,"max-height":"600","header-cell-style":t.tableHeaderColor,"cell-style":t.cellColor,"span-method":t.objectSpanMethod}},[n("el-table-column",{attrs:{align:"center",label:t.titledate}},[n("el-table-column",{attrs:{align:"center",prop:"id",width:"66",label:"序号",fixed:"left"}}),t._v(" "),n("el-table-column",{attrs:{align:"center",prop:"bumen","min-width":"120",label:"部门"}}),t._v(" "),n("el-table-column",{attrs:{align:"center",prop:"username","min-width":"120",label:"任务名称"}}),t._v(" "),n("el-table-column",{attrs:{align:"center",prop:"callnum","min-width":"120",label:"拨打数"}}),t._v(" "),n("el-table-column",{attrs:{align:"center",prop:"billnum","min-width":"120",label:"接通数"}}),t._v(" "),n("el-table-column",{attrs:{align:"center",prop:"billlv","min-width":"120",label:"接通率"}}),t._v(" "),n("el-table-column",{attrs:{align:"center",prop:"fenpeinum","min-width":"120",label:"分配数"}}),t._v(" "),n("el-table-column",{attrs:{align:"center",prop:"addnum","min-width":"120",label:"成功添加数"}}),t._v(" "),n("el-table-column",{attrs:{align:"center",prop:"addlv","min-width":"120",label:"添加率"}})],1)],1)],1)])},[],!1,null,"1edf18a2",null);u.options.__file="DocDetails.vue";e.default=u.exports},"pZP+":function(t,e,n){"use strict";var r={};function a(t){return("00"+t).substr(t.length)}r.delay=function(){var t=0;return function(e,n){clearTimeout(t),t=setTimeout(e,n)}}(),r.formatDate=function(t,e){/(y+)/.test(e)&&(e=e.replace(RegExp.$1,(t.getFullYear()+"").substr(4-RegExp.$1.length)));var n={"M+":t.getMonth()+1,"d+":t.getDate(),"h+":t.getHours(),"m+":t.getMinutes(),"s+":t.getSeconds()};for(var r in n)if(new RegExp("("+r+")").test(e)){var o=n[r]+"";e=e.replace(RegExp.$1,1===RegExp.$1.length?o:a(o))}return e},r.timer=function(t){var e={toDo:t.toDo||function(){},didStop:t.didStop||function(){},interval:t.interval||1e3,repeats:t.repeats||!0},n=null,r=1,a={clear:function(){clearInterval(n),e.didStop()}};return n=setInterval(function(){e.repeats?e.toDo(a):r>0?(r--,e.toDo(a)):(clearInterval(n),n=null,e.didStop())},e.interval)},r.getPar=function(t,e){var n=document.location.href,r=(n=decodeURI(n)).indexOf(t+"=");if(-1===r)return"";var a=n.slice(t.length+r+1);if(t===e)return a;var o=a.indexOf("&");return-1!==o&&(a=a.slice(0,o)),a},r.getUrlParams=function(t){var e=document.location.href;if(-1!=e.indexOf("?")){var n=e.substr(1).split("?")[1];n=n.split("&");for(var r=0;r<n.length;r++){var a=n[r].split("=")[0],o=n[r].split("=")[1];if(a==t)return o}}return""},r.setCookie=function(t,e,n){var r=new Date;r.setTime(r.getTime()+60*n*60*1e3);r.toUTCString();document.cookie=t+"="+e+"; path=/"},r.delCookie=function(t){var e=new Date;e.setTime(e.getTime()-1);var n=r.getCookie(t);null!==n&&(document.cookie=t+"="+n+";path=/;expires="+e.toUTCString())},r.getCookie=function(t){for(var e=t+"=",n=document.cookie.split(";"),r=0;r<n.length;r++){for(var a=n[r];" "==a.charAt(0);)a=a.substring(1);if(-1!=a.indexOf(e))return decodeURI(a.substring(e.length,a.length))}return""},r.getCurrentCookie=function(t){for(var e=t+"=",n=document.cookie.split(";"),r=0;r<n.length;r++){for(var a=n[r];" "==a.charAt(0);)a=a.substring(1);if(-1!=a.indexOf(e))return decodeURI(a.substring(e.length,a.length))}return""},e.a=r},yybV:function(t,e,n){"use strict";n.d(e,"E",function(){return a}),n.d(e,"x",function(){return o}),n.d(e,"k",function(){return i}),n.d(e,"t",function(){return u}),n.d(e,"D",function(){return l}),n.d(e,"o",function(){return c}),n.d(e,"C",function(){return d}),n.d(e,"j",function(){return s}),n.d(e,"w",function(){return f}),n.d(e,"I",function(){return p}),n.d(e,"H",function(){return m}),n.d(e,"v",function(){return h}),n.d(e,"l",function(){return b}),n.d(e,"y",function(){return g}),n.d(e,"h",function(){return v}),n.d(e,"i",function(){return w}),n.d(e,"B",function(){return D}),n.d(e,"F",function(){return O}),n.d(e,"c",function(){return j}),n.d(e,"r",function(){return k}),n.d(e,"n",function(){return C}),n.d(e,"z",function(){return x}),n.d(e,"a",function(){return T}),n.d(e,"p",function(){return y}),n.d(e,"A",function(){return _}),n.d(e,"m",function(){return I}),n.d(e,"d",function(){return S}),n.d(e,"G",function(){return L}),n.d(e,"b",function(){return A}),n.d(e,"q",function(){return M}),n.d(e,"s",function(){return $}),n.d(e,"u",function(){return E}),n.d(e,"e",function(){return R}),n.d(e,"g",function(){return U}),n.d(e,"f",function(){return P});var r=n("t3Un");function a(t){return Object(r.a)({url:"/userinfo",method:"post",data:t})}function o(t){return Object(r.a)({url:"/mobilelist",method:"post",data:t})}function i(t){return Object(r.a)({url:"/crmListApi",method:"post",data:t})}function u(t){return Object(r.a)({url:"/getCrmDetails",method:"post",data:t})}function l(t){return Object(r.a)({url:"/userlist",method:"post",data:t})}function c(t){return Object(r.a)({url:"/downchange",method:"post",data:t})}function d(t){return Object(r.a)({url:"/showchange",method:"post",data:t})}function s(t){return Object(r.a)({url:"/creckpc",method:"post",data:t})}function f(t){return Object(r.a)({url:"/lefthf",method:"post",data:t})}function p(t){return Object(r.a)({url:"/ywuserlist",method:"post",data:t})}function m(t){return Object(r.a)({url:"/yeuserlist",method:"post",data:t})}function h(t){return Object(r.a)({url:"/hfuserlist",method:"post",data:t})}function b(t){return Object(r.a)({url:"/delnumpc",method:"post",data:t})}function g(t){return Object(r.a)({url:"/mobilefp",method:"post",data:t})}function v(t){return Object(r.a)({url:"/chargefp",method:"post",data:t})}function w(t){return Object(r.a)({url:"/countdatalist",method:"post",data:t})}function D(t){return Object(r.a)({url:"/setremarks",method:"post",data:t})}function O(t){return Object(r.a)({url:"/voicelist",method:"post",data:t})}function j(t){return Object(r.a)({url:"/addvoice",method:"post",data:t})}function k(t){return Object(r.a)({url:"/editvoice",method:"post",data:t})}function C(t){return Object(r.a)({url:"/delvoice",method:"post",data:t})}function x(t){return Object(r.a)({url:"/patterlist",method:"post",data:t})}function T(t){return Object(r.a)({url:"/addpatter",method:"post",data:t})}function y(t){return Object(r.a)({url:"/editpatter",method:"post",data:t})}function _(t){return Object(r.a)({url:"/patterwordlist",method:"post",data:t})}function I(t){return Object(r.a)({url:"/delPatterWord",method:"post",data:t})}function S(t){return Object(r.a)({url:"/allvoice",method:"post",data:t})}function L(t){return Object(r.a)({url:"/wordlist",method:"post",data:t})}function A(t){return Object(r.a)({url:"/addpatterword",method:"post",data:t})}function M(t){return Object(r.a)({url:"/editpatterword",method:"post",data:t})}function $(t){return Object(r.a)({url:"/getPatterWord",method:"post",data:t})}function E(t){return Object(r.a)({url:"/getTaskState",method:"post",data:t})}function R(t){return Object(r.a)({url:"/approvalApi",method:"post",data:t})}function U(t){return Object(r.a)({url:"/changeOpenState",method:"post",data:t})}function P(t){return Object(r.a)({url:"/changeCloseState",method:"post",data:t})}}}]);