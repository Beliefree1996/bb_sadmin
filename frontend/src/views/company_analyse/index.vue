<template>
    <div class="app-container">
        <el-table :data="Userlist" border style="width: 100%">
            <el-table-column prop="id" label="用戶ID" width="80"></el-table-column>
            <el-table-column prop="username" label="用戶名"></el-table-column>
            <el-table-column prop="nikename" label="昵称"></el-table-column>
            <el-table-column align="center" min-width="150" label="操作">
                <template slot-scope="scope">
                    <router-link :to="'/company_analyse/info/?id='+scope.row.id">
                        <el-button type="primary" plain size="small" icon="el-icon-search">统计信息</el-button>
                    </router-link>
                </template>
            </el-table-column>
        </el-table>
    </div>
</template>

<script>
    import { fetchUserlist } from '@/api/company_analyse'

    export default {
        name: "index",
        data(){
            return{
                Userlist:[],
            }
        },
        created(){
            this.getUserlist()
        },
        methods:{
            getUserlist() {
                fetchUserlist().then(response => {
                    this.Userlist = response.data.data
                })
            },
        }
    }
</script>

<style scoped>

</style>