<template>
    <div class="app-container">
        <div class="filter-container">
            <el-button type="success" icon="el-icon-edit">添加类型</el-button>
        </div>

        <el-table
                :data="tableData"
                border
                style="width: 100%">
            <el-table-column
                    fixed
                    prop="id"
                    label="序号"
                    width="50">
            </el-table-column>
            <el-table-column
                    prop="title"
                    label="类型名称"
                    width="150">
            </el-table-column>
            <el-table-column
                    prop="title"
                    label="类型"
                    width="100">
            </el-table-column>
            <el-table-column
                    prop="neirong"
                    label="内容"
                    width="1000">
            </el-table-column>
            <el-table-column
                    prop="date"
                    label="创建时间"
                    width="150">
            </el-table-column>
            <el-table-column
                    prop="date"
                    label="更新时间"
                    width="150">
            </el-table-column>
            <el-table-column
                    prop="state"
                    label="操作">
            </el-table-column>
        </el-table>
    </div>
</template>

<script>
    export default {
        name: "Smssendtype"
    }
</script>

<style scoped>

</style>