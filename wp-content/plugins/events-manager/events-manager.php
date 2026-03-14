<?php
/**
 * Plugin Name: Events Manager
 * Description: Добавляет тип записей "event " с полями Название, Дата события, Место проведения
 * Version: 1.0
 * Author: Andrew Stepanov
 */

add_action( 'init', 'em_register_event_post_type' );

function em_register_event_post_type() {
    register_post_type(
        'event',
        array(
            'labels'      => array(
                'name'          => __( 'События' ),
                'singular_name' => __( 'Событие' ),
            ),
            'public'      => true,
            'has_archive' => true,
            'supports'    => array( 'title', 'editor' ),
            'menu_icon'   => 'dashicons-calendar',
            'show_in_rest' => true,
        )
    );
}

add_action( 'add_meta_boxes', 'em_add_meta_boxes' );
function em_add_meta_boxes() {
    error_log('Метабокс пытается добавиться');
    add_meta_box(
        'event_details',
        'Детали события',
        'em_render_meta_box',
        'event',
        'normal',
        'default',
    );
}

function em_render_meta_box( $post ) {
    wp_nonce_field( 'em_save_meta_box_data', 'em_meta_box_nonce' );

    $event_date  = get_post_meta( $post->ID, 'event_date', true );
    $event_place = get_post_meta( $post->ID, 'event_place', true );
    ?>
    <p>
        <label for="event_date">Дата события:</label>
        <input type="date" id="event_date" name="event_date" value="<?php echo esc_attr( $event_date ); ?>" />
    </p>
    <p>
        <label for="event_place">Место проведения:</label>
        <input type="text" id="event_place" name="event_place" value="<?php echo esc_attr( $event_place ); ?>" style="width:100%;" />
    </p>
    <?php
}

add_action( 'save_post', 'em_save_meta_box' );
function em_save_meta_box( $post_id ) {
    if ( ! isset( $_POST['em_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['em_meta_box_nonce'], 'em_save_meta_box_data' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    if ( get_post_type( $post_id ) !== 'event' ) {
        return;
    }

    if ( isset( $_POST['event_date'] ) ) {
        update_post_meta( $post_id, 'event_date', sanitize_text_field( $_POST['event_date'] ) );
    }
    if ( isset( $_POST['event_place'] ) ) {
        update_post_meta( $post_id, 'event_place', sanitize_text_field( $_POST['event_place'] ) );
    }
}

add_shortcode( 'events_list', 'em_events_list_shortcode' );
function em_events_list_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'posts_per_page' => 3,
    ), $atts );

    $today = current_time( 'Y-m-d' );

    $args = array(
        'post_type'      => 'event',
        'posts_per_page' => $atts['posts_per_page'],
        'meta_key'       => 'event_date',
        'orderby'        => 'meta_value',
        'order'          => 'ASC',
        'meta_query'     => array(
            array(
                'key'     => 'event_date',
                'value'   => $today,
                'compare' => '>=',
                'type'    => 'DATE'
            )
        ),
        'post_status'    => 'publish',
    );

    $query = new WP_Query( $args );

    ob_start();
    if ( $query->have_posts() ) {
        echo '<div class="em-events-list">';
        while ( $query->have_posts() ) {
            $query->the_post();
            $event_date = get_post_meta( get_the_ID(), 'event_date', true );
            $event_place = get_post_meta( get_the_ID(), 'event_place', true );
            $formatted_date = date_i18n( 'd.m.Y', strtotime( $event_date ) );
            ?>
            <div class="em-event-item">
                <h3><?php the_title(); ?></h3>
                <p><strong>Дата:</strong> <?php echo esc_html( $formatted_date ); ?></p>
                <p><strong>Место:</strong> <?php echo esc_html( $event_place ); ?></p>
                <?php if ( ! empty( $event_place ) ) : ?>
                    <div class="em-event-map">
                        <iframe
                            width="100%"
                            height="200"
                            frameborder="0"
                            style="border:0"
                            src="https://www.google.com/maps?q=<?php echo urlencode( $event_place ); ?>&output=embed"
                            allowfullscreen>
                        </iframe>
                    </div>
                <?php endif; ?>
            </div>
            <?php
        }
        echo '</div>';

        $total = $query->found_posts;
        if ( $total > $atts['posts_per_page'] ) {
            echo '<button id="em-load-more" data-offset="' . esc_attr( $atts['posts_per_page'] ) . '" data-nonce="' . wp_create_nonce( 'em_load_more_nonce' ) . '">Показать больше</button>';
        }
    } else {
        echo '<p>Нет ближайших событий.</p>';
    }
    wp_reset_postdata();

    return ob_get_clean();
}

add_action( 'wp_ajax_em_load_more', 'em_ajax_load_more' );
add_action( 'wp_ajax_nopriv_em_load_more', 'em_ajax_load_more' );

function em_ajax_load_more() {
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'em_load_more_nonce' ) ) {
        wp_die( 'Ошибка безопасности' );
    }

    $offset = isset( $_POST['offset'] ) ? intval( $_POST['offset'] ) : 0;
    $today  = current_time( 'Y-m-d' );

    $args = array(
        'post_type'      => 'event',
        'posts_per_page' => 3,
        'offset'         => $offset,
        'meta_key'       => 'event_date',
        'orderby'        => 'meta_value',
        'order'          => 'ASC',
        'meta_query'     => array(
            array(
                'key'     => 'event_date',
                'value'   => $today,
                'compare' => '>=',
                'type'    => 'DATE'
            )
        ),
        'post_status'    => 'publish',
    );

    $query = new WP_Query( $args );

    ob_start();
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $event_date = get_post_meta( get_the_ID(), 'event_date', true );
            $event_place = get_post_meta( get_the_ID(), 'event_place', true );
            $formatted_date = date_i18n( 'd.m.Y', strtotime( $event_date ) );
            ?>
            <div class="em-event-item">
                <h3><?php the_title(); ?></h3>
                <p><strong>Дата:</strong> <?php echo esc_html( $formatted_date ); ?></p>
                <p><strong>Место:</strong> <?php echo esc_html( $event_place ); ?></p>
                <?php if ( ! empty( $event_place ) ) : ?>
                    <div class="em-event-map">
                        <iframe
                            width="100%"
                            height="200"
                            frameborder="0"
                            style="border:0"
                            src="https://www.google.com/maps?q=<?php echo urlencode( $event_place ); ?>&output=embed"
                            allowfullscreen>
                        </iframe>
                    </div>
                <?php endif; ?>
            </div>
            <?php
        }
        wp_reset_postdata();
        $response = array(
            'success' => true,
            'data'    => ob_get_clean(),
            'total'   => $query->found_posts
        );
    } else {
        $response = array(
            'success' => false,
            'data'    => '',
        );
    }

    wp_send_json( $response );
}

add_action( 'wp_enqueue_scripts', 'em_enqueue_scripts' );
function em_enqueue_scripts() {
    wp_enqueue_style( 'em-styles', plugin_dir_url( __FILE__ ) . 'assets/em-styles.css' );

    wp_enqueue_script( 'em-script', plugin_dir_url( __FILE__ ) . 'assets/em-script.js', array(), '1.0', true );

    wp_localize_script( 'em-script', 'em_ajax', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
    ) );
}