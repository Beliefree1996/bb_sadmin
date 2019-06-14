<template>
    <div class="main_contain">
        <el-row :gutter="10">
            <el-col :xs="24" :sm="12" :md="12" :lg="8" v-for="(info,index) in informations" :key="index">
                <div class="information_box">
                    <div>
                        <div class="info_name">{{info.username}}</div>
                        <div class="info_content">
                            <div class="info_item" v-for="(info_item,i_index) in info.info_arr" :key="i_index">
                                <span class="info_num">{{info_item.num}}</span>
                                <span class="info_label">{{info_item.label}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </el-col>
        </el-row>
    </div>
</template>

<script>
    import {getToken, setToken, removeToken} from '@/utils/auth'
    import {getInformations} from '@/api/information_aggregation'

    export default {
        name: "index",
        data() {
            return {
                informations: [],
                info_timeout:null
            }
        },
        created() {
            this.getInformations()
        },
        methods: {
            getInformations() {
                getInformations().then(response => {
                    var data = response.data
                    if (data.code == "0000") {
                        if (data.data.length > 0) {
                            this.informations = []
                            for (var i = 0; i < data.data.length; i++) {
                                this.informations.push(this.getFormatData(data.data[i]))
                            }
                        }
                    } else {
                        this.$message.error(data.msg)
                    }
                    let _this = this
                    this.info_timeout = setTimeout(function () {
                        _this.getInformations()
                    },5000)
                }).catch(err => {
                    this.$message.error("获取数据失败，请刷新重试！")
                })
            },
            getFormatData(data) {
                return {
                    username: data.username,
                    info_arr: [
                        {
                            num: this.getformatBill(parseInt(data.bill_sum)),
                            label: '通话时长'
                        },
                        {
                            num: data.call_count,
                            label: '拨打数量'
                        },
                        {
                            num: data.new_count,
                            label: '新增归档'
                        },
                        {
                            num: data.bill_rate,
                            label: '接通率'
                        },
                        {
                            num: this.getformatBill(parseInt(data.average)),
                            label: '平均通话时长'
                        },
                        {
                            num: data.wx_count,
                            label: '加微信量'
                        },
                        {
                            num: data.unhandle_count,
                            label: '剩余任务'
                        },
                        {
                            num: data.nodeal_count,
                            label: '无需处理量'
                        },
                        {
                            num: data.file_rate,
                            label: '归档比例'
                        },
                    ]
                }
            },
            // 毫秒转换
            getformatBill(seconds) {
                var second = parseInt(seconds) % 60
                var minute = parseInt(seconds / 60) % 60
                var hour = parseInt(seconds / 60 / 60) % 60
                return (hour > 10 ? hour : "0" + hour) + ":" + (minute > 10 ? minute : "0" + minute) + ":" + (second > 10 ? second : "0" + second)
            },
        },
        beforeDestroy(){
            if(this.info_timeout ){
                clearTimeout(this.info_timeout )
            }
        }
    }
</script>

<style scoped lang="scss">
    .main_contain {
        padding: 20px;
        .information_box {
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            & > div {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                border-radius: 5px;
                .info_name {
                    font-size: 20px;
                    font-weight: 700;
                    line-height: 40px;
                    text-align: left;
                }
                .info_content {
                    display: flex;
                    flex-direction: row;
                    flex-wrap: wrap;
                    align-items: center;
                    justify-content: center;
                    .info_item {
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: space-around;
                        margin-bottom: 15px;
                        width: 30%;
                        .info_num {
                            font-size: 24px;
                            font-weight: 700;
                        }
                        .info_label {
                            font-size: 16px;
                            margin-top: 10px;
                        }
                    }
                }
            }
        }
        & .information_box:nth-child(2) > div {
            background-color: #67aefe;
        }
        & .information_box:nth-child(1) > div {
            background-color: #32c5d2;
        }
    }

    @media only screen and (max-width: 767px) {
        .main_contain {
            padding: 10px;
        }
    }
</style>