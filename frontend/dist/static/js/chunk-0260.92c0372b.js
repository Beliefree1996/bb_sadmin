(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-0260"],{Frd8:function(t,e,n){"use strict";n.r(e);var i=n("X4fA"),a=n("yybV"),r={name:"TwDistribute",data:function(){return{table_id:0,table_name:"",table_snum:0,table_znum:0,distribute_dialog:!1,table_list:[],tableLoading:!1,filters:{name:""},disnum:null,diszid:"",multipleSelection:[],isDistributing:!1,token:""}},created:function(){this.table_id=this.$route.params&&this.$route.params.id,this.token=Object(i.a)(),this.getTableInfo(),this.getTable()},methods:{getTable:function(){var t=this,e={token:this.token,name:this.filters.name};this.tableLoading=!0,Object(a.I)(e).then(function(e){var n=e.data;"0000"==n.code?t.table_list=n.data.rows:t.$message.error(n.msg),t.tableLoading=!1}).catch(function(e){console.log(e),t.tableLoading=!1,t.$message.error("网络错误")})},getTableInfo:function(){var t=this,e={token:t.token,id:t.table_id};Object(a.j)(e).then(function(e){var n=e.data;"0000"==n.code&&(t.table_id=n.data.id,t.table_name=n.data.name,t.table_snum=n.data.snum)})},handleSelectionChange:function(t){this.multipleSelection=t},distribute_phone:function(t,e){this.distribute_dialog=!0,this.diszid=e.id.toString()},groupDistribute_phone:function(){this.distribute_dialog=!0,this.diszid="";for(var t=0;t<this.multipleSelection.length;t++)t!=this.multipleSelection.length-1?this.diszid+=this.multipleSelection[t].id.toString()+",":this.diszid+=this.multipleSelection[t].id.toString()},distribute:function(){var t=this;if(this.isBatch){if(parseInt(this.disnum)*this.multipleSelection.length>this.table_snum)return void t.$message.error("请正确输入分配数量！")}else if(parseInt(this.disnum)>this.table_snum)return void t.$message.error("请正确输入分配数量！");t.isDistributing=!0;var e={token:t.token,aidlist:this.diszid,pid:this.table_id,num:this.disnum};Object(a.y)(e).then(function(e){if(t.isDistributing=!0,"0000"==e.data.code){t.distribute_dialog=!1;var n=t.$createElement;t.$notify({title:"分配成功",message:n("i",{style:"color: teal"},"号码分配成功")}),t.disnum=null,t.isDistributing=!1,t.getTableInfo(),t.getTable()}else t.$alert("号码分配失败，请重试","分配失败",{confirmButtonText:"确定"}),t.isDistributing=!1}).catch(function(e){t.isDistributing=!0,t.$message.error("网络加载失败！")})}},computed:{isBatch:function(){return this.diszid.toString().indexOf(",")>=0}}},o=(n("IUdK"),n("KHd+")),u=Object(o.a)(r,function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"app-container"},[n("div",{staticClass:"filter-container"},[n("el-col",{staticClass:"table_info",attrs:{span:16}},[n("span",{staticClass:"info_item"},[t._v("号码表名称："+t._s(t.table_name))]),t._v(" "),n("span",{staticClass:"info_item"},[t._v("剩余："+t._s(t.table_snum))])]),t._v(" "),n("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.tableLoading,expression:"tableLoading"}],staticClass:"auto_table",staticStyle:{width:"100%"},attrs:{data:t.table_list,height:"100%",border:""},on:{"selection-change":t.handleSelectionChange}},[n("el-table-column",{attrs:{align:"center",type:"selection",width:"55"}}),t._v(" "),n("el-table-column",{attrs:{align:"center",type:"index",label:"序号",width:"66"}}),t._v(" "),n("el-table-column",{attrs:{align:"center",prop:"username",label:"业务员"}}),t._v(" "),n("el-table-column",{attrs:{align:"center",prop:"syhm",label:"未分配号码"}}),t._v(" "),n("el-table-column",{attrs:{align:"center",label:"操作",width:"120",fixed:"right"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("el-button",{attrs:{size:"small"},on:{click:function(n){t.distribute_phone(e.$index,e.row)}}},[t._v("分配")])]}}])})],1),t._v(" "),n("el-col",{staticClass:"toolbar",staticStyle:{"margin-top":"10px"},attrs:{span:24}},[n("el-button",{attrs:{type:"primary",disabled:0===this.multipleSelection.length},on:{click:t.groupDistribute_phone}},[t._v("批量分配\n            ")])],1),t._v(" "),n("el-col",{staticClass:"selectAdminView",staticStyle:{"margin-top":"20px"},attrs:{span:24}},[n("span",{directives:[{name:"show",rawName:"v-show",value:0!==t.multipleSelection.length,expression:"multipleSelection.length !== 0"}],staticStyle:{color:"#555","margin-right":"10px"}},[t._v("选中的业务员:\n               ")]),t._v(" "),t._l(t.multipleSelection,function(e){return n("span",{staticClass:"selectAdminName"},[t._v("\n                "+t._s(e.username))])})],2),t._v(" "),n("el-dialog",{attrs:{title:"分配号码",visible:t.distribute_dialog,center:""},on:{"update:visible":function(e){t.distribute_dialog=e}}},[n("div",{staticClass:"disnum_box"},[n("span",[t._v("号码配额：")]),t._v(" "),n("el-input",{attrs:{max:t.isBatch?Math.floor(t.table_snum/t.multipleSelection.length):t.table_snum,value:"number",placeholder:t.isBatch?"最大能分配"+Math.floor(t.table_snum/t.multipleSelection.length)+"个号码":"请输入号码个数"},model:{value:t.disnum,callback:function(e){t.disnum=e},expression:"disnum"}})],1),t._v(" "),n("div",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[n("div",{staticStyle:{"text-align":"center","font-size":"14px",padding:"5px"},style:"block"},[t._v("\n                    "+t._s(t.isDistributing?"正在加载中...":""))]),t._v(" "),n("el-button",{attrs:{type:"primary",disabled:t.isDistributing,loading:t.isDistributing},on:{click:t.distribute}},[t._v("确 定")])],1)])],1)])},[],!1,null,"85aff96e",null);u.options.__file="TwDistribute.vue";e.default=u.exports},IUdK:function(t,e,n){"use strict";var i=n("NeB3");n.n(i).a},NeB3:function(t,e,n){},yybV:function(t,e,n){"use strict";n.d(e,"E",function(){return a}),n.d(e,"x",function(){return r}),n.d(e,"k",function(){return o}),n.d(e,"t",function(){return u}),n.d(e,"D",function(){return s}),n.d(e,"o",function(){return l}),n.d(e,"C",function(){return d}),n.d(e,"j",function(){return c}),n.d(e,"w",function(){return f}),n.d(e,"I",function(){return b}),n.d(e,"H",function(){return m}),n.d(e,"v",function(){return h}),n.d(e,"l",function(){return p}),n.d(e,"y",function(){return g}),n.d(e,"h",function(){return _}),n.d(e,"i",function(){return v}),n.d(e,"B",function(){return j}),n.d(e,"F",function(){return O}),n.d(e,"c",function(){return S}),n.d(e,"r",function(){return w}),n.d(e,"n",function(){return y}),n.d(e,"z",function(){return k}),n.d(e,"a",function(){return x}),n.d(e,"p",function(){return C}),n.d(e,"A",function(){return D}),n.d(e,"m",function(){return $}),n.d(e,"d",function(){return z}),n.d(e,"G",function(){return T}),n.d(e,"b",function(){return I}),n.d(e,"q",function(){return B}),n.d(e,"s",function(){return L}),n.d(e,"u",function(){return A}),n.d(e,"e",function(){return N}),n.d(e,"g",function(){return E}),n.d(e,"f",function(){return K});var i=n("t3Un");function a(t){return Object(i.a)({url:"/userinfo",method:"post",data:t})}function r(t){return Object(i.a)({url:"/mobilelist",method:"post",data:t})}function o(t){return Object(i.a)({url:"/crmListApi",method:"post",data:t})}function u(t){return Object(i.a)({url:"/getCrmDetails",method:"post",data:t})}function s(t){return Object(i.a)({url:"/userlist",method:"post",data:t})}function l(t){return Object(i.a)({url:"/downchange",method:"post",data:t})}function d(t){return Object(i.a)({url:"/showchange",method:"post",data:t})}function c(t){return Object(i.a)({url:"/creckpc",method:"post",data:t})}function f(t){return Object(i.a)({url:"/lefthf",method:"post",data:t})}function b(t){return Object(i.a)({url:"/ywuserlist",method:"post",data:t})}function m(t){return Object(i.a)({url:"/yeuserlist",method:"post",data:t})}function h(t){return Object(i.a)({url:"/hfuserlist",method:"post",data:t})}function p(t){return Object(i.a)({url:"/delnumpc",method:"post",data:t})}function g(t){return Object(i.a)({url:"/mobilefp",method:"post",data:t})}function _(t){return Object(i.a)({url:"/chargefp",method:"post",data:t})}function v(t){return Object(i.a)({url:"/countdatalist",method:"post",data:t})}function j(t){return Object(i.a)({url:"/setremarks",method:"post",data:t})}function O(t){return Object(i.a)({url:"/voicelist",method:"post",data:t})}function S(t){return Object(i.a)({url:"/addvoice",method:"post",data:t})}function w(t){return Object(i.a)({url:"/editvoice",method:"post",data:t})}function y(t){return Object(i.a)({url:"/delvoice",method:"post",data:t})}function k(t){return Object(i.a)({url:"/patterlist",method:"post",data:t})}function x(t){return Object(i.a)({url:"/addpatter",method:"post",data:t})}function C(t){return Object(i.a)({url:"/editpatter",method:"post",data:t})}function D(t){return Object(i.a)({url:"/patterwordlist",method:"post",data:t})}function $(t){return Object(i.a)({url:"/delPatterWord",method:"post",data:t})}function z(t){return Object(i.a)({url:"/allvoice",method:"post",data:t})}function T(t){return Object(i.a)({url:"/wordlist",method:"post",data:t})}function I(t){return Object(i.a)({url:"/addpatterword",method:"post",data:t})}function B(t){return Object(i.a)({url:"/editpatterword",method:"post",data:t})}function L(t){return Object(i.a)({url:"/getPatterWord",method:"post",data:t})}function A(t){return Object(i.a)({url:"/getTaskState",method:"post",data:t})}function N(t){return Object(i.a)({url:"/approvalApi",method:"post",data:t})}function E(t){return Object(i.a)({url:"/changeOpenState",method:"post",data:t})}function K(t){return Object(i.a)({url:"/changeCloseState",method:"post",data:t})}}}]);