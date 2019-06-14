<template>
    <div class="app-container">
        <div class="filter-container">
            <el-button type="success" icon="el-icon-edit">模版添加</el-button>
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
                    label="模版标题"
                    width="150">
            </el-table-column>
            <el-table-column
                    prop="neirong"
                    label="模版内容"
                    width="1200">
            </el-table-column>
            <el-table-column
                    prop="date"
                    label="发布时间"
                    width="150">
            </el-table-column>
            <el-table-column
                    prop="state"
                    label="模版状态">
            </el-table-column>
        </el-table>
    </div>
</template>

<script>
    export default {
        name: "Smsmoban"
    }
</script>

<style scoped>

</style>