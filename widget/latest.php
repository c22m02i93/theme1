<?php 
/**
 * Custom columns of category with various options
 *
 */
if ( ! class_exists( 'newszine_latest_post_col' ) ) {
    /**
     * Class for adding widget
     *
     */
    class newszine_latest_post_col extends WP_Widget {

        function __construct() {
            parent::__construct(
            /*Base ID of your widget*/
                'newszine_latest_post_col',
                /*Widget name will appear in UI*/
                __('Home Page Latest Posts Column', 'newszine'),
                /*Widget description*/
                array( 'description' => __( 'Show posts selected category', 'newszine' ), )
            );
        }
        /*Widget Backend*/
        public function form( $instance ) {

            if ( isset( $instance[ 'newszine_cat' ] ) ) {
                $newszine_selected_cat = $instance[ 'newszine_cat' ];
            }
            else {
                $newszine_selected_cat = 0;
            }
           
            /*Layout options*/
            $newszine_layout_arrays = array( '1'=>__('1','newszine'), '2'=>__('2','newszine'), '3'=>__('3','newszine'), '4'=>__('4','newszine'), '5'=>__('5','newszine'), '6'=>__('6','newszine'), '7'=>__('7','newszine')  );           
            if( isset( $instance['newszine_latest_post_col_layout'] )){
                $newszine_latest_post_col_layout = $instance['newszine_latest_post_col_layout'];
                
               
            }
            else{
                $newszine_latest_post_col_layout = 0;
            }

           ?>
            <p>
                <label for="<?php echo $this->get_field_id('newszine_cat'); ?>"><?php _e('Select Category', 'newszine'); ?></label>
                <?php
                $newszine_dropown_cat = array(
                    'show_option_none'   => __('From Recent Posts','newszine'),
                    'orderby'            => 'name',
                    'order'              => 'asc',
                    'show_count'         => 1,
                    'hide_empty'         => 1,
                    'echo'               => 1,
                    'selected'           => $newszine_selected_cat,
                    'hierarchical'       => 1,
                    'name'               => $this->get_field_name('newszine_cat'),
                    'id'                 => $this->get_field_name('newszine_cat'),
                    'class'              => 'widefat',
                    'taxonomy'           => 'category',
                    'hide_if_empty'      => false,
                );
                wp_dropdown_categories($newszine_dropown_cat);
                ?>
            </p>            
            <p>
                <label for="<?php echo $this->get_field_id('newszine_cat'); ?>"><?php _e('Select Number of Post', 'newszine'); ?></label>
                <select class="widefat" id="<?php echo $this->get_field_id( 'newszine_latest_post_col_layout' ); ?>" name="<?php echo $this->get_field_name( 'newszine_latest_post_col_layout' ); ?>">
                    <?php
                    foreach( $newszine_layout_arrays as $key => $newszine_column_array ){
                        echo ' <option value="'.$key.'"'.selected( $newszine_latest_post_col_layout, $key, 0).'>'.$newszine_column_array.'</option>';
                    }
                    ?>
                </select>
            </p>
            <p>
                <small><?php _e( 'Note: Some of the features only work in "Home main content area" due to minimum width in other areas.' ,'newszine'); ?></small>
            </p>
            <?php
        }
        /**
         * Function to Updating widget replacing old instances with new
         *
         * 
         * @since 1.0.3
         *
         * @param array $new_instance new arrays value
         * @param array $old_instance old arrays value
         * @return array
         *
         */
        public function update( $new_instance, $old_instance ) {
            $instance = array();
            $instance['newszine_cat'] = ( isset( $new_instance['newszine_cat'] ) ) ? esc_attr( $new_instance['newszine_cat'] ) : '';
            $instance['newszine_latest_post_col_layout'] = isset($new_instance['newszine_latest_post_col_layout'])? absint( $new_instance['newszine_latest_post_col_layout'] ) : 1;

            return $instance;
        }
        /**
         * Function to Creating widget front-end. This is where the action happens
         *
         *
         * @since 1.0.3
         *
         * @param array $args widget setting
         * @param array $instance saved values
         * @return array
         *
         */
        public function widget($args, $instance) {
            
            if( isset($instance['newszine_cat'] )){
                $newszine_cat = $instance['newszine_cat'];
            }
            else{
                $newszine_cat = -1;
            }
            
            /*column layout*/
            if( isset( $instance['newszine_latest_post_col_layout'] )){
                $newszine_latest_post_col_layout = absint( $instance['newszine_latest_post_col_layout'] );               
            }
            else{
                $newszine_latest_post_col_layout = 1;
            } 
            
            /**
             * Filter the arguments for the Recent Posts widget.
             *
             * @since 1.0.3
             *
             * @see WP_Query
             *
             */ ?>
             <div class="clearfix"></div>
             <div class="block">
                 <div class="news-tab">
                    <?php   $newszine_cat_post_args = array(
                        'posts_per_page'      => $newszine_latest_post_col_layout,
                        'no_found_rows'       => true,
                        'post_status'         => 'publish',
                        'ignore_sticky_posts' => true
                    );
                  
                    if( -1 != $newszine_cat ){
                                    $newszine_cat_post_args['cat'] = $newszine_cat;
                                    $newszine_title = get_cat_name($newszine_cat);
                                }
                                else{
                                    $newszine_title = __('Recent Posts','newszine');
                                }   ?>
                        <!-- Nav tabs -->
            			<ul class="nav nav-tabs" role="tablist">
            				<li role="presentation" class="active"><a href="#latest" aria-controls="latest" role="tab" data-toggle="tab"><?php _e('Latest','newszine');?></a></li>
            			</ul>
                   
                    <?php $newszine_featured_query = new WP_Query($newszine_cat_post_args); ?>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="latest">
                            <?php if ($newszine_featured_query->have_posts()) :
                                $newszine_latest_post_col_layout_class ='news-single large';
                                $thumb = 'newszine-post-thumb';
                                $newszine_list_classes = 'col-sm-6';
                                $newszine_sidebar_no_thumbnail = get_theme_mod( 'default_images');
                                $newszine_words = 48;
                                $cn = 0;                
                            ?>
                            <?php  while ( $newszine_featured_query->have_posts() ) :$newszine_featured_query->the_post(); $cn++;  ?> 
                                <?php if ( $cn == 1 ) : ?>
                                <div class="<?php echo esc_attr( $newszine_list_classes ); ?>" >
                                    <div class="<?php echo esc_attr( $newszine_latest_post_col_layout_class ); ?>">
                                        <div class="image">
                                            <?php
                                            if ( has_post_thumbnail() ):
                                                $post_thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), $thumb );
                                            elseif($newszine_sidebar_no_thumbnail):
                                                $post_thumb[0] = $newszine_sidebar_no_thumbnail;
                                            else:
                                                $post_thumb[0] = get_template_directory_uri() . '/images/n1.jpg';
                                            endif;
                                            ?>
                                            <a href="<?php the_permalink(); ?>" rel="bookmark">
                                                <img src="<?php echo esc_url( $post_thumb[0] ); ?>" class="img-responsive center-block wp-post-image" alt="<?php the_title_attribute(); ?>" title="<?php the_title_attribute(); ?>"  />
                                            </a>
                                        </div>
                                        <div class="content">
                                            <?php
                                                $archive_year  = get_the_date('Y');
                                                $archive_month = get_the_date('m');
                                                $archive_day   = get_the_date('d');
                                            ?>
                                            <h2><a href="<?php the_permalink(); ?>" title="" rel="bookmark"><?php the_title(); ?></a></h2>
                                            <h6 class="info">
                                                <i class="fa fa-user"></i> <?php echo esc_html( get_the_author() ); ?> 
                                                <i class="fa fa-clock-o"></i> <?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago'; ?>                                     
                                                
                                            </h6>
                                          <?php echo  newszine_exrcept(40); ?>
                                            <span class="read-more pull-right"><a href="<?php the_permalink();?>" class="btn btn-theme" ><?php _e('Read More','newszine')?><i class="fa fa-angle-double-right"></i></a></span>
                                        </div>                       
                                    </div> 
                                </div> 
                            <?php else : ?>
                            <?php                
                                $newszine_latest_post_col_layout_class ='news-single small';
                                $thumb = 'newszine-thumbnail';
                                $newszine_list_classes = 'col-sm-6';
                                $newszine_sidebar_no_thumbnail = get_theme_mod( 'default_images');
                                $newszine_words = 48;
                            ?>
                            <div class="<?php echo esc_attr( $newszine_list_classes ); ?>" >
                                <div class="<?php echo esc_attr( $newszine_latest_post_col_layout_class ); ?>">
                                    <div class="image">
                                        <?php
                                            if ( has_post_thumbnail() ):
                                            $post_thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), $thumb );
                                           elseif($newszine_sidebar_no_thumbnail):
                                                $post_thumb[0] = $newszine_sidebar_no_thumbnail;
                                            else:
                                                $post_thumb[0] = get_template_directory_uri() . '/images/n1.jpg';
                                            endif;
                                        ?>
                                        <a href="<?php the_permalink(); ?>" rel="bookmark">
                                        <img src="<?php echo esc_url( $post_thumb[0] ); ?>" class="img-responsive center-block wp-post-image" alt="<?php the_title_attribute(); ?>" title="<?php the_title_attribute(); ?>"  />
                                        </a>
                                    </div>
                                    
                                    <div class="content">
                                        <?php
                                            $archive_year  = get_the_date('Y');
                                            $archive_month = get_the_date('m');
                                            $archive_day   = get_the_date('d');
                                        ?>
                                        <h2><a href="<?php the_permalink(); ?>" title="" rel="bookmark"><?php the_title(); ?></a></h2>
                                        <h6 class="info">
                                            <i class="fa fa-user"></i> <?php echo esc_html( get_the_author() ); ?>  
                                            <i class="fa fa-clock-o"></i> <?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago'; ?>                                     
                                        </h6>
                                        <?php echo  newszine_exrcept(20); ?>
                                        
                                    </div>                       
                                </div> 
                            </div> 
                            <?php endif; ?>
                            <?php   endwhile; 
                            wp_reset_postdata();
                            endif; ?>
                        </div>
                    </div>
                </div>
            </div>
       <?php  }
    } // Class newszine_latest_post_col ends here
}
if ( ! function_exists( 'newszine_latest_post_col' ) ) :
    /**
     * Function to Register and load the widget
     *
     * @since 1.0.3
     *
     * @param null
     * @return null
     *
     */
    function newszine_latest_post_col() {
        register_widget( 'newszine_latest_post_col' );
    }
endif;
add_action( 'widgets_init', 'newszine_latest_post_col' );

