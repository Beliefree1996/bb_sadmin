<template>
    <div class="app-container">
        <div class="filter-container">
            <el-button type="success" icon="el-icon-upload2" @click="AddPatter">添加话术组</el-button>
        </div>

        <!--列表-->
        <el-table class="tableListView" :data="Patterlist" border
                  style="width: 100%;min-width: 600px;"
                  v-loading="tableLoading" :header-cell-style="tableHeaderColor">
            <el-table-column type="selection" width="55" align="center" label="全选"></el-table-column>
            <el-table-column align="center" type="index" width="66" label="序号" fixed="left"></el-table-column>
            <el-table-column align="center" prop="title" min-width="400" label="话术组标题"></el-table-column>
            <el-table-column align="center" prop="time" min-width="150" label="更新时间"></el-table-column>
            <el-table-column align="center" min-width="200" label="操作">
                <template slot-scope="scope">
                    <router-link :to="'PatterWord/'+scope.row.id" class="link-type">
                        <el-button type="success" plain size="small">详情</el-button>
                    </router-link>
                    <el-button type="primary" plain size="small" @click="PatterUpdate(scope.$index, scope.row)">编辑标题</el-button>
                    <!--<el-button type="danger" plain size="small" @click="PatterDel(scope.$index, scope.row)">刪除</el-button>-->
                </template>
            </el-table-column>
        </el-table>
        <!--页码条-->
        <el-col :span="24" class="toolbar" style="margin-top: 10px">
            <el-pagination layout="prev, pager, next" @current-change="handleCurrentChange"
                           :page-size="pagesize"
                           :total="total" style="float:right;"></el-pagination>
        </el-col>

        <!--对话框-->
        <el-dialog :title="isAddBtn?'添加话术组':'更新话术组'" :visible.sync="dialogFormVisible" width="480px">
            <el-form :model="addForm" :rules="rules" ref="addForm" size="small" >
                <el-form-item prop="title">
                    <el-input type="text" placeholder="请输入话术组标题" v-model="addForm.title"></el-input>
                </el-form-item>

                <div style="margin-top: 15px; display: flex; justify-content: flex-end;">
                    <el-button type="primary" @click="PatterAction">{{isAddBtn?'确定':'更新'}}</el-button>
                </div>
            </el-form>
        </el-dialog>
    </div>
</template>
<script>
    import { getToken, setToken, removeToken } from '@/utils/auth'
    import { patterlist, addpatter, editpatter } from '@/api/Number'

    export default {
        name: "Patter",
        data(){
            return{
                Patterlist:[],
                tableLoading: false,
                pagesize: 30,
                page: 1,
                total: 0,
                isAddBtn: true,      //是否点击添加按钮
                addForm: {
                    title: '',
                    id:'',
                },
                rules: {
                    title: [{required:true, message: '内容不能为空', trigger: 'blur'}],
                },
                dialogFormVisible: false,
            }
        },
        created(){
            this.token = getToken()
            this.getPatterlist()
        },
        methods:{
            getPatterlist(){
                var params = {
                    token: this.token,
                    page: this.page,
                    pagesize: this.pagesize,
                }
                this.tableLoading = true;
                patterlist(params).then(response => {

                    this.Patterlist = response.data.data.rows
                    this.total = response.data.data.total

                    this.tableLoading = false
                })
            },
            AddPatter(){
                this.dialogFormVisible = true;
                this.isAddBtn = true;
                this.addForm["token"] = this.token;
                this.addForm["title"] = "";
                this.$nextTick(() => {
                    this.$refs['addForm'].clearValidate()
                })
            },
            PatterUpdate(index, row){
                this.dialogFormVisible = true;
                this.isAddBtn = false;
                this.addForm["id"] = row.id;
                this.addForm["title"] = row.title;
                this.$nextTick(() => {
                    this.$refs['addForm'].clearValidate()
                })
            },
            PatterDel(index, row){

            },
            PatterAction(){
                this.$refs['addForm'].validate((valid) => {
                    if (valid) {
                        var params = this.addForm;
                        if (this.isAddBtn) {
                            this.addPatter1(params);
                        } else {
                            this.updatePatter(params);
                        }
                    } else {
                        return false;
                    }
                });
            },
            addPatter1(pp){
                addpatter(pp).then(response => {
                    if (response.data.code == '0000') {
                        this.$message.success(response.data.msg)
                        this.dialogFormVisible = false;
                        this.getPatterlist()
                    }else {
                        this.$message.error(response.data.msg)
                        this.dialogFormVisible = false;
                    }
                })
            },
            updatePatter(pp){

                editpatter(pp).then(response => {

                    if (response.data.code == 0) {
                        this.$message.success(response.data.msg)
                        this.dialogFormVisible = false;
                        this.getPatterlist()
                    }else {
                        this.$message.error(response.data.msg)
                        this.dialogFormVisible = false;
                    }
                })
            },
            // 点击页码
            handleCurrentChange(val) {
                this.page = val;
            },
            // 修改table header的背景色
            tableHeaderColor({row, column, rowIndex, columnIndex}) {
                if (rowIndex === 0) {
                    return 'background-color: #f7f7f7;color: #363636;font-weight: 500;'
                }
            },
            handleRemove(file, fileList) {
                console.log(file, fileList);
            },
            handlePreview(file) {

            },
        }
    }
</script>

<style scoped>

</style>