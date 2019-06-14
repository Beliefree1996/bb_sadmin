<template>
    <div id="recharge">
        <el-form ref="form" :rules="rules" :model="form" label-width="80px">
            <el-form-item label="充值金额" prop="amount" v-if="false">
                <el-input v-model="form.sid"></el-input>
            </el-form-item>
            <el-form-item label="充值金额" prop="amount">
                <el-input v-model="form.amount" style="width:496px"></el-input>
            </el-form-item>
            <el-form-item label="选择人员" v-if="gid==2">
                <el-transfer filterable filter-placeholder="请输入姓名" v-model="form.people" :data="data" :titles="transferTitle">
                </el-transfer>
            </el-form-item>

            <el-form-item>
                <el-button type="primary" @click="submitForm('form')">充值</el-button>
                <el-button @click="resetForm('form')">清空</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>
<script>
    import {fetchSUserlist, RechargeVos} from '@/api/mtuser'

    export default {
        name: "UserMTListTable",

        data() {
            return {
                transferTitle:['可选人员','已选人员'],
                data: [],
                form: {
                    sid: this.$route.params.name,
                    amount: "",
                    people: [],
                },
                gid: this.$route.params.gid,
                rules: {
                    amount: [{required: true, message: '请填写金额', trigger: 'blur'}],
                },
            };
        },
        created() {
            this.generateData();
        },
        methods: {
            submitForm(formName) {
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        if ((this.gid == 2 && this.form.people.length > 0)||(this.gid == 1||this.gid == 3)) {
                            RechargeVos(this.form).then(response => {
                                if (response.data.code == 200) {
                                    this.$notify({
                                        title: '成功',
                                        message: '充值成功',
                                        type: 'success',
                                        duration: 2000
                                    })
                                } else {
                                    this.$notify.error({
                                        title: '错误',
                                        message: response.data.msg,
                                        duration: 2000
                                    })
                                }
                            }).catch(error => {
                            })
                        }else{
                            this.$notify.error({
                                title: '错误',
                                message: '提交数据格式错误',
                                duration: 2000
                            })
                        }
                    }
                });
            },
            resetForm(formName) {
                this.$refs[formName].resetFields();
            },
            fetchRecharge() {

            },
            generateData() {
                if (this.gid == 2) {
                    fetchSUserlist(this.$route.params.name).then(response => {
                        if (response.data.code == 200) {
                            var data = []
                            var result = response.data.data;
                            for (var i = 0; i < result.length; i++) {
                                data.push({
                                    label: result[i].username,
                                    key: result[i].id,
                                });
                            }
                            this.data = data;
                        }
                    })
                }
            },
        },
    };
</script>

<style>
    #recharge {
        width: 40%;
        margin: 100px auto;
    }
</style>
