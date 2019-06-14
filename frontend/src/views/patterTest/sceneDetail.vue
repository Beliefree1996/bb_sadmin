<template>
    <div class="app-container">
        <div class="filter-container">
            <div style='font-size: 30px;'>
                {{name}}
            </div>
            <el-button type="success" icon="el-icon-upload2" @click="showAddSceneKey">添加关键词</el-button>
        </div>

        <!--列表-->
        <el-table class="tableListView" :data="sceneKeylist" border style="width: 100%;min-width: 600px;" v-loading="tableLoading" >
            <el-table-column align="center" type="index" width="66" label="序号" fixed="left"></el-table-column>
            <el-table-column align="center" prop="key" min-width="100" label="关键词"></el-table-column>
            <el-table-column align="center" prop="addtime" min-width="150" label="添加时间"></el-table-column>
            <el-table-column align="center" min-width="200" label="操作">
                <template slot-scope="scope">
                    <el-button type="primary" plain size="small" @click="showSceneKeyUpdate(scope.$index, scope.row)">编辑</el-button>
                    <el-button type="danger" plain size="small" @click="sceneKeyDel(scope.$index, scope.row)">刪除</el-button>
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
        <el-dialog :title="isAddBtn?'添加关键词':'更新关键词'" :visible.sync="sceneKeyDialog" width="600px">
            <el-form :model="sceneKeyForm" :rules="rules" ref="sceneKeyForm" size="small" label-width="180px">
                <el-form-item label="请输入关键词">
                    <el-input type="text" placeholder="请输入关键词" v-model="sceneKeyForm.key"></el-input>
                </el-form-item>

                <div style="margin-top: 15px; display: flex; justify-content: flex-end;">
                    <el-button type="primary" @click="sceneKeyAction">{{isAddBtn?'确定':'更新'}}</el-button>
                </div>
            </el-form>
        </el-dialog>
    </div>
</template>
<script>
    import { getToken, setToken, removeToken } from '@/utils/auth'
    import {getSceneInfo, sceneKeyList, addSceneKey, updateSceneKey ,sceneKeyDel} from '@/api/patterTest'

    export default {
        name: "index",
        data(){
            return{
                sid:0,
                name:'',
                sceneKeylist:[],
                tableLoading: false,
                pagesize: 30,
                page: 1,
                total: 0,
                isAddBtn: true,      //是否点击添加按钮
                sceneKeyForm: {
                    key: '',
                    id:0,
                    sid:0,
                    token:'',
                },
                rules: {
                    sceneKey: [{required:true, message: '关键词不能为空', trigger: 'blur'}],
                },
                sceneKeyDialog: false,
                token : getToken(),
            }
        },
        created(){
            if(this.$route.query && 'sid' in this.$route.query){
                this.sid = this.$route.query.sid
                this.getsceneKeylist()
                this.getSceneInfo()
            }else{
                this.$message.error("关键词库不存在")
            }
        },
        methods:{
            getSceneInfo(){
                getSceneInfo({token:this.token,sid:this.sid}).then(response=>{
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
            getsceneKeylist(){
                var params = {
                    token: this.token,
                    page: this.page,
                    pagesize: this.pagesize,
                    sid: this.sid,
                }
                this.tableLoading = true;
                sceneKeyList(params).then(response => {
                    let res = response.data
                    if(res.code == "0000"){
                        this.sceneKeylist=[]
                        if(res.data.list.length>0){
                            for(let curSceneKey of res.data.list){
                                curSceneKey.addtime = this.getAddTime(curSceneKey.addtime)
                                this.sceneKeylist.push(curSceneKey)
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
            showAddSceneKey(){
                this.sceneKeyDialog = true;
                this.isAddBtn = true;
                this.sceneKeyForm.token = this.token;
                this.sceneKeyForm.key = "";
                this.sceneKeyForm.sid = this.sid;
                this.$nextTick(() => {
                    this.$refs['sceneKeyForm'].clearValidate()
                })
            },
            showSceneKeyUpdate(index, row){
                this.sceneKeyDialog = true;
                this.isAddBtn = false;
                this.sceneKeyForm.token = this.token;
                this.sceneKeyForm.id = row.id;
                this.sceneKeyForm.key = row.key;
                this.$nextTick(() => {
                    this.$refs['sceneKeyForm'].clearValidate()
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
                this.getsceneKeylist()
            },
            sceneKeyAction(){
                this.$refs['sceneKeyForm'].validate((valid) => {
                    if (valid) {
                        var params = this.sceneKeyForm;
                        if (this.isAddBtn) {
                            this.addSceneKey(params);
                        } else {
                            this.updateSceneKey(params);
                        }
                    } else {
                        return false;
                    }
                });
            },
            sceneKeyDel(index, row){
                this.$confirm("确认删除？").then(res=>{
                    if(res === 'confirm'){
                        sceneKeyDel({id:row.id,token:this.token}).then(response => {
                            this.getsceneKeylist()
                        })
                    }
                }).catch(err=>{
                    console.log(err)
                })
            },
            addSceneKey(pp){
                addSceneKey(pp).then(response => {
                    if (response.data.code == '0000') {
                        this.$message.success(response.data.msg)
                        this.sceneKeyDialog = false;
                        this.getsceneKeylist()
                    }else {
                        this.$message.error(response.data.msg)
                        this.sceneKeyDialog = false;
                    }
                }).catch(err=>{
                    this.$message.error("添加关键词失败!")
                })
            },
            updateSceneKey(pp){
                updateSceneKey(pp).then(response => {
                    if (response.data.code == '0000') {
                        this.$message.success(response.data.msg)
                        this.sceneKeyDialog = false;
                        this.getsceneKeylist()
                    }else {
                        this.$message.error(response.data.msg)
                        this.sceneKeyDialog = false;
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