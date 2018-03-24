<?php

/**
 * @param $type
 * Display recently viewed products
 */
if (!defined('ABSPATH')) exit; // Exit if accessed directly


add_action('wp_footer', 'woo_chatbot_load_footer_html');
function woo_chatbot_load_footer_html()
{ ?>
    <?php if (get_option('disable_woo_chatbot') != 1): ?>
    <style>
        <?php if(get_option('woo_chatbot_custom_css')!=""){echo get_option('woo_chatbot_custom_css'); } ?>
    </style>
    <div id="woo-chatbot-icon-container">
        <div id="woo-chatbot-ball-wrapper" style="display:none">

            <div id="woo-chatbot-ball-container" style="display:none" class="woo-chatbot-ball-container">
                <div class="woo-chatbot-admin">
                    <h3>Conversations</h3>
                    <h4>with <?php if(get_option('qlcd_woo_chatbot_agent')!=''){echo get_option('qlcd_woo_chatbot_agent');} ?></h4>
                </div>
                <div class="woo-chatbot-ball-inner">
                    <div class="woo-chatbot-messages-wrapper">
                        <ul id="woo-chatbot-messages-container" class="woo-chatbot-messages-container">
                        </ul>
                    </div>
                </div>
                <div id="woo-chatbot-editor-container" class="woo-chatbot-editor-container">
                    <input id="woo-chatbot-editor" class="woo-chatbot-editor" required placeholder="Send a message."
                           maxlength="100">
                    <button type="button" id="woo-chatbot-send-message" class="woo-chatbot-button">send</button>
                </div>
            </div>
            <!--woo-chatbot-ball-container-->
            <div id="woo-chatbot-ball" class="woo-chatbot-ball">
                <img src="<?php echo QCLD_WOOCHATBOT_IMG_URL . '/' . get_option('woo_chatbot_icon'); ?>"
                     alt="WooChatIcon">
            </div>
            <!--container-->
        </div>
        <!--woo-chatbot-ball-wrapper-->
    </div>
<?php endif;

}

add_action('wp_ajax_qcld_woo_chatbot_keyword', 'qcld_woo_chatbot_keyword');
add_action('wp_ajax_nopriv_qcld_woo_chatbot_keyword', 'qcld_woo_chatbot_keyword');


function qcld_woo_chatbot_keyword()
{
    $keyword = sanitize_text_field($_POST['keyword']);
    $product_per_page=get_option('qlcd_woo_chatbot_ppp')!=''? get_option('qlcd_woo_chatbot_ppp') :10;
    //Merging all query together.
    $argu_params = array(
        'post_type' => 'product',
        'posts_per_page' => $product_per_page,
        'order' => 'ASC',
        's' => $keyword,
    );
    /******
     *WP Query Operation to get products.*
     *******/
    $product_query = new WP_Query($argu_params);
    $product_num = $product_query->post_count;
    $html = '<div class="woo-chatbot-featured-products">';

    $_pf = new WC_Product_Factory();
    //repeating the products
    if ($product_num > 0) {
        //$html .= '<p>sdf sdfdsf : '.$asdfdf.'</p>';
        $html .= '<ul class="woo-chatbot-products">';
        while ($product_query->have_posts()) : $product_query->the_post();
            $product = $_pf->get_product(get_the_ID());
            //$qcld_thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'shop_thumbnail' );
            $html .= '<li class="woo-chatbot-product">';
            $html .= '<a target="_blank" href="' . get_permalink(get_the_ID()) . '" title="' . esc_attr($product->post->post_title ? $product->post->post_title : get_the_ID()) . '">';
            $html .= get_the_post_thumbnail(get_the_ID(), 'shop_catalog') . '</a>
       <div class="woo-chatbot-product-summary">
       <div class="woo-chatbot-product-table">
       <div class="woo-chatbot-product-table-cell">
       <h3 class="woo-chatbot-product-title"><a target="_blank" href="' . get_permalink(get_the_ID()) . '" title="' . esc_attr($product->post->post_title ? $product->post->post_title : get_the_ID()) . '">' . $product->post->post_title . '</a></h3>
       <div class="price">' . $product->get_price_html() . '</div>';

//            if ($product->is_type('simple')) {
//                $html .= '<a target="_blank" href="' . get_site_url() . '?add-to-cart=' . get_the_ID() . '"  title="' . esc_attr($product->post->post_title ? $product->post->post_title : get_the_ID()) . '"  class="woo-chatbot-button woo-chatbot-button-cart add_to_cart_button ajax_add_to_cart"  data-quantity="1" data-product_id="' . get_the_ID() . '" >Add to Cart</a>';
//            } else {
//                $html .= '<a target="_blank" href="' . get_permalink(get_the_ID()) . '"  title="' . esc_attr($product->post->post_title ? $product->post->post_title : get_the_ID()) . '"  class="woo-chatbot-button woo-chatbot-button-cart"  >View Detail</a>';
//            }
            $html .= ' </div>
       </div>
       </div>
       </li>';
        endwhile;
        wp_reset_postdata();
        $html .= '</ul>';
    }
    $html .= '</div>';
    $response = array('html' => $html, 'product_num' => $product_num);
    echo wp_send_json($response);
    wp_die();
}

