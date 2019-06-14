<template>
    <div class="app-container">
        <div class="filter-container">
        </div>
        <el-form :model="addForm" :rules="rules" ref="addForm" size="small" label-width="150px">
            <el-form-item label="类型">
                <el-select v-model="addForm.type" placeholder="请选择类型">
                    <el-option v-for="item in type_arr" :key="item.value" :label="item.label" :value="item.value">
                    </el-option>
                </el-select>
            </el-form-item>

            <el-form-item label="语句简称">
                <el-input type="text" v-model="addForm.sign"></el-input>
            </el-form-item>

            <el-form-item label="关键词">
                <el-select filterable v-model="addForm.word_base" placeholder="请选择关键词" @change="wordBaseChange">
                    <el-option v-for="item in word_bases" :key="item.id" :label="item.name" :value="item.id">
                    </el-option>
                </el-select>
            </el-form-item>

            <el-form-item label="分值" prop="score">
                <el-input-number type="number" v-model="addForm.score"></el-input-number>
            </el-form-item>

            <el-form-item label="选择角色" >
                <el-select filterable v-model="addForm.role_id" style="width: 100%;" placeholder="请选择语音">
                    <el-option v-for="item in rolelist" :key="item.id" :label="item.common" :value="item.role">
                    </el-option>
                </el-select>
            </el-form-item>

            <el-form-item label="选择语音" >
                <el-select filterable v-model="addForm.voice_id" style="width: 100%;" placeholder="请选择语音">
                    <el-option v-for="item in voicelist" :key="item.id" :label="item.word" :value="item.id">
                    </el-option>
                </el-select>
            </el-form-item>

            <el-form-item label="排序" prop="order">
                <el-input-number type="number" v-model="addForm.order" :min="0"></el-input-number>
            </el-form-item>

            <el-form-item label="请选择肯定回答" >
                <el-select filterable v-model="addForm.y_next" style="width: 100%;" placeholder="请选择话术">
                    <el-option v-for="item in wordlist" :key="item.id" :label="item.sign" :value="item.id">
                        <span style="float: left">{{ item.sign }}</span>
                        <span style="float: right; color: #8492a6; font-size: 13px">{{ item.word }}</span>
                    </el-option>
                </el-select>
            </el-form-item>

            <el-form-item label="请选择中性回答" >
                <el-select filterable v-model="addForm.u_next" style="width: 100%;" placeholder="请选择话术">
                    <el-option v-for="item in wordlist" :key="item.id" :label="item.sign" :value="item.id">
                        <span style="float: left">{{ item.sign }}</span>
                        <span style="float: right; color: #8492a6; font-size: 13px">{{ item.word }}</span>
                    </el-option>
                </el-select>
            </el-form-item>

            <el-form-item label="请选择否定回答" >
                <el-select filterable v-model="addForm.n_next" style="width: 100%;" placeholder="请选择话术">
                    <el-option v-for="item in wordlist" :key="item.id" :label="item.sign" :value="item.id">
                        <span style="float: left">{{ item.sign }}</span>
                        <span style="float: right; color: #8492a6; font-size: 13px">{{ item.word }}</span>
                    </el-option>
                </el-select>
            </el-form-item>

            <el-form-item label="是否不可重复">
                <el-switch v-model="addForm.repeatable" :active-value="1" :inactive-value="0"></el-switch>
            </el-form-item>

            <el-form-item label="不可重复的替代语句">
                <el-select filterable v-model="addForm.replace" style="width: 100%;" placeholder="请选择替代语句">
                    <el-option v-for="item in row_wordlist" :key="item.id" :label="item.sign" :value="item.id">
                        <span style="float: left">{{ item.sign }}</span>
                        <span style="float: right; color: #8492a6; font-size: 13px">{{ item.word }}</span>
                    </el-option>
                </el-select>
            </el-form-item>

            <el-form-item label="不匹配关键词">
                <el-switch v-model="addForm.pass_key" :active-value="1" :inactive-value="0"></el-switch>
            </el-form-item>

            <el-form-item label="情景关键词">
                <el-select filterable v-model="addForm.scene_id" placeholder="请选择情景关键词" @change="sceneChange">
                    <el-option v-for="item in scenes" :key="item.id" :label="item.name" :value="item.id">
                    </el-option>
                </el-select>
            </el-form-item>

            <el-form-item label="情景关键词处理次序">
                <el-select v-model="addForm.normal_intention" placeholder="请选择情景关键词处理次序">
                    <el-option v-for="item in intention_arr" :key="item.value" :label="item.name" :value="item.value">
                    </el-option>
                </el-select>
            </el-form-item>

            <el-form-item label="不做任何处理直接进入下一肯定流程">
                <el-switch v-model="addForm.go_straight" :active-value="1" :inactive-value="0"></el-switch>
            </el-form-item>

            <el-form-item label="标签">
                <el-input type="text" v-model="addForm.label"></el-input>
            </el-form-item>

            <el-form-item label="最大等待对方说话时间（0为系统默认时间，单位：秒）" prop="waittime">
                <el-input-number type="number" v-model="addForm.waittime" :min="0"></el-input-number>
            </el-form-item>

            <div style="margin-top: 15px; display: flex; justify-content: flex-end;">
                <el-button type="primary" @click="PatterWordAction()">{{isAddBtn?'确定':'更新'}}</el-button>
            </div>
        </el-form>
    </div>

</template>

