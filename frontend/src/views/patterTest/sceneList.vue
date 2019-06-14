<template>
    <div class="app-container">
        <div class="filter-container">
            <el-button type="success" icon="el-icon-upload2" @click="showAddScene">添加情景关键词</el-button>
        </div>

        <!--列表-->
        <el-table class="tableListView" :data="scenelist" border style="width: 100%;min-width: 600px;" v-loading="tableLoading" >
            <el-table-column align="center" type="index" width="66" label="序号" fixed="left"></el-table-column>
            <el-table-column align="center" prop="name" min-width="100" label="情景关键词标题"></el-table-column>
            <el-table-column align="center" prop="addtime" min-width="150" label="添加时间"></el-table-column>
            <el-table-column align="center" min-width="200" label="操作">
                <template slot-scope="scope">
                    <router-link :to="'sceneDetail?sid='+scope.row.id" class="link-type">
                        <el-button type="success" plain size="small">详情</el-button>
                    </router-link>
                    <el-button type="primary" plain size="small" @click="showSceneUpdate(scope.$index, scope.row)">编辑</el-button>
                    <el-button type="danger" plain size="small" @click="scenerDel(scope.$index, scope.row)">刪除</el-button>
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
        <el-dialog :title="isAddBtn?'添加情景关键词':'更新情景关键词'" :visible.sync="sceneDialog" width="600px">
            <el-form :model="sceneForm" :rules="rules" ref="sceneForm" size="small" label-width="180px">
                <el-form-item label="请输入情景关键词标题">
                    <el-input type="text" placeholder="请输入情景关键词标题" v-model="sceneForm.name"></el-input>
                </el-form-item>

                <div style="margin-top: 15px; display: flex; justify-content: flex-end;">
                    <el-button type="primary" @click="sceneAction">{{isAddBtn?'确定':'更新'}}</el-button>
                </div>
            </el-form>
        </el-dialog>
    </div>
</template>
<script>
    import { getToken, setToken, removeToken } from '@/utils/auth'
    import { sceneList, addScene, updateScene ,scenerDel} from '@/api/patterTest'

    export default {
        name: "Patter",
        data(){
            return{
                scenelist:[],
                tableLoading: false,
                pagesize: 30,
                page: 1,
                total: 0,
                isAddBtn: true,      //是否点击添加按钮
                sceneForm: {
                    name: '',
                    id:'',
                    token:'',
                },
                rules: {
                    name: [{required:true, message: '内容不能为空', trigger: 'blur'}],
                },
                sceneDialog: false,
                token : getToken(),
            }
        },
        created(){
            this.getscenelist()
        },
        methods:{
            getscenelist(){
                var params = {
                    token: this.token,
                    page: this.page,
                    pagesize: this.pagesize,
                }
                this.tableLoading = true;
                sceneList(params).then(response => {
                    let res = response.data
                    if(res.code == "0000"){
                        this.scenelist=[]
                        if(res.data.list.length>0){
                            for(let curScene of res.data.list){
                                curScene.addtime = this.getAddTime(curScene.addtime)
                                this.scenelist.push(curScene)
                            }
                        }
                        this.total = res.data.total
                        this.tableLoading = false
                    }else{
                        this.$message.error(res.msg)
                    }
                }).catch(err=>{
                    this.$message.error("获取情景关键词列表失败!")
                })
            },
            showAddScene(){
                this.sceneDialog = true;
                this.isAddBtn = true;
                this.sceneForm.token = this.token;
                this.sceneForm.name = "";
                this.$nextTick(() => {
                    this.$refs['sceneForm'].clearValidate()
                })
            },
            showSceneUpdate(index, row){
                this.sceneDialog = true;
                this.isAddBtn = false;
                this.sceneForm.token = this.token;
                this.sceneForm.id = row.id;
                this.sceneForm.name = row.name;
                this.$nextTick(() => {
                    this.$refs['sceneForm'].clearValidate()
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
                this.getscenelist()
            },
            sceneAction(){
                this.$refs['sceneForm'].validate((valid) => {
                    if (valid) {
                        var params = this.sceneForm;
                        if (this.isAddBtn) {
                            this.addScene(params);
                        } else {
                            this.updateScene(params);
                        }
                    } else {
                        return false;
                    }
                });
            },
            scenerDel(index, row){
                this.$confirm("确认删除？").then(res=>{
                    if(res === 'confirm'){
                        scenerDel({id:row.id,token:this.token}).then(response => {
                            this.getscenelist()
                        })
                    }
                }).catch(err=>{
                    console.log(err)
                })
            },
            addScene(pp){
                addScene(pp).then(response => {
                    if (response.data.code == '0000') {
                        this.$message.success(response.data.msg)
                        this.sceneDialog = false;
                        this.getscenelist()
                    }else {
                        this.$message.error(response.data.msg)
                        this.sceneDialog = false;
                    }
                }).catch(err=>{
                    this.$message.error("添加情景关键词失败!")
                })
            },
            updateScene(pp){
                updateScene(pp).then(response => {
                    if (response.data.code == '0000') {
                        this.$message.success(response.data.msg)
                        this.sceneDialog = false;
                        this.getscenelist()
                    }else {
                        this.$message.error(response.data.msg)
                        this.sceneDialog = false;
                    }
                }).catch(err=>{
                    this.$message.error("更新情景关键词失败!")
                })
            },
        }
    }
</script>

<style scoped>

</style>