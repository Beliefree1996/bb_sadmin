<template>
    <div class="app-container">
        <div class="filter-container">
        </div>
        <el-form :model="addForm" :rules="rules" ref="addForm" size="small">
            <el-form-item label="类型" prop="sign">
                <el-select v-model="addForm.type" placeholder="请选择类型">
                    <el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value">
                    </el-option>
                </el-select>
                <span style="padding-left: 15px">语句简称:<el-input type="text" style="width: 500px;padding-left: 15px" v-model="addForm.sign"></el-input></span>
            </el-form-item>

            <el-form-item label="关键词">
                <el-input type="textarea" v-model="addForm.keyword"></el-input>
            </el-form-item>

            <el-form-item label="选择语音" prop="voice_id">
                <el-select filterable v-model="addForm.voice_id" style="width: 100%;" placeholder="请选择语音">
                    <el-option v-for="item in voicelist" :key="item.id" :label="item.word" :value="item.id">
                    </el-option>
                </el-select>
            </el-form-item>

            <el-form-item label="请选择肯定回答" prop="y_next">
                <el-select filterable v-model="addForm.y_next" style="width: 100%;" placeholder="请选择话术">
                    <el-option label="空" value="0"></el-option>
                    <el-option v-for="item in wordlist" :key="item.id" :label="item.sign" :value="item.id">
                        <span style="float: left">{{ item.sign }}</span>
                        <span style="float: right; color: #8492a6; font-size: 13px">{{ item.word }}</span>
                    </el-option>
                </el-select>
            </el-form-item>

            <el-form-item label="请选择中性回答" prop="u_next">
                <el-select filterable v-model="addForm.u_next" style="width: 100%;" placeholder="请选择话术">
                    <el-option label="空" value="0"></el-option>
                    <el-option v-for="item in wordlist" :key="item.id" :label="item.sign" :value="item.id">
                        <span style="float: left">{{ item.sign }}</span>
                        <span style="float: right; color: #8492a6; font-size: 13px">{{ item.word }}</span>
                    </el-option>
                </el-select>
            </el-form-item>

            <el-form-item label="请选择否定回答" prop="n_next">
                <el-select filterable v-model="addForm.n_next" style="width: 100%;" placeholder="请选择话术">
                    <el-option label="空" value="0"></el-option>
                    <el-option v-for="item in wordlist" :key="item.id" :label="item.sign" :value="item.id">
                        <span style="float: left">{{ item.sign }}</span>
                        <span style="float: right; color: #8492a6; font-size: 13px">{{ item.word }}</span>
                    </el-option>
                </el-select>
            </el-form-item>

            <el-form-item label="分值" prop="score">
                <el-input-number type="number" v-model="addForm.score"></el-input-number>
            </el-form-item>

            <div style="margin-top: 15px; display: flex; justify-content: flex-end;">
                <el-button type="primary" @click="PatterWordAction()">{{isAddBtn?'确定':'更新'}}</el-button>
            </div>
        </el-form>
    </div>

</template>

<script>
    import { getToken, setToken, removeToken } from '@/utils/auth'
    import {allvoice, wordlist, addpatterword, editpatterword, getPatterWord} from '@/api/Number'

    export default {
        name: "PatterWordEdit",
        data() {
            return {
                Info: [],
                voicelist: [],
                wordlist: [],
                isAddBtn: true,
                options: [
                    {
                        value: 0,
                        label: '关键词'
                    },
                    {
                        value: 1,
                        label: '问候语'
                    },
                    {
                        value: 2,
                        label: '开场白'
                    },
                    {
                        value: 3,
                        label: '主干'
                    },
                    {
                        value: 4,
                        label: '主干结束语'
                    },
                    {
                        value: 5,
                        label: '出错结束语'
                    },
                    {
                        value: 6,
                        label: '没说话的回答'
                    },
                    {
                        value: 7,
                        label: '语音翻译为空的回答'
                    },
                    {
                        value: 8,
                        label: '没说话3次的结束语'
                    },
                    {
                        value: 9,
                        label: '没听清3次的结束语'
                    },

                    {
                        value: 10,
                        label: '否定三次的结束语'
                    },
                ],
                addForm: {
                    id: '',
                    pid: 0,
                    sign: '',
                    type: 0,
                    keyword: '',
                    score: 0,
                    voice_id: 0,
                    y_next: 0,
                    n_next: 0,
                    u_next: 0,
                },
                pid:0,
                wid:0,
                rules: {
                    voice_id: [{required: true, message: '内容不能为空', trigger: 'blur'}],
                    score: [{required: true, message: '请填写数字', trigger: 'blur'}, {
                        type: 'number',
                        message: '分值必须为数字',
                        trigger: 'blur'
                    }],
                },
            }
        },
        created() {
            const id = this.$route.params && this.$route.params.id
            this.addForm.pid = id
            this.pid = id
            if('wid' in this.$route.query){
                this.wid = this.$route.query.wid
                this.getInfo(this.wid)
                this.isAddBtn = false
            }
            this.getwordlist()
            this.getVoicelist()
        },
        methods: {
            getInfo(wid) {
                getPatterWord({wid:wid}).then(response => {
                    let res = response.data.data
                    this.addForm.id = res.id
                    this.addForm.pid = res.pid
                    this.addForm.sign = res.sign
                    this.addForm.type = res.type
                    this.addForm.keyword = res.keyword
                    this.addForm.score = res.score
                    this.addForm.voice_id = res.voice_id
                    this.addForm.y_next = res.y_next
                    this.addForm.n_next = res.n_next
                    this.addForm.u_next = res.u_next
                })
            },
            getVoicelist(){
                allvoice({token:getToken()}).then(response => {
                    this.voicelist = response.data.data
                })
            },
            getwordlist() {
                wordlist({pid:this.pid}).then(response => {
                    this.wordlist = response.data.data
                    this.wordlist.unshift({
                        id:-1,
                        sign:'挂机'
                    })
                })
            },
            PatterWordAction() {
                this.$confirm('此操作将即时生效, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$refs['addForm'].validate((valid) => {
                        if (valid) {
                            var params = this.addForm;
                            if (this.isAddBtn) {
                                this.addPatter(params);
                            } else {
                                this.updatePatter(params);
                            }
                        } else {
                            return false;
                        }
                    });
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消'
                    });
                });

            },
            addPatter(pp) {

                addpatterword(pp).then(response => {

                    if (response.data.code == '0000') {
                        this.$message.success("添加成功")
                        this.dialogFormVisible = false;
                    }else {
                        this.$message.error(response.data.msg)
                        this.dialogFormVisible = false;
                    }
                })
            },
            updatePatter(pp) {

                editpatterword(pp).then(response => {

                    if (response.data.code == '0000') {
                        this.$message.success(response.data.msg)
                        this.dialogFormVisible = false;
                    }else {
                        this.$message.error(response.data.msg)
                        this.dialogFormVisible = false;
                    }
                })
            },
        }
    }
</script>

<style scoped>

</style>