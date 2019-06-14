<template>
    <div class="app-container">
        <div class="filter-container">

            <!--工具条-->
            <el-row :span="80" class="top_toolbar">
                <el-col :span="24">
                    <span style="color: #555;font-size: 16px;margin-left: 10px;">通话日期：</span>
                    <el-date-picker :editable="false" size="small" v-model="CallDateTime" type="daterange"
                                    placeholder="通话开始日期"
                                    align="right"
                                    :picker-options="pickerOptions">
                    </el-date-picker>
                </el-col>
            </el-row>
            <el-row>
                <br/>
            </el-row>
            <el-row :span="8" class="top_toolbar">
                <!--条件搜索-->
                <el-form :inline="true" style="display: inline-block;padding: 0;">
                    <el-form-item>
                        <el-cascader
                                placeholder="客户类型"
                                :options="options"
                                v-model="crmlisttype">
                        </el-cascader>
                    </el-form-item>
                </el-form>

                <el-form :inline="true" style="display: inline-block;padding: 0;">
                    <el-form-item>
                        <el-input v-model="crmlistcompany" placeholder="公司名称" style="width: 120px"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-input v-model="crmlistmobile" placeholder="联系电话" style="width: 120px"></el-input>
                    </el-form-item>
                    <el-form-item style="margin-left: 10px">
                        <el-button type="primary" v-on:click="getTable">查询</el-button>
                    </el-form-item>
                </el-form>
            </el-row>

            <!--列表-->
            <el-table :data="table_list" class="auto_table" height="100%" v-loading="tableLoading" border
                      style="width: 100%;" @selection-change="handleSelectionChange">
                <!--<el-table-column align="center" type="selection" width="55"></el-table-column>-->
                <el-table-column align="center" type="index" label="序号" width="66"></el-table-column>
                <el-table-column align="center" prop="company" label="公司名称"></el-table-column>
                <el-table-column align="center" prop="username" label="客户姓名"></el-table-column>
                <el-table-column align="center" label="类型">
                    <template slot-scope="scope">
                        <span style="margin-left: 10px">{{ getFormatType(scope.row) }}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" prop="mobile" label="联系电话"></el-table-column>
                <el-table-column align="center" prop="remark" label="最新备注"></el-table-column>
                <el-table-column align="center" prop="addtime" label="添加时间"></el-table-column>
                <el-table-column align="center" label="操作" width="120" fixed="right">
                    <template slot-scope="scope">
                        <el-button type="text" size="small" @click="showDetails(scope.row)">查看详情</el-button>
                    </template>
                </el-table-column>
            </el-table>
            <!--详情表单-->
            <el-dialog :visible.syanc="isShowRecords" title="客户录入" width="900px"
                       :before-close="closeCallRecorder" :center="true">
                <div class="callRecorderContainer">
                    <el-form ref="callForm" :model="callRecordsForm" label-width="100px" class="demo-ruleForm">
                        <div class="callRecorderItem">
                            <el-form-item label="客户名称:" class="formItem">
                                <el-input size="mini" v-model="callRecordsForm.customerName"></el-input>
                            </el-form-item>
                            <el-form-item label="客户性别:" class="formItem">
                                <el-select size="mini" v-model="callRecordsForm.customerSex" placeholder="请选择性别">
                                    <el-option label="先生/male" value="1"></el-option>
                                    <el-option label="女士/female" value="0"></el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item label="添加微信:" class="formItem">
                                <el-select size="mini" v-model="callRecordsForm.isaddwx" placeholder="请选择是或否">
                                    <el-option label="是" value="1"></el-option>
                                    <el-option label="否" value="0"></el-option>
                                </el-select>
                            </el-form-item>
                        </div>
                        <div class="callRecorderItem">
                            <!--<el-form-item label="客户类型:" class="formItem">-->
                            <!--<el-input size="mini" v-model="callRecordsForm.customerType"></el-input>-->
                            <!--</el-form-item>-->
                            <el-form-item label="客户类型:" class="formItem">
                                <el-cascader
                                        size="mini"
                                        :options="options"
                                        v-model="callRecordsForm.customerType">
                                </el-cascader>
                            </el-form-item>
                            <el-form-item label="联系电话:" class="formItem">
                                <el-input size="mini" v-model="callRecordsForm.relationPhone"></el-input>
                            </el-form-item>
                            <el-form-item label="电子邮箱:" class="formItem">
                                <el-input size="mini" v-model="callRecordsForm.email"></el-input>
                            </el-form-item>
                        </div>
                        <div class="callRecorderItem">
                            <el-form-item label="公司名称:" class="formItem">
                                <el-input size="mini" v-model="callRecordsForm.company"></el-input>
                            </el-form-item>
                            <el-form-item label="联系地址:" class="formItem">
                                <el-input size="mini" v-model="callRecordsForm.relationAddress"></el-input>
                            </el-form-item>
