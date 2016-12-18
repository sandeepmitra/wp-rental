<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no">

<title>
    <?php
    global $page, $paged;
    wp_title( '|', true, 'right' );
    bloginfo( 'name' );
    $site_description = get_bloginfo( 'description', 'display' );
    
    if ( $site_description && ( is_home() || is_front_page() ) ){
        echo " | $site_description";
    }
    
    if ( $paged >= 2 || $page >= 2 ){
        echo ' | ' . sprintf( __( 'Page %s', 'wpestate' ), max( $paged, $page ) );
    }
    ?>
</title>



<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
 
<?php 
$favicon        =   esc_html( get_option('wp_estate_favicon_image','') );
if ( $favicon!='' ){
    echo '<link rel="shortcut icon" href="'.$favicon.'" type="image/x-icon" />';
} else {
    echo '<link rel="shortcut icon" href="'.get_template_directory_uri().'/img/favicon.gif" type="image/x-icon" />';
}


wp_head();


if( is_tax() ) {
    echo '<meta name="description" content="'.strip_tags( term_description('', get_query_var( 'taxonomy' ) )).'" >';
}

if (get_post_type()== 'estate_property'){
    $image_id       =   get_post_thumbnail_id();
    $share_img= wp_get_attachment_image_src( $image_id, 'full'); 
    $the_post = get_post($post->ID);
    ?>
    <meta property="og:image" content="<?php echo esc_url($share_img[0]); ?>"/>
    <meta property="og:image:secure_url" content="<?php echo esc_url($share_img[0]); ?>" />
    <meta property="og:description"        content=" <?php echo wp_strip_all_tags( $the_post->post_content);?>" />
<?php 
} 
?>
</head>



<?php 

$wide_class      =   ' is_boxed ';
$wide_status     =   esc_html(get_option('wp_estate_wide_status',''));
if($wide_status==1){
    $wide_class=" wide ";
}

if( isset($post->ID) && wpestate_half_map_conditions ($post->ID) ){
    $wide_class="wide fixed_header ";
}


$halfmap_body_class='';
if( isset($post->ID) && wpestate_half_map_conditions ($post->ID) ){
    $halfmap_body_class=" half_map_body ";
}

if(esc_html ( get_option('wp_estate_show_top_bar_user_menu','') )=="yes"){
    $halfmap_body_class.=" has_top_bar ";
}

$logo_header_type           =   get_option('wp_estate_logo_header_type','');
$top_menu_hover_type        =   get_option('wp_estate_top_menu_hover_type','');  
$header_transparent_class   =   '';
$header_transparent         =   get_option('wp_estate_header_transparent','');


//  $header_transparent_class=' header_transparent '; 

if(isset($post->ID) && !is_tax() && !is_category() ){
        $header_transparent_page    =   get_post_meta ( $post->ID, 'header_transparent', true);
        if($header_transparent_page=="global" || $header_transparent_page==""){
            if ($header_transparent=='yes'){
                $header_transparent_class=' header_transparent ';
            }
        }else if($header_transparent_page=="yes"){
            $header_transparent_class=' header_transparent ';
        }
}else{
    if ($header_transparent=='yes'){
            $header_transparent_class=' header_transparent ';
    }
}

$logo                   =   get_option('wp_estate_logo_image','');  
$stikcy_logo_image      =   esc_html( get_option('wp_estate_stikcy_logo_image','') );
$logo_margin            =   intval( get_option('wp_estate_logo_margin','') );
$transparent_logo       =   esc_html( get_option('wp_estate_transparent_logo_image','') );

if(  trim($header_transparent_class) == 'header_transparent' && $transparent_logo!=''){
    $logo  = $transparent_logo;
}


?>




<body <?php body_class($halfmap_body_class); ?>>  
   

<?php   get_template_part('templates/mobile_menu' ); ?> 
    
<div class="website-wrapper" id="all_wrapper" >
<div class="container main_wrapper <?php print esc_html($wide_class); print esc_html('has_header_'.$logo_header_type.' '.$header_transparent_class); ?> ">

    <div class="master_header <?php print esc_html($wide_class.' '.$header_transparent_class); ?>">
        
        <?php   
            if(esc_html ( get_option('wp_estate_show_top_bar_user_menu','') )=="yes"){
                get_template_part( 'templates/top_bar' ); 
            } 
            get_template_part('templates/mobile_menu_header' );
        ?>
       
        
        <div class="header_wrapper <?php echo 'header_'.$logo_header_type; echo ' hover_type_'.$top_menu_hover_type.' ';?> ">
            <div class="header_wrapper_inside" data-logo="<?php echo $logo;?>" data-sticky-logo="<?php echo $stikcy_logo_image; ?>">
                
                <div class="logo" >
                    <a href="<?php echo home_url('','login');?>">
                        <?php  
                        if ( $logo!='' ){
                           print '<img id="logo_image" style="margin-top:'.$logo_margin.'px;" src="'.$logo.'" class="img-responsive retina_ready" alt="logo"/>';	
                        } else {
                           print '<img id="logo_image" class="img-responsive retina_ready" src="'. get_template_directory_uri().'/img/logo.png" alt="logo"/>';
                        }
                        ?>
                    </a>
                </div>   

              
                <?php 
                if(esc_html ( get_option('wp_estate_show_top_bar_user_login','') )=="yes"){
                   get_template_part('templates/top_user_menu');  
                }
                ?>    
                <nav id="access">
                    <?php 
                      /*wp_nav_menu( array( 'theme_location' => 'primary' ) );
                      
                       */  wp_nav_menu( 
                            array(  'theme_location'    => 'primary' ,
                                    'walker'            => new wpestate_custom_walker
                                ) 
                            ); 
                        
                        
                    ?>
                </nav><!-- #access -->
            </div>
        </div>

     </div> 
    
    <?php get_template_part( 'header_media' ); ?>   
    
  <div class="container content_wrapper">