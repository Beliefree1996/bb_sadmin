<template>
    <div class="app-container">
        <div class="filter-container">
            <div class="TopForm">
                <div class="textInfo">温馨提示：请按标准模板格式上传资料</div>
                <ul class="sms_many_up">
                    <li>
                        <el-button type="primary">上传资料</el-button>
                    </li>
                    <li>
                        <el-button type="success" plain>下载.txt模板</el-button>
                    </li>
                    <li>
                        <el-button type="warning" plain>下载.xls模板</el-button>
                    </li>
                </ul>
                <div class="sms_add">
                    <div class="form">
                        <el-form ref="form" :model="form" label-width="80px">
                            <el-form-item label="短信内容">
                                <el-input type="textarea" v-model="form.desc"></el-input>
                            </el-form-item>
                            <el-form-item>
                                <el-button type="primary" @click="onSubmit">立即创建</el-button>
                                <el-button>取消</el-button>
                            </el-form-item>
                        </el-form>
                    </div>
                    <div class="tpl">
                        <div class="TplMain">
                            <div class="tpl_title">短信模板</div>
                            <div class="tpl_list"><div>暂无模板</div>
                                <ul></ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Smsqunsend",
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
    .sms_many_up{padding:15px;overflow:hidden;}
    .sms_many_up > li{float:left;min-width:160px;min-height:100px;padding:5px;}
    ul{ padding: 0; margin: 0; list-style: none}
</style>