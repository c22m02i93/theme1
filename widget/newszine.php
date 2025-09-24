<?php
/**
 * Custom columns of category with various options
 *
 */
if ( ! class_exists( 'newszine_half_col' ) ) {
    /**
     * Class for adding widget
     *
     */
    class newszine_half_col extends WP_Widget {

        function __construct() {
            parent::__construct(
            /*Base ID of your widget*/
                'newszine_half_col',
                /*Widget name will appear in UI*/
                __(' Home Page Half Posts Column', 'newszine'),
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
            
            /*Select Number of  Posts*/
            $newszine_number_arrays = array( '1'=>__('1','newszine'), '2'=>__('2','newszine'), '3'=>__('3','newszine'), '4'=>__('4','newszine'), '5'=>__('5','newszine'), '6'=>__('6','newszine'), '7'=>__('7','newszine')  );
            if( isset( $instance['newszine_post_col_number'] )){
                $newszine_post_col_number = $instance['newszine_post_col_number'];
            }
            else{
                $newszine_post_col_number = 0;
            }
            
            /*Layout options*/
            $newszine_layout_arrays = array( '1'=>__('Layout Left','newszine'), '2'=>__('Layout Right','newszine')  );
            if( isset( $instance['newszine_post_col_layout'] )){
                $newszine_post_col_layout = $instance['newszine_post_col_layout'];
            }
            else{
                $newszine_post_col_layout = 0;
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
               <label for="<?php echo $this->get_field_id('newszine_cat'); ?>"><?php _e('Select Numnber of Post', 'newszine'); ?></label>
                <select class="widefat" id="<?php echo $this->get_field_id( 'newszine_post_col_number' ); ?>" name="<?php echo $this->get_field_name( 'newszine_post_col_number' ); ?>">
                    <?php
                    foreach( $newszine_number_arrays as $key => $newszine_column_array ){
                        echo ' <option value="'.$key.'"'.selected( $newszine_post_col_number, $key, 0).'>'.$newszine_column_array.'</option>';
                    }
                    ?>
                </select>
            </p>
            <p>
               <label for="<?php echo $this->get_field_id('newszine_cat'); ?>"><?php _e('Select Layout', 'newszine'); ?></label>
                <select class="widefat" id="<?php echo $this->get_field_id( 'newszine_post_col_layout' ); ?>" name="<?php echo $this->get_field_name( 'newszine_post_col_layout' ); ?>">
                    <?php
                    foreach( $newszine_layout_arrays as $key => $newszine_rows_array ){
                        echo ' <option value="'.$key.'"'.selected( $newszine_post_col_layout, $key, 0).'>'.$newszine_rows_array.'</option>';
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
         * @access public
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
            $instance['newszine_post_col_number'] = isset($new_instance['newszine_post_col_number'])? absint( $new_instance['newszine_post_col_number'] ) : 1;
            $instance['newszine_post_col_layout'] = isset($new_instance['newszine_post_col_layout'])? absint( $new_instance['newszine_post_col_layout'] ) : 1;

            return $instance;
        }
        /**
         * Function to Creating widget front-end. This is where the action happens
         *
         * @access public
         * @since 1.0.3
         *
         * @param array $args widget setting
         * @param array $instance saved values
         * @return array
         *
         */
        public function widget($args, $instance) {
            $newszine_sidebar_id = $args['id'];
            if( isset($instance['newszine_cat'] )){
                $newszine_cat = $instance['newszine_cat'];
            }
            else{
                $newszine_cat = -1;
            }
            
             /*Number of post*/
            if( isset( $instance['newszine_post_col_number'] )){
                $newszine_post_col_number = absint( $instance['newszine_post_col_number'] );               
            }
            else{
                $newszine_post_col_number = 1;
            }
           
           
           if( !isset( $instance['newszine_post_col_layout'] )){
            $newszine_post_col_layout=1;
            }
             

            if( isset( $instance['newszine_post_col_layout'] )){
                $newszine_post_col_layout = absint( $instance['newszine_post_col_layout'] );               
            }
            else{
                $newszine_post_col_layout = 1;
            }
            /**
             * Filter the arguments for the Recent Posts widget.
             *
             * @since 1.0.3
             *
             * @see WP_Query
             *
             */ ?>
            <?php                
                    if($newszine_post_col_layout==1){
                        $newszine_post_col_class='col-sm-12 left-clear'; ?>
                        <div class="<?php echo esc_attr( $newszine_post_col_class ); ?>" >
                            <div class="block">
                                <?php   $newszine_cat_post_args = array(
                                    'posts_per_page'      => $newszine_post_col_number,
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
                                }
                                
                                $newszine_featured_query = new WP_Query($newszine_cat_post_args);
                                
                                if ($newszine_featured_query->have_posts()) : $cn = 0;?>
                                    
                                    <?php 
                                        echo $args['before_title'] . esc_html( $newszine_title ). $args['after_title'];
                                        $newszine_post_col_layout_class ='news-single-3';
                                        $thumb = 'newszine-post-thumb';
                                        $medium = 'newszine-post-thumb';                                        
                                        $newszine_sidebar_no_thumbnail = get_theme_mod( 'default_images');
                                        $newszine_words = 48;                
                                    ?>
                                    
                                    
                                    <?php  while ( $newszine_featured_query->have_posts() ) :$newszine_featured_query->the_post(); $cn++; ?> 
                                    <?php if ( $cn == 1 ) : ?>
                                        <div class="<?php echo esc_attr( $newszine_post_col_layout_class ); ?>">
                                            <div class="image">
                                                <?php
                                                    if ( has_post_thumbnail() ):
                                                        $post_thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), $medium );
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
                                              
                                               <?php echo  newszine_exrcept(40); ?>
                                                <span class="read-more pull-right"><a href="<?php the_permalink();?>" class="btn btn-theme" ><?php _e('Read More','newszine')?><i class="fa fa-angle-double-right"></i></a></span>
                                            </div>                       
                                        </div> 
                                    
                                    <?php else: ?>
                                        <div class="<?php echo esc_attr( $newszine_post_col_layout_class ); ?>">                                           
                                                
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
                                    <?php endif;?>
                                    <?php   endwhile;                   
                                    
                                    wp_reset_postdata();
                                endif; ?>
                            </div>
                        </div> 
                <?php }  
                    else{
                         $newszine_post_col_class='col-sm-6 right-clear'; ?>
                        <div class="<?php echo esc_attr( $newszine_post_col_class ); ?>" >
                            <div class="block">
                                <?php   $newszine_cat_post_args = array(
                                    'posts_per_page'      => $newszine_post_col_number,
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
                                }
                                
                                $newszine_featured_query = new WP_Query($newszine_cat_post_args);
                                
                                if ($newszine_featured_query->have_posts()) : $cn = 0;?>
                                    
                                    <?php 
                                        echo $args['before_title'] . esc_html( $newszine_title ). $args['after_title'];
                                        $newszine_post_col_layout_class ='news-single-4';
                                        $thumb = 'newszine-post-thumb';
                                        $medium = 'newszine-post-thumb';                                        
                                        $newszine_sidebar_no_thumbnail = get_theme_mod( 'default_images');
                                        $newszine_words = 48;                
                                    ?>
                                    
                                    
                                    <?php  while ( $newszine_featured_query->have_posts() ) :$newszine_featured_query->the_post(); $cn++; ?> 
                                    <?php if ( $cn == 1 ) : ?>
                                        <div class="<?php echo esc_attr( $newszine_post_col_layout_class ); ?>">
                                            <div class="image">
                                                <?php
                                                    if ( has_post_thumbnail() ):
                                                        $post_thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), $medium );
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
                                    
                                    <?php else: ?>
                                        <div class="<?php echo esc_attr( $newszine_post_col_layout_class ); ?>">
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
                                                   
                                                </h6>
                                                <?php echo  newszine_exrcept(20); ?>
                                            </div> 
                                        </div>
                                    <?php endif;?>
                                    <?php   endwhile;                   
                                    
                                    wp_reset_postdata();
                                endif; ?>
                            </div>
                        </div>
                         
                  <?php }
            ?> 
            
       <?php  }
    } // Class newszine_post_col ends here
}
if ( ! function_exists( 'newszine_half_col' ) ) :
    /**
     * Function to Register and load the widget
     *
     * @since 1.0.3
     *
     * @param null
     * @return null
     *
     */
    function newszine_half_col() {
        register_widget( 'newszine_half_col' );
    }
endif;
add_action( 'widgets_init', 'newszine_half_col' );
