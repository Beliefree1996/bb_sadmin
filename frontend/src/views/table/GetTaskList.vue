<template>
    <div class="app-container">

        <el-table :data="Userlist" border style="width: 100%"  v-loading="listLoading">
            <el-table-column prop="id" label="用戶ID" width="80"></el-table-column>
            <el-table-column prop="username" label="用戶名"></el-table-column>
            <el-table-column align="center" min-width="150" label="操作">
                <template slot-scope="scope">
                    <router-link :to="'/table/GetTaskRobotList/'+scope.row.id">
                        <el-button type="primary" plain size="small" icon="el-icon-search">查看</el-button>
                    </router-link>
                </template>
            </el-table-column>
        </el-table>
    </div>
</template>

<script>
    import { fetchUserlist,fetchSUserlist } from '@/api/mtuser'

    export default {
        name: "UserlistTable",
        data(){
            return{
                Userlist:[],
                GUserlist:[],
                gatewayoptions:[],
                geerategroupoptions:[],
                listLoading: true,
            }
        },
        created(){
            this.getUserlist()
        },
        methods:{
            getUserlist() {
                fetchSUserlist(this.$route.params.name).then(response => {
                    this.Userlist = response.data.data
                    this.listLoading = false
                })
            },
        }
    }
</script>

<style scoped>

</style>