<template>
    <div class="app-container">
        <div class="filter-container">
            <div style='font-size: 50px;'>
                {{title}}
            </div>
            <el-button type="primary" icon="el-icon-upload2" @click="addNewRole()">添加新的角色</el-button>
            <span style="color: red;padding-left: 15px">角色修改即时生效，删除不可恢复，请谨慎操作</span>
        </div>

<!--        <div class="filter-container">-->
<!--            <el-select filterable v-model="filter_type" style="width: 150px;" placeholder="请选择类别" @change="handleChangeType">-->
<!--                <el-option label="全部话术" value="0"></el-option>-->
<!--                <el-option v-for="item in filter_types" :key="item.type" :label="item.title" :value="item.type"></el-option>-->
<!--            </el-select>-->
<!--        </div>-->

        <!--列表-->
        <el-table class="tableListView" :data="RoleManageList" border
                  style="width: 100%;min-width: 600px;"
                  v-loading="tableLoading" :header-cell-style="tableHeaderColor">
            <el-table-column type="selection" width="55" align="center" label="全选"></el-table-column>
            <el-table-column align="center" prop="id" min-width="50" label="角色ID"></el-table-column>
            <el-table-column align="center" prop="common" min-width="50" label="名称"></el-table-column>
            <el-table-column align="center" prop="weights" min-width="50" label="权重"></el-table-column>
            <el-table-column align="center" min-width="120" label="操作">
                <template slot-scope="scope">
                    <el-button type="primary" plain size="small" @click="updateRole(scope.row)">编辑</el-button>
<!--                    <el-button type="danger" plain size="small" @click="PatterWordDel(scope.$index, scope.row)">刪除</el-button>-->
                </template>
            </el-table-column>
        </el-table>

        <!--对话框-->
        <el-dialog :title="isAddBtn?'添加角色':'更新角色信息'" :visible.sync="dialogFormVisible" width="480px">
            <el-form :model="addForm" :rules="rules" ref="addForm" size="small" >
                <el-form-item label="名称：">
                    <el-input type="text" :rows="4" placeholder="请输入名称" v-model="addForm.name"></el-input>
                </el-form-item>
                <el-form-item label="权重：">
                    <el-input type="text" :rows="4" placeholder="请输入权重值" v-model="addForm.weights"></el-input>
                </el-form-item>

                <div style="margin-top: 15px; display: flex; justify-content: flex-end;">
                    <el-button type="primary" @click="RoleAction()">{{isAddBtn?'确定':'更新'}}</el-button>
                </div>
            </el-form>
        </el-dialog>

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
    import {rolemanagelist, delPatter1Word,getPatter1Info, changespeechrole} from '@/api/patterTest'

    export default {
        name: "index",
        data() {
            return {
                title: "",
                token : getToken(),
                RoleManageList: [],
                tableLoading: false,
                pagesize: 30,
                page: 1,
                total: 0,
                isAddBtn: true,      //是否点击添加按钮
                pid: 0,
                addForm: {
                    flag: 0,    // 0是add，1是update
                    name: '',
                    weights: 3,
                    id:'',      // 列表id
                    pid: '',    // 话术id
                },
                rules: {
                    name: [{required: true, message: '内容不能为空', trigger: 'blur'}],
                },
                dialogFormVisible: false,
            }
        },

        created() {
            if(this.$route.query && 'pid' in this.$route.query){
                this.pid = this.$route.query.pid;
                this.getRoleManageList();
                this.getPatterInfo()
            }else{
                this.$message.error("话术不存在")
            }
        },

        methods: {
            getRoleManageList() {
                var params = {
                    token: this.token,
                    pid: this.pid,
                    page: this.page,
                    pagesize: this.pagesize,
                };
                this.tableLoading = true;
                rolemanagelist(params).then(response => {
                    let res = response.data;
                    console.log(res);
                    if(res.code == "0000"){
                        this.RoleManageList = res.data.rows;
                        this.total = res.data.total;
                        this.tableLoading = false
                    }else{
                        this.$message.error(res.msg)
                    }
                }).catch(err=>{
                    this.$message.error("获取角色管理列表失败")
                })
            },

            // 获取角色信息列表
            getPatterInfo(){
                var params = {
                    token: this.token,
                    pid: this.pid,
                }
                getPatter1Info(params).then(response => {
                    let res = response.data;
                    if(res.code == "0000"){
                        this.title = res.data.title
                    }else{
                        this.$message.error(res.msg)
                    }
                }).catch(err=>{
                    this.$message.error("获取话术信息失败！")
                })
            },

            // 添加角色
            addNewRole() {
                this.dialogFormVisible = true;
                this.isAddBtn = true;
                this.addForm["flag"] = 0;
                this.addForm["pid"] = this.pid;
                this.addForm["name"] = '';
                this.addForm["weights"] = 3;
                this.$nextTick(() => {
                    this.$refs['addForm'].clearValidate()
                })
            },

            // 更新角色信息
            updateRole(row) {
                this.dialogFormVisible = true;
                this.isAddBtn = false;
                this.addForm["flag"] = 1;
                this.addForm["id"] = row.id;
                this.addForm["name"] = row.common;
                this.addForm["weights"] = row.weights;
                this.$nextTick(() => {
                    this.$refs['addForm'].clearValidate()
                })
            },

            // 弹窗提交按钮
            RoleAction(){
                this.$refs['addForm'].validate((valid) => {
                    if (valid) {
                        var params = this.addForm;
                        this.changeSpeechRole(params);
                    } else {
                        return false;
                    }
                });
            },

            // 增加或更新角色
            changeSpeechRole(pp){
                changespeechrole(pp).then(response => {

                    if (response.data.code == '0000') {
                        this.$message.success(response.data.msg);
                        this.dialogFormVisible = false;
                        this.getRoleManageList()
                    }else {
                        this.$message.error(response.data.msg);
                        this.dialogFormVisible = false;
                    }
                })
            },

            // 点击页码
            handleCurrentChange(val) {
                this.page = val;
                this.getRoleManageList()
            },

            // // 点击类别
            // handleChangeType() {
            //     this.page = 1;
            //     this.getRoleManageList()
            // },

            // 修改table header的背景色
            tableHeaderColor({row, column, rowIndex, columnIndex}) {
                if (rowIndex === 0) {
                    return 'background-color: #f7f7f7;color: #363636;font-weight: 500;'
                }
            },

            // 删除操作
            // PatterWordDel(index,row){
            //     this.$confirm('此操作将永久删除该文件, 是否继续?', '提示', {
            //         confirmButtonText: '确定',
            //         cancelButtonText: '取消',
            //         type: 'warning'
            //     }).then(() => {
            //         delPatter1Word({wid:row.id}).then(response => {
            //             if (response.data.code == '0000') {
            //                 this.$message.success("删除成功")
            //                 this.getRoleManageList()
            //             }else {
            //                 this.$message.error(response.data.msg)
            //             }
            //         })
            //     })
            // },
        }
    }
</script>

<style scoped>

</style>