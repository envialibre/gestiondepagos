<?php
/*
 * Template Name: Proyectos CRUD
 */

get_header();

// Delete proyecto
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['proyecto_id'])) {
  $proyecto_id = intval($_GET['proyecto_id']);

  // Delete proyecto
  wp_delete_post($proyecto_id, true);

  // Send success response
  wp_send_json_success();
  // Clear post parameters from storage
  $_POST = array();  
}

?>

<div id="primary" class="content-area">
  <main id="main" class="site-main" role="main">

  <?php
// Display proyectos list
$args = array(
  'post_type' => 'proyectos',
  'posts_per_page' => -1,
  'order' => 'DESC',
  'orderby' => 'date'
);
$proyectos_query = new WP_Query($args);

if ($proyectos_query->have_posts()) :
?>

  <h2>Proyectos</h2>
  <div class="row py-3">
    <div class="col text-start">    
      <a href="<?php echo home_url() ?>/crear-proyecto" class="btn btn-light">Crear Proyecto</a>
    </div>
  </div>

  <div class="box-table p-4">
    <table id="table-main" class="table table-responsive table-light table-striped" style="width:100%">
      <thead>
          <tr>
              <th>Proyecto</th>
              <th>Dirección del Proyecto</th>
              <th>Metros</th>
              <th>Cantidad de Lotes</th>
              <th>Áreas Comunes</th>
              <th>Fecha de Entrega</th>
              <th>Servicios que se Entregan</th>
              <th>Anotaciones</th>
              <th>Gestión</th>
          </tr>
      </thead>
      <tbody>
      <?php while ($proyectos_query->have_posts()) : $proyectos_query->the_post(); ?>
      <tr>
        <td><?php the_title(); ?></td>
        <td><?php echo get_post_meta(get_the_ID(), 'direccion_del_proyecto', true); ?></td>
        <td><?php echo get_post_meta(get_the_ID(), 'metros', true); ?></td>
        <td><?php echo get_post_meta(get_the_ID(), 'cantidad_de_lotes', true); ?></td>
        <td><?php if (get_post_meta(get_the_ID(), 'areas_comunes', true) ) { echo implode( ', ', get_post_meta(get_the_ID(), 'areas_comunes', true) );} ?></td>
        <td><?php echo get_post_meta(get_the_ID(), 'fecha_de_entrega', true); ?></td>
        <td><?php if (get_post_meta(get_the_ID(), 'servicios_que_se_entregan', true) ) { echo implode( ', ', get_post_meta(get_the_ID(), 'servicios_que_se_entregan', true) );} ?></td>
        <td><?php echo get_post_meta(get_the_ID(), 'anotaciones', true); ?></td>
        <td>
          <a class="btn btn-secondary" href="<?php the_permalink(); ?>">Ver</a>
          <a class="btn btn-secondary" href="<?php echo get_the_permalink() ?>?param_edit=edit" class="edit-proyecto-btn">Edit</a>
          <a href="#" class="delete-proyecto btn btn-secondary" data-proyecto-id="<?php echo get_the_ID(); ?>" data-permalink="<?php the_permalink(); ?>">Delete</a>
        </td>         
      </tr>
      <?php endwhile; ?>
      </tbody>        
    </table>
  </div>

<?php
  wp_reset_postdata();
else :
  echo 'No proyectos found.';
endif;
?>





    <script>
      jQuery(function ($) {
        // Handle delete button click
        jQuery('.delete-proyecto').click(function (e) {
          e.preventDefault();
          var proyectoId = jQuery(this).data('proyecto-id');
          var permalink = jQuery(this).data('permalink');

          // Show confirmation modal before deleting
          if (confirm('Are you sure you want to delete this proyecto?')) {
            // Send AJAX request to delete the proyecto
            console.log('proyectoId '+proyectoId);
            console.log('permalink '+permalink);
            console.log('<?php echo esc_url(add_query_arg(array('action' => 'delete', 'proyecto_id' => ''), '')); ?>' + proyectoId + '&permalink=' + encodeURIComponent(permalink));
            $.ajax({
              url: '?action=delete&proyecto_id='+proyectoId,
              method: 'GET',
              success: function (response) {
                if (response.success) {
                  // Reload the page after successful deletion
                  location.reload();
                } else {
                  // Handle unsuccessful deletion, display an error message if needed
                  //alert('Failed to delete proyecto.');
                }
              },
              error: function () {
                // Handle AJAX error, display an error message if needed
                //alert('An error occurred while deleting proyecto.');
              }
            });
            alert('Proyecto eliminado');
            location.reload();
          }
        });
      });
    </script>
  </main>
</div>

<?php get_footer(); ?>
