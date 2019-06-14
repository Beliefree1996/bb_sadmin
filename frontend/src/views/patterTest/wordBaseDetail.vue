<template>
    <div class="app-container">
        <div class="filter-container">
            <div style='font-size: 30px;'>
                {{name}}
            </div>
            <el-button type="success" icon="el-icon-upload2" @click="showAddWord">添加关键词</el-button>
        </div>

        <!--列表-->
        <el-table class="tableListView" :data="wordlist" border style="width: 100%;min-width: 600px;" v-loading="tableLoading" >
            <el-table-column align="center" type="index" width="66" label="序号" fixed="left"></el-table-column>
            <el-table-column align="center" prop="word" min-width="100" label="关键词"></el-table-column>
            <el-table-column align="center" prop="addtime" min-width="150" label="添加时间"></el-table-column>
            <el-table-column align="center" min-width="200" label="操作">
                <template slot-scope="scope">
                    <el-button type="primary" plain size="small" @click="showWordUpdate(scope.$index, scope.row)">编辑</el-button>
                    <el-button type="danger" plain size="small" @click="wordrDel(scope.$index, scope.row)">刪除</el-button>
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
        <el-dialog :title="isAddBtn?'添加关键词':'更新关键词'" :visible.sync="wordDialog" width="600px">
            <el-form :model="wordForm" :rules="rules" ref="wordForm" size="small" label-width="180px">
                <el-form-item label="请输入关键词">
                    <el-input type="text" placeholder="请输入关键词" v-model="wordForm.word"></el-input>
                </el-form-item>

                <div style="margin-top: 15px; display: flex; justify-content: flex-end;">
                    <el-button type="primary" @click="wordAction">{{isAddBtn?'确定':'更新'}}</el-button>
                </div>
            </el-form>
        </el-dialog>
    </div>
</template>
<script>
    import { getToken, setToken, removeToken } from '@/utils/auth'
    import {getwordBaseInfo, wordList, addWord, updateWord ,wordrDel} from '@/api/patterTest'

    export default {
        name: "index",
        data(){
            return{
                bid:0,
                name:'',
                wordlist:[],
                tableLoading: false,
                pagesize: 30,
                page: 1,
                total: 0,
                isAddBtn: true,      //是否点击添加按钮
                wordForm: {
                    word: '',
                    id:0,
                    bid:0,
                    token:'',
                },
                rules: {
                    word: [{required:true, message: '关键词不能为空', trigger: 'blur'}],
                },
                wordDialog: false,
                token : getToken(),
            }
        },
        created(){
            if(this.$route.query && 'bid' in this.$route.query){
                this.bid = this.$route.query.bid
                this.getwordlist()
                this.getwordBaseInfo()
            }else{
                this.$message.error("关键词库不存在")
            }
        },
        methods:{
            getwordBaseInfo(){
                getwordBaseInfo({token:this.token,bid:this.bid}).then(response=>{
                    let res = response.data
                    if(res.code == "0000"){
                        this.name = res.data.name
                    }else{
                        this.$message.error(res.msg)
                    }
                }).catch(err=>{
                    console.log(err)
                    this.$message.error("获取关键词库信息失败！")
                })
            },
            getwordlist(){
                var params = {
                    token: this.token,
                    page: this.page,
                    pagesize: this.pagesize,
                    bid: this.bid,
                }
                this.tableLoading = true;
                wordList(params).then(response => {
                    let res = response.data
                    if(res.code == "0000"){
                        this.wordlist=[]
                        if(res.data.list.length>0){
                            for(let curWord of res.data.list){
                                curWord.addtime = this.getAddTime(curWord.addtime)
                                this.wordlist.push(curWord)
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
            showAddWord(){
                this.wordDialog = true;
                this.isAddBtn = true;
                this.wordForm.token = this.token;
                this.wordForm.word = "";
                this.wordForm.bid = this.bid;
                this.$nextTick(() => {
                    this.$refs['wordForm'].clearValidate()
                })
            },
            showWordUpdate(index, row){
                this.wordDialog = true;
                this.isAddBtn = false;
                this.wordForm.token = this.token;
                this.wordForm.id = row.id;
                this.wordForm.word = row.word;
                this.$nextTick(() => {
                    this.$refs['wordForm'].clearValidate()
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
                this.getwordlist()
            },
            wordAction(){
                this.$refs['wordForm'].validate((valid) => {
                    if (valid) {
                        var params = this.wordForm;
                        if (this.isAddBtn) {
                            this.addWord(params);
                        } else {
                            this.updateWord(params);
                        }
                    } else {
                        return false;
                    }
                });
            },
            wordrDel(index, row){
                this.$confirm("确认删除？").then(res=>{
                    if(res === 'confirm'){
                        wordrDel({id:row.id,token:this.token}).then(response => {
                            this.getwordlist()
                        })
                    }
                }).catch(err=>{
                    console.log(err)
                })
            },
            addWord(pp){
                addWord(pp).then(response => {
                    if (response.data.code == '0000') {
                        this.$message.success(response.data.msg)
                        this.wordDialog = false;
                        this.getwordlist()
                    }else {
                        this.$message.error(response.data.msg)
                        this.wordDialog = false;
                    }
                }).catch(err=>{
                    this.$message.error("添加关键词失败!")
                })
            },
            updateWord(pp){
                updateWord(pp).then(response => {
                    if (response.data.code == '0000') {
                        this.$message.success(response.data.msg)
                        this.wordDialog = false;
                        this.getwordlist()
                    }else {
                        this.$message.error(response.data.msg)
                        this.wordDialog = false;
                    }
                }).catch(err=>{
                    this.$message.error("更新关键词失败!")
                })
            },
        }
    }
</script>

<style scoped>

</style>