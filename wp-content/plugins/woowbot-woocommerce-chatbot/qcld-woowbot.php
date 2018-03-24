<?php
/**
 * Plugin Name: WooWBot WooCommerce Chatbot
 * Plugin URI: https://wordpress.org/plugins/woowbot-woocommerce-chatbot/
 * Description: WooWBot is a WooCommerce Chat Bot. This stand alone ChatBot helps shoppers find the product they are looking for easily and increase sales! 
 * Donate link: http://www.quantumcloud.com
 * Version: 1.3.0
 * @author    QuantumCloud
 * @category  WooCommerce
 * Author: QunatumCloud
 * Author URI: https://www.quantumcloud.com/
 * Requires at least: 4.0
 * Tested up to: 4.9
 * Text Domain: woochatbot
 * Domain Path: /lang/
 * License: GPL2
 */


if (!defined('ABSPATH')) exit; // Exit if accessed directly

define('QCLD_WOOCHATBOT_VERSION', '1.0');
define('QCLD_WOOCHATBOT_REQUIRED_WOOCOMMERCE_VERSION', 2.2);
define('QCLD_WOOCHATBOT_PLUGIN_DIR_PATH', basename(plugin_dir_path(__FILE__)));
define('QCLD_WOOCHATBOT_PLUGIN_URL', plugin_dir_url(__FILE__));
define('QCLD_WOOCHATBOT_IMG_URL', QCLD_WOOCHATBOT_PLUGIN_URL . "images/");
define('QCLD_WOOCHATBOT_IMG_ABSOLUTE_PATH', plugin_dir_path(__FILE__) . "images");
require_once("functions.php");


/**
 * Main Class.
 */
class QCLD_Woo_Chatbot
{

    private $id = 'woowbot';

    private static $instance;

