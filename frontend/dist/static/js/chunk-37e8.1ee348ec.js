(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-37e8"],{Iy5S:function(t,e,a){"use strict";var r=a("hkRt");a.n(r).a},"RU/L":function(t,e,a){a("Rqdy");var r=a("WEpk").Object;t.exports=function(t,e,a){return r.defineProperty(t,e,a)}},Rqdy:function(t,e,a){var r=a("Y7ZC");r(r.S+r.F*!a("jmDH"),"Object",{defineProperty:a("2faE").f})},SEkw:function(t,e,a){t.exports={default:a("RU/L"),__esModule:!0}},SZAA:function(t,e,a){"use strict";a.r(e);var r=a("FyfS"),l=a.n(r),o=a("YEIV"),n=a.n(o),i=a("X4fA"),c=a("yybV"),s=a("pZP+"),u={name:"CallCharge",data:function(){return{table_snum:0,distribute_dialog:!1,table_list:[],tableLoading:!1,total:1e3,page:1,pagesize:10,crmlisttype:[],crmlistcompany:"",crmlistmobile:"",disnum:null,diszid:"",multipleSelection:[],isDistributing:!1,token:"",options:[{value:"",label:"全部"},{value:"new_customer",label:"新客户",children:[{value:"new_levelA",label:"A类高意向（已询价）"},{value:"new_levelB",label:"B类中等意向（加微信）"},{value:"new_levelC",label:"C类低意向"}]},{value:"old_customer",label:"老客户"},{value:"unintentional",label:"无意向"},{value:"unreachable",label:"未接通"},{value:"autoaddwechat",label:"自动添加微信"},{value:"nodeal",label:"无需处理"}],service_recording:[],service_remarks:[],pickerOptions:{shortcuts:[{text:"今天",onClick:function(t){t.$emit("pick",new Date)}},{text:"昨天",onClick:function(t){var e=new Date;e.setTime(e.getTime()-864e5),t.$emit("pick",e)}},{text:"一周前",onClick:function(t){var e=new Date;e.setTime(e.getTime()-6048e5),t.$emit("pick",e)}}],disabledDate:function(t){return t.getTime()>=Date.now()}},CallDateTime:"",isShowRecords:!1,callRecordsForm:n()({id:0,callId:"",customerName:"",customerSex:"1",isaddwx:"1",relationPhone:"",email:"",customerType:["unreachable"],relationAddress:"",addRemarks:"",company:"",exist:0,stage:"0",appointment:!1,appointmentDate:"",appointContent:""},"exist",0),recordDialog:!1,cur_record:""}},created:function(){this.table_id=this.$route.params&&this.$route.params.id,this.token=Object(i.a)(),this.getTable()},methods:{handleCurrentChange:function(t){this.page=t,this.getTable()},getTable:function(){var t=this,e={uid:this.$route.params.id,page:this.page,starttime:this.formatDate(this.CallDateTime[0]),endtime:this.formatDate(this.CallDateTime[1]),type0:this.crmlisttype[0],type1:this.crmlisttype[1],crmcompany:this.crmlistcompany,crmmobile:this.crmlistmobile};this.tableLoading=!0,Object(c.k)(e).then(function(e){var a=e.data;"0000"==a.code?(t.table_list=a.data.rows,t.total=a.data.count):t.$message.error(a.msg),t.tableLoading=!1}).catch(function(e){console.log(e),t.tableLoading=!1,t.$message.error("网络错误")})},Approval:function(t,e){var a=this,r={index:t,id:e};Object(c.e)(r).then(function(t){var e=t.data;"0000"==e.code?a.$message.success(e.msg):a.$message.error(e.msg)}).catch(function(t){a.$message.error("网络错误")})},showDetails:function(t){var e=this,a={id:t.id};Object(c.t)(a).then(function(t){var a=t.data;if(console.log(a),"0000"==a.code){if(a.data.isaddwx&&(a.data.isaddwx=a.data.isaddwx.toString()),e.callRecordsForm={id:a.data.id,exist:a.data.exist,customerName:a.data.username,customerSex:a.data.gender,isaddwx:a.data.isaddwx.toString(),relationPhone:a.data.mobile,email:a.data.email,customerType:a.data.type,relationAddress:a.data.dizhi,stage:a.data.stage.toString(),company:a.data.company,appointment:a.data.isyuyue,appointmentDate:a.data.yydateline,appointContent:a.data.yytxt},e.service_recording=[],a.data.robotcall&&(a.data.robotcall.username="任务",a.data.robotcall.bill=e.getformatBill(a.data.robotcall.bill),a.data.robotcall.hangupcause=e.getCallstate(a.data.robotcall.hangupcause),a.data.robotcall.recordfile=e.getRecordFile(a.data.robotcall.recordfile),e.service_recording.push(a.data.robotcall)),a.data.realcall.length>0){var r=!0,o=!1,n=void 0;try{for(var i,c=l()(a.data.realcall);!(r=(i=c.next()).done);r=!0){var s=i.value;s.calldate=e.getCalldate(s.starttime),s.bill=e.getformatBill(1e3*s.billsec),s.hangupcause=e.getCallstate(s.cause),s.recordfile=e.getRecordFile(s.record_file),e.service_recording.push(s)}}catch(t){o=!0,n=t}finally{try{!r&&c.return&&c.return()}finally{if(o)throw n}}}if(e.service_remarks=[],a.data.remarks.length>0){var u=!0,d=!1,m=void 0;try{for(var p,f=l()(a.data.remarks);!(u=(p=f.next()).done);u=!0){var b=p.value;b.remarkdate=e.getCalldate(b.dateline),e.service_remarks.push(b)}}catch(t){d=!0,m=t}finally{try{!u&&f.return&&f.return()}finally{if(d)throw m}}}e.isShowRecords=!0}else e.$message.error(a.msg)}).catch(function(t){console.log(t),e.$message.error("网络错误")})},closeCallRecorder:function(){this.isShowRecords=!1,this.callRecordsForm={uid:0,callId:"",customerName:"",customerSex:"1",isaddwx:"1",relationPhone:"",email:"",customerType:["unreachable"],relationAddress:"",stage:"0",addRemarks:"",company:"",appointment:!1,appointmentDate:"",appointContent:""}},showRecord:function(t){t.recordfile&&t.recordfile.length>0?(this.cur_record=t.recordfile,this.recordDialog=!0):this.$message.error("当前通话无录音!")},handleDistribute:function(t,e){this.$emit("distributeBack",e.id)},handleSelectionChange:function(t){this.multipleSelection=t},formatDate:function(t){if(t){var e=new Date(t);return s.a.formatDate(e,"yyyy-MM-dd")}return""},getFormatType:function(t){return t.type0+t.type1},getRecordFile:function(t){return t?t.replace("/usr/local/freeswitch/recordings","https://voice.bbxxjs.com/static/talk_recording/full_talk"):""},getformatBill:function(t){var e=parseInt(t/1e3)%60,a=parseInt(t/1e3/60)%60,r=parseInt(t/1e3/60/60)%60;return(r>10?r:"0"+r)+":"+(a>10?a:"0"+a)+":"+(e>10?e:"0"+e)},getCallstate:function(t){return"NORMAL_CLEARING"==t?"正常呼叫结束":"USER_BUSY"==t?"用户忙":"NO_ANSWER"==t?"无应答":"其他"},getCalldate:function(t){return(t=new Date(1e3*t)).getFullYear()+"-"+((t.getMonth()+1<10?"0"+(t.getMonth()+1):t.getMonth()+1)+"-")+((t.getDate()<10?"0"+t.getDate():t.getDate())+" ")+((t.getHours()<10?"0"+t.getHours():t.getHours())+":")+((t.getMinutes()<10?"0"+t.getMinutes():t.getMinutes())+":")+(t.getSeconds()<10?"0"+t.getSeconds():t.getSeconds())}},computed:{}},d=(a("Iy5S"),a("KHd+")),m=Object(d.a)(u,function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"app-container"},[a("div",{staticClass:"filter-container"},[a("el-row",{staticClass:"top_toolbar",attrs:{span:80}},[a("el-col",{attrs:{span:24}},[a("span",{staticStyle:{color:"#555","font-size":"16px","margin-left":"10px"}},[t._v("通话日期：")]),t._v(" "),a("el-date-picker",{attrs:{editable:!1,size:"small",type:"daterange",placeholder:"通话开始日期",align:"right","picker-options":t.pickerOptions},model:{value:t.CallDateTime,callback:function(e){t.CallDateTime=e},expression:"CallDateTime"}})],1)],1),t._v(" "),a("el-row",[a("br")]),t._v(" "),a("el-row",{staticClass:"top_toolbar",attrs:{span:8}},[a("el-form",{staticStyle:{display:"inline-block",padding:"0"},attrs:{inline:!0}},[a("el-form-item",[a("el-cascader",{attrs:{placeholder:"客户类型",options:t.options},model:{value:t.crmlisttype,callback:function(e){t.crmlisttype=e},expression:"crmlisttype"}})],1)],1),t._v(" "),a("el-form",{staticStyle:{display:"inline-block",padding:"0"},attrs:{inline:!0}},[a("el-form-item",[a("el-input",{staticStyle:{width:"120px"},attrs:{placeholder:"公司名称"},model:{value:t.crmlistcompany,callback:function(e){t.crmlistcompany=e},expression:"crmlistcompany"}})],1),t._v(" "),a("el-form-item",[a("el-input",{staticStyle:{width:"120px"},attrs:{placeholder:"联系电话"},model:{value:t.crmlistmobile,callback:function(e){t.crmlistmobile=e},expression:"crmlistmobile"}})],1),t._v(" "),a("el-form-item",{staticStyle:{"margin-left":"10px"}},[a("el-button",{attrs:{type:"primary"},on:{click:t.getTable}},[t._v("查询")])],1)],1)],1),t._v(" "),a("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.tableLoading,expression:"tableLoading"}],staticClass:"auto_table",staticStyle:{width:"100%"},attrs:{data:t.table_list,height:"100%",border:""},on:{"selection-change":t.handleSelectionChange}},[a("el-table-column",{attrs:{align:"center",type:"index",label:"序号",width:"66"}}),t._v(" "),a("el-table-column",{attrs:{align:"center",prop:"company",label:"公司名称"}}),t._v(" "),a("el-table-column",{attrs:{align:"center",prop:"username",label:"客户姓名"}}),t._v(" "),a("el-table-column",{attrs:{align:"center",label:"类型"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("span",{staticStyle:{"margin-left":"10px"}},[t._v(t._s(t.getFormatType(e.row)))])]}}])}),t._v(" "),a("el-table-column",{attrs:{align:"center",prop:"mobile",label:"联系电话"}}),t._v(" "),a("el-table-column",{attrs:{align:"center",prop:"remark",label:"最新备注"}}),t._v(" "),a("el-table-column",{attrs:{align:"center",prop:"addtime",label:"添加时间"}}),t._v(" "),a("el-table-column",{attrs:{align:"center",label:"操作",width:"120",fixed:"right"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("el-button",{attrs:{type:"text",size:"small"},on:{click:function(a){t.showDetails(e.row)}}},[t._v("查看详情")])]}}])})],1),t._v(" "),a("el-dialog",{attrs:{visible:t.isShowRecords,title:"客户录入",width:"900px","before-close":t.closeCallRecorder,center:!0}},[a("div",{staticClass:"callRecorderContainer"},[a("el-form",{ref:"callForm",staticClass:"demo-ruleForm",attrs:{model:t.callRecordsForm,"label-width":"100px"}},[a("div",{staticClass:"callRecorderItem"},[a("el-form-item",{staticClass:"formItem",attrs:{label:"客户名称:"}},[a("el-input",{attrs:{size:"mini"},model:{value:t.callRecordsForm.customerName,callback:function(e){t.$set(t.callRecordsForm,"customerName",e)},expression:"callRecordsForm.customerName"}})],1),t._v(" "),a("el-form-item",{staticClass:"formItem",attrs:{label:"客户性别:"}},[a("el-select",{attrs:{size:"mini",placeholder:"请选择性别"},model:{value:t.callRecordsForm.customerSex,callback:function(e){t.$set(t.callRecordsForm,"customerSex",e)},expression:"callRecordsForm.customerSex"}},[a("el-option",{attrs:{label:"先生/male",value:"1"}}),t._v(" "),a("el-option",{attrs:{label:"女士/female",value:"0"}})],1)],1),t._v(" "),a("el-form-item",{staticClass:"formItem",attrs:{label:"添加微信:"}},[a("el-select",{attrs:{size:"mini",placeholder:"请选择是或否"},model:{value:t.callRecordsForm.isaddwx,callback:function(e){t.$set(t.callRecordsForm,"isaddwx",e)},expression:"callRecordsForm.isaddwx"}},[a("el-option",{attrs:{label:"是",value:"1"}}),t._v(" "),a("el-option",{attrs:{label:"否",value:"0"}})],1)],1)],1),t._v(" "),a("div",{staticClass:"callRecorderItem"},[a("el-form-item",{staticClass:"formItem",attrs:{label:"客户类型:"}},[a("el-cascader",{attrs:{size:"mini",options:t.options},model:{value:t.callRecordsForm.customerType,callback:function(e){t.$set(t.callRecordsForm,"customerType",e)},expression:"callRecordsForm.customerType"}})],1),t._v(" "),a("el-form-item",{staticClass:"formItem",attrs:{label:"联系电话:"}},[a("el-input",{attrs:{size:"mini"},model:{value:t.callRecordsForm.relationPhone,callback:function(e){t.$set(t.callRecordsForm,"relationPhone",e)},expression:"callRecordsForm.relationPhone"}})],1),t._v(" "),a("el-form-item",{staticClass:"formItem",attrs:{label:"电子邮箱:"}},[a("el-input",{attrs:{size:"mini"},model:{value:t.callRecordsForm.email,callback:function(e){t.$set(t.callRecordsForm,"email",e)},expression:"callRecordsForm.email"}})],1)],1),t._v(" "),a("div",{staticClass:"callRecorderItem"},[a("el-form-item",{staticClass:"formItem",attrs:{label:"公司名称:"}},[a("el-input",{attrs:{size:"mini"},model:{value:t.callRecordsForm.company,callback:function(e){t.$set(t.callRecordsForm,"company",e)},expression:"callRecordsForm.company"}})],1),t._v(" "),a("el-form-item",{staticClass:"formItem",attrs:{label:"联系地址:"}},[a("el-input",{attrs:{size:"mini"},model:{value:t.callRecordsForm.relationAddress,callback:function(e){t.$set(t.callRecordsForm,"relationAddress",e)},expression:"callRecordsForm.relationAddress"}})],1),t._v(" "),a("el-form-item",{staticClass:"formItem",attrs:{label:"跟进阶段:"}},[a("el-select",{attrs:{size:"mini",placeholder:"请选择"},model:{value:t.callRecordsForm.stage,callback:function(e){t.$set(t.callRecordsForm,"stage",e)},expression:"callRecordsForm.stage"}},[a("el-option",{attrs:{label:"线索",value:"0"}}),t._v(" "),a("el-option",{attrs:{label:"AI初访",value:"1"}}),t._v(" "),a("el-option",{attrs:{label:"人工初访",value:"2"}}),t._v(" "),a("el-option",{attrs:{label:"意向",value:"3"}}),t._v(" "),a("el-option",{attrs:{label:"报价",value:"4"}}),t._v(" "),a("el-option",{attrs:{label:"成交",value:"5"}})],1)],1)],1),t._v(" "),a("div",{staticClass:"appointmentDate"},[a("el-form-item",{staticClass:"appointmentItem"},[a("el-checkbox",{model:{value:t.callRecordsForm.appointment,callback:function(e){t.$set(t.callRecordsForm,"appointment",e)},expression:"callRecordsForm.appointment"}},[t._v("添加预约")])],1),t._v(" "),a("el-form-item",{staticClass:"appointmentItem",attrs:{label:"预约时间:"}},[a("el-date-picker",{attrs:{size:"mini",type:"datetime",placeholder:"选择预约日期时间","default-time":"12:00:00"},model:{value:t.callRecordsForm.appointmentDate,callback:function(e){t.$set(t.callRecordsForm,"appointmentDate",e)},expression:"callRecordsForm.appointmentDate"}})],1),t._v(" "),a("el-form-item",{staticClass:"appointmentItem",attrs:{label:"预约内容:"}},[a("el-input",{attrs:{size:"mini",type:"textarea"},model:{value:t.callRecordsForm.appointContent,callback:function(e){t.$set(t.callRecordsForm,"appointContent",e)},expression:"callRecordsForm.appointContent"}})],1)],1),t._v(" "),a("div",{staticClass:"historyCallList"},[a("el-tabs",{attrs:{type:"border-card"}},[a("el-tab-pane",{attrs:{label:"服务记录"}},[a("div",[a("el-table",{staticClass:"callRecorderTable",attrs:{data:t.service_recording,height:"200"}},[a("el-table-column",{attrs:{label:"呼叫主体","min-width":"80",prop:"username"}}),t._v(" "),a("el-table-column",{attrs:{label:"拨打时间","min-width":"160",prop:"calldate"}}),t._v(" "),a("el-table-column",{attrs:{label:"通话时长","min-width":"120",prop:"bill"}}),t._v(" "),a("el-table-column",{attrs:{label:"接通状态","min-width":"120",prop:"hangupcause"}}),t._v(" "),a("el-table-column",{attrs:{label:"客户类型","min-width":"120",prop:"type"}}),t._v(" "),a("el-table-column",{attrs:{label:"操作","min-width":"120"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("el-button",{attrs:{size:"mini",type:"text"},on:{click:function(a){t.showRecord(e.row)}}},[t._v("\n                                                        录音\n                                                    ")])]}}])})],1)],1)]),t._v(" "),a("el-tab-pane",{attrs:{label:"备注历史"}},[a("div",[a("el-table",{staticStyle:{width:"100%"},attrs:{"max-height":"200",border:"",data:t.service_remarks}},[a("el-table-column",{attrs:{prop:"remarkdate",label:"日期",width:"200"}}),t._v(" "),a("el-table-column",{attrs:{prop:"content",label:"备注",width:"580"}})],1)],1)])],1)],1),t._v(" "),1==t.callRecordsForm.exist?a("el-form-item",{staticStyle:{"text-align":"right",width:"100%"}},[a("el-button",{attrs:{type:"warning"},on:{click:function(e){t.Approval(1,t.callRecordsForm.id)}}},[t._v("修改")]),t._v(" "),a("el-button",{attrs:{type:"success"},on:{click:function(e){t.Approval(2,t.callRecordsForm.id)}}},[t._v("通过")])],1):t._e()],1)],1)]),t._v(" "),a("el-col",{staticClass:"toolbar",staticStyle:{"margin-top":"10px"},attrs:{span:24}},[a("el-pagination",{staticStyle:{float:"right"},attrs:{layout:"prev, pager, next","page-size":t.pagesize,total:t.total},on:{"current-change":t.handleCurrentChange}})],1),t._v(" "),a("el-dialog",{attrs:{title:"录音",visible:t.recordDialog,width:"400px"},on:{"update:visible":function(e){t.recordDialog=e}}},[a("audio",{attrs:{src:t.cur_record,controls:""}})])],1)])},[],!1,null,"2ce97c96",null);m.options.__file="CrmListDetails.vue";e.default=m.exports},YEIV:function(t,e,a){"use strict";e.__esModule=!0;var r=function(t){return t&&t.__esModule?t:{default:t}}(a("SEkw"));e.default=function(t,e,a){return e in t?(0,r.default)(t,e,{value:a,enumerable:!0,configurable:!0,writable:!0}):t[e]=a,t}},hkRt:function(t,e,a){},"pZP+":function(t,e,a){"use strict";var r={};function l(t){return("00"+t).substr(t.length)}r.delay=function(){var t=0;return function(e,a){clearTimeout(t),t=setTimeout(e,a)}}(),r.formatDate=function(t,e){/(y+)/.test(e)&&(e=e.replace(RegExp.$1,(t.getFullYear()+"").substr(4-RegExp.$1.length)));var a={"M+":t.getMonth()+1,"d+":t.getDate(),"h+":t.getHours(),"m+":t.getMinutes(),"s+":t.getSeconds()};for(var r in a)if(new RegExp("("+r+")").test(e)){var o=a[r]+"";e=e.replace(RegExp.$1,1===RegExp.$1.length?o:l(o))}return e},r.timer=function(t){var e={toDo:t.toDo||function(){},didStop:t.didStop||function(){},interval:t.interval||1e3,repeats:t.repeats||!0},a=null,r=1,l={clear:function(){clearInterval(a),e.didStop()}};return a=setInterval(function(){e.repeats?e.toDo(l):r>0?(r--,e.toDo(l)):(clearInterval(a),a=null,e.didStop())},e.interval)},r.getPar=function(t,e){var a=document.location.href,r=(a=decodeURI(a)).indexOf(t+"=");if(-1===r)return"";var l=a.slice(t.length+r+1);if(t===e)return l;var o=l.indexOf("&");return-1!==o&&(l=l.slice(0,o)),l},r.getUrlParams=function(t){var e=document.location.href;if(-1!=e.indexOf("?")){var a=e.substr(1).split("?")[1];a=a.split("&");for(var r=0;r<a.length;r++){var l=a[r].split("=")[0],o=a[r].split("=")[1];if(l==t)return o}}return""},r.setCookie=function(t,e,a){var r=new Date;r.setTime(r.getTime()+60*a*60*1e3);r.toUTCString();document.cookie=t+"="+e+"; path=/"},r.delCookie=function(t){var e=new Date;e.setTime(e.getTime()-1);var a=r.getCookie(t);null!==a&&(document.cookie=t+"="+a+";path=/;expires="+e.toUTCString())},r.getCookie=function(t){for(var e=t+"=",a=document.cookie.split(";"),r=0;r<a.length;r++){for(var l=a[r];" "==l.charAt(0);)l=l.substring(1);if(-1!=l.indexOf(e))return decodeURI(l.substring(e.length,l.length))}return""},r.getCurrentCookie=function(t){for(var e=t+"=",a=document.cookie.split(";"),r=0;r<a.length;r++){for(var l=a[r];" "==l.charAt(0);)l=l.substring(1);if(-1!=l.indexOf(e))return decodeURI(l.substring(e.length,l.length))}return""},e.a=r},yybV:function(t,e,a){"use strict";a.d(e,"E",function(){return l}),a.d(e,"x",function(){return o}),a.d(e,"k",function(){return n}),a.d(e,"t",function(){return i}),a.d(e,"D",function(){return c}),a.d(e,"o",function(){return s}),a.d(e,"C",function(){return u}),a.d(e,"j",function(){return d}),a.d(e,"w",function(){return m}),a.d(e,"I",function(){return p}),a.d(e,"H",function(){return f}),a.d(e,"v",function(){return b}),a.d(e,"l",function(){return h}),a.d(e,"y",function(){return v}),a.d(e,"h",function(){return g}),a.d(e,"i",function(){return _}),a.d(e,"B",function(){return y}),a.d(e,"F",function(){return x}),a.d(e,"c",function(){return w}),a.d(e,"r",function(){return R}),a.d(e,"n",function(){return k}),a.d(e,"z",function(){return C}),a.d(e,"a",function(){return F}),a.d(e,"p",function(){return S}),a.d(e,"A",function(){return O}),a.d(e,"m",function(){return D}),a.d(e,"d",function(){return j}),a.d(e,"G",function(){return I}),a.d(e,"b",function(){return T}),a.d(e,"q",function(){return $}),a.d(e,"s",function(){return A}),a.d(e,"u",function(){return z}),a.d(e,"e",function(){return E}),a.d(e,"g",function(){return M}),a.d(e,"f",function(){return P});var r=a("t3Un");function l(t){return Object(r.a)({url:"/userinfo",method:"post",data:t})}function o(t){return Object(r.a)({url:"/mobilelist",method:"post",data:t})}function n(t){return Object(r.a)({url:"/crmListApi",method:"post",data:t})}function i(t){return Object(r.a)({url:"/getCrmDetails",method:"post",data:t})}function c(t){return Object(r.a)({url:"/userlist",method:"post",data:t})}function s(t){return Object(r.a)({url:"/downchange",method:"post",data:t})}function u(t){return Object(r.a)({url:"/showchange",method:"post",data:t})}function d(t){return Object(r.a)({url:"/creckpc",method:"post",data:t})}function m(t){return Object(r.a)({url:"/lefthf",method:"post",data:t})}function p(t){return Object(r.a)({url:"/ywuserlist",method:"post",data:t})}function f(t){return Object(r.a)({url:"/yeuserlist",method:"post",data:t})}function b(t){return Object(r.a)({url:"/hfuserlist",method:"post",data:t})}function h(t){return Object(r.a)({url:"/delnumpc",method:"post",data:t})}function v(t){return Object(r.a)({url:"/mobilefp",method:"post",data:t})}function g(t){return Object(r.a)({url:"/chargefp",method:"post",data:t})}function _(t){return Object(r.a)({url:"/countdatalist",method:"post",data:t})}function y(t){return Object(r.a)({url:"/setremarks",method:"post",data:t})}function x(t){return Object(r.a)({url:"/voicelist",method:"post",data:t})}function w(t){return Object(r.a)({url:"/addvoice",method:"post",data:t})}function R(t){return Object(r.a)({url:"/editvoice",method:"post",data:t})}function k(t){return Object(r.a)({url:"/delvoice",method:"post",data:t})}function C(t){return Object(r.a)({url:"/patterlist",method:"post",data:t})}function F(t){return Object(r.a)({url:"/addpatter",method:"post",data:t})}function S(t){return Object(r.a)({url:"/editpatter",method:"post",data:t})}function O(t){return Object(r.a)({url:"/patterwordlist",method:"post",data:t})}function D(t){return Object(r.a)({url:"/delPatterWord",method:"post",data:t})}function j(t){return Object(r.a)({url:"/allvoice",method:"post",data:t})}function I(t){return Object(r.a)({url:"/wordlist",method:"post",data:t})}function T(t){return Object(r.a)({url:"/addpatterword",method:"post",data:t})}function $(t){return Object(r.a)({url:"/editpatterword",method:"post",data:t})}function A(t){return Object(r.a)({url:"/getPatterWord",method:"post",data:t})}function z(t){return Object(r.a)({url:"/getTaskState",method:"post",data:t})}function E(t){return Object(r.a)({url:"/approvalApi",method:"post",data:t})}function M(t){return Object(r.a)({url:"/changeOpenState",method:"post",data:t})}function P(t){return Object(r.a)({url:"/changeCloseState",method:"post",data:t})}}}]);