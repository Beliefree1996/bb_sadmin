<template>
    <div class="app-container">
        <div class="filter-container">
            <el-button type="success" icon="el-icon-upload2" @click="AddVoice">添加录音</el-button><span style="color: red;padding-left: 15px">话术修改即时生效，删除不可恢复，请谨慎操作</span>
        </div>

        <!--列表-->
        <el-table class="tableListView" :data="Voicelist" border
                  style="width: 100%;min-width: 600px;"
                  v-loading="tableLoading" :header-cell-style="tableHeaderColor">
            <el-table-column type="selection" width="55" align="center" label="全选"></el-table-column>
            <el-table-column align="leftvoicelist" prop="id" min-width="50" label="ID"></el-table-column>
            <el-table-column align="leftvoicelist" prop="word" min-width="600" label="话术"></el-table-column>
            <el-table-column align="center" min-width="130" label="录音文件">
                <template slot-scope="scope">
                    <a @click="showRecordAction(scope.row)" style="color: #3a8ee6;cursor: pointer;">点击听取录音</a>
                </template>
            </el-table-column>
            <el-table-column align="center" min-width="150" label="操作">
                <template slot-scope="scope">
                    <el-button type="primary" plain size="small" @click="VioceUpdate(scope.$index, scope.row)">编辑</el-button>
                    <el-button type="danger" plain size="small" @click="VoiceDel(scope.$index, scope.row)">刪除</el-button>
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
        <el-dialog :title="isAddBtn?'添加录音':'更新录音'" :visible.sync="dialogFormVisible" width="480px">
            <el-form :model="addForm" :rules="rules" ref="addForm" size="small" >
                <el-form-item prop="word">
                    <el-input type="textarea" :rows="4" placeholder="请输入内容" v-model="addForm.word"></el-input>
                </el-form-item>

                <el-form-item prop="path">
                    <el-upload class="upload-demo" action="https://voice.bbxxjs.com/uploadwav" :on-success="succup" :on-remove="handleRemove" :data="updata" accept=".mp3,.wav" :file-list="fileList" list-type="text">
                        <el-button size="small" type="primary">点击上传</el-button>
                        <div class="el-upload__tip">只能上传mp3或wav文件</div>
                    </el-upload>
                </el-form-item>

                <div style="margin-top: 15px; display: flex; justify-content: flex-end;">
                    <el-button type="primary" @click="VoiceAction()">{{isAddBtn?'确定':'更新'}}</el-button>
                </div>
            </el-form>
        </el-dialog>

        <el-dialog :visible.sync="showRecord" title="录音播报" width="600px" :before-close="handleClose">
            <audio id="myAudio" :src="currentRecordFile" controls
                   style="display: block;margin: 0 auto;"></audio>
            <div style="margin-top: 20px;display: flex;align-items: center;justify-content: center;">
            </div>
        </el-dialog>
    </div>
</template>

<script>
    import { getToken, setToken, removeToken } from '@/utils/auth'
    import { voicelist, addvoice, editvoice, delvoice } from '@/api/Number'
    import Cookies from 'js-cookie'

    export default {
        name: "index",
        data(){
            return{
                Voicelist:[],
                fileList: [],
                updata:{
                    uid:""
                },
                tableLoading: false,
                pagesize: 30,
                page: 1,
                total: 0,
                showRecord:false,
                currentRecordFile:'',
                isAddBtn: true,      //是否点击添加按钮
                addForm: {
                    word: '',
                    path:'',
                    id:'',
                },
                rules: {
                    word: [{required:true, message: '内容不能为空', trigger: 'blur'}],
                },
                dialogFormVisible: false,
            }
        },
        created() {
            console.log(this.$router)
            this.token = getToken()
            this.getVoicelist()
            var sid = Cookies.get("sid")
            if(sid&& sid.length>0){
                this.updata.uid = sid
            }
        },
        methods:{
            getVoicelist(){
                var params = {
                    token: this.token,
                    page: this.page,
                    pagesize: this.pagesize,
                }
                this.tableLoading = true;
                voicelist(params).then(response => {
                    this.Voicelist = response.data.data.rows
                    this.total = response.data.data.total
                    this.tableLoading = false
                })
            },
            showRecordAction(row){
                this.showRecord = true;
                this.currentRecordFile = row.path;
            },
            AddVoice(){
                this.dialogFormVisible = true;
                this.isAddBtn = true;
                this.fileList = []
                this.addForm["token"] = this.token;
                this.addForm["word"] = "";
                this.addForm["path"] = "";
                this.$nextTick(() => {
                    this.$refs['addForm'].clearValidate()
                })
            },
            VioceUpdate(index, row){
                this.dialogFormVisible = true;
                this.isAddBtn = false;
                this.fileList = []
                this.addForm["id"] = row.id;
                this.addForm["word"] = row.word;
                this.addForm["path"] = row.path;
                this.$nextTick(() => {
                    this.$refs['addForm'].clearValidate()
                })
            },
            VoiceDel(index, row){

                this.$confirm('此操作将永久删除该文件, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    var params = {
                        token: this.token,
                        id: row.id,
                    }
                    delvoice(params).then(response => {

                        if (response.data.code == '0000') {
                            this.$message.success(response.data.msg)
                            this.getVoicelist()
                        }else {
                            this.$message.error(response.data.msg)
                        }
                    })
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });
                });
            },
            VoiceAction(){
                this.$refs['addForm'].validate((valid) => {
                    if (valid) {
                        var params = this.addForm;
                        if (this.isAddBtn) {

                            if (this.addForm.path.length  < 1) {
                                this.$message.warning("请上传录音");
                                return;
                            }
                            this.addVoice(params);

                        }else{

                            this.updateVoice(params);

                        }
                    } else {

                        return false;
                    }
                });
            },
            addVoice(pp){

                addvoice(pp).then(response => {

                    if (response.data.code == '0000') {
                        this.$message.success(response.data.msg)
                        this.dialogFormVisible = false;
                        this.getVoicelist()
                    }else {
                        this.$message.error(response.data.msg)
                        this.dialogFormVisible = false;
                    }
                })
            },
            updateVoice(pp){

                editvoice(pp).then(response => {

                    if (response.data.code == '0000') {
                        this.$message.success(response.data.msg)
                        this.dialogFormVisible = false;
                        this.getVoicelist()
                    }else {
                        this.$message.error(response.data.msg)
                        this.dialogFormVisible = false;
                    }
                })
            },
            handleClose(){
                this.showRecord = false;
                var audio = document.getElementById("myAudio");
                audio.pause();
            },
            // 点击页码
            handleCurrentChange(val) {
                this.page = val;
                this.getVoicelist()
            },
            // 修改table header的背景色
            tableHeaderColor({row, column, rowIndex, columnIndex}) {
                if (rowIndex === 0) {
                    return 'background-color: #f7f7f7;color: #363636;font-weight: 500;'
                }
            },
            handleRemove(file, fileList) {
                this.addForm["path"] = ''
            },
            succup(response, file, fileList){
                let vioce_path = ''
                if(response.data.indexOf("voice.bbxxjs.com") != -1){
                    var str = response.data
                    var index = str .lastIndexOf("\/");
                    vioce_path  = str .substring(index + 1, str .length);
                }else{
                    vioce_path = response.data
                }

                this.addForm["path"] = vioce_path;
            }
        }
    }
</script>

<style scoped>

</style>