    /**
     *  Get Instance creates a singleton class that's cached to stop duplicate instances
     */
    public static function qcld_woo_chatbot_get_instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
            self::$instance->qcld_woo_chatbot_init();
        }
        return self::$instance;
    }

    /**
     *  Construct empty on purpose
     */

    private function __construct()
    {
    }

    /**
     *  Init behaves like, and replaces, construct
     */

    public function qcld_woo_chatbot_init()
    {

        // Check if WooCommerce is active, and is required WooCommerce version.
        if (!class_exists('WooCommerce') || version_compare(get_option('woocommerce_db_version'), QCLD_WOOCHATBOT_REQUIRED_WOOCOMMERCE_VERSION, '<')) {
            add_action('admin_notices', array($this, 'woocommerce_inactive_notice_for_woo_chatbot'));
            return;
        }

        add_action('admin_menu', array($this, 'qcld_woo_chatbot_admin_menu'));

        if ((!empty($_GET["page"])) && ($_GET["page"] == "woowbot")) {

            add_action('admin_init', array($this, 'qcld_woo_chatbot_save_options'));
        }
        if (is_admin()) {
            add_action('admin_enqueue_scripts', array($this, 'qcld_woo_chatbot_admin_scripts'));
        }
        if (!is_admin()) {
            add_action('wp_enqueue_scripts', array($this, 'qcld_woo_chatbot_frontend_scripts'));
        }
    }


    /**
     * Add a submenu item to the WooCommerce menu
     */
    public function qcld_woo_chatbot_admin_menu()
    {

        add_submenu_page('woocommerce',
            __('WoowBot', 'woochatbot'),
            __('WoowBot', 'woochatbot'),
            'manage_woocommerce',
            $this->id,
            array($this, 'qcld_woo_chatbot_admin_page'));

    }



    /**
     * Include admin scripts
     */
    public function qcld_woo_chatbot_admin_scripts($hook)
    {
        global $woocommerce, $wp_scripts;

        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

        if (((!empty($_GET["page"])) && ($_GET["page"] == "woowbot")) || ($hook == "widgets.php")) {

            wp_enqueue_script('jquery');

            wp_enqueue_style('woocommerce_admin_styles', $woocommerce->plugin_url() . '/assets/css/admin.css');

            wp_register_style('qlcd-woo-chatbot-admin-style', plugins_url(basename(plugin_dir_path(__FILE__)) . '/css/admin-style.css', basename(__FILE__)), '', QCLD_WOOCHATBOT_VERSION, 'screen');
            wp_enqueue_style('qlcd-woo-chatbot-admin-style');

            wp_register_style('qlcd-woo-chatbot-font-awesome', plugins_url(basename(plugin_dir_path(__FILE__)) . '/css/font-awesome.min.css', basename(__FILE__)), '', QCLD_WOOCHATBOT_VERSION, 'screen');
            wp_enqueue_style('qlcd-woo-chatbot-font-awesome');


            wp_register_style('qlcd-woo-chatbot-tabs-style', plugins_url(basename(plugin_dir_path(__FILE__)) . '/css/woo-chatbot-tabs.css', basename(__FILE__)), '', QCLD_WOOCHATBOT_VERSION, 'screen');
            wp_enqueue_style('qlcd-woo-chatbot-tabs-style');


            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'jquery-ui-core');
            wp_register_script('qcld-woo-chatbot-cbpFWTabs', plugins_url(basename(plugin_dir_path(__FILE__)) . '/js/cbpFWTabs.js', basename(__FILE__)), array(), true);
            wp_enqueue_script('qcld-woo-chatbot-cbpFWTabs');

            wp_register_script('qcld-woo-chatbot-modernizr-custom', plugins_url(basename(plugin_dir_path(__FILE__)) . '/js/modernizr.custom.js', basename(__FILE__)), array(), true);
            wp_enqueue_script('qcld-woo-chatbot-modernizr-custom');

            wp_register_script('qcld-woo-chatbot-bootstrap-js', plugins_url(basename(plugin_dir_path(__FILE__)) . '/js/bootstrap.js', basename(__FILE__)), array('jquery'), true);
            wp_enqueue_script('qcld-woo-chatbot-bootstrap-js');

            wp_register_style('qcld-woo-chatbot-bootstrap-css', plugins_url(basename(plugin_dir_path(__FILE__)) . '/css/bootstrap.min.css', basename(__FILE__)), '', QCLD_WOOCHATBOT_VERSION, 'screen');
            wp_enqueue_style('qcld-woo-chatbot-bootstrap-css');

            wp_register_script('qcld-woo-chatbot-repeatable', plugins_url(basename(plugin_dir_path(__FILE__)) . '/js/jquery.repeatable.js', basename(__FILE__)), array('jquery'));
            wp_enqueue_script('qcld-woo-chatbot-repeatable');

            wp_register_script('qcld-woo-chatbot-admin-js', plugins_url(basename(plugin_dir_path(__FILE__)) . '/js/qcld-woo-chatbot-admin.js', basename(__FILE__)), array('jquery', 'jquery-ui-core'), true);
            wp_enqueue_script('qcld-woo-chatbot-admin-js');

            wp_localize_script('qcld-woo-chatbot-admin-js', 'ajax_object',
                array('ajax_url' => admin_url('admin-ajax.php')));

        }

    }


    public function qcld_woo_chatbot_frontend_scripts(){
        global $woocommerce, $wp_scripts;

        $woo_chatbot_obj = array(
            'woo_chatbot_position_x' => get_option('woo_chatbot_position_x'),
            'woo_chatbot_position_y' => get_option('woo_chatbot_position_y'),
            'ajax_url' => admin_url('admin-ajax.php'),
            'image_path'=>QCLD_WOOCHATBOT_IMG_URL,
            'host'=> get_option('qlcd_woo_chatbot_host'),
            'agent'=> get_option('qlcd_woo_chatbot_agent'),
            'agent_join'=> get_option('qlcd_woo_chatbot_agent_join'),
            'welcome'=> get_option('qlcd_woo_chatbot_welcome'),
            'asking_name'=> get_option('qlcd_woo_chatbot_asking_name'),
            'i_am'=> get_option('qlcd_woo_chatbot_i_am'),
            'name_greeting'=> get_option('qlcd_woo_chatbot_name_greeting'),
            'product_asking'=> get_option('qlcd_woo_chatbot_product_asking'),
            'product_suggest'=> get_option('qlcd_woo_chatbot_product_suggest'),
            'product_infinite'=> get_option('qlcd_woo_chatbot_product_infinite'),
            'product_success'=> get_option('qlcd_woo_chatbot_product_success'),
            'product_fail'=> get_option('qlcd_woo_chatbot_product_fail'),
            'currency_symbol' => get_woocommerce_currency_symbol(),
        );

        wp_register_script('qcld-woo-chatbot-slimscroll-js', plugins_url(basename(plugin_dir_path(__FILE__)) . '/js/jquery.slimscroll.min.js', basename(__FILE__)), array('jquery'), QCLD_WOOCHATBOT_VERSION, true);
        wp_enqueue_script('qcld-woo-chatbot-slimscroll-js');

        wp_register_script('qcld-woo-chatbot-frontend', plugins_url(basename(plugin_dir_path(__FILE__)) . '/js/qcld-woo-chatbot-frontend.js', basename(__FILE__)), array('jquery'), QCLD_WOOCHATBOT_VERSION, true);
        wp_enqueue_script('qcld-woo-chatbot-frontend');


        wp_localize_script('qcld-woo-chatbot-frontend', 'woo_chatbot_obj', $woo_chatbot_obj);
        wp_register_style('qcld-woo-chatbot-frontend-style', plugins_url(basename(plugin_dir_path(__FILE__)) . '/css/frontend-style.css', basename(__FILE__)), '', QCLD_WOOCHATBOT_VERSION, 'screen');
        wp_enqueue_style('qcld-woo-chatbot-frontend-style');
    }


    /**
     * Render the admin page
     */
    public function qcld_woo_chatbot_admin_page()
    {

        global $woocommerce;

        $action = 'admin.php?page=woowbot'; ?>
        <div class="woo-chatbot-wrap wrap">
            <div class="icon32"><br></div>
            <form action="<?php echo esc_attr($action); ?>" method="POST" enctype="multipart/form-data">
                <div class="container form-container">
                    <h2><?php echo __('Woowbot Control Panel', 'woochatbot'); ?></h2>
                    <section class="woo-chatbot-tab-container-inner">
                        <div class="woo-chatbot-tabs woo-chatbot-tabs-style-flip">
                            <nav>
                                <ul>
                                    <li><a href="#section-flip-1"><i class="fa fa-toggle-on"></i><span> <strong>GENERAL SETTINGS</strong></span></a>
                                    </li>
                                    <li><a href="#section-flip-3"><i class="fa fa-gear faa-spin"></i><span> Woowbot ICONS</span></a></li>
                                    <li><a href="#section-flip-7"><i class="fa fa-language"></i><span> LANGUAGE CENTER</span></a></li>
                                    <li><a href="#section-flip-8"><i class="fa fa-code"></i><span> Custom CSS</span></a></li>
                                </ul>
                            </nav>
                            <div class="content-wrap">
                                <section id="section-flip-1">
                                    <div class="top-section">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <p class="qc-opt-title-font">
                                                    Disable Woowbot
                                                </p>
                                                <div class="cxsc-settings-blocks">
                                                    <input  value="1" id="disable_woo_chatbot" type="checkbox" name="disable_woo_chatbot" <?php echo(get_option('disable_woo_chatbot') == 1 ? 'checked' : ''); ?>>
                                                    <label for="disable_woo_chatbot">Disable Woowbot to load </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <p class="qc-opt-title-font">
                                                    Override Woowbot Icon's Position
                                                </p>
                                                <div class="cxsc-settings-blocks">
                                                    <?php
                                                    $qcld_woo_chatbot_position_x = get_option('woo_chatbot_position_x');
                                                    if ((!isset($qcld_woo_chatbot_position_x)) || ($qcld_woo_chatbot_position_x == "")) {
                                                        $qcld_woo_chatbot_position_x = __("120", "woo_chatbot");
                                                    }
                                                    $qcld_woo_chatbot_position_y = get_option('woo_chatbot_position_y');
                                                    if ((!isset($qcld_woo_chatbot_position_y)) || ($qcld_woo_chatbot_position_y == "")) {
                                                        $qcld_woo_chatbot_position_y = __("50", "woo_chatbot");
                                                    } ?>

                                                    <input type="number" class="qc-opt-dcs-font"
                                                           name="woo_chatbot_position_x"
                                                           id=""
                                                           value="<?php echo($qcld_woo_chatbot_position_x); ?>"
                                                           placeholder="From Right In px"> <span class="qc-opt-dcs-font">From Right In px</span>
                                                    <input type="number" class="qc-opt-dcs-font"
                                                           name="woo_chatbot_position_y"
                                                           id=""
                                                           value="<?php echo($qcld_woo_chatbot_position_y); ?>"
                                                           placeholder="From Bottom In Px"> <span class="qc-opt-dcs-font">From Bottom In px</span>
                                                </div>
                                            </div>
                                            <div class="col-xs-12">
                                                <div class="form-group">
                                                    <p class="qc-opt-title-font">Number of products to show in search results. ( '-1' for all products ).</p>
                                                    <input type="text" class="form-control qc-opt-dcs-font" name="qlcd_woo_chatbot_ppp" value="<?php echo(get_option('qlcd_woo_chatbot_ppp')!=''? get_option('qlcd_woo_chatbot_ppp') :10);?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <section id="section-flip-3">
                                    <div class="top-section">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <ul class="radio-list">
                                                    <li><img src="<?php echo QCLD_WOOCHATBOT_IMG_URL; ?>/icon-0.png"
                                                             alt=""> <input type="radio"
                                                                            name="woo_chatbot_icon" <?php echo(get_option('woo_chatbot_icon') == 'icon-0.png' ? 'checked' : ''); ?>
                                                                            value="icon-0.png">
                                                       <span class="qc-opt-dcs-font">Icon - 0</span>
                                                    </li>


                                                    <li><img src="<?php echo QCLD_WOOCHATBOT_IMG_URL; ?>/icon-1.png"
                                                             alt=""> <input type="radio"
                                                                            name="woo_chatbot_icon" <?php echo(get_option('woo_chatbot_icon') == 'icon-1.png' ? 'checked' : ''); ?>
                                                                            value="icon-1.png">
                                                        <span class="qc-opt-dcs-font">Icon - 1</span>
                                                    </li>
                                                    <li><img src="<?php echo QCLD_WOOCHATBOT_IMG_URL; ?>/icon-2.png"
                                                             alt=""> <input type="radio" name="woo_chatbot_icon"
                                                                            value="icon-2.png" <?php echo(get_option('woo_chatbot_icon') == 'icon-2.png' ? 'checked' : ''); ?>>
                                                        <span class="qc-opt-dcs-font">Icon - 2</span>
                                                    </li>
                                                    <li><img src="<?php echo QCLD_WOOCHATBOT_IMG_URL; ?>/icon-3.png"
                                                             alt=""> <input type="radio" name="woo_chatbot_icon"
                                                                            value="icon-3.png" <?php echo(get_option('woo_chatbot_icon') == 'icon-3.png' ? 'checked' : ''); ?>>
                                                        <span class="qc-opt-dcs-font">Icon - 3</span>
                                                    </li>

                                                    <li><img src="<?php echo QCLD_WOOCHATBOT_IMG_URL; ?>/icon-4.png"
                                                             alt=""> <input type="radio" name="woo_chatbot_icon"
                                                                            value="icon-4.png" <?php echo(get_option('woo_chatbot_icon') == 'icon-4.png' ? 'checked' : ''); ?>>
                                                        <span class="qc-opt-dcs-font">Icon - 4</span>
                                                    </li>


                                                    <li><img src="<?php echo QCLD_WOOCHATBOT_IMG_URL; ?>/icon-5.png"
                                                             alt=""> <input type="radio" name="woo_chatbot_icon"
                                                                            value="icon-5.png" <?php echo(get_option('woo_chatbot_icon') == 'icon-5.png' ? 'checked' : ''); ?>>
                                                        <span class="qc-opt-dcs-font">Icon - 5</span>
                                                    </li>
                                                    <li><img src="<?php echo QCLD_WOOCHATBOT_IMG_URL; ?>/icon-6.png"
                                                             alt=""> <input type="radio" name="woo_chatbot_icon"
                                                                            value="icon-6.png" <?php echo(get_option('woo_chatbot_icon') == 'icon-6.png' ? 'checked' : ''); ?>>
                                                        <span class="qc-opt-dcs-font">Icon - 6</span>
                                                    </li>
                                                    <li><img src="<?php echo QCLD_WOOCHATBOT_IMG_URL; ?>/icon-7.png"
                                                             alt=""> <input type="radio" name="woo_chatbot_icon"
                                                                            value="icon-7.png" <?php echo(get_option('woo_chatbot_icon') == 'icon-7.png' ? 'checked' : ''); ?>>
                                                        <span class="qc-opt-dcs-font">Icon - 7</span>
                                                    </li>
                                                    <li><img src="<?php echo QCLD_WOOCHATBOT_IMG_URL; ?>/icon-8.png"
                                                             alt=""> <input type="radio" name="woo_chatbot_icon"
                                                                            value="icon-8.png" <?php echo(get_option('woo_chatbot_icon') == 'icon-8.png' ? 'checked' : ''); ?>>
                                                        <span class="qc-opt-dcs-font">Icon - 8</span>
                                                    </li>
                                                    <li><img src="<?php echo QCLD_WOOCHATBOT_IMG_URL; ?>/icon-9.png"
                                                             alt=""> <input type="radio" name="woo_chatbot_icon"
                                                                            value="icon-9.png" <?php echo(get_option('woo_chatbot_icon') == 'icon-9.png' ? 'checked' : ''); ?>>
                                                        <span class="qc-opt-dcs-font">Icon -9</span>
                                                    </li>
                                                    <li><img src="<?php echo QCLD_WOOCHATBOT_IMG_URL; ?>/icon-10.png"
                                                             alt=""> <input type="radio" name="woo_chatbot_icon"
                                                                            value="icon-10.png" <?php echo(get_option('woo_chatbot_icon') == 'icon-10.png' ? 'checked' : ''); ?>>
                                                        <span class="qc-opt-dcs-font">Icon - 10</span>
                                                    </li>
                                                    <li><img src="<?php echo QCLD_WOOCHATBOT_IMG_URL; ?>/icon-11.png"
                                                             alt=""> <input type="radio" name="woo_chatbot_icon"
                                                                            value="icon-11.png" <?php echo(get_option('woo_chatbot_icon') == 'icon-11.png' ? 'checked' : ''); ?>>
                                                        <span class="qc-opt-dcs-font">Icon - 11</span>
                                                    </li>
                                                    <li><img src="<?php echo QCLD_WOOCHATBOT_IMG_URL; ?>/icon-12.png"
                                                             alt=""> <input type="radio" name="woo_chatbot_icon"
                                                                            value="icon-12.png" <?php echo(get_option('woo_chatbot_icon') == 'icon-12.png' ? 'checked' : ''); ?>>
                                                        <span class="qc-opt-dcs-font">Icon - 12</span>
                                                    </li>


                                                    <li>
                                                        <img src="<?php echo QCLD_WOOCHATBOT_IMG_URL; ?>/custom.png?<?php echo time(); ?>"
                                                             alt=""> <input type="radio" name="woo_chatbot_icon"
                                                                            value="custom.png" <?php echo(get_option('woo_chatbot_icon') == 'custom.png' ? 'checked' : ''); ?>>

                                                        <span class="qc-opt-dcs-font">Custom Icon</span>
                                                    </li>


                                                </ul>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xs-12">
                                                <p class="qc-opt-title-font">
                                                    Upload custom Icon
                                                </p>
                                                <div class="cxsc-settings-blocks">
                                                    <p class="qc-opt-dcs-font"><?php echo __('Select file to upload') ?><input type="file"
                                                                                                       name="custom_icon"
                                                                                                       id="custom_icon"
                                                                                                       size="35"
                                                                                                       class=""/>
                                                        <label class="qc-opt-dcs-font" for="pepperoni">Upload Custom Icon</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <section id="section-flip-7">
                                    <div class="top-section">
                                        <div class="row">
                                            <div class="col-xs-12" id="woo-chatbot-language-section">
                                                <p class="qc-opt-title-font"> Message setting for <strong>Identity </strong ></p>

                                                <div class="form-group">
                                                    <p class="qc-opt-title-font">Your Company or Website Name</p>
                                                    <input type="text" class="form-control qc-opt-dcs-font" name="qlcd_woo_chatbot_host" value="<?php echo(get_option('qlcd_woo_chatbot_host')!=''? get_option('qlcd_woo_chatbot_host') :'Our Store');?>">
                                                </div>
                                                <div class="form-group">
                                                    <p class="qc-opt-title-font">Agent name</p>
                                                    <input type="text" class="form-control qc-opt-dcs-font" name="qlcd_woo_chatbot_agent" value="<?php echo(get_option('qlcd_woo_chatbot_agent')!=''? get_option('qlcd_woo_chatbot_agent') :'Carrie');?>">
                                                </div>
                                                <div class="form-group">
                                                    <p class="qc-opt-title-font">has joined the conversation</p>
                                                    <input type="text" class="form-control qc-opt-dcs-font" name="qlcd_woo_chatbot_agent_join" value="<?php echo(get_option('qlcd_woo_chatbot_agent_join')!=''? get_option('qlcd_woo_chatbot_agent_join') :'has joined the conversation');?>">
                                                </div>
                                            </div>
                                            <div class="col-xs-12" id="woo-chatbot-language-section">
                                                <p class="qc-opt-title-font"> Message setting for <strong>Greetings: </strong ></p>
                                                <div class="form-group">
                                                    <p class="qc-opt-title-font">Welcome to </p>
                                                    <input type="text" class="form-control qc-opt-dcs-font" name="qlcd_woo_chatbot_welcome" value="<?php echo(get_option('qlcd_woo_chatbot_welcome')!=''? get_option('qlcd_woo_chatbot_welcome') :'Welcome to ');?>">
                                                </div>

                                                <div class="form-group">
                                                    <p class="qc-opt-title-font">Hi There! May I know your name?</p>
                                                    <input type="text" class="form-control qc-opt-dcs-font" name="qlcd_woo_chatbot_asking_name" value="<?php echo(get_option('qlcd_woo_chatbot_asking_name')!=''? get_option('qlcd_woo_chatbot_asking_name') :'Hi There! May I know your name?');?>">
                                                </div>
                                                <div class="form-group">
                                                    <p class="qc-opt-title-font">I am  </p>
                                                    <input type="text" class="form-control qc-opt-dcs-font" name="qlcd_woo_chatbot_i_am" value="<?php echo(get_option('qlcd_woo_chatbot_i_am')!=''? get_option('qlcd_woo_chatbot_i_am') :'I am ');?>">
                                                </div>

                                                <div class="form-group">
                                                    <p class="qc-opt-title-font">Nice to meet you</p>
                                                    <input type="text" class="form-control qc-opt-dcs-font" name="qlcd_woo_chatbot_name_greeting" value="<?php echo(get_option('qlcd_woo_chatbot_name_greeting')!=''? get_option('qlcd_woo_chatbot_name_greeting') :'Nice to meet you');?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="top-section">
                                        <div class="row">
                                            <div class="col-xs-12" id="woo-chatbot-language-section">
                                                <p class="qc-opt-title-font">Message settings for <strong> Products Search:</strong ></p>
                                            <div class="form-group">
                                                <p class="qc-opt-dcs-font">I am here to find you the product you need. What are you shopping for </p>
                                                <input type="text" class="form-control qc-opt-dcs-font" name="qlcd_woo_chatbot_product_asking" value="<?php echo(get_option('qlcd_woo_chatbot_product_asking')!=''? get_option('qlcd_woo_chatbot_product_asking') :'I am here to find you the product you need. What are you shopping for');?>">
                                            </div>
                                            <div class="form-group">
                                                <p class="qc-opt-dcs-font">if products found: </p>
                                                <input type="text" class="form-control qc-opt-dcs-font" name="qlcd_woo_chatbot_product_success" value="<?php echo(get_option('qlcd_woo_chatbot_product_success')!=''? get_option('qlcd_woo_chatbot_product_success') :'Great! We have these products.');?>">
                                            </div>

                                            <div class="form-group">
                                                <p class="qc-opt-dcs-font">If no matching products is found: </p>
                                                <input type="text" class="form-control qc-opt-dcs-font" name="qlcd_woo_chatbot_product_fail" value="<?php echo(get_option('qlcd_woo_chatbot_product_fail')!=''? get_option('qlcd_woo_chatbot_product_fail') :'Oops! Nothing matches your criteria ');?>">
                                            </div>
                                             <div class="form-group">
                                                <p class="qc-opt-dcs-font">You can browse our extensive catalog. Just pick a category from below:</p>
                                                <input type="text" class="form-control qc-opt-dcs-font" name="qlcd_woo_chatbot_product_suggest" value="<?php echo(get_option('qlcd_woo_chatbot_product_suggest')!=''? get_option('qlcd_woo_chatbot_product_suggest') :'You can browse our extensive catalog. Just pick a category from below:');?>">
                                            </div>
                                             <div class="form-group">
                                                <p class="qc-opt-dcs-font">Too many choices? Let's try another search term</p>
                                                <input type="text" class="form-control qc-opt-dcs-font" name="qlcd_woo_chatbot_product_infinite" value="<?php echo(get_option('qlcd_woo_chatbot_product_infinite')!=''? get_option('qlcd_woo_chatbot_product_infinite') :'Too many choices? Let\'s try another search term');?>">
                                            </div>

                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <section id="section-flip-8">
                                    <div class="top-section">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <p class="qc-opt-dcs-font">You can paste or write your custom css here.</p>
                                                <textarea name="woo_chatbot_custom_css"
                                                          class="form-control woo-chatbot-custom-css"
                                                          cols="10"
                                                          rows="8"><?php echo get_option('woo_chatbot_custom_css'); ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div><!-- /content -->
                        </div><!-- /woo-chatbot-tabs -->
                        <hr>
                        <div class="text-right">
                            <input type="submit" class="btn btn-primary submit-button" name="submit"
                                   id="submit" value="<?php _e('Save Settings', 'woo_chatbot'); ?>"/>
                        </div>
                    </section>
                </div>


                <?php wp_nonce_field('woo_chatbot'); ?>
            </form>


        </div>


        <?php

    }

    function qcld_woo_chatbot_save_options()
    {


        global $woocommerce;
        if (isset($_POST['_wpnonce']) && $_POST['_wpnonce']) {


            wp_verify_nonce($_POST['_wpnonce'], 'woo_chatbot');


            // Check if the form is submitted or not

            if (isset($_POST['submit'])) {

                //Woowboticon position settings.
                if (isset($_POST["woo_chatbot_position_x"])) {
                    $woo_chatbot_position_x = stripslashes(($_POST["woo_chatbot_position_x"]));
                    update_option('woo_chatbot_position_x', $woo_chatbot_position_x);
                }
                if (isset($_POST["woo_chatbot_position_y"])) {
                    $woo_chatbot_position_y = stripslashes(($_POST["woo_chatbot_position_y"]));
                    update_option('woo_chatbot_position_y', $woo_chatbot_position_y);
                }
                //Enable /disable Woowbot
                    $disable_woo_chatbot = $_POST["disable_woo_chatbot"];
                    update_option('disable_woo_chatbot', stripslashes($disable_woo_chatbot));
                //Product per page settings.
                if (isset($_POST["qlcd_woo_chatbot_ppp"])) {
                    $qlcd_woo_chatbot_ppp = $_POST["qlcd_woo_chatbot_ppp"];
                    update_option('qlcd_woo_chatbot_ppp', intval($qlcd_woo_chatbot_ppp));
                }
                //Woowbot icon settings.
                    $woo_chatbot_icon = $_POST['woo_chatbot_icon'] ? $_POST['woo_chatbot_icon'] : 'icon-1.png';
                    update_option('woo_chatbot_icon', sanitize_text_field($woo_chatbot_icon));
                // upload custom Woowbot icon

                if ($_FILES['custom_icon']['tmp_name'] != "") {

                    $pic = 'custom.png';
                    $img_path = QCLD_WOOCHATBOT_IMG_ABSOLUTE_PATH . '/' . $pic;

                    $pic_loc = $_FILES['custom_icon']['tmp_name'];


                    if (move_uploaded_file($pic_loc, $img_path)) {
                        update_option('woo_chatbot_icon', $pic);
                        ?>
                        <script> alert('successfully uploaded');</script><?php
                    } else {
                        ?>
                        <script> alert('error while uploading file');</script><?php
                    }


                }
                //To override style use custom css.
                $woo_chatbot_custom_css = $_POST["woo_chatbot_custom_css"];
                update_option('woo_chatbot_custom_css', stripslashes($woo_chatbot_custom_css));

                /****Language center settings.   ****/
                //identity
                $qlcd_woo_chatbot_host = $_POST["qlcd_woo_chatbot_host"];
                update_option('qlcd_woo_chatbot_host', sanitize_text_field($qlcd_woo_chatbot_host));

                $qlcd_woo_chatbot_agent = $_POST["qlcd_woo_chatbot_agent"];
                update_option('qlcd_woo_chatbot_agent', sanitize_text_field($qlcd_woo_chatbot_agent));

                $qlcd_woo_chatbot_agent_join = $_POST["qlcd_woo_chatbot_agent_join"];
                update_option('qlcd_woo_chatbot_agent_join', sanitize_text_field($qlcd_woo_chatbot_agent_join));

              //Greeting.
                $qlcd_woo_chatbot_welcome = $_POST["qlcd_woo_chatbot_welcome"];
                update_option('qlcd_woo_chatbot_welcome', sanitize_text_field($qlcd_woo_chatbot_welcome));

                $qlcd_woo_chatbot_asking_name = $_POST["qlcd_woo_chatbot_asking_name"];
                update_option('qlcd_woo_chatbot_asking_name', sanitize_text_field($qlcd_woo_chatbot_asking_name));

                $qlcd_woo_chatbot_name_greeting = $_POST["qlcd_woo_chatbot_name_greeting"];
                update_option('qlcd_woo_chatbot_name_greeting', sanitize_text_field($qlcd_woo_chatbot_name_greeting));

                $qlcd_woo_chatbot_i_am = $_POST["qlcd_woo_chatbot_i_am"];
                update_option('qlcd_woo_chatbot_i_am', sanitize_text_field($qlcd_woo_chatbot_i_am));

                //Products search .
                if (isset($_POST["qlcd_woo_chatbot_product_success"])) {
                    $qlcd_woo_chatbot_product_success = $_POST["qlcd_woo_chatbot_product_success"];
                    update_option('qlcd_woo_chatbot_product_success', sanitize_text_field($qlcd_woo_chatbot_product_success));
                }
                if (isset($_POST["qlcd_woo_chatbot_product_fail"])) {
                    $qlcd_woo_chatbot_product_fail = $_POST["qlcd_woo_chatbot_product_fail"];
                    update_option('qlcd_woo_chatbot_product_fail', sanitize_text_field($qlcd_woo_chatbot_product_fail));
                }
                $qlcd_woo_chatbot_product_asking = $_POST["qlcd_woo_chatbot_product_asking"];
                update_option('qlcd_woo_chatbot_product_asking', sanitize_text_field($qlcd_woo_chatbot_product_asking));

                $qlcd_woo_chatbot_product_suggest = $_POST["qlcd_woo_chatbot_product_suggest"];
                update_option('qlcd_woo_chatbot_product_suggest', sanitize_text_field($qlcd_woo_chatbot_product_suggest));

                $qlcd_woo_chatbot_product_infinite = $_POST["qlcd_woo_chatbot_product_infinite"];
                update_option('qlcd_woo_chatbot_product_infinite', sanitize_text_field($qlcd_woo_chatbot_product_infinite));

            }
        }
    }
    /**
     * Display Notifications on specific criteria.
     *
     * @since    2.14
     */
    public static function woocommerce_inactive_notice_for_woo_chatbot()
    {
        if (current_user_can('activate_plugins')) :
            if (!class_exists('WooCommerce')) :
                deactivate_plugins(plugin_basename(__FILE__));
                ?>
                <div id="message" class="error">
                    <p>
                        <?php
                        printf(
                            __('%s WoowBot for WooCommerce REQUIRES WooCommerce%s %sWooCommerce%s must be active for WoowBot to work. Please install & activate WooCommerce.', 'woo_chatbot'),
                            '<strong>',
                            '</strong><br>',
                            '<a href="http://wordpress.org/extend/plugins/woocommerce/" target="_blank" >',
                            '</a>'
                        );
                        ?>
                    </p>
                </div>
                <?php
            elseif (version_compare(get_option('woocommerce_db_version'), QCLD_WOOCHATBOT_REQUIRED_WOOCOMMERCE_VERSION, '<')) :
                ?>
                <div id="message" class="error">
                    <!--<p style="float: right; color: #9A9A9A; font-size: 13px; font-style: italic;">For more information <a href="http://cxthemes.com/plugins/update-notice.html" target="_blank" style="color: inheret;">click here</a></p>-->
                    <p>
                        <?php
                        printf(
                            __('%WoowBot for WooCommerce is inactive%s This version of WoowBot requires WooCommerce %s or newer. For more information about our WooCommerce version support %sclick here%s.', 'woo_chatbot'),
                            '<strong>',
                            '</strong><br>',
                            QCLD_WOOCHATBOT_REQUIRED_WOOCOMMERCE_VERSION
                        );
                        ?>
                    </p>
                    <div style="clear:both;"></div>
                </div>
                <?php
            endif;
        endif;
    }



}

