<template>
    <div class="app-container">
        <!-- <div class="filter-container">
            <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="addforname">添加用户</el-button>
            <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="addfornames">批量添加用户</el-button>
        </div> -->

        <el-table :data="Userlist" border style="width: 100%" @selection-change="handleSelectionChange">
            <el-table-column type="selection" width="55"></el-table-column>
            <el-table-column prop="id" label="用戶ID" width="80"></el-table-column>
            <el-table-column prop="username" label="用戶名"></el-table-column>
            <el-table-column label="用户组" prop="gid">
                 <template slot-scope="scope">                   
                    <span v-if="scope.row.gid=='1'">
                        销售
                    </span>
                    <span v-else-if="scope.row.gid=='2'">
                        经理
                    </span>
                    <span v-else-if="scope.row.gid=='3'">
                        员工
                    </span>
                </template>
            </el-table-column>
            <el-table-column align="center" min-width="150" label="操作">
                <template slot-scope="scope">
                    <router-link :to="scope.row.gid!=3?'/table/GetTaskList/'+scope.row.id:'/table/GetTaskRobotList/'+scope.row.id">
                        <el-button type="primary" plain size="small" icon="el-icon-search">查看</el-button>
                    </router-link>
                    <router-link :to="'/table/RechArgeUserTable/'+scope.row.id+'/'+scope.row.gid">
                        <el-button type="primary" plain size="small" icon="el-icon-goods">充值</el-button>
                    </router-link>
                    <!--<el-button type="danger" plain size="small" icon="el-icon-delete" @click="postDelUser(scope.row.id)">删除</el-button>-->
                </template>
            </el-table-column>
        </el-table>
        <div style="margin-top: 10px">
            <el-select v-model="gatewayaicti" filterable  placeholder="请选择" style="min-width: 100px">
                <el-option v-for="item in gatewayoptions" v-if="item.memo!==''" :key="item.memo" :label="item.remoteIp" :value="item.name"></el-option>
            </el-select>
            <el-button @click="toggleSelection(Userlist)" type="success">批量操作</el-button>
            <el-button @click="toggleSelectioncler()" type="primary">取消选择</el-button>
        </div>

        <el-dialog title="添加用户" :visible.sync="dialogFormVisible">
            <el-form ref="dataForm" :rules="rules" :model="form" label-position="left" label-width="110px" style="width: 800px; margin-left:50px;">
                
                <el-form-item label="用户名" prop="username">
                    <el-input v-model="form.username"></el-input>
                </el-form-item>
                <el-form-item label="昵称" prop="nikename">
                    <el-input v-model="form.nikename"></el-input>
                </el-form-item>
                <el-form-item label="密码" prop="password">
                    <el-input v-model="form.password" show-password></el-input>
                </el-form-item>
                <el-form-item label="选择权限" prop="gid">
                    <el-select v-model="form.gid" filterable  placeholder="请选择" style="min-width: 500px">
                        <el-option label="销售" value="1"></el-option>
                        <el-option label="经理" value="2"></el-option>
                        <el-option label="员工" value="3"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="所属" prop="guser" v-if="form.gid!=2">
                    <el-select v-model="form.guser" filterable  placeholder="请选择" style="min-width: 100px">
                        <el-option v-for="item in GUserlist" :key="item.id" :label="item.nikename" :value="item.id"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="电话" prop="mobile">
                    <el-input v-model="form.mobile"></el-input>
                </el-form-item>

                <el-form-item label="选择费率" prop="fee" v-if="form.gid!=2">
                    <el-select v-model="form.fee" filterable  placeholder="请选择" style="min-width: 500px">
                        <el-option v-for="item in geerategroupoptions" :key="item.name" :label="item.name" :value="item.name"></el-option>
                    </el-select>
                </el-form-item>
            </el-form>

            <div slot="footer" class="dialog-footer">
                <el-button @click="dialogFormVisible = false">{{ $t('table.cancel') }}</el-button>
                <el-button type="primary" @click="createusers('one')">{{ $t('table.confirm') }}</el-button>
            </div>
        </el-dialog>

        <el-dialog title="批量添加用户" :visible.sync="dialogFormListVisible">
            <el-form ref="dataForm" :rules="rulesbatch" :model="temp" label-position="left" label-width="110px" style="width: 800px; margin-left:50px;">
                <el-form-item label="用户名" prop="names">
                    <tags-input :tags="tags" v-model="temp.names" @tags-change="handleChange"></tags-input>
                </el-form-item>

                <el-form-item label="所属" prop="guser">
                    <el-select v-model="temp.guser" filterable  placeholder="请选择" style="min-width: 100px">
                        <el-option v-for="item in GUserlist" :key="item.id" :label="item.nikename" :value="item.id"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="选择费率" prop="fee">
                    <el-select v-model="temp.fee" filterable  placeholder="请选择" style="min-width: 500px">
                        <el-option v-for="item in geerategroupoptions" :key="item.name" :label="item.name" :value="item.name"></el-option>
                    </el-select>
                </el-form-item>
            </el-form>

            <div slot="footer" class="dialog-footer">
                <el-button @click="dialogFormListVisible = false">{{ $t('table.cancel') }}</el-button>
                <el-button type="primary" @click="createusers('list')">{{ $t('table.confirm') }}</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script>
    import { fetchUserlist,fetchSUserlist,delUser } from '@/api/mtuser'

    export default {
        name: "UserMTListTable",
        data(){
            return{
                Userlist:[],
                GUserlist:[],
                gatewayoptions:[],
                geerategroupoptions:[],
                listLoading: true,
                gatewayaicti:undefined,
                dialogFormVisible:false,
                dialogFormListVisible:false,
                multipleSelection:[],
                form:{
                    username:undefined,
                    nikename:undefined,
                    password:undefined,
                    mobile:undefined,
                    gid:undefined,
                    fee:undefined,
                    guser:undefined,
                },
                temp:{
                    names:[],
                    guser:0,
                    fee:undefined,
                },
                tags: [],
                rules:{
                    username: [{ required:true,message: '请填写名字', trigger: 'blur'}],
                    nikename: [{ required:true,message: '请填写昵称', trigger: 'blur'}],
                    password: [{ required:true,message: '请设置密码', trigger: 'blur'}],
                    gid: [{ required:true,message: '请选择权限', trigger: 'blur'}],
                },
                rulesbatch:{
                    names: [{ required:true,message: '请填写名字', trigger: 'blur'}],
                },
            }
        },
        created(){
            this.getUserlist()
        },
        methods:{
            resetTemp() {
                this.temp = {
                    names:[],
                    gateway:undefined,
                    fee:undefined,
                    maxsnum:undefined,
                }
                this.tags = []
            },
            getUserlist() {
                fetchUserlist().then(response => {
                    this.Userlist = response.data.data
                })
            },
            postDelUser(id){
                delUser(id).then(response => {
                    this.getUserlist()
                })
            },
            addforname(){
                this.resetTemp()
                this.dialogFormVisible = true
                this.$nextTick(() => {
                    this.$refs['dataForm'].clearValidate()
                })
            },
            addfornames(){
                this.resetTemp()
                this.dialogFormListVisible = true
                this.$nextTick(() => {
                    this.$refs['dataForm'].clearValidate()
                })
            },
            handleChange(index, text) {
                if (text) {
                    this.tags.splice(index, 0, text)
                } else {
                    this.tags.splice(index, 1)
                }
                this.temp.names = this.tags
            },
            createusers(field){

                this.$refs['dataForm'].validate((valid) => {
                    if (valid) {
                        if (field=='list'){
                            adduerlist(this.temp).then(() => {
                                this.getUserlist()
                                this.dialogFormListVisible = false
                                this.$notify({
                                    title: '成功',
                                    message: '创建成功',
                                    type: 'success',
                                    duration: 2000
                                })
                            })
                        }else{
                            if (this.form==1){
                                this.form
                            }
                            adduser(this.form).then(() => {
                                this.getUserlist()
                                this.dialogFormListVisible = false
                                this.$notify({
                                    title: '成功',
                                    message: '创建成功',
                                    type: 'success',
                                    duration: 2000
                                })
                            })
                        }
                    }
                })
            },
            handleSelectionChange(val) {
                this.multipleSelection = val;
            },
            toggleSelectioncler(){
                this.$refs.AiTable.clearSelection();
            },
            toggleSelection(row) {
                if (!this.multipleSelection) {
                    this.$message.error("请选择用户")
                    return
                }

                let ids = ""
                let data = {
                    ids:"",
                }

                for(var i= 0;i<this.multipleSelection.length;i++) {
                    data.ids += this.multipleSelection[i].id

                    if (i < this.multipleSelection.length - 1) {
                        data.ids += ","
                    }

                }

                this.listLoading = true
                createlistaicti(data).then(response => {

                    this.$message.success(response.data.msg)
                    this.listLoading = false
                })
            },
        }
    }
</script>

<style scoped>

</style>