<!--                            <el-form-item label="添加备注:" class="formItem">-->
<!--                                <el-input size="mini" v-model="callRecordsForm.addRemarks"></el-input>-->
<!--                            </el-form-item>-->
                            <el-form-item label="跟进阶段:" class="formItem">
                                <el-select size="mini" v-model="callRecordsForm.stage" placeholder="请选择">
                                    <el-option label="线索" value="0"></el-option>
                                    <el-option label="AI初访" value="1"></el-option>
                                    <el-option label="人工初访" value="2"></el-option>
                                    <el-option label="意向" value="3"></el-option>
                                    <el-option label="报价" value="4"></el-option>
                                    <el-option label="成交" value="5"></el-option>
                                </el-select>
                            </el-form-item>
                        </div>
                        <div class="appointmentDate">
                            <el-form-item class="appointmentItem">
                                <el-checkbox v-model="callRecordsForm.appointment">添加预约</el-checkbox>
                            </el-form-item>
                            <el-form-item class="appointmentItem" label="预约时间:">
                                <el-date-picker size="mini"
                                                v-model="callRecordsForm.appointmentDate" type="datetime"
                                                placeholder="选择预约日期时间" default-time="12:00:00">
                                </el-date-picker>
                            </el-form-item>
                            <el-form-item class="appointmentItem" label="预约内容:">
                                <el-input size="mini" type="textarea"
                                          v-model="callRecordsForm.appointContent"></el-input>
                            </el-form-item>
                        </div>
                        <div class="historyCallList">
                            <el-tabs type="border-card">
                                <el-tab-pane label="服务记录">
                                    <div>
                                        <el-table class="callRecorderTable" :data="service_recording" height="200">
                                            <el-table-column label="呼叫主体" min-width="80"
                                                             prop="username"></el-table-column>

                                            <el-table-column label="拨打时间" min-width="160" prop="calldate">
                                            </el-table-column>

                                            <el-table-column label="通话时长" min-width="120" prop="bill">
                                            </el-table-column>

                                            <el-table-column label="接通状态" min-width="120" prop="hangupcause">
                                            </el-table-column>

                                            <el-table-column label="客户类型" min-width="120" prop="type">
                                            </el-table-column>

                                            <el-table-column label="操作" min-width="120">
                                                <template slot-scope="scope">
                                                    <el-button size="mini" type="text" @click="showRecord(scope.row)">
                                                        录音
                                                    </el-button>
                                                </template>
                                            </el-table-column>
                                        </el-table>
                                    </div>
                                </el-tab-pane>
                                <el-tab-pane label="备注历史">
                                    <div>
                                        <el-table max-height="200" border :data="service_remarks" style="width: 100%">
                                            <el-table-column prop="remarkdate" label="日期" width="200">
                                            </el-table-column>
                                            <el-table-column prop="content" label="备注" width="580">
                                            </el-table-column>
                                        </el-table>
                                    </div>
                                </el-tab-pane>
                            </el-tabs>
                        </div>
                        <el-form-item v-if="callRecordsForm.exist == 1" style="text-align:right;width: 100%">
                            <el-button type="warning" @click="Approval(1,callRecordsForm.id)">修改</el-button>
                            <el-button type="success" @click="Approval(2,callRecordsForm.id)">通过</el-button>
                        </el-form-item>
                    </el-form>
                </div>
            </el-dialog>
            <!--页码条-->
            <el-col :span="24" class="toolbar" style="margin-top: 10px">
                <el-pagination layout="prev, pager, next" @current-change="handleCurrentChange" :page-size="pagesize"
                               :total="total" style="float:right;"></el-pagination>
            </el-col>

            <!--录音弹窗-->
            <el-dialog
                    title="录音"
                    :visible.sync="recordDialog"
                    width="400px">
                <audio :src="cur_record" controls></audio>
            </el-dialog>
        </div>
    </div>
