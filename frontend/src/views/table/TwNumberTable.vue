<template>
    <div class="app-container">
        <div class="filter-container">
            <!--工具条-->
            <el-row :span="8" class="top_toolbar">
                <!--条件搜索-->
                <el-form :inline="true" :model="filters" style="display: inline-block;padding: 0;">
                    <el-form-item>
                        <el-input v-model="filters.name" placeholder="批次名称" style="width: 120px"></el-input>
                    </el-form-item>
                    <el-form-item style="margin-left: 10px">
                        <el-button type="primary" v-on:click="getTable">查询</el-button>
                    </el-form-item>
                </el-form>
            </el-row>
            <!--号码表导入-->
            <el-button type="primary" @click="add_number_table = true">导入号码表</el-button>
            <el-button type="primary">
                <a href="/static/file/demo.xlsx" style="color: #fff;text-decoration: none">下载示例</a>
            </el-button>
            <!--&nbsp;&nbsp;&nbsp;&nbsp;-->
            <!--<el-button type="primary"  @click="transform_number_table = true">提取表格数据</el-button>-->
            <el-row>
                <br/>
            </el-row>
            <!--导入弹窗-->
            <el-dialog title="导入号码表" :visible.sync="add_number_table" center>
                <div class="first">
                    <span>第一步：请选择需导入的号码模板文件</span>
                    <span class="red">(不用模板文件将不能成功导入，建议单次导入号码数不要超过30000行)</span>
                    <el-tooltip class="tool_item" effect="dark" content="下载.xlsx模板" placement="bottom">
                        <a href="/static/file/demo.xlsx" style="text-decoration: none">
                            <i class="fa fa-download"></i></a>
                    </el-tooltip>
                </div>
                <div class="second">
                    <span>第二步：请上传需要上传的文件</span>
                    <div slot="tip" class="el-upload__tip">只能上传xlsx文件</div>
                    <el-upload :limit="1" class="upload_table" ref="upload" action="https://sai.bbxxjs.com/uploadexl"
                               :data="uploadfileParams"
                               :file-list="fileList"
                               :on-success="uploaded_table" :on-error="unupload_table"
                               :auto-upload="false">
                        <el-button slot="trigger" size="small" type="primary">选取文件</el-button>
                    </el-upload>
                </div>
                <div slot="footer" class="dialog-footer">
                    <el-button type="primary" @click="submitUpload">确 定</el-button>
                </div>
            </el-dialog>

            <el-dialog title="提取号码" :visible.sync="transform_number_table" center>
                <div class="first">
                    <span>第一步：请上传从天眼查下载的客户信息Excel表格</span>
                    <span class="red">(不用指定格式的文件将不能成功提取)</span>
                    <el-tooltip class="tool_item" effect="dark" content="下载.xlsx模板" placement="bottom">
                        <a href="/static/file/transform.xlsx" style="text-decoration: none">
                            <i class="fa fa-download"></i></a>
                    </el-tooltip>
                </div>
                <div class="second">
                    <span>第二步：请下载提取出的号码文件</span>
                    <div slot="tip" class="el-upload__tip">只能上传xlsx文件</div>
                    <el-upload :limit="1" class="upload_table" ref="transfor" action="https://sai.bbxxjs.com/transformexl"
                               :data="uploadfileParams"
                               :file-list="fileList"
                               :on-success="transform_table" :on-error="untransform_table"
                               :auto-upload="false">
                        <el-button slot="trigger" size="small" type="primary">选取文件</el-button>
                    </el-upload>
                </div>
                <div slot="footer" class="dialog-footer">
                    <el-button type="primary" @click="submitTransor">提取号码</el-button>
                </div>
                <div slot="footer" class="dialog-footer">
                    <template v-if="isdown">
                        <el-button type="primary" style="margin-top: 10px" @click="transorDownload">下载</el-button>
                    </template>

                </div>
            </el-dialog>

            <!--条件搜索-->
            <!--<el-form :inline="true" :model="filters" style="display: inline-block;padding: 0;margin-left: 15px">-->
                <!--<el-form-item>-->
                    <!--<el-input  v-model="filters.name" placeholder="批次名称"></el-input>-->
                <!--</el-form-item>-->
                <!--<el-form-item style="margin-left: 10px">-->
                    <!--<el-button  type="primary" v-on:click="getTable">查询</el-button>-->
                <!--</el-form-item>-->
            <!--</el-form>-->

            <!--列表-->
            <el-table :data="table_list" class="auto_table" height="100%" v-loading="tableLoading"
                      @selection-change="selsChange" border style="width: 100%;" :header-cell-style="tableHeaderColor">
                <!--<el-table-column type="selection" width="55" label="全选"></el-table-column>-->
                <el-table-column align="center" type="index" width="66" label="序号" fixed="left"></el-table-column>
                <el-table-column align="center" prop="name" min-width="160" label="批次名称"></el-table-column>
                <el-table-column align="center" prop="user" min-width="160" label="导入者"></el-table-column>
                <el-table-column align="center" prop="znum" min-width="100" label="总行数" sortable></el-table-column>
                <el-table-column align="center" prop="snum" min-width="100" label="剩余行数" sortable></el-table-column>
                <el-table-column align="center" prop="dateline" min-width="160" label="导入时间" sortable></el-table-column>
                <el-table-column align="center" label="操作" width="100" fixed="right">
                    <template slot-scope="scope">
                        <router-link :to="'TwDistribute/'+scope.row.id">
                            <el-button type="text" size="small">分配</el-button>
                        </router-link>
                        <el-button type="text" size="small" @click="handleDel(scope.$index, scope.row)">删除</el-button>
                    </template>
                </el-table-column>
            </el-table>
            <!--页码条-->
            <el-col :span="24" class="toolbar" style="margin-top: 10px">
                <!--<el-button type="primary" :disabled="this.sels.length===0"> 批量分配 </el-button>-->
                <!--<el-pagination layout="prev, pager, next" @current-change="handleCurrentChange" :page-size="pagesize"-->
                               <!--:total="total" style="float:right;"></el-pagination>-->
            </el-col>
        </div>
    </div>
