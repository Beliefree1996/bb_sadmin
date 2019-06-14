<template>
    <div class="main_content">
        <!--<div class="topView">-->
            <!--<img src="imgs/aotoouter_headimg.png" alt="">-->
            <!--<div class="information">-->
                <!--<img src="" alt="">-->
                <!--<span></span>-->
            <!--</div>-->
        <!--</div>-->
        <!--<div class="content_body" id="m_container">-->
            <!--<template>-->
                <!--<div class="collectionView">-->
                    <!--<div class="collection_item" v-for="(item,index) in listData" >-->
                        <!--<img :src="item.icon" alt="">-->
                        <!--<div>{{item.name}}</div>-->
                        <!--</div>-->
                    <!--</div>-->
            <!--</template>-->
        <!--</div>-->
    </div>
</template>

<script>
    // import { mapGetters } from 'vuex'
    // import PanThumb from '@/components/PanThumb'
    // import GithubCorner from '@/components/GithubCorner'

    export default {
        // name: 'DashboardSale',
        // components: { PanThumb, GithubCorner },
        // data() {
        //     return {
        //         listData:[
        //             {name: "机器人", icon: "imgs/autoouter_task.png"},
        //             {name: "证券", icon: "imgs/autoouter_selfstudy.png"},
        //             {name: "贷款", icon: "imgs/autoouter_library.png"},
        //             {name: "保险", icon: "imgs/autoouter_debug.png"},
        //             {name: "培训", icon: "imgs/autoouter_other.png"},
        //         ],
        //     }
        // },
        // computed: {
        //     ...mapGetters([
        //         'name',
        //         'avatar',
        //         'roles'
        //     ])
        // }
    }
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
    /*.emptyGif {*/
        /*display: block;*/
        /*width: 45%;*/
        /*margin: 0 auto;*/
    /*}*/

    /*.dashboard-editor-container {*/
        /*background-color: #e3e3e3;*/
        /*min-height: 100vh;*/
        /*padding: 50px 60px 0px;*/
        /*.pan-info-roles {*/
            /*font-size: 12px;*/
            /*font-weight: 700;*/
            /*color: #333;*/
            /*display: block;*/
        /*}*/
        /*.info-container {*/
            /*position: relative;*/
            /*margin-left: 190px;*/
            /*height: 150px;*/
            /*line-height: 200px;*/
            /*.display_name {*/
                /*font-size: 48px;*/
                /*line-height: 48px;*/
                /*color: #212121;*/
                /*position: absolute;*/
                /*top: 25px;*/
            /*}*/
        /*}*/
    /*}*/
    /*.main_content {*/
        /*width: 100%;*/
        /*font-size: 0.16rem;*/
    /*}*/
    /*.topView {*/
        /*width: 100%;*/
        /*position: relative;*/
    /*}*/
    /*.topView >img{*/
        /*width: 100%;*/
    /*}*/
    /*.topView .information{*/
        /*position: absolute;*/
        /*left: 0.05rem;*/
        /*top: 0.05rem;*/
        /*display: flex;*/
        /*flex-direction: column;*/
        /*align-items: center;*/
        /*font-size: 0.12rem;*/
        /*color: white;*/
    /*}*/
    /*.topView .information img{*/
        /*width: 0.46rem;*/
        /*height: 0.46rem;*/
        /*border-radius: 0.23rem;*/
    /*}*/
    /*!**!*/
    /*.seatView{*/
        /*width: 100%;*/
        /*margin: 0.1rem 0;*/
        /*font-size: 0.16rem;*/
    /*}*/
    /*.seatView h3{*/
        /*font-size: 0.16rem;*/
        /*padding-left: 0.2rem;*/
        /*margin-bottom: 0.05rem;*/
    /*}*/
    /*.seatView .seatList{*/
        /*border: 0.01rem solid #eee;*/
        /*height: 0.55rem;*/
        /*display: flex;*/
        /*align-items: center;*/
        /*padding: 0 0.15rem;*/
        /*overflow:scroll;*/
    /*}*/
    /*.seatView .seatList span{*/
        /*font-size: 0.14rem;*/
        /*display: inline-block;*/
        /*padding: 3px 8px;*/
        /*height: 35px;*/
        /*line-height: 29px;*/
        /*border: 0.01rem solid #eee;*/
        /*text-align: center;*/
    /*}*/


    /*.seatEmpty > a{*/
        /*color: rgb(41,135,192);*/
        /*font-size: 0.14rem;*/
        /*margin-top: 0.2rem;*/
    /*}*/
    /*!**!*/
    /*.content_body{*/
        /*width: 100%;*/
        /*background-color: white;*/
    /*}*/
    /*.navView{*/
        /*width: 100%;*/
        /*height: 0.5rem;*/
        /*overflow: auto;*/
        /*display: inline;*/
        /*white-space: nowrap;*/
        /*overflow-x:scroll;*/
        /*float:left;*/
         /*-webkit-overflow-scrolling: touch;*/
        /*overflow-scrolling: touch;*/
    /*}*/
    /*.element::-webkit-scrollbar {display:none}*/

    /*.navView:after{*/
        /*clear: both;*/
    /*}*/
    /*.navView li{*/
        /*width: 1rem;*/
        /*height: 100%;*/
        /*display: inline-block;*/
        /*line-height: 0.5rem;*/
        /*text-align: center;*/
    /*}*/
    /*.collectionView{*/
        /*width: 100%;*/
        /*padding: 0.1rem;*/
        /*display: flex;*/
        /*justify-content: flex-start;*/
        /*flex-wrap: wrap;*/
    /*}*/
    /*.collectionView .collection_item{*/
        /*display: flex;*/
        /*flex-direction: column;*/
        /*justify-content: center;*/
        /*align-items: center;*/
        /*padding: 0.1rem;*/
        /*width: 33.3%;*/
        /*margin-top: 0.3rem;*/
    /*}*/
    /*.collectionView .collection_item img{*/
        /*height: 0.35rem;*/
    /*}*/
    /*.collectionView .collection_item div{*/
        /*font-size: 0.12rem;*/
        /*margin-top: 0.08rem;*/
        /*display: inline-block;*/
        /*color: white;*/
        /*background-color: rgb(41,135,192);*/
        /*padding: 0.03rem;*/
        /*border-radius: 0.03rem;*/
        /*width: 100%;*/
        /*text-align: center;*/
    /*}*/
</style>