</template>

<script>
    import {getToken, setToken, removeToken} from '@/utils/auth'
    import {crmlistapi, getcrmdetails, approvalapi} from '@/api/Number'
    import tool from '@/config/tools'

    export default {
        name: "CallCharge",
        data() {
            return {
                table_snum: 0,
                distribute_dialog: false,
                table_list: [],
                tableLoading: false,
                total: 1000,
                page: 1,
                pagesize: 10,
                crmlisttype: [],
                crmlistcompany: '',
                crmlistmobile: '',
                disnum: null,
                diszid: '',
                multipleSelection: [],
                isDistributing: false,
                token: "",
                options: [
                    {
                        value: '',
                        label: '全部',
                    }, {
                        value: 'new_customer',
                        label: '新客户',
                        children: [{
                            value: 'new_levelA',
                            label: 'A类高意向（已询价）',
                        }, {
                            value: 'new_levelB',
                            label: 'B类中等意向（加微信）',
                        }, {
                            value: 'new_levelC',
                            label: 'C类低意向',
                        }]
                    }, {
                        value: 'old_customer',
                        label: '老客户',
                        // children: [{
                        //     value: 'old_levelA',
                        //     label: '50台以上',
                        // }, {
                        //     value: 'old_levelB',
                        //     label: '10-20台',
                        // }, {
                        //     value: 'old_levelC',
                        //     label: '10台以下',
                        // }]
                    }, {
                        value: 'unintentional',
                        label: '无意向',
                    }, {
                        value: 'unreachable',
                        label: '未接通',
                    }, {
                        value: 'autoaddwechat',
                        label: '自动添加微信'
                    },
                    {
                        value: 'nodeal',
                        label: '无需处理'
                    },],

                service_recording: [],
                service_remarks: [],
                pickerOptions: {
                    shortcuts: [{
                        text: '今天',
                        onClick(picker) {
                            picker.$emit('pick', new Date());
                        }
                    }, {
                        text: '昨天',
                        onClick(picker) {
                            const date = new Date();
                            date.setTime(date.getTime() - 3600 * 1000 * 24);
                            picker.$emit('pick', date);
                        }
                    }, {
                        text: '一周前',
                        onClick(picker) {
                            const date = new Date();
                            date.setTime(date.getTime() - 3600 * 1000 * 24 * 7);
                            picker.$emit('pick', date);
                        }
                    }],
                    disabledDate(date) { //disabledDate 文档上：设置禁用状态，参数为当前日期，要求返回 Boolean
                        return date.getTime() >= Date.now();
                    }
                },
                CallDateTime: '',
                isShowRecords: false,
                callRecordsForm: {
                    id: 0, callId: '', customerName: '', customerSex: '1', isaddwx: '1'
                    , relationPhone: '', email: '', customerType: ['unreachable'],
                    relationAddress: '', addRemarks: '', company: '', exist: 0, stage: '0',
                    appointment: false, appointmentDate: '', appointContent: '',exist: 0
                },

                // 录音弹窗数据
                recordDialog: false,
                cur_record: '',
            }
        },

        created() {
            this.table_id = this.$route.params && this.$route.params.id;
            this.token = getToken();
            this.getTable();
        },

        methods: {
            // 点击页码
            handleCurrentChange(val) {
                this.page = val;
                this.getTable();
            },
            // 请求表格数据
            getTable() {
                var params = {
                    uid: this.$route.params.id,
                    page: this.page,
                    starttime: this.formatDate(this.CallDateTime[0]),
                    endtime: this.formatDate(this.CallDateTime[1]),
                    type0: this.crmlisttype[0],
                    type1: this.crmlisttype[1],
                    crmcompany: this.crmlistcompany,
                    crmmobile: this.crmlistmobile,
                };
                this.tableLoading = true;

                crmlistapi(params).then(response => {
                    const res = response.data;
                    if (res.code == '0000') {
                        this.table_list = res.data.rows;
                        this.total = res.data.count;
                    } else {
                        this.$message.error(res.msg);
                    }
                    this.tableLoading = false
                }).catch(err => {
                    console.log(err);
                    this.tableLoading = false;
                    this.$message.error("网络错误");
                });
            },

            // 审批
            Approval(index,id){
                var params = {
                    index: index,
                    id: id,
                };
                approvalapi(params).then(response => {
                    const res = response.data;
                    if (res.code == '0000') {
                        this.$message.success(res.msg);
                    } else {
                        this.$message.error(res.msg);
                    }
                }).catch(err => {
                    this.$message.error("网络错误");
                });
            },

            //打开拨打输入记录弹框
            showDetails(row) {
                var params = {
                    id: row.id,
                };
                getcrmdetails(params).then(response => {
                    const res = response.data;
                    console.log(res)
                    if (res.code == '0000') {
                        if(res.data.isaddwx){
                            res.data.isaddwx = res.data.isaddwx.toString();
                        }
                        this.callRecordsForm = {
                            id: res.data.id,
                            exist: res.data.exist,
                            customerName: res.data.username,
                            customerSex: res.data.gender,
                            isaddwx: res.data.isaddwx.toString(),
                            relationPhone: res.data.mobile,
                            email: res.data.email,
                            customerType: res.data.type,
                            relationAddress: res.data.dizhi,
                            stage: res.data.stage.toString(),
                            // addRemarks: res.data.bz,
                            company: res.data.company,
                            appointment: res.data.isyuyue,
                            appointmentDate: res.data.yydateline,
                            appointContent: res.data.yytxt
                        }
                        this.service_recording = []
                        if (res.data.robotcall) {
                            res.data.robotcall.username = "任务"
                            res.data.robotcall.bill = this.getformatBill(res.data.robotcall.bill)
                            res.data.robotcall.hangupcause = this.getCallstate(res.data.robotcall.hangupcause)
                            res.data.robotcall.recordfile = this.getRecordFile(res.data.robotcall.recordfile)
                            this.service_recording.push(res.data.robotcall)
                        }
                        if (res.data.realcall.length > 0) {
                            for (let call of res.data.realcall) {
                                call.calldate = this.getCalldate(call.starttime)
                                call.bill = this.getformatBill(call.billsec *1000)
                                call.hangupcause = this.getCallstate(call.cause)
                                call.recordfile = this.getRecordFile(call.record_file)
                                this.service_recording.push(call)
                            }
                        }
                        this.service_remarks = []
                        if(res.data.remarks.length > 0){
                            for(let remark of res.data.remarks){
                                remark.remarkdate = this.getCalldate(remark.dateline)
                                this.service_remarks.push(remark)
                            }
                        }
                        this.isShowRecords = true;
                    } else {
                        this.$message.error(res.msg);
                    }
                }).catch(err => {
                    console.log(err);
                    this.$message.error("网络错误");
                });
            },

            //关闭拨打输入记录弹框
            closeCallRecorder() {
                this.isShowRecords = false;
                this.callRecordsForm = {
                    uid: 0,
                    callId: '',
                    customerName: '',
                    customerSex: '1',
                    isaddwx: '1',
                    relationPhone: '',
                    email: '',
                    customerType: ['unreachable'],
                    relationAddress: '',
                    stage: '0',
                    addRemarks: '',
                    company: '',
                    appointment: false,
                    appointmentDate: '',
                    appointContent: ''
                }
            },
            // 显示播放录音弹窗
            showRecord(row) {
                if (row.recordfile && row.recordfile.length > 0) {
                    this.cur_record = row.recordfile
                    this.recordDialog = true
                } else {
                    this.$message.error("当前通话无录音!")
                }
            },
            // 查看详情
            handleDistribute: function (index, row) {
                // window.location.href = "/index/index/distribute?id=" + row.id;
                this.$emit("distributeBack", row.id);
            },
            handleSelectionChange(val) {
                this.multipleSelection = val;
            },
            formatDate(time) {
                if (time) {
                    var date = new Date(time);
                    return tool.formatDate(date, 'yyyy-MM-dd');
                } else {
                    return "";
                }
            },
            getFormatType(row) {
                return row.type0 + row.type1
            },
            // 转换录音文件
            getRecordFile(file) {
                if (file) {
                    return file.replace('/usr/local/freeswitch/recordings', 'https://voice.bbxxjs.com/static/talk_recording/full_talk')
                } else {
                    return ''
                }
            },
            // 毫秒转换
            getformatBill(timestamp) {
                var second = parseInt(timestamp / 1000) % 60
                var minute = parseInt(timestamp / 1000 / 60) % 60
                var hour = parseInt(timestamp / 1000 / 60 / 60) % 60
                return (hour > 10 ? hour : "0" + hour) + ":" + (minute > 10 ? minute : "0" + minute) + ":" + (second > 10 ? second : "0" + second)
            },
            // 转换接通状态
            getCallstate(state) {
                if (state == "NORMAL_CLEARING") {
                    return "正常呼叫结束";
                } else if (state == "USER_BUSY") {
                    return "用户忙";
                } else if (state == "NO_ANSWER") {
                    return "无应答";
                } else {
                    return "其他";
                }
            },
            //转换拨打时间
            getCalldate(date) {
                var date = new Date(date*1000);//如果date为13位不需要乘1000
                var Y = date.getFullYear() + '-';
                var M = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '-';
                var D = (date.getDate() < 10 ? '0' + (date.getDate()) : date.getDate()) + ' ';
                var h = (date.getHours() < 10 ? '0' + date.getHours() : date.getHours()) + ':';
                var m = (date.getMinutes() <10 ? '0' + date.getMinutes() : date.getMinutes()) + ':';
                var s = (date.getSeconds() <10 ? '0' + date.getSeconds() : date.getSeconds());
                return Y+M+D+h+m+s;
            }
        },
        computed: {
            // isBatch(){
            //     if (this.diszid.toString().indexOf(",") >= 0){
            //         return true;
            //     }
            //     return false;
            // }
        }
    }
