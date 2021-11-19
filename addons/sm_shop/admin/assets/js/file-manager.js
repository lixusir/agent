Vue.component('file-manager', {
    template: `
        <el-dialog
            title="文件管理器"
            :visible.sync="fileManagerVisible"
            width="30%"
            center>
            <span>需要注意的是内容是默认不居中的</span>
            <span slot="footer" class="dialog-footer">
                <el-button @click="fileManagerVisible = false">取 消</el-button>
                <el-button type="primary" @click="fileManagerVisible = false">确 定</el-button>
            </span>
        </el-dialog>
    `,
    props: [],
    data: function () {
        return {
            fileManagerVisible: true
        }
    },
    methods: {
        init:function(){

        },
    },
    created: function () {

    },
});