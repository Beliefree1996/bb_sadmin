(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-3e17"],{"1ll7":function(t,e,n){"use strict";n.r(e);var r=n("X4fA"),a=n("6air"),o={name:"index",data:function(){return{title:"",token:Object(r.a)(),PatterWordList:[],tableLoading:!1,pagesize:30,page:1,total:0,isAddBtn:!0,pid:0,addForm:{word:"",path:"",id:""},rules:{word:[{required:!0,message:"内容不能为空",trigger:"blur"}]},dialogFormVisible:!1,filter_type:"",filter_types:[{title:"非关键词",type:1},{title:"关键词",type:2}]}},created:function(){this.$route.query&&"pid"in this.$route.query?(this.pid=this.$route.query.pid,this.getPatterWordList(),this.getPatterInfo()):this.$message.error("话术不存在")},methods:{getPatterWordList:function(){var t=this,e={token:this.token,pid:this.pid,page:this.page,pagesize:this.pagesize,type:this.filter_type};this.tableLoading=!0,Object(a.u)(e).then(function(e){var n=e.data;"0000"==n.code?(t.PatterWordList=n.data.rows,t.total=n.data.total,t.tableLoading=!1):t.$message.error(n.msg)}).catch(function(e){t.$message.error("获取话术详情列表失败")})},getPatterInfo:function(){var t=this,e={token:this.token,pid:this.pid};Object(a.p)(e).then(function(e){var n=e.data;"0000"==n.code?t.title=n.data.title:t.$message.error(n.msg)}).catch(function(e){t.$message.error("获取话术信息失败！")})},handleCurrentChange:function(t){this.page=t,this.getPatterWordList()},handleChangeType:function(){this.page=1,this.getPatterWordList()},tableHeaderColor:function(t){t.row,t.column;var e=t.rowIndex;t.columnIndex;if(0===e)return"background-color: #f7f7f7;color: #363636;font-weight: 500;"},getType:function(t){return 0==t?"关键词":1==t?"入口":2==t?"主干":3==t?"出错结束语":4==t?"没说话的回答":5==t?"没说话3次的结束语":6==t?"否定三次的结束语":void 0},PatterWordDel:function(t,e){var n=this;this.$confirm("此操作将永久删除该文件, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then(function(){Object(a.k)({wid:e.id}).then(function(t){"0000"==t.data.code?(n.$message.success("删除成功"),n.getPatterWordList()):n.$message.error(t.data.msg)})})}}},i=(n("aN+e"),n("KHd+")),l=Object(i.a)(o,function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"app-container"},[n("div",{staticClass:"filter-container"},[n("div",{staticStyle:{"font-size":"50px"}},[t._v("\n            "+t._s(t.title)+"\n        ")]),t._v(" "),n("router-link",{staticClass:"link-type",attrs:{to:"patterWordEdit?pid="+t.pid}},[n("el-button",{attrs:{type:"success",icon:"el-icon-upload2"}},[t._v("添加话术流程")])],1),t._v(" "),n("span",{staticStyle:{color:"red","padding-left":"15px"}},[t._v("话术修改即时生效，删除不可恢复，请谨慎操作")])],1),t._v(" "),n("div",{staticClass:"filter-container"},[n("el-select",{staticStyle:{width:"150px"},attrs:{filterable:"",placeholder:"请选择类别"},on:{change:t.handleChangeType},model:{value:t.filter_type,callback:function(e){t.filter_type=e},expression:"filter_type"}},[n("el-option",{attrs:{label:"全部话术",value:"0"}}),t._v(" "),t._l(t.filter_types,function(t){return n("el-option",{key:t.type,attrs:{label:t.title,value:t.type}})})],2)],1),t._v(" "),n("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.tableLoading,expression:"tableLoading"}],staticClass:"tableListView",staticStyle:{width:"100%","min-width":"600px"},attrs:{data:t.PatterWordList,border:"","header-cell-style":t.tableHeaderColor}},[n("el-table-column",{attrs:{type:"selection",width:"55",align:"center",label:"全选"}}),t._v(" "),n("el-table-column",{attrs:{align:"center",prop:"id","min-width":"50",label:"语句ID"}}),t._v(" "),n("el-table-column",{attrs:{align:"center",prop:"sign","min-width":"50",label:"语句简称"}}),t._v(" "),n("el-table-column",{attrs:{align:"center","min-width":"50",label:"语句类型"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("span",[t._v(t._s(t.getType(e.row.type)))])]}}])}),t._v(" "),n("el-table-column",{attrs:{align:"center",prop:"word_base_name","min-width":"80",label:"关键词"}}),t._v(" "),n("el-table-column",{attrs:{align:"center",prop:"score","min-width":"50",label:"分值"}}),t._v(" "),n("el-table-column",{attrs:{align:"center",prop:"word","min-width":"300",label:"应答语句"}}),t._v(" "),n("el-table-column",{attrs:{align:"center",prop:"y_word","min-width":"50",label:"肯定跳转"}}),t._v(" "),n("el-table-column",{attrs:{align:"center",prop:"u_word","min-width":"50",label:"中性跳转"}}),t._v(" "),n("el-table-column",{attrs:{align:"center",prop:"n_word","min-width":"50",label:"否定跳转"}}),t._v(" "),n("el-table-column",{attrs:{align:"center",prop:"scene_name","min-width":"80",label:"情景关键词"}}),t._v(" "),n("el-table-column",{attrs:{align:"center",prop:"role","min-width":"80",label:"角色"}}),t._v(" "),n("el-table-column",{attrs:{align:"center",prop:"label","min-width":"80",label:"标签"}}),t._v(" "),n("el-table-column",{attrs:{align:"center","min-width":"120",label:"操作"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("router-link",{staticClass:"link-type",attrs:{to:"patterWordEdit?pid="+t.pid+"&wid="+e.row.id}},[n("el-button",{attrs:{type:"primary",plain:"",size:"small"}},[t._v("编辑")])],1),t._v(" "),n("el-button",{attrs:{type:"danger",plain:"",size:"small"},on:{click:function(n){t.PatterWordDel(e.$index,e.row)}}},[t._v("刪除\n                ")])]}}])})],1),t._v(" "),n("el-col",{staticClass:"toolbar",staticStyle:{"margin-top":"10px"},attrs:{span:24}},[n("el-pagination",{staticStyle:{float:"right"},attrs:{layout:"prev, pager, next","page-size":t.pagesize,total:t.total},on:{"current-change":t.handleCurrentChange}})],1)],1)},[],!1,null,"122dab87",null);l.options.__file="patterDetail.vue";e.default=l.exports},"6air":function(t,e,n){"use strict";n.d(e,"t",function(){return a}),n.d(e,"e",function(){return o}),n.d(e,"l",function(){return i}),n.d(e,"u",function(){return l}),n.d(e,"v",function(){return u}),n.d(e,"j",function(){return d}),n.d(e,"g",function(){return c}),n.d(e,"k",function(){return s}),n.d(e,"p",function(){return p}),n.d(e,"o",function(){return f}),n.d(e,"n",function(){return h}),n.d(e,"F",function(){return b}),n.d(e,"d",function(){return m}),n.d(e,"D",function(){return g}),n.d(e,"G",function(){return w}),n.d(e,"s",function(){return v}),n.d(e,"H",function(){return _}),n.d(e,"c",function(){return y}),n.d(e,"C",function(){return j}),n.d(e,"I",function(){return O}),n.d(e,"y",function(){return C}),n.d(e,"a",function(){return k}),n.d(e,"A",function(){return x}),n.d(e,"z",function(){return L}),n.d(e,"r",function(){return P}),n.d(e,"x",function(){return W}),n.d(e,"b",function(){return $}),n.d(e,"B",function(){return A}),n.d(e,"w",function(){return B}),n.d(e,"h",function(){return S}),n.d(e,"E",function(){return z}),n.d(e,"f",function(){return D}),n.d(e,"m",function(){return I}),n.d(e,"q",function(){return q}),n.d(e,"i",function(){return T});var r=n("t3Un");function a(t){return Object(r.a)({url:"/patter1list",method:"post",data:t})}function o(t){return Object(r.a)({url:"/addpatter1",method:"post",data:t})}function i(t){return Object(r.a)({url:"/editpatter1",method:"post",data:t})}function l(t){return Object(r.a)({url:"/patterwordlist",method:"post",data:t})}function u(t){return Object(r.a)({url:"/rolemanagelist",method:"post",data:t})}function d(t){return Object(r.a)({url:"/changespeechrole",method:"post",data:t})}function c(t){return Object(r.a)({url:"/allrole",method:"post",data:t})}function s(t){return Object(r.a)({url:"/delPatter1Word",method:"post",data:t})}function p(t){return Object(r.a)({url:"/getPatter1Info",method:"post",data:t})}function f(t){return Object(r.a)({url:"/getAllWordBase",method:"post",data:t})}function h(t){return Object(r.a)({url:"/getAllScene",method:"post",data:t})}function b(t){return Object(r.a)({url:"/wordBaseList",method:"post",data:t})}function m(t){return Object(r.a)({url:"/wordBaseAdd",method:"post",data:t})}function g(t){return Object(r.a)({url:"/wordBaseChange",method:"post",data:t})}function w(t){return Object(r.a)({url:"/wordBaseDelete",method:"post",data:t})}function v(t){return Object(r.a)({url:"/getwordBaseInfo",method:"post",data:t})}function _(t){return Object(r.a)({url:"/wordListApi",method:"post",data:t})}function y(t){return Object(r.a)({url:"/wordAdd",method:"post",data:t})}function j(t){return Object(r.a)({url:"/wordChange",method:"post",data:t})}function O(t){return Object(r.a)({url:"/wordDelete",method:"post",data:t})}function C(t){return Object(r.a)({url:"/sceneListApi",method:"post",data:t})}function k(t){return Object(r.a)({url:"/sceneAdd",method:"post",data:t})}function x(t){return Object(r.a)({url:"/sceneChange",method:"post",data:t})}function L(t){return Object(r.a)({url:"/sceneDelete",method:"post",data:t})}function P(t){return Object(r.a)({url:"/getSceneInfo",method:"post",data:t})}function W(t){return Object(r.a)({url:"/sceneKeyList",method:"post",data:t})}function $(t){return Object(r.a)({url:"/sceneKeyAdd",method:"post",data:t})}function A(t){return Object(r.a)({url:"/sceneKeyChange",method:"post",data:t})}function B(t){return Object(r.a)({url:"/sceneKeyDelete",method:"post",data:t})}function S(t){return Object(r.a)({url:"/allvoice",method:"post",data:t})}function z(t){return Object(r.a)({url:"/word1list",method:"post",data:t})}function D(t){return Object(r.a)({url:"/addpatter1word",method:"post",data:t})}function I(t){return Object(r.a)({url:"/editpatter1word",method:"post",data:t})}function q(t){return Object(r.a)({url:"/getPatter1Word",method:"post",data:t})}function T(t){return Object(r.a)({url:"/changePatter",method:"post",data:t})}},"aN+e":function(t,e,n){"use strict";var r=n("qRxC");n.n(r).a},qRxC:function(t,e,n){}}]);