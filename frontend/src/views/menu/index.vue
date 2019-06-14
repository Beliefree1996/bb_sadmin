<template>
    <div class="app-container">

        <div class="filter-container">
            <span>快捷操作：</span>
            <el-button type="success" icon="el-icon-upload2" @click="editAll">一键设置</el-button>
        </div>

        <!--列表-->
        <el-table class="tableListView" :data="userList" border
                  style="width: 100%;min-width: 600px;"
                  v-loading="tableLoading" @selection-change="handleSelectionChange">
            <el-table-column type="selection" width="55" align="center" label="全选"></el-table-column>
            <el-table-column align="center" prop="id" min-width="50" label="ID"></el-table-column>
            <el-table-column align="center" prop="username" min-width="100" label="用户名"></el-table-column>
            <el-table-column align="center" prop="role" min-width="100" label="角色"></el-table-column>
            <el-table-column align="center" prop="menu" min-width="200" label="菜单"></el-table-column>
            <el-table-column align="center" min-width="150" label="操作">
                <template slot-scope="scope">
                    <el-button type="primary" plain size="small" @click="editUserMenu(scope.$index, scope.row)">编辑菜单
                    </el-button>
                </template>
            </el-table-column>
        </el-table>

        <!--页码条-->
        <el-col :span="24" class="toolbar" style="margin-top: 20px">
            <el-button type="primary" @click="editBatch">批量管理</el-button>
        </el-col>
        <el-col :span="24" class="toolbar" style="margin-top: 10px">
            <el-pagination layout="prev, pager, next" @current-change="handleCurrentChange"
                           :page-size="pagesize"
                           :total="total" style="float:right;"></el-pagination>
        </el-col>

        <!--对话框-->
        <el-dialog title="编辑菜单" :visible.sync="editDialog" width="480px" :before-close="beforeDialogClose">
            <el-form :model="editForm" ref="editForm" size="small">
                <el-form-item prop="menu">
                    <el-checkbox :indeterminate="isIndeterminate" v-model="checkAll" @change="checkAllMenu">全选
                    </el-checkbox>
                    <div style="margin: 15px 0;"></div>
                    <el-checkbox-group v-model="editForm.menu" class="menuList" @change="checkMenuChange">
                        <el-checkbox v-for="m in menuList" :label="m.id" :key="m.id" class="menuItem" >{{m.name}}
                        </el-checkbox>
                    </el-checkbox-group>
                </el-form-item>

                <div style="margin-top: 15px; display: flex; justify-content: flex-end;">
                    <el-button type="primary" @click="doEditMenu">更新</el-button>
                </div>
            </el-form>
        </el-dialog>

    </div>
</template>

<script>
    import {getMenus, editMenu, getMenuer} from '@/api/menu'
    import {isInArray} from '@/tool/index'
    import Cookies from 'js-cookie'


    export default {
        name: "index",
        data() {
            return {
                userList: [],
                checkAll: false,
                isIndeterminate: true,
                menuList: [],
                tableLoading: false,
                page: 1,
                pagesize: 30,
                total: 0,
                editDialog: false,
                editForm: {
                    id: [],
                    menu: [],
                    type: 0, //0一般模式，1全部模式
                },
                multipleSelection:[],
            }
        },
        created() {
            this.getMenuList()
        },
        methods: {
            editAll() {
                this.editDialog = true
                this.editForm.type = 1
            },
            // 点击页码
            handleCurrentChange(val) {
                this.page = val;
                this.getUserList()
            },
            doEditMenu() {
                editMenu(this.editForm).then(response => {
                    let res = response.data
                    if (res.code == "0000") {
                        this.$message.success("更新成功！")
                        this.editDialog = false
                        this.editForm = {
                            id: [],
                            menu: [],
                            type: 0,
                        }
                        this.checkAll = false
                        this.isIndeterminate = true
                        this.getUserList()
                    } else {
                        this.$message.error("更新菜单失败！")
                    }
                }).catch(error => {
                    console.log(error)
                    this.$message.error("网络错误，请重试！")
                })
            },
            getUserList() {
                var params = {
                    page: this.page,
                    pagesize: this.pagesize,
                }
                this.tableLoading = true;
                getMenuer(params).then(response => {
                    let res = response.data
                    if (res.code == "0000") {
                        if(res.data.list.length>0){
                            this.userList = []
                            this.userList = res.data.list.map(v=>{
                                v.menu = this.formatMenu(v.menus)
                                return v
                            })
                        }
                        this.page = res.data.page
                        this.total = res.data.total
                        this.tableLoading = false;
                    } else {
                        this.$message.error("获取用户列表失败！")
                    }
                })
            },
            getMenuList() {
                getMenus().then(response => {
                    let res = response.data
                    if (res.code == "0000") {
                        if (res.data.length > 0) {
                            this.menuList = res.data
                        }
                        this.getUserList()
                    } else {
                        this.$message.error("获取菜单列表失败！")
                    }
                }).catch(error => {
                    console.log(error)
                    this.$message.error("网络错误，请重试！")
                })
            },
            checkAllMenu(val) {
                let menuIds = []
                this.menuList.map(function (v) {
                    menuIds.push(v.id)
                })
                this.editForm.menu = val ? menuIds : [];
                this.isIndeterminate = false;
            },
            checkMenuChange(val) {
                let checkedCount = val.length;
                this.checkAll = checkedCount === this.menuList.length;
                this.isIndeterminate = checkedCount > 0 && checkedCount < this.menuList.length;
            },
            formatMenu(data){
                if(data){
                    if(data.length>0){
                        data = data.split(",")
                        let ret ='   '
                        this.menuList.forEach(m=>{
                            if(isInArray(m.id,data)){
                                ret +=m.name +"   "
                            }
                        })
                        return ret
                    }else{
                        return ''
                    }
                }else{
                    return ''
                }
            },
            editBatch(){
                if(this.multipleSelection.length>0){
                    let ids = this.multipleSelection.map(i =>{
                        return i.id
                    })
                    this.editForm.id = ids
                    this.editDialog = true
                }else{
                    this.$message({
                        message: '请先选择用户',
                        type: 'warning'
                    })
                }
            },
            handleSelectionChange(val){
                this.multipleSelection = val;
            },
            beforeDialogClose(){
                this.editDialog = false
                this.editForm = {
                    id: [],
                    menu: [],
                    type: 0,
                }
                this.checkAll = false
                this.isIndeterminate = true
            },
            editUserMenu(index,row){
                let user_menu = row.menus.split(",")
                user_menu = user_menu.map(u =>{
                    return parseInt(u)
                })
                this.checkAll = user_menu.length === this.menuList.length;
                this.isIndeterminate = user_menu.length > 0 && user_menu.length < this.menuList.length;
                this.editForm.menu = user_menu
                this.editForm.id = [row.id]
                this.editDialog = true
            }
        }
    }
</script>

<style scoped>
    .menuList {
        display: flex;
        flex-direction: column;
    }

    .menuItem {
        margin-left: 20px;
    }
</style>