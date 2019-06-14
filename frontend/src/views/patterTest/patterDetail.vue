<template>
    <div class="app-container">
        <div class="filter-container">
            <div style='font-size: 50px;'>
                {{title}}
            </div>
            <router-link :to="'patterWordEdit?pid='+pid" class="link-type">
                <el-button type="success" icon="el-icon-upload2">添加话术流程</el-button>
            </router-link>
            <span style="color: red;padding-left: 15px">话术修改即时生效，删除不可恢复，请谨慎操作</span>

        </div>
        <div class="filter-container">
            <el-select filterable v-model="filter_type" style="width: 150px;" placeholder="请选择类别" @change="handleChangeType">
                <el-option label="全部话术" value="0"></el-option>
                <el-option v-for="item in filter_types" :key="item.type" :label="item.title" :value="item.type"></el-option>
            </el-select>
        </div>

        <!--列表-->
        <el-table class="tableListView" :data="PatterWordList" border
                  style="width: 100%;min-width: 600px;"
                  v-loading="tableLoading" :header-cell-style="tableHeaderColor">
            <el-table-column type="selection" width="55" align="center" label="全选"></el-table-column>
            <el-table-column align="center" prop="id" min-width="50" label="语句ID"></el-table-column>
            <el-table-column align="center" prop="sign" min-width="50" label="语句简称"></el-table-column>
            <el-table-column align="center" min-width="50" label="语句类型">
                <template slot-scope="scope">
                    <span>{{ getType(scope.row.type) }}</span>
                </template>
            </el-table-column>
            <el-table-column align="center" prop="word_base_name" min-width="80" label="关键词"></el-table-column>
            <el-table-column align="center" prop="score" min-width="50" label="分值"></el-table-column>
            <el-table-column align="center" prop="word" min-width="300" label="应答语句"></el-table-column>
            <el-table-column align="center" prop="y_word" min-width="50" label="肯定跳转"></el-table-column>
            <el-table-column align="center" prop="u_word" min-width="50" label="中性跳转"></el-table-column>
            <el-table-column align="center" prop="n_word" min-width="50" label="否定跳转"></el-table-column>
            <el-table-column align="center" prop="scene_name" min-width="80" label="情景关键词"></el-table-column>
            <el-table-column align="center" prop="role" min-width="80" label="角色"></el-table-column>
            <el-table-column align="center" prop="label" min-width="80" label="标签"></el-table-column>
            <el-table-column align="center" min-width="120" label="操作">
                <template slot-scope="scope">
                    <router-link :to="'patterWordEdit?pid='+pid+'&wid='+scope.row.id" class="link-type">
                        <el-button type="primary" plain size="small">编辑</el-button>
                    </router-link>
                    <el-button type="danger" plain size="small" @click="PatterWordDel(scope.$index, scope.row)">刪除
                    </el-button>
                </template>
            </el-table-column>
        </el-table>
        <!--页码条-->
        <el-col :span="24" class="toolbar" style="margin-top: 10px">
            <el-pagination layout="prev, pager, next" @current-change="handleCurrentChange"
                           :page-size="pagesize"
                           :total="total" style="float:right;"></el-pagination>
        </el-col>
    </div>
</template>

<script>
    import { getToken, setToken, removeToken } from '@/utils/auth'
    import {patter1wordlist, delPatter1Word,getPatter1Info} from '@/api/patterTest'

    export default {
        name: "index",
        data() {
            return {
                title: "",
                token : getToken(),
                PatterWordList: [],
                tableLoading: false,
                pagesize: 30,
                page: 1,
                total: 0,
                isAddBtn: true,      //是否点击添加按钮
                pid: 0,
                addForm: {
                    word: '',
                    path: '',
                    id: '',
                },
                rules: {
                    word: [{required: true, message: '内容不能为空', trigger: 'blur'}],
                },
                dialogFormVisible: false,

                filter_type:'',
                filter_types:[
                    {
                        title:'非关键词',
                        type:1
                    },
                    {
                        title:'关键词',
                        type:2
                    }
                ],
            }
        },
        created() {
            if(this.$route.query && 'pid' in this.$route.query){
                this.pid = this.$route.query.pid
                this.getPatterWordList()
                this.getPatterInfo()
            }else{
                this.$message.error("话术不存在")
            }
        },
        methods: {
            getPatterWordList() {
                var params = {
                    token: this.token,
                    pid: this.pid,
                    page: this.page,
                    pagesize: this.pagesize,
                    type:this.filter_type,
                }
                this.tableLoading = true;
                patter1wordlist(params).then(response => {
                    let res = response.data
                    if(res.code == "0000"){
                        this.PatterWordList = res.data.rows
                        this.total = res.data.total
                        this.tableLoading = false
                    }else{
                        this.$message.error(res.msg)
                    }
                }).catch(err=>{
                    this.$message.error("获取话术详情列表失败")
                })
            },
            getPatterInfo(){
                var params = {
                    token: this.token,
                    pid: this.pid,
                }
                getPatter1Info(params).then(response => {
                    let res = response.data
                    if(res.code == "0000"){
                        this.title = res.data.title
                    }else{
                        this.$message.error(res.msg)
                    }
                }).catch(err=>{
                    this.$message.error("获取话术信息失败！")
                })
            },
            // 点击页码
            handleCurrentChange(val) {
                this.page = val;
                this.getPatterWordList()
            },
            // 点击类别
            handleChangeType() {
                this.page = 1;
                this.getPatterWordList()
            },
            // 修改table header的背景色
            tableHeaderColor({row, column, rowIndex, columnIndex}) {
                if (rowIndex === 0) {
                    return 'background-color: #f7f7f7;color: #363636;font-weight: 500;'
                }
            },
            getType(type) {
                if (type == 0) {
                    return '关键词'
                }
                if (type == 1) {
                    return '入口'
                }
                if (type == 2) {
                    return '主干'
                }
                if (type == 3) {
                    return '出错结束语'
                }
                if (type == 4) {
                    return '没说话的回答'
                }
                if (type == 5) {
                    return '没说话3次的结束语'
                }
                if (type == 6) {
                    return '否定三次的结束语'
                }
            },
            PatterWordDel(index,row){
                this.$confirm('此操作将永久删除该文件, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    delPatter1Word({wid:row.id}).then(response => {
                        if (response.data.code == '0000') {
                            this.$message.success("删除成功")
                            this.getPatterWordList()
                        }else {
                            this.$message.error(response.data.msg)
                        }
                    })
                })
            },
        }
    }
</script>

<style scoped>

</style>