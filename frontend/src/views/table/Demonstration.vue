<template>
    <div class="main_content">
        <div class="topView">
            <img src="/static/imgs/aotoouter_headimg.png">
        </div>

        <el-row :gutter="10" class="topView_items">
            <el-col :md="4" :xs="8" class="topView_item">
                <div class="topView_item_des">
                    <img src="/static/imgs/robot.png" height="100" width="100">
                </div>
                <div class="topView_item_btn">
                    <el-button type="text" @click="TestCall">机器人</el-button>
                </div>
            </el-col>
            <el-col :md="4" :xs="8"  class="topView_item">
                <div class="topView_item_des">
                    <img src="/static/imgs/zhengquan.png" height="100" width="100">
                </div>
                <div class="topView_item_btn">
                    <el-button type="text" @click="TestCall">证券</el-button>
                </div>
            </el-col>
            <el-col :md="4" :xs="8"  class="topView_item">
                <div class="topView_item_des">
                    <img src="/static/imgs/daikuan.png" height="100" width="100">
                </div>
                <div class="topView_item_btn">
                    <el-button type="text" @click="TestCall">贷款</el-button>
                </div>
            </el-col>
            <el-col :md="4" :xs="8"  class="topView_item">
                <div class="topView_item_des">
                    <img src="/static/imgs/baoxian.png" height="100" width="100">
                </div>
                <div class="topView_item_btn">
                    <el-button type="text" @click="TestCall">保险</el-button>
                </div>
            </el-col>
            <el-col :md="4"  :xs="8" class="topView_item">
                <div class="topView_item_des">
                    <img src="/static/imgs/peixun.png" height="100" width="100">
                </div>
                <div class="topView_item_btn">
                    <el-button type="text" @click="TestCall">培训</el-button>
                </div>
            </el-col>
            <el-col :md="4" :xs="8"  class="topView_item">
                <div class="topView_item_des">
                    <img src="/static/imgs/building.png" height="100" width="100">
                </div>
                <div class="topView_item_btn">
                    <el-button type="text" @click="TestCall">房地产</el-button>
                </div>
            </el-col>
        </el-row>

        <el-row align="middle" justify="center" type="flex">
            <el-button size="large"  type="primary" @click="test">话术测试</el-button>
        </el-row>

        <!--对话框-->
        <el-dialog :title="'话术测试'" :visible.sync="dialogFormVisible" :width="device==='mobile'?'300px':'480px'" :fs="device">
            <el-form :model="callForm" :rules="rules" ref="callForm" size="small"  label-width="60px">
                <el-form-item label="号码" prop="mobile">
                    <el-input size="mini" type="number"  placeholder="请输入测试号码" v-model="callForm.mobile"></el-input>
                </el-form-item>
                <el-form-item label="话术">
                    <el-select v-model="callForm.patter" placeholder="请选择话术">
                        <el-option
                                v-for="item in patters"
                                :key="item.id"
                                :label="item.title"
                                :value="item.id">
                        </el-option>
                    </el-select>
                </el-form-item>

                <div style="margin-top: 15px; display: flex; justify-content: flex-end;">
                    <el-button type="primary" @click="CallAction">拨打</el-button>
                </div>
            </el-form>
        </el-dialog>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex'

    import { getToken, setToken, removeToken } from '@/utils/auth'
    import { getAllPatter ,startCall,getCurQueue} from '@/api/patter'

    export default{
        name: "demonstration",
        data(){
            return{
                callForm: {
                    token:getToken(),
                    mobile: '',
                    patter: '',
                },
                rules: {
                    mobile: [{required:true, message: '内容不能为空', trigger: 'blur'}],
                },
                dialogFormVisible: false,
                patters:[],
            }
        },
        computed: {
            ...mapGetters([
                'device'
            ])
        },
        created(){
            this.getAllPatter()
        },
        methods:{
            TestCall(){
                // this.dialogFormVisible = true;
            },
            CallAction(){
                if(this.callForm.mobile.length<=0){
                    this.$message.warning("手机号为空")
                    return
                }
                if(this.callForm.patter<=0){
                    this.$message.warning("请选择话术")
                    return
                }
                getCurQueue({token:getToken()}).then(response=>{
                    var res = response.data
                    if(res.code == "0000"){
                        if(res.data.status == 0){
                            startCall(this.callForm).then(response1=>{
                                var res = response1.data
                                if(res.code == "0000"){
                                    this.$message.success("正在拨打中,请等待")
                                    this.callForm.mobile = ''
                                    this.callForm.patter = ''
                                }else{
                                    this.$message.error("加入队列失败")
                                }
                            }).catch(err=>{
                                this.$message.error("拨打失败")
                            })
                        }else{
                            this.$message.warning("当前队列已满，请稍后测试")
                        }
                    }else{
                        this.$message.error("获取当前通话队列失败")
                    }
                }).catch(err=>{
                    this.$message.error("获取当前通话队列失败")
                })

            },
            test(){
                this.dialogFormVisible = true;
            },
            getAllPatter(){
                getAllPatter().then(response=>{
                    var res = response.data
                    if(res.code == "0000"){
                        this.patters = res.data
                    }else{
                        this.$message.error("话术获取失败")
                    }
                }).catch(err=>{
                    this.$message.error("话术获取失败")
                })
            }
        }
    }

</script>

<style scoped>
    .main_content {
        width: 100%;
        font-size: 0.16rem;
    }
    .topView {
        width: 100%;
        position: relative;
    }
    .topView img{
        width: 100%;
    }
    .topView .information{
        position: absolute;
        left: 0.05rem;
        top: 0.05rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        font-size: 0.12rem;
        color: white;
    }
    .topView .information img{
        width: 0.46rem;
        height: 0.46rem;
        border-radius: 0.23rem;
    }
    .main_content .topView_items{
        text-align: center;
    }
    .main_content .topView_item{
        position: relative;
        padding: 5px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-direction: column;
    }
    .main_content .topView_item_des{
        font-size: 0.12rem;
    }
    .main_content .topView_item_btn{
        margin-top: 15px;
    }
</style>
