<template>
    <div class="app-container">
        <el-table :data="Userlist" :span-method="objectSpanMethod" border style="width: 100%">
            <el-table-column prop="id" label="用戶ID" width="80"></el-table-column>
            <el-table-column prop="username" label="用戶名"></el-table-column>
            <el-table-column prop="fee" label="话费余额"></el-table-column>

            <el-table-column prop="date" label="时间"></el-table-column>
            <el-table-column prop="call" label="拨打量"></el-table-column>
            <el-table-column prop="reply_rate" label="接通率"></el-table-column>
            <el-table-column prop="reply_num" label="接通数"></el-table-column>
            <el-table-column prop="fee_time" label="通话时长">
                <template slot-scope="scope">
                    <span style="margin-left: 10px">{{ getFormatSec(scope.row.fee_time) }}</span>
                </template>
            </el-table-column>
            <el-table-column prop="fee_time_rate" label="平均通话时长">
                <template slot-scope="scope">
                    <span style="margin-left: 10px">{{ getFormatSec(scope.row.fee_time_rate) }}</span>
                </template>
            </el-table-column>
            <el-table-column prop="a_class" label="A类客户"></el-table-column>
            <el-table-column prop="b_class" label="B类客户"></el-table-column>
            <el-table-column prop="c_class" label="C类客户"></el-table-column>
            <el-table-column prop="remain" label="剩余号码数量"></el-table-column>
        </el-table>
    </div>
</template>

<script>
    import { getCompanyAnalyse} from '@/api/company_analyse'

    export default {
        name: "info",
        data(){
            return{
                Userlist:[],
                id:null
            }
        },
        created(){
            console.log(this.$route.query)
            if("id" in this.$route.query){
                this.id = this.$route.query['id']
            }
            this.getUserlist({id: this.id })
        },
        methods:{
            getUserlist(data) {
                getCompanyAnalyse(data).then(response => {
                    this.Userlist = response.data.data
                })
            },
            objectSpanMethod({ row, column, rowIndex, columnIndex }){
                if (columnIndex === 0||columnIndex === 1||columnIndex === 2) {
                    if (rowIndex % 2 === 0) {
                        return {
                            rowspan: 2,
                            colspan: 1
                        };
                    } else {
                        return {
                            rowspan: 0,
                            colspan: 0
                        };
                    }
                }
            },
            getFormatSec(sec){
                let h = parseInt(parseInt(sec) / (60*60))
                let h_s = sec % (60*60)
                let m = parseInt(h_s / 60)
                let m_s = h_s % 60
                return h+":"+m+":"+m_s
            },
        }
    }
</script>

<style scoped>

</style>