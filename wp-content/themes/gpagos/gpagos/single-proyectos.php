<?php
get_header();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Retrieve the updated custom field values
  $titulo = sanitize_text_field($_POST['title']);
  $direccion_del_proyecto = sanitize_text_field($_POST['direccion_del_proyecto']);
  $metros = sanitize_text_field($_POST['metros']);
  $cantidad_de_lotes = sanitize_text_field($_POST['cantidad_de_lotes']);
  $areas_comunes = sanitize_text_field($_POST['areas_comunes']);
  $fecha_de_entrega = sanitize_text_field($_POST['fecha_de_entrega']);
  $servicios_que_se_entregan = sanitize_text_field($_POST['servicios_que_se_entregan']);
  $anotaciones = sanitize_textarea_field($_POST['anotaciones']);

  // Prepare the response data
  $response_data = array(
    'success' => true,
    'message' => 'Post updated successfully!',
    'data' => array(
      'title' => $titulo,
      'direccion_del_proyecto' => $direccion_del_proyecto,
      'metros' => $metros,
      'cantidad_de_lotes' => $cantidad_de_lotes,
      'areas_comunes' => $areas_comunes,
      'fecha_de_entrega' => $fecha_de_entrega,
      'servicios_que_se_entregan' => $servicios_que_se_entregan,
      'anotaciones' => $anotaciones,
    )
  );

  // Send the JSON response
  wp_send_json_success($response_data);
}

while (have_posts()) {
  the_post();

  // Retrieve the custom field values
  $titulo = get_the_title(get_the_ID());
  $direccion_del_proyecto = get_post_meta(get_the_ID(), 'direccion_del_proyecto', true);
  $metros = get_post_meta(get_the_ID(), 'metros', true);
  $cantidad_de_lotes = get_post_meta(get_the_ID(), 'cantidad_de_lotes', true);
  $areas_comunes = get_post_meta(get_the_ID(), 'areas_comunes', true);
  $fecha_de_entrega = get_post_meta(get_the_ID(), 'fecha_de_entrega', true);
  $servicios_que_se_entregan = get_post_meta(get_the_ID(), 'servicios_que_se_entregan', true);
  $anotaciones = get_post_meta(get_the_ID(), 'anotaciones', true);

  // Check if the param_edit is not present in the GET parameters
  $param_edit = isset($_GET['param_edit']) ? $_GET['param_edit'] : '';
  $readonly = ($param_edit != 'edit') ? 'readonly' : '';
  $submit_visible = ($param_edit == 'edit') ? '' : 'hidden';
?>

  <article id="post-<?php the_ID(); ?>" <?php post_class('p-5'); ?>>
    <h1 class="entry-title"><?php the_title(); ?></h1>

    <?php if (has_post_thumbnail()) : ?>
      <div class="featured-image">
        <?php the_post_thumbnail('large'); ?>
      </div>
    <?php endif; ?>

    <div class="entry-content">
      <?php the_content(); ?>
    </div>

    <div class="box-form bg-light p-4">
      <form id="update-post" method="POST" data-post-id="<?php echo get_the_ID(); ?>">
        <?php wp_nonce_field('update_post', 'update_post_nonce'); ?>

        <div class="row">
          <div class="col-md-4">
            <label for="post_title">Title:</label>
            <input type="text" id="post_title" name="post_title" value="<?php echo esc_attr(get_the_title()); ?>" class="form-control" <?php echo $readonly; ?>><br>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4">
            <label for="direccion_del_proyecto">Dirección del Proyecto:</label>
            <input type="text" id="direccion_del_proyecto" name="direccion_del_proyecto" value="<?php echo esc_attr($direccion_del_proyecto); ?>" class="form-control" <?php echo $readonly; ?>><br>
          </div>

          <div class="col-md-4">
            <label for="metros">Metros:</label>
            <input type="text" id="metros" name="metros" value="<?php echo esc_attr($metros); ?>" class="form-control" <?php echo $readonly; ?>><br>
          </div>

          <div class="col-md-4">
            <label for="cantidad_de_lotes">Cantidad de Lotes:</label>
            <input type="text" id="cantidad_de_lotes" name="cantidad_de_lotes" value="<?php echo esc_attr($cantidad_de_lotes); ?>" class="form-control" <?php echo $readonly; ?>><br>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4">
            <label for="areas_comunes">Áreas Comunes:</label>
            <input type="text" id="areas_comunes" name="areas_comunes" value="<?php echo esc_attr($areas_comunes); ?>" class="form-control" <?php echo $readonly; ?>><br>
          </div>

          <div class="col-md-4">
            <label for="fecha_de_entrega">Fecha de Entrega:</label>
            <input type="date" id="fecha_de_entrega" name="fecha_de_entrega" value="<?php echo esc_attr($fecha_de_entrega); ?>" class="form-control" <?php echo $readonly; ?>><br>
          </div>

          <div class="col-md-4">
            <label for="servicios_que_se_entregan">Servicios que se Entregan:</label>
            <input type="text" id="servicios_que_se_entregan" name="servicios_que_se_entregan" value="<?php echo esc_attr($servicios_que_se_entregan); ?>" class="form-control" <?php echo $readonly; ?>><br>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <label for="anotaciones">Anotaciones:</label>
            <textarea id="anotaciones" name="anotaciones" class="form-control" <?php echo $readonly; ?>><?php echo esc_textarea($anotaciones); ?></textarea><br>
          </div>
        </div>

        <!-- Add more fields if needed -->

        <?php if ($param_edit == 'edit') : ?>
          <input type="hidden" name="action" value="update_post">
          <input type="submit" value="Submit" class="btn btn-primary">
        <?php endif; ?>
      </form>
    </div>
  </article>

<?php
}

get_footer();

?>

<script>
jQuery(document).ready(function($) {
  // Submit form using AJAX
  $('#update-post').on('submit', function(e) {
    e.preventDefault();

    var form = $(this);
    var formData = form.serialize();

    // Get the post ID from a data attribute in the form
    var postID = form.data('post-id');

    // Add the post ID to the formData
    formData += '&post_id=' + postID;

    $.ajax({
      type: 'POST',
      url: ajax_obj.ajax_url,
      data: formData,
      dataType: 'json',
      success: function(response) {
        console.log(JSON.stringify(response));
        if (response.success) {
          // Display success message with fade-out effect
          var successAlert = $('<div class="alert alert-success" role="alert">' + response.data.data.message + '</div>');
          $('.box-alerts').prepend(successAlert);
          successAlert.fadeOut(3000);


          // Update input fields with new data
          $('#post_title').val(response.data.data.title);
          $('#direccion_del_proyecto').val(response.data.data.direccion_del_proyecto);
          $('#metros').val(response.data.data.metros);
          $('#cantidad_de_lotes').val(response.data.data.cantidad_de_lotes);
          $('#areas_comunes').val(response.data.data.areas_comunes);
          $('#fecha_de_entrega').val(response.data.data.fecha_de_entrega);
          $('#servicios_que_se_entregan').val(response.data.data.servicios_que_se_entregan);
          $('#anotaciones').val(response.data.data.anotaciones);
        } else {
          // Display error message with fade-out effect
          var errorAlert = $('<div class="alert alert-danger" role="alert">' + response.data.data.message + '</div>');
          $('.box-alerts').prepend(errorAlert);
          errorAlert.fadeOut(3000);
        }
      },
      error: function() {
        // Display error message with fade-out effect
        var errorAlert = $('<div class="alert alert-danger" role="alert">Error updating post.</div>');
        $('.box-alerts').prepend(errorAlert);
        errorAlert.fadeOut(3000);
      }
    });
  });
});


</script>