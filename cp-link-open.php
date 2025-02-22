<?php
/*
Plugin Name: WordPress外链跳转插件
Plugin URI: https://github.com/hsmaocn/cp-link-open
Description: WordPress外链跳转模块，外部链接，打开以后提示是否外站跳转
Version: 1.0.2
Author: applek(原作者)、hmao
Author URI: https://www.lovestu.com
*/

$cp_link_open = cp_link_open::get_instance();
class cp_link_open
{
    private $plugin_url;
    private $plugin_static;
    private $plugin_name;
    private $plugin_version;
    private $plugin_set;
    private static $instance;
    private $plugin_version_name;

    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {
        $this->plugin_name = 'wp_link_open';
        $this->plugin_version = 1;
        $this->plugin_version_name = '1.0.0';
        $this->plugins_url = plugins_url('', __FILE__);
        $this->plugin_static = plugins_url('static', __FILE__);
        add_action('admin_menu', array($this, 'add_menu'));
        $this->loadset();
        add_action('admin_enqueue_scripts', array($this, 'loadfile'));
        add_filter('the_content', array($this, 'content'));
        add_action('wp_ajax_wp_link_open_ajax', array($this, 'ajax'));
        add_action('init', array($this, 'add_rewrite_rules'));
        add_filter('query_vars', array($this, 'add_query_vars'));
        add_action('template_redirect', array($this, 'handle_redirect'));
        // 确保在插件激活时刷新重写规则
        register_activation_hook(__FILE__, array($this, 'flush_rewrite_rules'));
        // 确保在插件停用时清理重写规则
        register_deactivation_hook(__FILE__, array($this, 'remove_rewrite_rules'));
    }

    function flush_rewrite_rules()
    {
        $this->add_rewrite_rules();
        flush_rewrite_rules();
    }

    function remove_rewrite_rules()
    {
        flush_rewrite_rules();
    }

    function content($content)
    {
        preg_match_all('|<a.*?href="(.*?)".*?>(.*?)\</a>|', $content, $domain_arr, PREG_SET_ORDER);
        $url_whiteList_arr = preg_split('/[;\r\n]+/s', $this->plugin_set['whiteList']);
        foreach ($domain_arr as $item) {
            $isreplace = true;
            foreach ($url_whiteList_arr as $url_item) {
                $re = stripos($item[1], $url_item);
                if ($re !== false) {
                    $isreplace = false;
                    break;
                }
            }
            if ($isreplace == true) {
                $encoded_link = base64_encode($item[1]);
                $redirect_url = home_url('/wp-link-redirect/') . '?link=' . $encoded_link;
                $html = $this->str_replace_once($item[1], $redirect_url, $item[0]);
                $content = str_replace($item[0], $html, $content);
            }
        }
        return $content;
    }

    function loadset()
    {
        $data['version'] = $this->plugin_version;
        $data['whiteList'] = '';
        $this->plugin_set = json_decode(get_option($this->plugin_name . '_set', json_encode($data)), true);
    }

    function loadfile($slug)
    {
        if ($slug == 'settings_page_wp_link_open') {
            wp_enqueue_script($this->plugin_name . '_vue', $this->plugin_static . '/js/vue.min.js', '', $this->plugin_version, false);
            wp_enqueue_script($this->plugin_name . '_element', $this->plugin_static . '/lib/element/index.js', '', $this->plugin_version, true);
            wp_enqueue_style($this->plugin_name . '_element_css', $this->plugin_static . '/lib/element/index.css', '', $this->plugin_version, false);
            wp_enqueue_style($this->plugin_name . '_admin_css', $this->plugin_static . '/css/style.css', '', $this->plugin_version, false);
            wp_enqueue_script($this->plugin_name . '_index', $this->plugin_static . '/js/index.js', array($this->plugin_name . '_vue', $this->plugin_name . '_element'), $this->plugin_version, true);
            wp_localize_script($this->plugin_name . '_index', 'set', json_encode($this->plugin_set));
            wp_enqueue_script($this->plugin_name . '_base64', $this->plugin_static . '/js/base64.js', '', $this->plugin_version, true);
        }
    }

    function add_menu()
    {
        add_submenu_page('options-general.php', 'WP外链跳转', 'WP外链跳转', 'manage_options', 'wp_link_open', array($this, 'creat_set_menu'), '');
    }

    function creat_set_menu()
    {
        require_once 'cp-link-open-set.php';
    }

    function str_replace_once($needle, $replace, $haystack)
    {//只替换一次字符串
        $pos = strpos($haystack, $needle);
        if ($pos === false) {
            return $haystack;
        }
        return substr_replace($haystack, $replace, $pos, strlen($needle));
    }

    function ajax()
    {
        if (isset($_POST['type']) == false) {
            die();
        }
        $type = $_POST['type'];
        if ($type == 'set') {
            if (isset($_POST['set']) == false) {
                die();
            }
            $this->plugin_set = json_decode(base64_decode($_POST['set']), true);
            if ($this->save_set($this->plugin_set)) {
                $data['code'] = 200;
            } else {
                $data['code'] = 500;
            }
            die(json_encode($data));
        }

        die();
    }

    function save_set($set)
    {
        return update_option($this->plugin_name . '_set', json_encode($set));
    }

    function add_rewrite_rules()
    {
        add_rewrite_rule('^wp-link-redirect/?', 'index.php?wp_link_redirect=1', 'top');
    }

    function add_query_vars($vars)
    {
        $vars[] = 'wp_link_redirect';
        return $vars;
    }

    function handle_redirect()
    {
        if (get_query_var('wp_link_redirect')) {
            if (isset($_GET['link'])) {
                $link = base64_decode($_GET['link']);
                require_once(ABSPATH . 'wp-load.php');
                $site_name = get_bloginfo('name');
                $redirect_url = esc_url($link);
                include 'link.php';
                exit;
            }
        }
    }
}