<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<?php wp_head(); ?>
    <link href="https://cdn.datatables.net/v/dt/dt-1.13.4/datatables.min.css" rel="stylesheet"/>

</head>


<body <?php body_class(); ?>>
<div class="box-alerts"></div>
<?php wp_body_open(); ?>

<a href="#main" class="visually-hidden-focusable"><?php esc_html_e( 'Skip to main content', 'gpagos' ); ?></a>

<div id="wrapper">
	<main id="main" class="">

	<div class="container-fluid">
    <div class="row flex-nowrap">
        <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-light">
            <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-80">
                <ul class="nav nav-pills flex-column mb-4 align-items-center align-items-sm-start" id="menu">
                    <li>                
                        <a class="nav-link px-0 align-middle d-flex align-items-center" href="<?php echo esc_url( home_url() ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
                            <?php
                                $header_logo = get_theme_mod( 'header_logo' ); // Get custom meta-value.

                                if ( ! empty( $header_logo ) ) :
                            ?>
                                <i class="fs-4 pe-1"><img src="<?php echo get_site_icon_url(); ?>" alt="icono"></i>
                                <span class="ms-1 d-none d-sm-inline">
                                    <img src="<?php echo esc_url( $header_logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" />
                                </span>
                            <?php
                                else :
                                    echo esc_attr( get_bloginfo( 'name', 'display' ) );
                                endif;
                            ?>
                        </a>
                    </li>
                </ul>

                <?php
					wp_nav_menu(
						array(
							'walker'         => new Custom_Menu_Walker,
							'theme_location' => 'main-menu',
                            'container_class' => 'nav flex-column align-items-center align-items-sm-start nav-pills mb-sm-auto mb-0',
                            'items_wrap'      => '<ul class="nav flex-column align-items-center align-items-sm-start nav-pills mb-sm-auto mb-0">%3$s</ul>'
						)
					);
                ?>
                <hr>
                <div class="pb-4 flex-column align-items-center width-100 nav-pills mb-5">
                    <a href="<?php echo wp_logout_url(); ?>" class="nav-logout">
                        <button class="btn btn-secondary py-1 width-100 d-flex align-items-center justify-content-center">
                            <span class="d-none d-sm-inline mx-1">Salir</span>                             
                            <i class="fs-4 bi-box-arrow-right"></i>                           
                        </button>
                    </a>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="row bg-light d-flex align-items-center py-2">
                <div class="col-md-10">
                    <div class="breadcrumb"><?php get_breadcrumb(); ?></div>
                </div>
                <div class="col-md-2">
                    <div class="user-info d-flex align-items-center justify-content-end">
                        <?php
                        global $current_user;
                        get_currentuserinfo();
                        // Output the profile alias                       
                        echo '<div class="">';
                        echo '<div class="display-name px-2 text-profile text-end">' .$current_user->first_name. '</div>';
                        echo '<div class="display-name px-2 text-profile text-end">' .$current_user->nickname. '</div>';
                        echo '</div>';
                        echo '<div>';
                        echo '<span class="profile-img">'.get_avatar($current_user->ID, 42).'</span>'; // Adjust the size as needed
                        echo '</div>';
                        ?>
                    </div> 
                </div>           
            </div>