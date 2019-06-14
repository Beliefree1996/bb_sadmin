<template>
    <div style="overflow: auto;padding-bottom: 100px">
        <el-container>
            <el-button type="primary" @click="updateKeyIndex" :disabled="updateState">更新关键词索引</el-button>
            <span style="color: #f00;font-size: 20px;margin-left: 20px;display: inline-block">关键词更改之后必须更新此文件</span>
        </el-container>
        <el-container>
            <el-header>
                <div id="dashboard" style="font-size:2em">
                    <span>关键词</span><span style="color:#67C23A">{{ keyword }}</span>
                    <span>最高分关键词</span><span style="color:#409EFF">{{ word_max }}</span>
                    <span>最高分</span><span style="color:#409EFF">{{ score }}</span>
                </div>
                <div v-if="show_admin" style="display: inline-block">
                    <el-button type="primary" @click="putGeneralkeyVisible = true" round>添加通用关键词</el-button><span>用于干扰特殊关键字带来的跳转错误问题</span>
                </div>
                <el-button type="primary" @click="diffKeywordVisible = true" round >关键词对比</el-button>
            </el-header>
        </el-container>
        <el-container>
            <el-main>
                <p>评分标准</p>
                <div id="recording">
                    <el-table :data="items" style="width: 100%" :default-sort="{prop: 'score', order: 'descending'}">
                        <el-table-column prop="id" label="ID" width="180"></el-table-column>
                        <el-table-column prop="y_next" label="肯定走向" width="180"></el-table-column>
                        <el-table-column prop="n_next" label="否定走向"></el-table-column>
                        <el-table-column prop="keyword" label="关键词"></el-table-column>
                        <el-table-column prop="word" label="文本"></el-table-column>
                        <el-table-column prop="score" label="分值"></el-table-column>
                    </el-table>
                </div>
            </el-main>
        </el-container>
        <el-container style="position: fixed;bottom: 0;height: 100px;right: 0">
            <el-footer>
                <div class="demonstration">关键词</div>
                <el-select v-model="pid" placeholder="请选择话术">
                    <el-option v-for="item in patters" :key="item[0]" :label="item[1]" :value="item[0]">
                    </el-option>
                </el-select>
                <el-input placeholder="请输入关键词" v-model="word" clearable
                          style="width:500px;margin-right: 20px"></el-input>
                <el-button @click="reDispatchList" type="success" plain>确定</el-button>
            </el-footer>
        </el-container>
        <el-dialog title="提示" :visible.sync="putGeneralkeyVisible" width="30%">
            <span slot="footer" class="dialog-footer">
                <el-form ref="form" :model="form" label-width="100px">
                    <el-form-item label="文本">
                        <el-input v-model="form.text"></el-input>
                    </el-form-item>
                    <el-form-item label="意图">
                        <el-select v-model="form.sentiment" placeholder="请选择意图">
                            <el-option label="消极" value=0></el-option>
                            <el-option label="中性" value=1></el-option>
                            <el-option label="积极" value=2></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="是否强制匹配">
                        <el-switch v-model="form.flag" active-value="1" inactive-value="0"></el-switch>
                    </el-form-item>
                    <el-form-item label="大类意图">
                        <el-select v-model="form.action" placeholder="请选择大类意图">
                            <el-option label="结束" value=0></el-option>
                            <el-option label="通过" value=1></el-option>
                            <el-option label="重复" value=2></el-option>
                            <el-option label="继续" value=3></el-option>
                            <el-option label="忙碌" value=4></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="小类意图" v-if="form.action==3">
                        <el-select v-model="form.category" placeholder="请选择小类意图">
                        <el-option label="正向" value=1></el-option>
                        <el-option label="负向" value=3></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="onSubmit('form')">立即创建</el-button>
                        <el-button @click="putGeneralkeyVisible = false">取消</el-button>
                    </el-form-item>
                </el-form>
            </span>
        </el-dialog>
        <el-dialog title="提示" :visible.sync="diffKeywordVisible" width="30%">
            <span slot="footer" class="dialog-footer">
                <el-form ref="difform" :model="difform" label-width="100px">
                    <el-form-item label="原文本">
                        <el-input v-model="difform.text"></el-input>
                    </el-form-item>
                    <el-form-item label="关键词文本1">
                        <el-input v-model="difform.text1"></el-input>
                    </el-form-item>
                    <el-form-item label="关键词文本2">
                        <el-input v-model="difform.text2"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="onSubmitDiff">对比</el-button>
                        <el-button @click="diffKeywordVisible = false">关闭</el-button>
                    </el-form-item>
                </el-form>
            </span>
            <span>结果</span>
            <span v-for="i,v in diffresult" style="display: block">{{v}}:{{i}}</span>
        </el-dialog>
    </div>
</template>

