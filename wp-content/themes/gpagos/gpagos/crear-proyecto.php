<?php
/*
 * Template Name: Crear proyecto
 */

get_header();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_title = sanitize_text_field($_POST['post_title']);
    $direccion_del_proyecto = sanitize_text_field($_POST['direccion_del_proyecto']);
    $metros = sanitize_text_field($_POST['metros']);
    $cantidad_de_lotes = sanitize_text_field($_POST['cantidad_de_lotes']);
    $areas_comunes = sanitize_text_field($_POST['areas_comunes']);
    $fecha_de_entrega = sanitize_text_field($_POST['fecha_de_entrega']);
    $servicios_que_se_entregan = sanitize_text_field($_POST['servicios_que_se_entregan']);
    $anotaciones = sanitize_textarea_field($_POST['anotaciones']);

    // Prepare post data
    $project_data = array(
        'post_title'   => $project_title,
        'post_content' => $anotaciones,
        'post_status'  => 'publish',
        'post_type'    => 'proyectos',
        'meta_input'   => array(
            'direccion_del_proyecto'       => $direccion_del_proyecto,
            'metros'                      => $metros,
            'cantidad_de_lotes'           => $cantidad_de_lotes,
            'areas_comunes'               => $areas_comunes,
            'fecha_de_entrega'            => $fecha_de_entrega,
            'servicios_que_se_entregan'   => $servicios_que_se_entregan
        )
    );

    // Insert the post into the database
    $new_project_id = wp_insert_post($project_data);

    if ($new_project_id) {
        $success_message = 'The new project has been created successfully with ID: ' . $new_project_id;
        ?>
        <script>
            jQuery(document).ready(function($) {
                var successAlert = $('<div class="alert alert-success" role="alert"><?php echo $success_message; ?></div>');
                $('.box-alerts').prepend(successAlert);
                successAlert.fadeOut(3000);
            });
        </script>
        <?php
    } else {
        $error_message = 'There was an error creating the new project.';
        ?>
        <script>
            jQuery(document).ready(function($) {
                var errorAlert = $('<div class="alert alert-danger" role="alert"><?php echo $error_message; ?></div>');
                $('.box-alerts').prepend(errorAlert);
                errorAlert.fadeOut(3000);
            });
        </script>
        <?php
    }
}
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

            <header class="entry-header">
                <h1 class="entry-title"><?php the_title(); ?></h1>
            </header>

            <div class="entry-content">
                <form method="post">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="post_title">Title:</label>
                            <input type="text" id="post_title" name="post_title" value="" class="form-control"><br>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label for="direccion_del_proyecto">Dirección del Proyecto:</label>
                            <input type="text" id="direccion_del_proyecto" name="direccion_del_proyecto" value="" class="form-control"><br>
                        </div>

                        <div class="col-md-4">
                            <label for="metros">Metros:</label>
                            <input type="text" id="metros" name="metros" value="" class="form-control"><br>
                        </div>

                        <div class="col-md-4">
                            <label for="cantidad_de_lotes">Cantidad de Lotes:</label>
                            <input type="text" id="cantidad_de_lotes" name="cantidad_de_lotes" value="" class="form-control"><br>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label for="areas_comunes">Áreas Comunes:</label>
                            <input type="text" id="areas_comunes" name="areas_comunes" value="" class="form-control"><br>
                        </div>

                        <div class="col-md-4">
                            <label for="fecha_de_entrega">Fecha de Entrega:</label>
                            <input type="date" id="fecha_de_entrega" name="fecha_de_entrega" value="" class="form-control"><br>
                        </div>

                        <div class="col-md-4">
                            <label for="servicios_que_se_entregan">Servicios que se Entregan:</label>
                            <input type="text" id="servicios_que_se_entregan" name="servicios_que_se_entregan" value="" class="form-control"><br>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label for="anotaciones">Anotaciones:</label>
                            <textarea id="anotaciones" name="anotaciones" class="form-control"></textarea><br>
                        </div>
                    </div>

                    <input type="submit" value="Create Project" class="btn btn-primary">
                </form>
            </div>

        </article>

    </main>
</div>

<?php get_footer(); ?>
