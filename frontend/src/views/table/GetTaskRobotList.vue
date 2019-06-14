<template>
    <div class="app-container">
        <div class="filter-container">
            <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="addforname">添加任务</el-button>
        </div>

        <el-table :data="Userlist" border style="width: 100%">
            <el-table-column type="selection" width="55"></el-table-column>
            <el-table-column prop="id" label="任务ID" width="80"></el-table-column>
            <el-table-column prop="name" label="任务名"></el-table-column>
            <el-table-column align="center" min-width="150" label="操作">
                <template slot-scope="scope">
                    <el-button type="danger" plain size="small" icon="el-icon-delete" @click="postDelUser(scope.row.id)">删除</el-button>
                    <el-button type="primary" plain size="small" icon="el-icon-edit" @click="choosePatter(scope.row.id, scope.row.pid)">修改话术</el-button>
                </template>
            </el-table-column>
        </el-table>

<!--        添加任务弹窗-->
        <el-dialog title="添加任务" :visible.sync="dialogFormVisible" width="600px">
            <el-form ref="dataForm" :rules="rules" :model="form" label-position="left" label-width="100px">
                
                <el-form-item label="启始值" prop="value">
                    <el-input v-model="form.value"></el-input>
                </el-form-item>
                <el-form-item label="任务数量" prop="range">
                    <el-slider v-model="form.range"> 
                    </el-slider>
                </el-form-item>
                <el-form-item label="主叫号码" prop="origination_caller_id_number">
                    <el-input v-model="form.origination_caller_id_number"></el-input>
                </el-form-item>
                <el-form-item label="话术" prop="pid">
                    <el-select v-model="form.pid" filterable  placeholder="请选择" style="min-width: 100px">
                        <el-option v-for="item in patterList" :key="item.id" :label="item.title" :value="item.id"></el-option>
                    </el-select>
                </el-form-item>
            </el-form>

            <div slot="footer" class="dialog-footer">
                <el-button @click="dialogFormVisible = false">{{ $t('table.cancel') }}</el-button>
                <el-button type="primary" @click="createuser()">{{ $t('table.confirm') }}</el-button>
            </div>
        </el-dialog>

<!--        修改话术弹窗-->
        <el-dialog title="修改话术" :visible.sync="choosePatterDialogVisible" width="600px">
            <el-form ref="dataForm" label-position="left" label-width="100px">
                <el-form-item label="话术" prop="pid">
                    <el-select v-model="choosePid" filterable  placeholder="请选择" style="min-width: 300px">
                        <el-option v-for="item in patterList" :key="item.id" :label="item.title" :value="item.id"></el-option>
                    </el-select>
                </el-form-item>
            </el-form>
            <div style="margin-top: 15px; display: flex; justify-content: flex-end;">
                <el-button type="primary" :disabled="choosePid == 0" @click="changePatter(choosePid)">确定</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script>
    import { fetchUserlist,fetchSRTUserlist,addAutoDialertask,delSRTUser,fetchSpeech } from '@/api/mtuser'
    import {changePatter} from '@/api/patterTest'

    export default {
        name: "TaskRobotList",
        data(){
            return{
                Userlist:[],
                patterList:[],
                listLoading: true,
                gatewayaicti:undefined,
                dialogFormVisible:false,
                choosePatterDialogVisible: false,
                chooseTid: 0,
                choosePid: 0,
                form:{
                    uid:this.$route.params.name,
                    range:[],
                    value:undefined,
                    origination_caller_id_number:undefined,
                    pid:undefined
                },
                rules:{
                    // range: [{ required:true,message: '请选择范围', trigger: 'blur'}],
                    value: [{ required:true,message: '请填写启始值', trigger: 'blur'}],
                },
            }
        },
        created(){
            this.getUserlist()
            this.getPatterList()
        },
        methods:{
            resetTemp() {
                this.form = {
                    uid:this.$route.params.name,
                    range:[],
                    value:undefined,
                    origination_caller_id_number:undefined,
                    pid:undefined
                }
                this.tags = []
            },
            getUserlist() {
                fetchSRTUserlist(this.$route.params.name).then(response => {
                    this.Userlist = response.data.data
                    this.listLoading = false
                })
            },
            getPatterList(){
                fetchSpeech(this.$route.params.name).then(response =>{
                    if (response.data.code==200){
                        this.patterList = response.data.data;
                    }else{
                        this.$notify.error({
                            title: '失败',
                            message: response.data.msg,
                            duration: 2000
                        })
                    }
                })
            },
            addforname(){
                if(this.patterList.length>0){
                    this.resetTemp()
                    this.dialogFormVisible = true
                    this.$nextTick(() => {
                        this.$refs['dataForm'].clearValidate()
                    })
                }else{
                    this.$message.error("无可用话术")
                }
            },
            createuser(){
                this.$refs['dataForm'].validate((valid) => {
                    if (valid) {
                        addAutoDialertask(this.form).then(response => {
                            if (response.data.code==200){
                                this.getUserlist()
                                this.dialogFormVisible = false
                                this.$notify({
                                    title: '成功',
                                    message: '创建成功',
                                    type: 'success',
                                    duration: 2000
                                })
                            }else{
                                this.$notify({
                                    title: '失败',
                                    message: response.data.msg,
                                    type: 'error',
                                    duration: 2000
                                })
                            }
                        }).catch(err =>{

                        })
                    }
                })
            },
            postDelUser(id){
                this.$confirm("确认删除？").then(res=>{
                    if(res === 'confirm'){
                        delSRTUser(id).then(response => {
                            this.getUserlist()
                        })
                    }
                }).catch(err=>{
                    console.log(err)
                })
            },
            choosePatter(id,pid){
                this.chooseTid = id;
                this.choosePid = pid;
                this.choosePatterDialogVisible = true;
            },
            changePatter(pid){
                console.log(this.chooseTid)
                console.log(pid)
                let params = {
                    tid: this.chooseTid,
                    pid: pid,
                }
                changePatter(params).then(response => {
                    let res = response.data
                    if(res.code == "0000"){
                        this.choosePatterDialogVisible = false;
                        this.$message.success("话术修改成功！");
                        this.getUserlist();
                    }else{
                        this.$message.error(res.msg)
                    }
                }).catch(err=>{
                    this.$message.error("话术修改失败，请重新尝试修改")
                })
            },
        }
    }
</script>

<style scoped>

</style>