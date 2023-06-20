</div>
    </div>
</div>




		</main><!-- /#main -->
		<footer id="footer" class="bg-light">
			<div class="container">
				<div class="row">
					<div class="col-md-12 text-center">
						<p>© Inmovisión <?php echo wp_date( 'Y' ); ?>. todos los derechos reservados. <a target="_blank" href="#">Términos y condiciones</a> <a target="_blank" href="#">Política de tratamiento de datos</a></p>
					</div>
				</div><!-- /.row -->
			</div><!-- /.container -->
		</footer><!-- /#footer -->
	</div><!-- /#wrapper -->
	<?php
		wp_footer();
	?>
<script src="https://cdn.datatables.net/v/dt/dt-1.13.4/datatables.min.js"></script>

<script>

jQuery(document).ready(function () {
	var table = new DataTable('#table-main', {
		order: [],
		language: {
			url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json',
		},
	});
});




</script>


</body>
</html>
