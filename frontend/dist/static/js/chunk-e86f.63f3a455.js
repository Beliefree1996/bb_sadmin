(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-e86f"],{"37Dv":function(t,e,i){},"91lG":function(t,e,i){"use strict";i.r(e);var s=i("QbLZ"),a=i.n(s),l=i("L2JU"),o=i("X4fA"),r=i("t3Un");var c={name:"demonstration",data:function(){return{callForm:{token:Object(o.a)(),mobile:"",patter:""},rules:{mobile:[{required:!0,message:"内容不能为空",trigger:"blur"}]},dialogFormVisible:!1,patters:[]}},computed:a()({},Object(l.b)(["device"])),created:function(){this.getAllPatter()},methods:{TestCall:function(){},CallAction:function(){var t=this;this.callForm.mobile.length<=0?this.$message.warning("手机号为空"):this.callForm.patter<=0?this.$message.warning("请选择话术"):function(t){return Object(r.a)({url:"/getCurQueue",method:"post",data:t})}({token:Object(o.a)()}).then(function(e){var i=e.data;"0000"==i.code?0==i.data.status?function(t){return Object(r.a)({url:"/startCall",method:"post",data:t})}(t.callForm).then(function(e){"0000"==e.data.code?(t.$message.success("正在拨打中,请等待"),t.callForm.mobile="",t.callForm.patter=""):t.$message.error("加入队列失败")}).catch(function(e){t.$message.error("拨打失败")}):t.$message.warning("当前队列已满，请稍后测试"):t.$message.error("获取当前通话队列失败")}).catch(function(e){t.$message.error("获取当前通话队列失败")})},test:function(){this.dialogFormVisible=!0},getAllPatter:function(){var t=this;Object(r.a)({url:"/getAllPatter",method:"post"}).then(function(e){var i=e.data;"0000"==i.code?t.patters=i.data:t.$message.error("话术获取失败")}).catch(function(e){t.$message.error("话术获取失败")})}}},n=(i("oIhT"),i("KHd+")),m=Object(n.a)(c,function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"main_content"},[t._m(0),t._v(" "),i("el-row",{staticClass:"topView_items",attrs:{gutter:10}},[i("el-col",{staticClass:"topView_item",attrs:{md:4,xs:8}},[i("div",{staticClass:"topView_item_des"},[i("img",{attrs:{src:"/static/imgs/robot.png",height:"100",width:"100"}})]),t._v(" "),i("div",{staticClass:"topView_item_btn"},[i("el-button",{attrs:{type:"text"},on:{click:t.TestCall}},[t._v("机器人")])],1)]),t._v(" "),i("el-col",{staticClass:"topView_item",attrs:{md:4,xs:8}},[i("div",{staticClass:"topView_item_des"},[i("img",{attrs:{src:"/static/imgs/zhengquan.png",height:"100",width:"100"}})]),t._v(" "),i("div",{staticClass:"topView_item_btn"},[i("el-button",{attrs:{type:"text"},on:{click:t.TestCall}},[t._v("证券")])],1)]),t._v(" "),i("el-col",{staticClass:"topView_item",attrs:{md:4,xs:8}},[i("div",{staticClass:"topView_item_des"},[i("img",{attrs:{src:"/static/imgs/daikuan.png",height:"100",width:"100"}})]),t._v(" "),i("div",{staticClass:"topView_item_btn"},[i("el-button",{attrs:{type:"text"},on:{click:t.TestCall}},[t._v("贷款")])],1)]),t._v(" "),i("el-col",{staticClass:"topView_item",attrs:{md:4,xs:8}},[i("div",{staticClass:"topView_item_des"},[i("img",{attrs:{src:"/static/imgs/baoxian.png",height:"100",width:"100"}})]),t._v(" "),i("div",{staticClass:"topView_item_btn"},[i("el-button",{attrs:{type:"text"},on:{click:t.TestCall}},[t._v("保险")])],1)]),t._v(" "),i("el-col",{staticClass:"topView_item",attrs:{md:4,xs:8}},[i("div",{staticClass:"topView_item_des"},[i("img",{attrs:{src:"/static/imgs/peixun.png",height:"100",width:"100"}})]),t._v(" "),i("div",{staticClass:"topView_item_btn"},[i("el-button",{attrs:{type:"text"},on:{click:t.TestCall}},[t._v("培训")])],1)]),t._v(" "),i("el-col",{staticClass:"topView_item",attrs:{md:4,xs:8}},[i("div",{staticClass:"topView_item_des"},[i("img",{attrs:{src:"/static/imgs/building.png",height:"100",width:"100"}})]),t._v(" "),i("div",{staticClass:"topView_item_btn"},[i("el-button",{attrs:{type:"text"},on:{click:t.TestCall}},[t._v("房地产")])],1)])],1),t._v(" "),i("el-row",{attrs:{align:"middle",justify:"center",type:"flex"}},[i("el-button",{attrs:{size:"large",type:"primary"},on:{click:t.test}},[t._v("话术测试")])],1),t._v(" "),i("el-dialog",{attrs:{title:"话术测试",visible:t.dialogFormVisible,width:"mobile"===t.device?"300px":"480px",fs:t.device},on:{"update:visible":function(e){t.dialogFormVisible=e}}},[i("el-form",{ref:"callForm",attrs:{model:t.callForm,rules:t.rules,size:"small","label-width":"60px"}},[i("el-form-item",{attrs:{label:"号码",prop:"mobile"}},[i("el-input",{attrs:{size:"mini",type:"number",placeholder:"请输入测试号码"},model:{value:t.callForm.mobile,callback:function(e){t.$set(t.callForm,"mobile",e)},expression:"callForm.mobile"}})],1),t._v(" "),i("el-form-item",{attrs:{label:"话术"}},[i("el-select",{attrs:{placeholder:"请选择话术"},model:{value:t.callForm.patter,callback:function(e){t.$set(t.callForm,"patter",e)},expression:"callForm.patter"}},t._l(t.patters,function(t){return i("el-option",{key:t.id,attrs:{label:t.title,value:t.id}})}))],1),t._v(" "),i("div",{staticStyle:{"margin-top":"15px",display:"flex","justify-content":"flex-end"}},[i("el-button",{attrs:{type:"primary"},on:{click:t.CallAction}},[t._v("拨打")])],1)],1)],1)],1)},[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"topView"},[e("img",{attrs:{src:"/static/imgs/aotoouter_headimg.png"}})])}],!1,null,"a72b35d4",null);m.options.__file="Demonstration.vue";e.default=m.exports},oIhT:function(t,e,i){"use strict";var s=i("37Dv");i.n(s).a}}]);