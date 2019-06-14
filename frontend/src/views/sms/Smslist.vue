<template>
    <div class="app-container">
        <div class="filter-container">
            <el-form :inline="true" :model="form" class="demo-form-inline">
                <el-form-item label="接收号码">
                    <el-input v-model="form.mobile"></el-input>
                </el-form-item>
                <el-form-item label="模版标题">
                    <el-select v-model="form.title" placeholder="请选择标题">
                        <el-option label="全部" value="all"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="发送状态">
                    <el-select v-model="form.state" placeholder="请选择状态">
                        <el-option label="全部" value="all"></el-option>
                        <el-option label="发送成功" value="done"></el-option>
                        <el-option label="失败" value="fail"></el-option>
                    </el-select>
                </el-form-item><br>
                <el-form-item label="发送时间">
                    <el-col :span="11">
                        <el-date-picker type="date" placeholder="请选择开始日期" v-model="form.date1" style="width: 100%;"></el-date-picker>
                    </el-col>
                    <el-col class="line" :span="2">-</el-col>
                    <el-col :span="11">
                        <el-time-picker placeholder="请选择结束日期" v-model="form.date2" style="width: 100%;"></el-time-picker>
                    </el-col>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="onSubmit">立即搜索</el-button>
                    <el-button type="primary" @click="onSubmit">重新发送</el-button>
                </el-form-item>
            </el-form>
            <div class="remainNum">剩余可发送条数：<span></span> 条</div>

            <el-table
                    :data="tableData"
                    border
                    style="width: 100%"  @selection-change="handleSelectionChange">
                <el-table-column
                        type="selection"
                        width="55">
                </el-table-column>
                <el-table-column
                        prop="mobile"
                        label="接收号码"
                        width="180">
                </el-table-column>
                <el-table-column
                        prop="neirong"
                        label="发送内容"
                        width="1000">
                </el-table-column>
                <el-table-column
                        prop="state"
                        label="发送状态"
                        width="180">
                </el-table-column>
                <el-table-column
                        prop="date"
                        label="发送时间"
                        width="180">
                </el-table-column>
                <el-table-column
                        prop="do"
                        label="操作">
                </el-table-column>
            </el-table>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Smslist",
        data(){
            return{
                form: {
                    name: '',
                    region: '',
                    date1: '',
                    date2: '',
                    delivery: false,
                    type: [],
                    resource: '',
                    desc: ''
                }
            }
        },
        created(){
            //
        },
        methods:{
            onSubmit() {
                console.log('submit!');
            }
        }
    }
</script>

<style scoped>
    .remainNum{
        color: red;
        font-size: 20px;
        padding: 10px 0;
    }
</style>