<template>
    <div class="app-container">
        <div class="filter-container">
            <div class="sms_add">
                <div class="form">
                    <el-form ref="form" :model="form" label-width="80px">
                        <el-form-item label="发送号码">
                            <el-input v-model="form.name"></el-input>
                        </el-form-item>
                        <el-form-item label="短信内容">
                            <el-input type="textarea" v-model="form.desc"></el-input>
                            <div class="text">提示：当前一次只能发送一条短信</div>
                        </el-form-item>
                        <el-form-item>
                            <el-button type="primary" @click="onSubmit">立即发送</el-button>
                        </el-form-item>
                    </el-form>
                </div>
                <div class="tpl">
                    <div class="TplMain">
                        <div class="tpl_title">短信模板</div>
                        <div class="tpl_list">
                            <div>暂无模板</div>
                            <ul></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        name: "Smssend",
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
        created() {
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
    .sms_add{ height: auto; overflow: hidden; display: flex; width: 900px}
    .sms_add .form{ flex: 6; width: 600px}
    .sms_add .text{color: #F56C6C}
    .sms_add .tpl{ padding:0px 15px; flex: 4; width: 300px;}
    .sms_add .tpl .TplMain{ border: 1px solid #409EFF; border-radius:3px; }
    .sms_add .tpl .TplMain .tpl_title{ background: #409EFF; height: 35px; color: #fff;font-size: 16px; line-height: 35px; padding-left: 15px}
    .sms_add .tpl .TplMain .tpl_list{ padding: 10px 15px;}
    .sms_add .tpl .TplMain .tpl_list ul li{ line-height: 25px;font-size: 14px; cursor: pointer}
    .sms_add .tpl .TplMain .tpl_list ul li:hover{ color: #409EFF}
</style>