<script>
    import { getToken, setToken, removeToken } from '@/utils/auth'
    import { addpatter1word, editpatter1word,getPatter1Word,word1list, allvoice,getAllWordBase,getAllScene,allrole} from '@/api/patterTest'

    export default {
        name: "PatterWordEdit",
        data() {
            return {
                pid:0,
                wid:0,
                isAddBtn: true,
                addForm: {
                    id: '',
                    pid: 0,
                    sign: '',
                    type: 0,
                    word_base: null,
                    word_base_name: '',
                    score: 0,
                    voice_id: 0,
                    role_id: 0,
                    order: 0,
                    y_next: 0,
                    n_next: 0,
                    u_next: 0,
                    repeatable: 0,
                    replace: 0,
                    pass_key: 0,
                    scene_id: 0,
                    scene_name: '',
                    normal_intention: 0,
                    go_straight: 0,
                    label: '',
                    waittime: 0,
                },
                word_bases:[],
                scenes:[],
                voicelist: [],
                rolelist: [],
                wordlist: [],
                row_wordlist:[],
                type_arr: [
                    {
                        value: 0,
                        label: '关键词'
                    },
                    {
                        value: 1,
                        label: '入口'
                    },
                    {
                        value: 2,
                        label: '主干'
                    },
                    {
                        value: 3,
                        label: '出错结束语'
                    },
                    {
                        value: 4,
                        label: '没说话的回答'
                    },
                    {
                        value: 5,
                        label: '没说话3次的结束语'
                    },
                    {
                        value: 6,
                        label: '否定三次的结束语'
                    },
                ],
                intention_arr:[
                    {
                        value:0,
                        name:'不进行普通意向判断'
                    },
                    {
                        value:1,
                        name:'先进行普通意向判断'
                    },
                    {
                        value:2,
                        name:'后进行普通意向判断'
                    },
                ],
                rules: {
                    voice_id: [{required: true, message: '内容不能为空', trigger: 'blur'}],
                },
            }
        },
        created() {
            if(this.$route.query && 'pid' in this.$route.query){
                this.pid = this.$route.query.pid
                this.addForm.pid = this.pid
                if('wid' in this.$route.query){
                    this.wid = this.$route.query.wid
                    this.getInfo()
                    this.isAddBtn = false
                }
                this.getWordBase()
                this.getScene()
                this.getRolelist()
                this.getVoicelist()
                this.getwordlist()
            }else{
                this.$message.error("话术不存在")
            }
        },
        methods: {
            getWordBase(){
                getAllWordBase({token:getToken()}).then(response=>{
                    let res = response.data
                    if(res.code == "0000"){
                        this.word_bases = res.data
                        this.word_bases.unshift({id:0,name:'空'})
                    }else{
                        this.$message.error(res.msg)
                    }
                }).catch(err=>{
                    this.$message.error("获取关键词列表失败！")
                })
            },
            getScene(){
                getAllScene({token:getToken()}).then(response=>{
                    let res = response.data
                    if(res.code == "0000"){
                        this.scenes = res.data
                        this.scenes.unshift({id:0,name:'空'})
                    }else{
                        this.$message.error(res.msg)
                    }
                }).catch(err=>{
                    this.$message.error("获取情景关键词列表失败！")
                })
            },
            wordBaseChange(val){
                for(let word of this.word_bases){
                    if(word.id == val){
                        this.addForm.word_base_name = word.name
                        break
                    }
                }
            },
            sceneChange(val){
                for(let scene of this.scenes){
                    if(scene.id == val){
                        this.addForm.scene_name = scene.name
                        break
                    }
                }
            },
            getRolelist(){
                allrole({pid:this.pid}).then(response => {
                    this.rolelist = response.data.data
                })
            },
            getVoicelist(){
                allvoice({token:getToken()}).then(response => {
                    this.voicelist = response.data.data
                })
            },
            getwordlist() {
                word1list({pid:this.pid}).then(response => {
                    this.wordlist = response.data.data
                    this.row_wordlist = response.data.data.concat([])
                    this.wordlist.unshift({
                        id:-1,
                        sign:'挂机'
                    })
                    this.wordlist.unshift({
                        id:0,
                        sign:'空'
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
                addpatter1word(pp).then(response => {
                    if (response.data.code == '0000') {
                        this.$message.success("添加成功")
                    }else {
                        this.$message.error(response.data.msg)
                    }
                })
            },
            updatePatter(pp) {
                editpatter1word(pp).then(response => {
                    if (response.data.code == '0000') {
                        this.$message.success(response.data.msg)
                    }else {
                        this.$message.error(response.data.msg)
                    }
                })
            },
            getInfo() {
                getPatter1Word({wid:this.wid}).then(response => {
                    let res = response.data.data
                    this.addForm.id = res.id
                    this.addForm.pid = res.pid
                    this.addForm.sign = res.sign
                    this.addForm.type = res.type
                    this.addForm.word_base = res.word_base
                    this.addForm.word_base_name = res.word_base_name
                    this.addForm.score = res.score
                    this.addForm.voice_id = res.voice_id
                    this.addForm.role_id = res.role
                    this.addForm.order = res.order
                    this.addForm.y_next = res.y_next
                    this.addForm.n_next = res.n_next
                    this.addForm.u_next = res.u_next
                    this.addForm.repeatable = res.repeatable
                    this.addForm.replace = res.replace
                    this.addForm.pass_key = res.pass_key
                    this.addForm.scene_id = res.scene_id
                    this.addForm.scene_name = res.scene_name
                    this.addForm.normal_intention = res.normal_intention
                    this.addForm.go_straight = res.go_straight
                    this.addForm.label = res.label
                    this.addForm.waittime = res.waittime
                })
            },
        }
    }
</script>

<style scoped>

</style>