<template>
    <div class="app-container">
        <div class="filter-container">
            <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="addforname">添加用户</el-button>
            <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="addfornames">批量添加用户</el-button>
        </div>

        <el-table :data="Userlist" border style="width: 100%" @selection-change="handleSelectionChange">
            <el-table-column type="selection" width="55"></el-table-column>
            <el-table-column prop="id" label="用戶ID" width="80"></el-table-column>
            <el-table-column prop="username" label="用戶名"></el-table-column>
            <el-table-column prop="sid" label="超級管理"></el-table-column>
            <el-table-column align="center" label="下载权限" width="120px">
                <template slot-scope="scope">
                    <el-switch v-model="scope.row.isdown" :active-value="1" :inactive-value="0" active-color="#13ce66" inactive-color="#ff4949" @change="openorclosedown(scope.row,$event)"></el-switch>
                </template>
            </el-table-column>
            <el-table-column align="center" min-width="150" label="操作">
                <template slot-scope="scope">
                    <router-link :to="'/table/GetCurrentCall/AI'+scope.row.id">
                        <el-button type="primary" plain size="small" icon="el-icon-edit">查询当前通话</el-button>
                    </router-link>
                    <router-link :to="'/table/GetPayHistory/AI'+scope.row.id">
                        <el-button type="primary" plain size="small" icon="el-icon-edit">查询缴费记录</el-button>
                    </router-link>
                    <router-link :to="'/table/GetCustomer/AI'+scope.row.id">
                        <el-button type="primary" plain size="small" icon="el-icon-edit">查询账户信息</el-button>
                    </router-link>
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

        <el-dialog title="添加用户" :visible.sync="dialogFormVisible" width="800px">
            <el-form ref="dataForm" :rules="rules" :model="form" label-position="left" label-width="110px">
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
                        <el-option v-for="item in GUserlist" :key="item.id" :label="item.id +'--' +item.username" :value="item.id"></el-option>
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

        <el-dialog title="批量添加用户" :visible.sync="dialogFormListVisible" width="800px">
            <el-form ref="batchForm" :rules="rulesbatch" :model="temp" label-position="left" label-width="110px">
                <el-form-item label="用户名" prop="names">
                    <tags-input :tags="tags" v-model="temp.names" @tags-change="handleChange"></tags-input>
                </el-form-item>

                <el-form-item label="密码" prop="password">
                    <el-input v-model="temp.password" show-password></el-input>
                </el-form-item>

                <el-form-item label="所属" prop="guser">
                    <el-select v-model="temp.guser" filterable  placeholder="请选择" style="min-width: 100px">
                        <el-option v-for="item in GUserlist" :key="item.id" :label="item.username" :value="item.id"></el-option>
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
    import { fetchUserlist,fetchGUserlist,setisdown,adduerlist,createlistaicti,adduser } from '@/api/article'
    import { getgatewayrouting,getgeerategroup } from '@/api/pay'
    import tags from 'vue-tagsinput'
    export default {
        name: "UserlistTable",
        components: {
            'tags-input': tags
        },
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
                    password:undefined,
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
                    password: [{ required:true,message: '请设置密码', trigger: 'blur'}],
                },
            }
        },
        created(){
            this.getUserlist()
            this.getGatewaylist()
            this.getGUserlist()
            this.GetFeeRateGroup()
        },
        methods:{
            resetTemp() {
                this.temp = {
                    names:[],
                    guser:0,
                    password:undefined,
                    fee:undefined,
                }
                this.tags = []
            },
            resetForm() {
                this.form = {
                    username:undefined,
                        nikename:undefined,
                        password:undefined,
                        mobile:undefined,
                        gid:undefined,
                        fee:undefined,
                        guser:undefined,
                }
                this.tags = []
            },
            getUserlist() {
                fetchUserlist().then(response => {
                    this.Userlist = response.data.data
                    this.listLoading = false
                })
            },
            getGUserlist(){
                fetchGUserlist().then(response=>{
                    this.GUserlist = response.data.data
                })
            },
            getGatewaylist(){
                getgatewayrouting().then(response => {
                    this.gatewayoptions = response.data.data.infoGatewayRoutings
                })
            },
            GetFeeRateGroup(){
                getgeerategroup().then(response => {
                    this.geerategroupoptions = response.data.data.infoFeeRateGroups
                })
            },
            openorclosedown(row,event){
                let data = {
                    id:row.id,
                    start:event
                }

                this.listLoading = true
                setisdown(data).then(response => {
                    if (response.data.code == -1){
                        this.$message.error(response.data.msg)
                    } else {
                        if (response.data.code== 0){
                            this.$message.success(response.data.msg)
                        } else {
                            this.$message.warning(response.data.msg)
                        }
                    }
                    this.listLoading = false
                })
            },
            addforname(){
                this.resetForm()
                this.dialogFormVisible = true
                this.$nextTick(() => {
                    this.$refs['dataForm'].clearValidate()
                })
            },
            addfornames(){
                this.resetTemp()
                this.dialogFormListVisible = true
                this.$nextTick(() => {
                    this.$refs['batchForm'].clearValidate()
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
                if (field=='list'){
                    this.$refs['batchForm'].validate((valid) => {
                        if (valid) {

                            adduerlist(this.temp).then(() => {
                                this.getUserlist()
                                this.getGUserlist()
                                this.$notify({
                                    title: '成功',
                                    message: '创建成功',
                                    type: 'success',
                                    duration: 2000
                                })
                                this.dialogFormListVisible = false
                            }).catch(error=>{
                                this.$message.error("创建失败")
                            })
                        }
                    })
                }else{
                    this.$refs['dataForm'].validate((valid) => {
                        if (valid) {
                            adduser(this.form).then(() => {
                                this.getUserlist()
                                this.getGUserlist()
                                this.$notify({
                                    title: '成功',
                                    message: '创建成功',
                                    type: 'success',
                                    duration: 2000
                                })
                                this.dialogFormVisible = false
                            }).catch(error => {
                                this.$message.error("创建失败")
                            })
                        }
                    })
                }
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