(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-1e9a"],{"91+L":function(t,e,n){},Mvut:function(t,e,n){"use strict";var i=n("91+L");n.n(i).a},UXWv:function(t,e,n){"use strict";n.r(e);var i=n("X4fA"),r=n("yybV"),a={name:"CallCharge",data:function(){return{table_snum:0,distribute_dialog:!1,table_list:[],tableLoading:!1,filters:{name:""},disnum:null,diszid:"",multipleSelection:[],isDistributing:!1,token:"",zzzz:0}},created:function(){this.table_id=this.$route.params&&this.$route.params.id,this.token=Object(i.a)(),this.getTableInfo(),this.getTable()},methods:{getTable:function(){var t=this,e={token:this.token,name:this.filters.name};this.tableLoading=!0,Object(r.v)(e).then(function(e){var n=e.data;"0000"==n.code?t.table_list=n.data.rows:t.$message.error(n.msg),t.tableLoading=!1}).catch(function(e){t.tableLoading=!1,t.$message.error("网络错误")})},getTableInfo:function(){var t=this,e={token:t.token,id:t.table_id};Object(r.w)(e).then(function(e){var n=e.data;"0000"==n.code&&(t.table_snum=n.data.snum)})},handleSelectionChange:function(t){this.multipleSelection=t},distribute_charge:function(t,e){this.distribute_dialog=!0,this.diszid=e.id.toString()},groupDistribute_charge:function(){this.distribute_dialog=!0,this.diszid="",console.log(this.multipleSelection);for(var t=0;t<this.multipleSelection.length;t++)t!=this.multipleSelection.length-1?this.diszid+=this.multipleSelection[t].id.toString()+",":this.diszid+=this.multipleSelection[t].id.toString()},distribute:function(){var t=this;if(this.isBatch){if(parseInt(this.disnum)*this.multipleSelection.length<0)return void t.$message.error("请正确输入分配数额！")}else if(parseInt(this.disnum)<0)return void t.$message.error("请正确输入分配数额！");t.isDistributing=!0;var e={aidlist:this.diszid,num:this.disnum};Object(r.h)(e).then(function(e){if(t.isDistributing=!0,"0000"==e.data.code){t.distribute_dialog=!1;var n=t.$createElement;t.$notify({title:"分配成功",message:n("i",{style:"color: teal"},"话费分配成功")}),t.disnum=null,t.isDistributing=!1,t.getTable()}else t.$alert("话费分配失败，请重试","分配失败",{confirmButtonText:"确定"})}).catch(function(e){t.isDistributing=!0,t.$message.error("网络加载失败！")})}},computed:{isBatch:function(){return this.diszid.toString().indexOf(",")>=0}}},o=(n("Mvut"),n("KHd+")),u=Object(o.a)(a,function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"app-container"},[n("div",{staticClass:"filter-container"},[n("el-col",{staticClass:"table_info",attrs:{span:16}}),t._v(" "),n("el-col",{staticClass:"top_toolbar",attrs:{span:8}},[n("el-form",{staticStyle:{display:"inline-block",padding:"0"},attrs:{inline:!0,model:t.filters}},[n("el-form-item",[n("el-input",{staticStyle:{width:"120px"},attrs:{placeholder:"业务员名字"},model:{value:t.filters.name,callback:function(e){t.$set(t.filters,"name",e)},expression:"filters.name"}})],1),t._v(" "),n("el-form-item",{staticStyle:{"margin-left":"10px"}},[n("el-button",{attrs:{type:"primary"},on:{click:t.getTable}},[t._v("查询")])],1)],1)],1),t._v(" "),n("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.tableLoading,expression:"tableLoading"}],staticClass:"auto_table",staticStyle:{width:"100%"},attrs:{data:t.table_list,height:"100%",border:""},on:{"selection-change":t.handleSelectionChange}},[n("el-table-column",{attrs:{align:"center",type:"selection",width:"55"}}),t._v(" "),n("el-table-column",{attrs:{align:"center",type:"index",label:"序号",width:"66"}}),t._v(" "),n("el-table-column",{attrs:{align:"center",prop:"username",label:"业务员"}}),t._v(" "),n("el-table-column",{attrs:{align:"center",prop:"amount",label:"机器人数量"}}),t._v(" "),n("el-table-column",{attrs:{align:"center",prop:"limit",label:"当前额度"}}),t._v(" "),n("el-table-column",{attrs:{align:"center",label:"操作",width:"120",fixed:"right"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("el-button",{attrs:{size:"small"},on:{click:function(n){t.distribute_charge(e.$index,e.row)}}},[t._v("设置额度")])]}}])})],1),t._v(" "),n("el-col",{staticClass:"toolbar",staticStyle:{"margin-top":"10px"},attrs:{span:24}},[n("el-button",{attrs:{type:"primary",disabled:0===this.multipleSelection.length},on:{click:t.groupDistribute_charge}},[t._v("批量设置\n            ")])],1),t._v(" "),n("el-col",{staticClass:"selectAdminView",staticStyle:{"margin-top":"20px"},attrs:{span:24}},[n("span",{directives:[{name:"show",rawName:"v-show",value:0!==t.multipleSelection.length,expression:"multipleSelection.length !== 0"}],staticStyle:{color:"#555","margin-right":"10px"}},[t._v("选中的管理员:\n               ")]),t._v(" "),t._l(t.multipleSelection,function(e){return n("span",{staticClass:"selectAdminName"},[t._v("\n                "+t._s(e.username))])})],2),t._v(" "),n("el-dialog",{attrs:{title:"分配话费",visible:t.distribute_dialog,center:""},on:{"update:visible":function(e){t.distribute_dialog=e}}},[n("div",{staticClass:"disnum_box"},[n("span",[t._v("话费配额：")]),t._v(" "),n("el-input",{attrs:{max:t.isBatch?Math.floor(t.table_snum/t.multipleSelection.length):t.table_snum,value:"number",placeholder:t.isBatch?"最大能分配"+Math.floor(t.table_snum/t.multipleSelection.length)+"元话费":"请输入话费数额"},model:{value:t.disnum,callback:function(e){t.disnum=e},expression:"disnum"}})],1),t._v(" "),n("div",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[n("div",{staticStyle:{"text-align":"center","font-size":"14px",padding:"5px"},style:"block"},[t._v("\n                    "+t._s(t.isDistributing?"正在加载中...":""))]),t._v(" "),n("el-button",{attrs:{type:"primary",disabled:t.isDistributing,loading:t.isDistributing},on:{click:t.distribute}},[t._v("确 定")])],1)])],1)])},[],!1,null,"56696f9a",null);u.options.__file="CallCharge.vue";e.default=u.exports},yybV:function(t,e,n){"use strict";n.d(e,"E",function(){return r}),n.d(e,"x",function(){return a}),n.d(e,"k",function(){return o}),n.d(e,"t",function(){return u}),n.d(e,"D",function(){return l}),n.d(e,"o",function(){return s}),n.d(e,"C",function(){return c}),n.d(e,"j",function(){return d}),n.d(e,"w",function(){return f}),n.d(e,"I",function(){return m}),n.d(e,"H",function(){return p}),n.d(e,"v",function(){return h}),n.d(e,"l",function(){return b}),n.d(e,"y",function(){return g}),n.d(e,"h",function(){return v}),n.d(e,"i",function(){return _}),n.d(e,"B",function(){return O}),n.d(e,"F",function(){return j}),n.d(e,"c",function(){return S}),n.d(e,"r",function(){return y}),n.d(e,"n",function(){return w}),n.d(e,"z",function(){return k}),n.d(e,"a",function(){return x}),n.d(e,"p",function(){return C}),n.d(e,"A",function(){return z}),n.d(e,"m",function(){return $}),n.d(e,"d",function(){return D}),n.d(e,"G",function(){return L}),n.d(e,"b",function(){return T}),n.d(e,"q",function(){return A}),n.d(e,"s",function(){return B}),n.d(e,"u",function(){return I}),n.d(e,"e",function(){return M}),n.d(e,"g",function(){return E}),n.d(e,"f",function(){return N});var i=n("t3Un");function r(t){return Object(i.a)({url:"/userinfo",method:"post",data:t})}function a(t){return Object(i.a)({url:"/mobilelist",method:"post",data:t})}function o(t){return Object(i.a)({url:"/crmListApi",method:"post",data:t})}function u(t){return Object(i.a)({url:"/getCrmDetails",method:"post",data:t})}function l(t){return Object(i.a)({url:"/userlist",method:"post",data:t})}function s(t){return Object(i.a)({url:"/downchange",method:"post",data:t})}function c(t){return Object(i.a)({url:"/showchange",method:"post",data:t})}function d(t){return Object(i.a)({url:"/creckpc",method:"post",data:t})}function f(t){return Object(i.a)({url:"/lefthf",method:"post",data:t})}function m(t){return Object(i.a)({url:"/ywuserlist",method:"post",data:t})}function p(t){return Object(i.a)({url:"/yeuserlist",method:"post",data:t})}function h(t){return Object(i.a)({url:"/hfuserlist",method:"post",data:t})}function b(t){return Object(i.a)({url:"/delnumpc",method:"post",data:t})}function g(t){return Object(i.a)({url:"/mobilefp",method:"post",data:t})}function v(t){return Object(i.a)({url:"/chargefp",method:"post",data:t})}function _(t){return Object(i.a)({url:"/countdatalist",method:"post",data:t})}function O(t){return Object(i.a)({url:"/setremarks",method:"post",data:t})}function j(t){return Object(i.a)({url:"/voicelist",method:"post",data:t})}function S(t){return Object(i.a)({url:"/addvoice",method:"post",data:t})}function y(t){return Object(i.a)({url:"/editvoice",method:"post",data:t})}function w(t){return Object(i.a)({url:"/delvoice",method:"post",data:t})}function k(t){return Object(i.a)({url:"/patterlist",method:"post",data:t})}function x(t){return Object(i.a)({url:"/addpatter",method:"post",data:t})}function C(t){return Object(i.a)({url:"/editpatter",method:"post",data:t})}function z(t){return Object(i.a)({url:"/patterwordlist",method:"post",data:t})}function $(t){return Object(i.a)({url:"/delPatterWord",method:"post",data:t})}function D(t){return Object(i.a)({url:"/allvoice",method:"post",data:t})}function L(t){return Object(i.a)({url:"/wordlist",method:"post",data:t})}function T(t){return Object(i.a)({url:"/addpatterword",method:"post",data:t})}function A(t){return Object(i.a)({url:"/editpatterword",method:"post",data:t})}function B(t){return Object(i.a)({url:"/getPatterWord",method:"post",data:t})}function I(t){return Object(i.a)({url:"/getTaskState",method:"post",data:t})}function M(t){return Object(i.a)({url:"/approvalApi",method:"post",data:t})}function E(t){return Object(i.a)({url:"/changeOpenState",method:"post",data:t})}function N(t){return Object(i.a)({url:"/changeCloseState",method:"post",data:t})}}}]);