add_action('wp_ajax_qcld_woo_chatbot_category', 'qcld_woo_chatbot_category');
add_action('wp_ajax_nopriv_qcld_woo_chatbot_category', 'qcld_woo_chatbot_category');

function qcld_woo_chatbot_category()
{
    $terms = get_terms(array(
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
    ));
    $html = "";
    foreach ($terms as $term) {

        $html .= '<span class="qcld-chatbot-product-category" type="button" data-category-slug="' . $term->slug . '" data-category-id="' . $term->term_id . '">' . $term->name . '</span>';
    }
    echo wp_send_json($html);
    wp_die();
}

add_action('wp_ajax_qcld_woo_chatbot_category_products', 'qcld_woo_chatbot_category_products');
add_action('wp_ajax_nopriv_qcld_woo_chatbot_category_products', 'qcld_woo_chatbot_category_products');
function qcld_woo_chatbot_category_products()
{
    $category_id = stripslashes($_POST['category']);
    $product_per_page=get_option('qlcd_woo_chatbot_ppp')!=''? get_option('qlcd_woo_chatbot_ppp') :10;
    //Merging all query together.
    $argu_params = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'ignore_sticky_posts' => 1,
        'posts_per_page' => $product_per_page,
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => $category_id,
                'operator' => 'IN'
            )
        )
    );
    /******
     *WP Query Operation to get products.*
     *******/
    $product_query = new WP_Query($argu_params);
    $product_num = $product_query->post_count;

    $_pf = new WC_Product_Factory();
    //repeating the products
    $html="";
    if ($product_num > 0) {

        $html .= '<div class="woo-chatbot-featured-products">';
        //$html .= '<p>sdf sdfdsf : '.$asdfdf.'</p>';
        $html .= '<ul class="woo-chatbot-products">';
        while ($product_query->have_posts()) : $product_query->the_post();
            $product = $_pf->get_product(get_the_ID());
            //$qcld_thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'shop_thumbnail' );
            $html .= '<li class="woo-chatbot-product">';
            $html .= '<a target="_blank" href="' . get_permalink(get_the_ID()) . '" title="' . esc_attr($product->post->post_title ? $product->post->post_title : get_the_ID()) . '">';
            $html .= get_the_post_thumbnail(get_the_ID(), 'shop_catalog') . '</a>
       <div class="woo-chatbot-product-summary">
       <div class="woo-chatbot-product-table">
       <div class="woo-chatbot-product-table-cell">
       <h3 class="woo-chatbot-product-title"><a target="_blank" href="' . get_permalink(get_the_ID()) . '" title="' . esc_attr($product->post->post_title ? $product->post->post_title : get_the_ID()) . '">' . $product->post->post_title . '</a></h3>
       <div class="price">' . $product->get_price_html() . '</div>';

//            if ($product->is_type('simple')) {
//                $html .= '<a target="_blank" href="' . get_site_url() . '?add-to-cart=' . get_the_ID() . '"  title="' . esc_attr($product->post->post_title ? $product->post->post_title : get_the_ID()) . '"  class="woo-chatbot-button woo-chatbot-button-cart add_to_cart_button ajax_add_to_cart"  data-quantity="1" data-product_id="' . get_the_ID() . '" >Add to Cart</a>';
//            } else {
//                $html .= '<a target="_blank" href="' . get_permalink(get_the_ID()) . '"  title="' . esc_attr($product->post->post_title ? $product->post->post_title : get_the_ID()) . '"  class="woo-chatbot-button woo-chatbot-button-cart"  >View Detail</a>';
//            }
            $html .= ' </div>
       </div>
       </div>
       </li>';
        endwhile;
        wp_reset_postdata();
        $html .= '</ul>';

        $html .= '</div>';
    }else{
        $html.="";
    }
    $response = array('html' => $html, 'product_num' => $product_num);
    echo wp_send_json($response);
    wp_die();

}