/**
 * Instantiate plugin.
 *
 */

if (!function_exists('qcld_woo_chatboot_plugin_init')) {
    function qcld_woo_chatboot_plugin_init()
    {

        global $qcld_woo_chatbot;

        $qcld_woo_chatbot = QCLD_Woo_Chatbot::qcld_woo_chatbot_get_instance();
    }
}
add_action('plugins_loaded', 'qcld_woo_chatboot_plugin_init');

/*
* Initial Options will be insert as defualt data
*/
register_activation_hook(__FILE__, 'qcld_woo_chatboot_defualt_options');
function qcld_woo_chatboot_defualt_options(){
    update_option('woo_chatbot_position_x', intval(120));
    update_option('woo_chatbot_position_y', intval(120));
    update_option('qlcd_woo_chatbot_ppp', intval(10));
	update_option('disable_woo_chatbot', '');
	update_option('woo_chatbot_icon', sanitize_text_field('icon-0.png'));
    update_option('qlcd_woo_chatbot_host',sanitize_text_field('Our Store'));
    update_option('qlcd_woo_chatbot_agent',sanitize_text_field('Carrie'));
    update_option('qlcd_woo_chatbot_agent_join', sanitize_text_field('has joined the conversation'));
    update_option('qlcd_woo_chatbot_welcome', sanitize_text_field('Welcome to'));
    update_option('qlcd_woo_chatbot_asking_name', sanitize_text_field('May I know your name?!'));
    update_option('qlcd_woo_chatbot_name_greeting', sanitize_text_field('Nice to meet you'));
    update_option('qlcd_woo_chatbot_i_am', sanitize_text_field('I am!'));
    update_option('qlcd_woo_chatbot_product_success', sanitize_text_field('Great! We have these products.'));
    update_option('qlcd_woo_chatbot_product_fail', sanitize_text_field('Oops! Nothing matches your criteria'));
    update_option('qlcd_woo_chatbot_product_asking', sanitize_text_field('I am here to find you the product you need. What are you shopping for?'));
    update_option('qlcd_woo_chatbot_product_suggest', sanitize_text_field('You can browse our extensive catalog. Just pick a category from below:'));
    update_option('qlcd_woo_chatbot_product_infinite', sanitize_text_field('Too many choices? Lets try another search term'));
}