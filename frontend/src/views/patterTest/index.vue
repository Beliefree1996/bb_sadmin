<template>
    <div class="app-container">
        <div class="filter-container">
            <el-button type="success" icon="el-icon-upload2" @click="showAddPatter">添加话术组</el-button>
        </div>

        <!--列表-->
        <el-table class="tableListView" :data="Patterlist" border
                  style="width: 100%;min-width: 600px;"
                  v-loading="tableLoading" :header-cell-style="tableHeaderColor">
            <el-table-column type="selection" width="55" align="center" label="全选"></el-table-column>
            <el-table-column align="center" type="index" width="66" label="序号" fixed="left"></el-table-column>
            <el-table-column align="center" prop="title" min-width="100" label="话术组标题"></el-table-column>
            <el-table-column align="center" prop="score_a" min-width="100" label="A类分值"></el-table-column>
            <el-table-column align="center" prop="score_b" min-width="100" label="B类分值"></el-table-column>
            <el-table-column align="center" prop="bill_a" min-width="100" label="A类通话时长(单位：秒)"></el-table-column>
            <el-table-column align="center" prop="bill_b" min-width="100" label="B类通话时长(单位：秒)"></el-table-column>
            <el-table-column align="center" prop="time" min-width="150" label="更新时间"></el-table-column>
            <el-table-column align="center" min-width="200" label="操作">
                <template slot-scope="scope">
                    <router-link :to="'patterDetail?pid='+scope.row.id" class="link-type">
                        <el-button type="success" plain size="small">详情</el-button>
                    </router-link>
                    <el-button type="primary" plain size="small" @click="showPatterUpdate(scope.$index, scope.row)">编辑</el-button>
                    <router-link :to="'roleManage?pid='+scope.row.id" class="link-type">
                        <el-button type="success" plain size="small">角色管理</el-button>
                    </router-link>
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
        <el-dialog :title="isAddBtn?'添加话术组':'更新话术组'" :visible.sync="patterDialog" width="600px">
            <el-form :model="patterForm" :rules="rules" ref="patterForm" size="small" label-width="180px">
                <el-form-item label="请输入话术组标题">
                    <el-input type="text" placeholder="请输入话术组标题" v-model="patterForm.title"></el-input>
                </el-form-item>
                <el-form-item label="请选择话术组版本">
                    <el-select v-model="patterForm.version" placeholder="请选择话术组版本">
                        <el-option v-for="item in versions" :key="item.value" :label="item.title" :value="item.value"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="请输入A类分值">
                    <el-input-number v-model="patterForm.score_a" :min="0" :max="100" label="请输入A类分值"></el-input-number>
                </el-form-item>
                <el-form-item  label="请输入B类分值">
                    <el-input-number v-model="patterForm.score_b" :min="0" :max="100" label="请输入B类分值"></el-input-number>
                </el-form-item>
                <el-form-item  label="请输入A类通话时长(单位：秒)">
                    <el-input-number v-model="patterForm.bill_a" :min="0" label="请输入A类通话时长(单位：秒)"></el-input-number>
                </el-form-item>
                <el-form-item  label="请输入B类通话时长(单位：秒)">
                    <el-input-number v-model="patterForm.bill_b" :min="0" label="请输入B类通话时长(单位：秒)"></el-input-number>
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
    import { patter1list, addpatter1, editpatter1 } from '@/api/patterTest'

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
                patterForm: {
                    title: '',
                    version: 2,
                    id:'',
                    score_a:0,
                    score_b:0,
                    bill_a:0,
                    bill_b:0,
                    token:'',
                },
                rules: {
                    title: [{required:true, message: '内容不能为空', trigger: 'blur'}],
                },
                patterDialog: false,
                token : getToken(),
                versions:[
                    {
                        value:0,
                        title:"版本1"
                    },
                    {
                        value:1,
                        title:"版本2"
                    },
                    {
                        value:2,
                        title:"版本3"
                    },
                ]
            }
        },
        created(){
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
                patter1list(params).then(response => {

                    this.Patterlist = response.data.data.rows
                    this.total = response.data.data.total

                    this.tableLoading = false
                })
            },
            showAddPatter(){
                this.patterDialog = true;
                this.isAddBtn = true;
                this.patterForm.token = this.token;
                this.patterForm.title = "";
                this.patterForm.score_a = 0;
                this.patterForm.score_b = 0;
                this.patterForm.bill_a = 0;
                this.patterForm.bill_b = 0;
                this.$nextTick(() => {
                    this.$refs['patterForm'].clearValidate()
                })
            },
            showPatterUpdate(index, row){
                this.patterDialog = true;
                this.isAddBtn = false;
                this.patterForm.token = this.token;
                this.patterForm["id"] = row.id;
                this.patterForm["title"] = row.title;
                this.patterForm["version"] = row.version;
                this.patterForm["score_a"] = row.score_a;
                this.patterForm["score_b"] = row.score_b;
                this.patterForm["bill_a"] = row.bill_a;
                this.patterForm["bill_b"] = row.bill_b;
                this.$nextTick(() => {
                    this.$refs['patterForm'].clearValidate()
                })
            },
            PatterDel(index, row){

            },
            PatterAction(){
                this.$refs['patterForm'].validate((valid) => {
                    if (valid) {
                        var params = this.patterForm;
                        if (this.isAddBtn) {
                            this.addPatter(params);
                        } else {
                            this.updatePatter(params);
                        }
                    } else {
                        return false;
                    }
                });
            },
            addPatter(pp){
                addpatter1(pp).then(response => {
                    if (response.data.code == '0000') {
                        this.$message.success(response.data.msg)
                        this.patterDialog = false;
                        this.getPatterlist()
                    }else {
                        this.$message.error(response.data.msg)
                        this.patterDialog = false;
                    }
                })
            },
            updatePatter(pp){
                editpatter1(pp).then(response => {
                    if (response.data.code == '0000') {
                        this.$message.success(response.data.msg)
                        this.patterDialog = false;
                        this.getPatterlist()
                    }else {
                        this.$message.error(response.data.msg)
                        this.patterDialog = false;
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