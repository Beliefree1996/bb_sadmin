<template>
    <div class="app-container">
        <div class="filter-container">
            <!--工具条-->
            <el-col :span="8" class="top_toolbar">
                <!--条件搜索-->
                <el-form :inline="true" :model="filters" style="display: inline-block;padding: 0;">
                    <el-form-item>
                        <el-input v-model="filters.name" placeholder="业务员名字" style="width: 120px"></el-input>
                    </el-form-item>
                    <el-form-item style="margin-left: 10px">
                        <el-button type="primary" v-on:click="getTable">查询</el-button>
                    </el-form-item>
                </el-form>
            </el-col>

            <!--列表-->
            <el-table :data="table_list"  class="auto_table" height="100%" v-loading="tableLoading" border
                      style="width: 100%;" @selection-change="handleSelectionChange">
                <el-table-column align="center" type="selection" width="55"></el-table-column>
                <el-table-column align="center" type="index" label="序号" width="66"></el-table-column>
                <el-table-column align="center" prop="username" label="业务员"></el-table-column>
                <el-table-column align="center" prop="amount" label="总任务数"></el-table-column>
                <el-table-column align="center" prop="opened" label="已开启任务数"></el-table-column>
            </el-table>

            <!--页码条-->
            <el-col :span="24" class="toolbar" style="margin-top: 10px">
                <el-button type="success" :disabled="this.multipleSelection.length === 0" @click="ChangeOpenState">开启所有任务
                </el-button>
                <el-button type="danger" :disabled="this.multipleSelection.length === 0" @click="ChangeCloseState">关闭所有任务
                </el-button>
            </el-col>
            <el-col :span="24" class="selectAdminView" style="margin-top: 20px">
                   <span v-show="multipleSelection.length !== 0" style="color: #555;margin-right: 10px">选中的业务员:
                   </span> <span class="selectAdminName" v-for="item in multipleSelection">
                    {{item.username}}</span>
            </el-col>
            <!--分配弹窗-->
        </div>
    </div>
</template>

<script>
    import { getToken, setToken, removeToken } from '@/utils/auth'
    import { gettaskstate, changeopenstate, changeclosestate } from '@/api/Number'

    export default {
        name: "IsOpenTask",
        data(){
            return{
                table_snum: 0,
                table_list: [],
                tableLoading: false,
                // total: 0,
                // page: 1,
                // pagesize: 10,
                filters: {name: ''},
                diszid: '',
                multipleSelection:[],
                token:"",
            }
        },
        created(){
            this.table_id = this.$route.params && this.$route.params.id
            this.token = getToken()
            this.getTable();
        },
        methods:{
            // 点击页码
            // handleCurrentChange(val) {
            //     this.page = val;
            //     this.getTable();
            // },
            // 请求表格数据
            getTable() {
                var _this = this;
                var params = {
                    token:_this.token,
                    // s: _this.page,
                    // p: _this.pagesize,
                };
                this.tableLoading = true;
                gettaskstate(params).then(response => {
                    const res = response.data;
                    if (res.code == '0000') {
                        this.table_list = res.data.rows;
                        // this.total = res.data.total;
                    } else {
                        this.$message.error(res.msg);
                    }
                    this.tableLoading = false
                }).catch(err => {
                    this.tableLoading = false;
                    this.$message.error("网络错误");
                });

            },
            handleSelectionChange(val){
                this.multipleSelection = val;
            },
            // 批量开启任务
            ChangeOpenState(){
                this.diszid = "";
                for (var i=0;i<this.multipleSelection.length;i++){
                    if (i!=this.multipleSelection.length-1){
                        this.diszid += this.multipleSelection[i].id.toString() +',';
                    }else{
                        this.diszid += this.multipleSelection[i].id.toString();
                    }
                }

                var _this = this;
                var Params = {
                    aidlist: this.diszid,
                };
                changeopenstate(Params).then(response => {
                    var res = response.data;
                    if (res.code == '0000') {
                        const h = _this.$createElement;
                        _this.getTable();
                    }else{
                        _this.$alert('任务状态切换失败，请重试', '切换失败', {
                            confirmButtonText: '确定'
                        });
                    }
                }).catch(err => {
                    _this.isDistributing = true;
                    _this.$message.error("网络加载失败！");
                });
            },

            // 批量关闭任务
            ChangeCloseState(){
                this.diszid = "";
                for (var i=0;i<this.multipleSelection.length;i++){
                    if (i!=this.multipleSelection.length-1){
                        this.diszid += this.multipleSelection[i].id.toString() +',';
                    }else{
                        this.diszid += this.multipleSelection[i].id.toString();
                    }
                }

                var _this = this;
                var Params = {
                    aidlist: this.diszid,
                };
                changeclosestate(Params).then(response => {
                    var res = response.data;
                    if (res.code == '0000') {
                        const h = _this.$createElement;

                        _this.getTable();
                    }else{
                        _this.$alert('任务状态切换失败，请重试', '切换失败', {
                            confirmButtonText: '确定'
                        });
                    }
                }).catch(err => {
                    _this.isDistributing = true;
                    _this.$message.error("网络加载失败！");
                });
            }
        },
        computed:{
            isBatch(){
                if (this.diszid.toString().indexOf(",") >= 0){
                    return true;
                }
                return false;
            }
        }
    }
</script>

<style scoped>
    .selectAdminView {
        overflow: auto;
        white-space: nowrap;
        vertical-align: middle;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        height: 40px;
    }

    .selectAdminName {
        margin-right: 5px;
        padding: 3px 10px;
        background-color: #3a8ee6;
        color: white;
        border-radius: 5px;
    }

    .filter-container{
        height: calc(100vh - 84px - 40px);
    }

    .filter-container:after{
        content: '';
        height: 0;
        width: 0;
        clear: both;
        display: block;
    }

    .auto_table{
        max-height: calc(100% - 144px);
        overflow: auto;
    }
</style>