<script>
    import Cookies from 'js-cookie'
    import {fetchPatter, getDispatchList,updateKeyIndex,putKeyword,diffKeyword} from '@/api/matchTest'
    import store from '@/store'
    import {isInArray} from '@/tool/index'

    export default {
        name: "test",
        data() {
            return {
                patters: [],
                word: '',
                word_max: '',
                keyword: '',
                score: 0,
                pid: '',
                items: [],
                putGeneralkeyVisible: false,
                form:{
                    sentiment:'',
                    text:'',
                    flag:0,
                    action:undefined,
                    category:undefined,
                },
                diffKeywordVisible:false,
                diffresult:{},
                difform:{
                    text:'',
                    text1:'',
                    text2:'',
                },
                show_admin:false,
                updateState:false,
                updateTimer:null,
            }
        },
        created() {
            if(isInArray('admin',store.getters.roles)){
                this.show_admin = true
            }
            this.getPatterList();
        },
        methods: {
            reDispatchList() {
                this.items = []
                if (!this.pid || !this.word) {
                    this.$notify.error({
                        title: '错误',
                        message: "参数为空",
                        duration: 2000
                    })
                    return
                }
                getDispatchList({pid: this.pid, text: this.word}).then(res => {
                    let response = res.data
                    console.log(response)
                    if (response.code == 200) {
                        this.items = response.message.keywords;
                        this.keyword = response.message.keyword;
                        this.score = response.message.score_max;
                        this.word_max = response.message.word_max;
                        this.$notify({
                            title: '成功',
                            message: '处理成功',
                            type: 'success',
                            duration: 2000
                        })
                    } else {
                        this.$notify.error({
                            title: '错误',
                            message: response.msg,
                            duration: 2000
                        })
                    }
                }).catch(error=>{
                    console.log("ERRRO" + error)
                    this.$message.error("网络错误")
                })
            },
            getPatterList() {
                // Cookies.set("a",23,{domain:"cti.bbxxjs.com"})
                fetchPatter().then(response => {
                    this.patters = response.data.message;
                }).catch(error => {
                    console.log("ERROR" + error)
                    this.$message.error("网络错误")
                })
            },
   /*         getDispatchList(pid, word) {
                if (pid == '' || word == '') {
                    this.$notify.error({
                        title: '错误',
                        message: "参数为空",
                        duration: 2000
                    })
                    return
                }
                this.fetchKeyword(pid, word).then(response => {
                    if (response.code == 200) {
                        this.items = response.message.keywords;
                        this.keyword = response.message.keyword;
                        this.score = response.message.score_max;
                        this.word_max = response.message.word_max;
                        this.$notify({
                            title: '成功',
                            message: '处理成功',
                            type: 'success',
                            duration: 2000
                        })
                    } else {
                        this.$notify.error({
                            title: '错误',
                            message: response.msg,
                            duration: 2000
                        })
                    }
                }).catch(error => {
                    console.log("ERRRO" + error)
                })
            },*/
      /*      fetchKeyword(pid, text) {
                var url = "http://127.0.0.1:8000/api/ai/bsword?pid=" + pid + "&text=" + text;
                return $.ajax({
                    url: url,
                    xhrFields: {
                        withCredentials: true
                    },
                    crossDomain: true
                });
            },*/
            // fetchPatter() {
            //     var url = "http://127.0.0.1:8000/api/ai/patter";
            //     return $.ajax({
            //         url: url,
            //         xhrFields: {
            //             withCredentials: true
            //         },
            //         crossDomain: true
            //     });
            // },
            updateKeyIndex(){
                updateKeyIndex().then(response=>{
                    response = response.data
                    if(response.code == 200){
                        this.$message.success("索引可更新")
                        this.updateState = true
                    }else if(response.code == 201){
                        this.$message("被占用，请稍后再试")
                        this.updateState = false
                    }else if(response.code == 202){
                        this.$message.success("后台更新中...")
                        this.updateState = false
                    } else{
                        this.$message.error("更新失败，请刷新重试")
                    }
                }).catch(error=>{
                    console.log(error)
                    this.$message.error("网络错误，请联系管理员")
                })
            },
            onSubmit(formName) {
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        putKeyword(this.form).then(response => {
                            this.$notify({
                                title: '成功',
                                message: '添加成功',
                                type: 'success',
                                duration: 2000
                            })
                            this.form = {
                                sentiment:'',
                                text:'',
                                flag:0,
                                action:undefined,
                                category:undefined,
                            }
                            this.putGeneralkeyVisible = false
                        })
                    } else {
                        this.$notify.error({
                            title: '错误',
                            message: '表单验证错误',
                            duration: 2000
                        })
                    }
                });
            },
            onSubmitDiff() {
                diffKeyword(this.difform).then(response => {
                    console.log(response)
                    this.diffresult=response.data.message
                })
            },
        }
    }
</script>

<style scoped>

</style>