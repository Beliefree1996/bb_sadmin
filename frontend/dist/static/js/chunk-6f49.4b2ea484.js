(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-6f49"],{Hjhp:function(t,e,r){"use strict";r.r(e);var n=r("X4fA"),a=r("yybV"),o={name:"PatterWordEdit",data:function(){return{Info:[],voicelist:[],wordlist:[],isAddBtn:!0,options:[{value:0,label:"关键词"},{value:1,label:"问候语"},{value:2,label:"开场白"},{value:3,label:"主干"},{value:4,label:"主干结束语"},{value:5,label:"出错结束语"},{value:6,label:"没说话的回答"},{value:7,label:"语音翻译为空的回答"},{value:8,label:"没说话3次的结束语"},{value:9,label:"没听清3次的结束语"},{value:10,label:"否定三次的结束语"}],addForm:{id:"",pid:0,sign:"",type:0,keyword:"",score:0,voice_id:0,y_next:0,n_next:0,u_next:0},pid:0,wid:0,rules:{voice_id:[{required:!0,message:"内容不能为空",trigger:"blur"}],score:[{required:!0,message:"请填写数字",trigger:"blur"},{type:"number",message:"分值必须为数字",trigger:"blur"}]}}},created:function(){var t=this.$route.params&&this.$route.params.id;this.addForm.pid=t,this.pid=t,"wid"in this.$route.query&&(this.wid=this.$route.query.wid,this.getInfo(this.wid),this.isAddBtn=!1),this.getwordlist(),this.getVoicelist()},methods:{getInfo:function(t){var e=this;Object(a.s)({wid:t}).then(function(t){var r=t.data.data;e.addForm.id=r.id,e.addForm.pid=r.pid,e.addForm.sign=r.sign,e.addForm.type=r.type,e.addForm.keyword=r.keyword,e.addForm.score=r.score,e.addForm.voice_id=r.voice_id,e.addForm.y_next=r.y_next,e.addForm.n_next=r.n_next,e.addForm.u_next=r.u_next})},getVoicelist:function(){var t=this;Object(a.d)({token:Object(n.a)()}).then(function(e){t.voicelist=e.data.data})},getwordlist:function(){var t=this;Object(a.G)({pid:this.pid}).then(function(e){t.wordlist=e.data.data,t.wordlist.unshift({id:-1,sign:"挂机"})})},PatterWordAction:function(){var t=this;this.$confirm("此操作将即时生效, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then(function(){t.$refs.addForm.validate(function(e){if(!e)return!1;var r=t.addForm;t.isAddBtn?t.addPatter(r):t.updatePatter(r)})}).catch(function(){t.$message({type:"info",message:"已取消"})})},addPatter:function(t){var e=this;Object(a.b)(t).then(function(t){"0000"==t.data.code?(e.$message.success("添加成功"),e.dialogFormVisible=!1):(e.$message.error(t.data.msg),e.dialogFormVisible=!1)})},updatePatter:function(t){var e=this;Object(a.q)(t).then(function(t){"0000"==t.data.code?(e.$message.success(t.data.msg),e.dialogFormVisible=!1):(e.$message.error(t.data.msg),e.dialogFormVisible=!1)})}}},i=(r("RAQp"),r("KHd+")),d=Object(i.a)(o,function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"app-container"},[r("div",{staticClass:"filter-container"}),t._v(" "),r("el-form",{ref:"addForm",attrs:{model:t.addForm,rules:t.rules,size:"small"}},[r("el-form-item",{attrs:{label:"类型",prop:"sign"}},[r("el-select",{attrs:{placeholder:"请选择类型"},model:{value:t.addForm.type,callback:function(e){t.$set(t.addForm,"type",e)},expression:"addForm.type"}},t._l(t.options,function(t){return r("el-option",{key:t.value,attrs:{label:t.label,value:t.value}})})),t._v(" "),r("span",{staticStyle:{"padding-left":"15px"}},[t._v("语句简称:"),r("el-input",{staticStyle:{width:"500px","padding-left":"15px"},attrs:{type:"text"},model:{value:t.addForm.sign,callback:function(e){t.$set(t.addForm,"sign",e)},expression:"addForm.sign"}})],1)],1),t._v(" "),r("el-form-item",{attrs:{label:"关键词"}},[r("el-input",{attrs:{type:"textarea"},model:{value:t.addForm.keyword,callback:function(e){t.$set(t.addForm,"keyword",e)},expression:"addForm.keyword"}})],1),t._v(" "),r("el-form-item",{attrs:{label:"选择语音",prop:"voice_id"}},[r("el-select",{staticStyle:{width:"100%"},attrs:{filterable:"",placeholder:"请选择语音"},model:{value:t.addForm.voice_id,callback:function(e){t.$set(t.addForm,"voice_id",e)},expression:"addForm.voice_id"}},t._l(t.voicelist,function(t){return r("el-option",{key:t.id,attrs:{label:t.word,value:t.id}})}))],1),t._v(" "),r("el-form-item",{attrs:{label:"请选择肯定回答",prop:"y_next"}},[r("el-select",{staticStyle:{width:"100%"},attrs:{filterable:"",placeholder:"请选择话术"},model:{value:t.addForm.y_next,callback:function(e){t.$set(t.addForm,"y_next",e)},expression:"addForm.y_next"}},[r("el-option",{attrs:{label:"空",value:"0"}}),t._v(" "),t._l(t.wordlist,function(e){return r("el-option",{key:e.id,attrs:{label:e.sign,value:e.id}},[r("span",{staticStyle:{float:"left"}},[t._v(t._s(e.sign))]),t._v(" "),r("span",{staticStyle:{float:"right",color:"#8492a6","font-size":"13px"}},[t._v(t._s(e.word))])])})],2)],1),t._v(" "),r("el-form-item",{attrs:{label:"请选择中性回答",prop:"u_next"}},[r("el-select",{staticStyle:{width:"100%"},attrs:{filterable:"",placeholder:"请选择话术"},model:{value:t.addForm.u_next,callback:function(e){t.$set(t.addForm,"u_next",e)},expression:"addForm.u_next"}},[r("el-option",{attrs:{label:"空",value:"0"}}),t._v(" "),t._l(t.wordlist,function(e){return r("el-option",{key:e.id,attrs:{label:e.sign,value:e.id}},[r("span",{staticStyle:{float:"left"}},[t._v(t._s(e.sign))]),t._v(" "),r("span",{staticStyle:{float:"right",color:"#8492a6","font-size":"13px"}},[t._v(t._s(e.word))])])})],2)],1),t._v(" "),r("el-form-item",{attrs:{label:"请选择否定回答",prop:"n_next"}},[r("el-select",{staticStyle:{width:"100%"},attrs:{filterable:"",placeholder:"请选择话术"},model:{value:t.addForm.n_next,callback:function(e){t.$set(t.addForm,"n_next",e)},expression:"addForm.n_next"}},[r("el-option",{attrs:{label:"空",value:"0"}}),t._v(" "),t._l(t.wordlist,function(e){return r("el-option",{key:e.id,attrs:{label:e.sign,value:e.id}},[r("span",{staticStyle:{float:"left"}},[t._v(t._s(e.sign))]),t._v(" "),r("span",{staticStyle:{float:"right",color:"#8492a6","font-size":"13px"}},[t._v(t._s(e.word))])])})],2)],1),t._v(" "),r("el-form-item",{attrs:{label:"分值",prop:"score"}},[r("el-input-number",{attrs:{type:"number"},model:{value:t.addForm.score,callback:function(e){t.$set(t.addForm,"score",e)},expression:"addForm.score"}})],1),t._v(" "),r("div",{staticStyle:{"margin-top":"15px",display:"flex","justify-content":"flex-end"}},[r("el-button",{attrs:{type:"primary"},on:{click:function(e){t.PatterWordAction()}}},[t._v(t._s(t.isAddBtn?"确定":"更新"))])],1)],1)],1)},[],!1,null,"d54a72ce",null);d.options.__file="PatterWordEdit.vue";e.default=d.exports},RAQp:function(t,e,r){"use strict";var n=r("u+mg");r.n(n).a},"u+mg":function(t,e,r){},yybV:function(t,e,r){"use strict";r.d(e,"E",function(){return a}),r.d(e,"x",function(){return o}),r.d(e,"k",function(){return i}),r.d(e,"t",function(){return d}),r.d(e,"D",function(){return u}),r.d(e,"o",function(){return l}),r.d(e,"C",function(){return s}),r.d(e,"j",function(){return c}),r.d(e,"w",function(){return f}),r.d(e,"I",function(){return p}),r.d(e,"H",function(){return m}),r.d(e,"v",function(){return b}),r.d(e,"l",function(){return h}),r.d(e,"y",function(){return v}),r.d(e,"h",function(){return _}),r.d(e,"i",function(){return g}),r.d(e,"B",function(){return y}),r.d(e,"F",function(){return j}),r.d(e,"c",function(){return F}),r.d(e,"r",function(){return w}),r.d(e,"n",function(){return x}),r.d(e,"z",function(){return O}),r.d(e,"a",function(){return k}),r.d(e,"p",function(){return $}),r.d(e,"A",function(){return S}),r.d(e,"m",function(){return A}),r.d(e,"d",function(){return P}),r.d(e,"G",function(){return V}),r.d(e,"b",function(){return B}),r.d(e,"q",function(){return q}),r.d(e,"s",function(){return W}),r.d(e,"u",function(){return z}),r.d(e,"e",function(){return C}),r.d(e,"g",function(){return E}),r.d(e,"f",function(){return I});var n=r("t3Un");function a(t){return Object(n.a)({url:"/userinfo",method:"post",data:t})}function o(t){return Object(n.a)({url:"/mobilelist",method:"post",data:t})}function i(t){return Object(n.a)({url:"/crmListApi",method:"post",data:t})}function d(t){return Object(n.a)({url:"/getCrmDetails",method:"post",data:t})}function u(t){return Object(n.a)({url:"/userlist",method:"post",data:t})}function l(t){return Object(n.a)({url:"/downchange",method:"post",data:t})}function s(t){return Object(n.a)({url:"/showchange",method:"post",data:t})}function c(t){return Object(n.a)({url:"/creckpc",method:"post",data:t})}function f(t){return Object(n.a)({url:"/lefthf",method:"post",data:t})}function p(t){return Object(n.a)({url:"/ywuserlist",method:"post",data:t})}function m(t){return Object(n.a)({url:"/yeuserlist",method:"post",data:t})}function b(t){return Object(n.a)({url:"/hfuserlist",method:"post",data:t})}function h(t){return Object(n.a)({url:"/delnumpc",method:"post",data:t})}function v(t){return Object(n.a)({url:"/mobilefp",method:"post",data:t})}function _(t){return Object(n.a)({url:"/chargefp",method:"post",data:t})}function g(t){return Object(n.a)({url:"/countdatalist",method:"post",data:t})}function y(t){return Object(n.a)({url:"/setremarks",method:"post",data:t})}function j(t){return Object(n.a)({url:"/voicelist",method:"post",data:t})}function F(t){return Object(n.a)({url:"/addvoice",method:"post",data:t})}function w(t){return Object(n.a)({url:"/editvoice",method:"post",data:t})}function x(t){return Object(n.a)({url:"/delvoice",method:"post",data:t})}function O(t){return Object(n.a)({url:"/patterlist",method:"post",data:t})}function k(t){return Object(n.a)({url:"/addpatter",method:"post",data:t})}function $(t){return Object(n.a)({url:"/editpatter",method:"post",data:t})}function S(t){return Object(n.a)({url:"/patterwordlist",method:"post",data:t})}function A(t){return Object(n.a)({url:"/delPatterWord",method:"post",data:t})}function P(t){return Object(n.a)({url:"/allvoice",method:"post",data:t})}function V(t){return Object(n.a)({url:"/wordlist",method:"post",data:t})}function B(t){return Object(n.a)({url:"/addpatterword",method:"post",data:t})}function q(t){return Object(n.a)({url:"/editpatterword",method:"post",data:t})}function W(t){return Object(n.a)({url:"/getPatterWord",method:"post",data:t})}function z(t){return Object(n.a)({url:"/getTaskState",method:"post",data:t})}function C(t){return Object(n.a)({url:"/approvalApi",method:"post",data:t})}function E(t){return Object(n.a)({url:"/changeOpenState",method:"post",data:t})}function I(t){return Object(n.a)({url:"/changeCloseState",method:"post",data:t})}}}]);