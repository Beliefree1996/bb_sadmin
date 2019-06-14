<template>
    <div class="app-container">
        <div class="filter-container">
            <el-button type="success" icon="el-icon-upload2" @click="showAddWordBase">添加关键词库</el-button>
        </div>

        <!--列表-->
        <el-table class="tableListView" :data="wordBaselist" border style="width: 100%;min-width: 600px;" v-loading="tableLoading" >
            <el-table-column align="center" type="index" width="66" label="序号" fixed="left"></el-table-column>
            <el-table-column align="center" prop="name" min-width="100" label="关键词库标题"></el-table-column>
            <el-table-column align="center" prop="addtime" min-width="150" label="添加时间"></el-table-column>
            <el-table-column align="center" min-width="200" label="操作">
                <template slot-scope="scope">
                    <router-link :to="'wordBaseDetail?bid='+scope.row.id" class="link-type">
                        <el-button type="success" plain size="small">详情</el-button>
                    </router-link>
                    <el-button type="primary" plain size="small" @click="showWordBaseUpdate(scope.$index, scope.row)">编辑</el-button>
                    <el-button type="danger" plain size="small" @click="wordBaserDel(scope.$index, scope.row)">刪除</el-button>
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
        <el-dialog :title="isAddBtn?'添加关键词库':'更新关键词库'" :visible.sync="wordBaseDialog" width="600px">
            <el-form :model="wordBaseForm" :rules="rules" ref="wordBaseForm" size="small" label-width="180px">
                <el-form-item label="请输入关键词库标题">
                    <el-input type="text" placeholder="请输入关键词库标题" v-model="wordBaseForm.name"></el-input>
                </el-form-item>

                <div style="margin-top: 15px; display: flex; justify-content: flex-end;">
                    <el-button type="primary" @click="wordBaseAction">{{isAddBtn?'确定':'更新'}}</el-button>
                </div>
            </el-form>
        </el-dialog>
    </div>
</template>
<script>
    import { getToken, setToken, removeToken } from '@/utils/auth'
    import { wordBaseList, addWordBase, updateWordBase ,wordBaserDel} from '@/api/patterTest'

    export default {
        name: "Patter",
        data(){
            return{
                wordBaselist:[],
                tableLoading: false,
                pagesize: 30,
                page: 1,
                total: 0,
                isAddBtn: true,      //是否点击添加按钮
                wordBaseForm: {
                    name: '',
                    id:'',
                    token:'',
                },
                rules: {
                    name: [{required:true, message: '内容不能为空', trigger: 'blur'}],
                },
                wordBaseDialog: false,
                token : getToken(),
            }
        },
        created(){
            this.getwordBaselist()
        },
        methods:{
            getwordBaselist(){
                var params = {
                    token: this.token,
                    page: this.page,
                    pagesize: this.pagesize,
                }
                this.tableLoading = true;
                wordBaseList(params).then(response => {
                    let res = response.data
                    if(res.code == "0000"){
                        this.wordBaselist=[]
                        if(res.data.list.length>0){
                            for(let curWordBase of res.data.list){
                                curWordBase.addtime = this.getAddTime(curWordBase.addtime)
                                this.wordBaselist.push(curWordBase)
                            }
                        }
                        this.total = res.data.total
                        this.tableLoading = false
                    }else{
                        this.$message.error(res.msg)
                    }
                }).catch(err=>{
                    this.$message.error("获取关键词库列表失败!")
                })
            },
            showAddWordBase(){
                this.wordBaseDialog = true;
                this.isAddBtn = true;
                this.wordBaseForm.token = this.token;
                this.wordBaseForm.name = "";
                this.$nextTick(() => {
                    this.$refs['wordBaseForm'].clearValidate()
                })
            },
            showWordBaseUpdate(index, row){
                this.wordBaseDialog = true;
                this.isAddBtn = false;
                this.wordBaseForm.token = this.token;
                this.wordBaseForm.id = row.id;
                this.wordBaseForm.name = row.name;
                this.$nextTick(() => {
                    this.$refs['wordBaseForm'].clearValidate()
                })
            },
            getAddTime(timestamp){
                function add0(m){return m<10?'0'+m:m }
                if(timestamp>0){
                    //shijianchuo是整数，否则要parseInt转换
                    var time = new Date(timestamp *1000);
                    var y = time.getFullYear();
                    var m = time.getMonth()+1;
                    var d = time.getDate();
                    var h = time.getHours();
                    var mm = time.getMinutes();
                    var s = time.getSeconds();
                    return y+'-'+add0(m)+'-'+add0(d)+' '+add0(h)+':'+add0(mm)+':'+add0(s);
                }else{
                    return ''
                }
            },
            // 点击页码
            handleCurrentChange(val) {
                this.page = val;
                this.getwordBaselist()
            },
            wordBaseAction(){
                this.$refs['wordBaseForm'].validate((valid) => {
                    if (valid) {
                        var params = this.wordBaseForm;
                        if (this.isAddBtn) {
                            this.addWordBase(params);
                        } else {
                            this.updateWordBase(params);
                        }
                    } else {
                        return false;
                    }
                });
            },
            wordBaserDel(index, row){
                this.$confirm("确认删除？").then(res=>{
                    if(res === 'confirm'){
                        wordBaserDel({id:row.id,token:this.token}).then(response => {
                            this.getwordBaselist()
                        })
                    }
                }).catch(err=>{
                    console.log(err)
                })
            },
            addWordBase(pp){
                addWordBase(pp).then(response => {
                    if (response.data.code == '0000') {
                        this.$message.success(response.data.msg)
                        this.wordBaseDialog = false;
                        this.getwordBaselist()
                    }else {
                        this.$message.error(response.data.msg)
                        this.wordBaseDialog = false;
                    }
                }).catch(err=>{
                    this.$message.error("添加关键词库失败!")
                })
            },
            updateWordBase(pp){
                updateWordBase(pp).then(response => {
                    if (response.data.code == '0000') {
                        this.$message.success(response.data.msg)
                        this.wordBaseDialog = false;
                        this.getwordBaselist()
                    }else {
                        this.$message.error(response.data.msg)
                        this.wordBaseDialog = false;
                    }
                }).catch(err=>{
                    this.$message.error("更新关键词库失败!")
                })
            },
        }
    }
</script>

<style scoped>

</style>