</template>

<script>
    import { getToken, setToken, removeToken } from '@/utils/auth'
    import { mobileList,delNumpc } from '@/api/Number'


    export default {
        name: "TwNumberTable",
        data() {
            return {
                add_number_table: false,
                transform_number_table: false,
                fileList: [],
                uploadfileParams: {},
                table_list: [],
                sels: [],//列表选中列
                tableLoading: false,
                // total: 50,
                // page: 1,
                // pagesize: 10,
                filters: {name: ''},
                token:"",
                //来电弹窗
                inCallDialog:true,
                isdown:false,
                filename: "",
            }
        },
        created(){
            this.token = getToken()
            this.getTable()
        },
        mounted() {
            this.uploadfileParams = {token: this.token};
        },
        methods:{
            // 上传成功
            uploaded_table: function (res, file, fileList) {
                var _this = this;
                if (res.code == '0000') {
                    this.$confirm('文件上传成功', '上传成功', {
                        confirmButtonText: '确定',
                        showCancelButton: false,
                        callback: action => {
                            _this.add_number_table = false;
                            _this.getTable();
                        }
                    });
                }else{
                    _this.$message.error(res.msg)
                    _this.isDistributing = false;
                }
            },
            // 上传失败
            unupload_table: function (err, file, fileList) {
                var _this = this;
                this.$confirm('文件上传失败，请重新上传', '上传失败', {
                    confirmButtonText: '确定',
                    showCancelButton: false,
                    callback: action => {
                        _this.fileList = [];
                    }
                });
            },
            // 提取成功
            transform_table: function (res, file, fileList) {
                if (res.code == '0000') {
                    this.$message.success("号码提取成功！");
                    this.isdown = true;
                    this.filename = res.data;
                } else{
                    this.$message.error("号码提取失败！请确认上传的文件格式再试！");
                }
            },
            // 上传失败
            untransform_table: function (err, file, fileList) {
                var _this = this;
                this.$confirm('文件上传失败，请重新上传', '上传失败', {
                    confirmButtonText: '确定',
                    showCancelButton: false,
                    callback: action => {
                        _this.fileList = [];
                    }
                });
            },
            ////上传excel号码表
            submitUpload() {
                this.$refs.upload.submit();
            },

            // 提取号码表
            submitTransor() {
                this.$refs.transfor.submit();
            },
            // 下载提取后的号码表
            transorDownload() {
                window.location.href = "https://sai.bbxxjs.com/transdown/" + this.filename;
            },
            // 修改table header的背景色
            tableHeaderColor({ row, column, rowIndex, columnIndex }) {
                if (rowIndex === 0) {
                    return 'background-color: #f7f7f7;color: #363636;font-weight: 500;'
                }
            },
            // 选择行
            selsChange (sels) {
                this.sels = sels;
            },
            // 点击页码
            // handleCurrentChange(val) {
            //     this.page = val;
            //     this.getTable()
            // },
            // 请求表格数据
            getTable() {
                this.tableLoading = true;
                // TODO获取表格数据
                var params = {
                    // s: this.page,
                    // p: this.pagesize,
                    token:getToken(),
                    name: this.filters.name,
                };

                mobileList(params).then(response => {
                    const res = response.data;
                    if (res.code == '0000') {
                        this.table_list = res.data.rows;
                        // this.total = res.data.total;
                    } else {
                        this.$message.error(res.msg);
                    }
                    this.tableLoading = false
                }).catch(err => {
                    console.log(err);
                    this.tableLoading = false;
                    this.$message.error("网络错误");
                });
            },
            // 分配
            handleDistribute: function (index, row) {
                // window.location.href = "/index/index/distribute?id=" + row.id;
                this.$emit("distributeBack",row.id);
            },
            // 删除
            handleDel: function (index, row) {
                this.$confirm('此操作将永久删除该文件, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    delNumpc({id:row.id}).then(response => {
                        const res = response.data;
                        if (res.code == '0000') {
                            this.$message({
                                type: 'success',
                                message: '删除成功!'
                            });
                            this.getTable();
                        } else {
                            this.$message.error(res.msg);
                        }
                    }).catch(err => {
                        console.log(err);
                        this.tableLoading = false;
                        this.$message.error("网络错误");
                    });
                }).catch(()=>{});
            },
        },
    }
</script>

<style scoped>
    .upload_table {
        display: inline-block;
    }

    .filter-container{
        height: calc(100vh - 84px - 40px);
    }

    .first, .second {
        line-height: 35px;
    }

    .first > .red {
        color: #ff0000;
        font-size: 12px;
    }

    .tool_item {
        padding: 3px 20px;
        border: 1px solid #409eff;
        border-radius: 10px;
        color: #409eff;
        cursor: pointer;
    }

    .upload_table {
        display: inline-block;
    }

    .filter-container:after{
        content: '';
        height: 0;
        width: 0;
        clear: both;
        display: block;
    }

    .auto_table{
        max-height: calc(100% - 112px);
        overflow: auto;
    }
</style>