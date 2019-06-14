<template>
    <div class="app-container">
        <div class="filter-container">
            <!--工具条-->
            <el-row :span="8" class="top_toolbar">
                <!--条件搜索-->
                <el-form :inline="true" :model="filters" style="display: inline-block;padding: 0;">
                    <el-form-item>
                        <el-input v-model="filters.name" placeholder="业务员名字" style="width: 120px"></el-input>
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
                <el-table-column align="center" prop="username" label="业务员"></el-table-column>
                <el-table-column align="center" prop="amount" label="机器人数量"></el-table-column>
                <el-table-column align="center" label="操作" width="120" fixed="right">
                    <template slot-scope="scope">
                        <router-link :to="'CrmListDetails/'+scope.row.id">
                            <el-button type="text" size="small">客户详情</el-button>
                        </router-link>
                    </template>
                </el-table-column>
            </el-table>
        </div>
    </div>
</template>

<script>
    import { getToken, setToken, removeToken } from '@/utils/auth'
    import { hfuserlist,lefthf,chargefp,extoexl } from '@/api/Number'
    import tool from '@/config/tools'

    export default {
        name: "CrmList",
        data(){
            return{
                table_snum: 0,
                distribute_dialog: false,
                table_list: [],
                tableLoading: false,
                // total: 0,
                // page: 1,
                // pagesize: 10,
                filters: {name: ''},
                disnum: null,
                diszid: '',
                multipleSelection:[],
                isDistributing:false,
                token:"",

            }
        },

        created(){
            this.table_id = this.$route.params && this.$route.params.id;
            this.token = getToken();
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
                    name: _this.filters.name
                };
                this.tableLoading = true;

                hfuserlist(params).then(response => {
                    const res = response.data;
                    if (res.code == '0000') {
                        this.table_list = res.data.rows;
                        // this.total = res.data.total;
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
            // 查看详情
            handleDistribute: function (index, row) {
                // window.location.href = "/index/index/distribute?id=" + row.id;
                this.$emit("distributeBack",row.id);
            },
            handleSelectionChange(val){
                this.multipleSelection = val;
            },
        },
        computed:{

        }
    }
</script>

<style scoped>

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
        max-height: calc(100% - 224px);
        overflow: auto;
    }
</style>