</script>

<style scoped>

    .filter-container {
        height: calc(100vh - 84px - 40px);
    }

    .filter-container:after {
        content: '';
        height: 0;
        width: 0;
        clear: both;
        display: block;
    }

    .auto_table {
        max-height: calc(100% - 224px);
        overflow: auto;
    }

    /*call records*/
    .callRecorderContainer {
    }

    .callRecorderContainer .callRecorderItem {
        width: 100%;
        font-size: 14px;
        display: flex;
        align-items: center;
        /*justify-content: space-between;*/
        padding: 10px;
    }

    .callRecorderContainer .callRecorderItem .formItem {
        width: 33%;
        margin-bottom: 0 !important;
    }

    .callRecorderContainer .appointmentDate {
        width: 100%;
        border-top: 1px solid #aaaaaa;
        padding: 10px 15px;
        display: flex;
        align-items: center;
    }

    .callRecorderContainer .appointmentDate .appointmentItem {
        margin-bottom: 0 !important;
    }

    .callRecorderContainer .historyCallList {
        width: 100%;
        margin-top: 10px;
        padding: 10px;
    }

    .callRecorderContainer .historyCallList .callRecorderTable {
        border: 1px solid #aaa;
        font-size: 13px;
        border-collapse: collapse;
        width: 100%;
    }

    .callRecorderContainer .historyCallList .callRecorderTable tr th {
        border-right: 1px solid #aaa;
    }

    .callRecorderContainer .historyCallList .callRecorderTable tr td {
        padding: 5px 0;
        border-right: 1px solid #aaa;
    }

</style>