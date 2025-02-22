<div id="plugin">
    <div class="plugin-header">
        <p class="plugin-header-title">WP外链跳转插件</p>
    </div>
    <div class="cp-info">
        <p><a href="https://www.lovestu.com/" target="_blank">Lovestu</a>系列插件，兼容所有主题</p>
        <p>当前版本：<?php echo $this->plugin_version_name ?> ，最新版本：{{version}} <span
                    v-if="'<?php echo $this->plugin_version_name ?>'!=version"><a target="_blank" :href="download_url">去下载更新</a></span></p>
    </div>
    <div class="plugin-body">

        <h3>白名单设置</h3>
        <p>域名一行一个，不需要加http前缀，顶级域名会匹配所有子域名</p>
        <el-input
                type="textarea"
                placeholder="请输入内容"
                v-model="set.whiteList"
                show-word-limit
                :rows="5">
        </el-input>
        <div class="btn-plane">
            <el-button type="primary" @click="save">保存</el-button>
        </div>
    </div>
</div>
<script>
    var ajaxurl = '<?php echo admin_url('admin-ajax.php')?>';

</script>