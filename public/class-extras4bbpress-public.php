<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://rafael.business
 * @since      1.0.0
 *
 * @package    Extras4bbpress
 * @subpackage Extras4bbpress/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Extras4bbpress
 * @subpackage Extras4bbpress/public
 * @author     Codash <rafael@codash.com.br>
 */
class Extras4bbpress_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Extras4bbpress_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Extras4bbpress_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/extras4bbpress-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Extras4bbpress_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Extras4bbpress_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/extras4bbpress-public.js', array( 'jquery' ), $this->version, false );

	}


	public function bbp_extra_fields() {

	   $value = get_post_meta( bbp_get_topic_id(), 'bbp_extra_dt_inicio', true);
	   ?>
	   	<div class="form-item col-md-4">
	   		<label for="bbp_extra_dt_inicio">Data de Início</label><br>
			<input type='datetime-local' name='bbp_extra_dt_inicio' value='<?= $value ?>'>
		</div>
	   <?php

	   $value = get_post_meta( bbp_get_topic_id(), 'bbp_extra_dt_termino', true);
	   ?>
	   	<div class="form-item col-md-4">
	   		<label for="bbp_extra_dt_termino">Data de Término</label><br>
	   		<input type='datetime-local' name='bbp_extra_dt_termino' value='<?= $value ?>'>
		</div>
	   <?php

		$value = get_post_meta( bbp_get_topic_id(), 'bbp_extra_limite', true);
		?>
		<div class="form-item col-md-4">
			<label for="bbp_extra_limite"><?php esc_html_e( 'Qtd. de Vozes', 'extras4bbpress' ); ?></label>
			<abbr title="Para deixar o etiqueta livre, coloque o número zero">?</abbr><br>
			<input type="number" name="bbp_extra_limite" value="<?= $value ?>" min="0">
		</div>
		<?php
	}

	public function bbp_current_user_can_reply_this_topic() {
		
		$voice_count = bbp_get_topic_voice_count( null, true );
		$topic_id = bbp_get_topic_id();
		$bbp_extra_limite = get_post_meta( $topic_id, 'bbp_extra_limite', true );
		
		$bbp_db = bbp_db();

		$sql_select = "SELECT `post_author` FROM `{$bbp_db->posts}` WHERE `post_type` = '" . bbp_get_reply_post_type() . "' AND `post_status` = '" . bbp_get_public_status_id() . "' AND `post_parent` = '" . $topic_id . "'";

		$vozes = $bbp_db->get_results( $sql_select );

		if ( !is_wp_error( $vozes ) ) {

			$falantes = array();
			foreach ( $vozes as $voz ) {
				
				array_push( $falantes, $voz->post_author );
			}
		}

		if ( 0 === intval( $bbp_extra_limite ) ) return true;

		if ( 
			intval( $bbp_extra_limite ) > $voice_count || 
			in_array( get_current_user_id(), $falantes ) 
		) { return true; } else { return false; }
	}

	public function bbp_current_user_no_reply() {

		add_filter( 
			'bbp_current_user_can_access_create_reply_form', 
			'__return_false' 
		);
	}
	
	public function bbp_get_infos_header() { 
	    
	    $topic_id 	= bbp_get_topic_id();
		$inicio 	= get_post_meta( $topic_id, 'bbp_extra_dt_inicio', true );
		$termino 	= get_post_meta( $topic_id, 'bbp_extra_dt_termino', true );

		$_inicio	= wp_date( 'j \d\e F \d\e Y à\s H:i', strtotime( $inicio ) );
		$_termino	= wp_date( 'j \d\e F \d\e Y à\s H:i', strtotime( $termino ) );

		if ( time() < strtotime( $inicio ) && time() < strtotime( $termino ) ) : 
		
		$this->bbp_current_user_no_reply();

	    ?>
	    <div class="bbp-template-notice info">
			<ul>
				<li>
                    Discussão com início em <strong><?= $_inicio ?></strong>.
				</li>
			</ul>
		</div>
	    <?php 

		elseif ( time() > strtotime( $inicio ) && time() < strtotime( $termino ) ) : 
		
		?>
		<div class="bbp-template-notice info">
			<ul>
				<li>
                    Discussão com início em <strong><?= $_inicio ?></strong> e término em <strong><?= $_termino ?></strong>.
				</li>
			</ul>
		</div>
		<?php

		elseif ( time() > strtotime( $inicio ) && time() > strtotime( $termino ) ) : 
		$this->bbp_current_user_no_reply();

		?>
		<div class="bbp-template-notice">
			<ul>
				<li>
                    Discussão encerrada em <strong><?= $_termino ?></strong>.
				</li>
			</ul>
		</div>
		<?php
		endif;

		$limite = get_post_meta( bbp_get_topic_id(), 'bbp_extra_limite', true);
		if ( 0 !== $limite ) : 
		?>
		<div class="bbp-template-notice info">
			<ul>
				<li>
                    Discussão limitada a <strong><?= $limite ?> participantes</strong>.
				</li>
			</ul>
		</div>
		<?php
		endif;
	}

	public function bbp_new_admin_links( $links = '', $args ){ 
		
		if ( ! current_user_can( 'moderate', bbp_get_forum_id() ) )
			return;

		$reply_id = bbp_get_reply_id();

		$links .= $args['before'];
		$links .= '<label data-id="' . $reply_id . '"><input class="resposta" type="checkbox" value="'.$reply_id.'">&nbsp;';
		$links .= __( 'Incluir no Relatório', 'extras4bbpress' ) . '</label>';
		$links .= $args['sep'] . $args['after'];
		
		return $links;
	}

	public function bbp_btn_get_relatorio(){ 

		if ( ! current_user_can( 'moderate', bbp_get_forum_id() ) )
			return;

		$url_save = get_rest_url( null, 'relatorio/save' );
		$resumo = get_the_content();
		$topic_id 	= bbp_get_topic_id();
		$inicio 	= get_post_meta( $topic_id, 'bbp_extra_dt_inicio', true );
		$termino 	= get_post_meta( $topic_id, 'bbp_extra_dt_termino', true );

		$doc_tags = get_terms( array( 
		    'taxonomy'   => 'doc_tag',
		    'parent'   	 => 0,
		    'hide_empty' => false
		));

		$query_docs = new WP_Query( array(
			'post_type'		=> 'docs',
			'post_parent'	=> 0,
			'post_status' 	=> array( 'publish' ),
    		'perm'        	=> 'readable',
			'nopaging'      => TRUE,
			'posts_per_page'=> -1,
			'order'         => 'DESC',
			'orderby'       => 'ID'
		));

		$topic_tags = array();
		foreach ( bbp_get_topic_tags() as $tag ) : 
			
			array_push( $topic_tags, $tag->name );
		endforeach;
		$topic_tags = implode( ', ', $topic_tags );
		
		?>
		<div class="row">
			<fieldset class="col relatorios border rounded-top">
				<legend><?= __( 'Relatórios', 'extras4bbpress' ) ?></legend>
				<p class="pl-2">
					<?= __( 'Selecione as respostas, que deseja incluir, acima.', 'extras4bbpress' ) ?>
					<br />
					<span id="badge_respostas" class="badge bg-danger text-light" data-change='bg-success'>
						<?= __( 'Nenhuma', 'extras4bbpress' ) ?>
					</span>
					<span><?= __( ' resposta(s) adicionada(s) ao relatório.', 'extras4bbpress' ) ?></span>
				</p>
				<input type="hidden" id="respostas">
				<input type="hidden" id="relatorio_topic_id" value="<?= get_the_ID() ?>">
				<input type="hidden" id="relatorio_topic" value="<?= get_the_title() ?>">
				<input type="hidden" id="relatorio_topic_tags" value="<?= $topic_tags ?>">
				<input type="hidden" id="relatorio_gestor" value="<?= get_current_user_id() ?>">
				<div class="form-group row">
					<label class="col-sm-3 col-form-label pl-4" for="relatorio_resumo">
						<?= __( 'Resumo', 'extras4bbpress' ) ?>
					</label>
					<div class="col-sm-9">
						<div class="border rounded">
							<textarea id="relatorio_resumo" name="relatorio_resumo"><?= $resumo ?></textarea>
						</div>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-3 col-form-label pl-4" for="relatorio_parecer">
						<?= __( 'Seu parecer', 'extras4bbpress' ) ?>
					</label>
					<div class="col-sm-9">
						<div class="border rounded">
							<textarea id="relatorio_parecer" name="relatorio_parecer"></textarea>
						</div>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-3 col-form-label pl-4">
						<?= __( 'Etiqueta', 'extras4bbpress' ) ?>
					</label>
					<div class="col-sm-9">
						<?php
						if ( !empty($doc_tags) ) :
					    foreach( $doc_tags as $tag ) : 
				        if( $tag->parent == 0 ) : ?>
			        	<div class="form-check form-check-inline">
							<input 
								class="form-check-input" 
								id="etiqueta_<?= $tag->term_id ?>" 
								type="radio" 
								name="relatorio_etiqueta" 
								value="<?= $tag->slug ?>" 
								checked
							>
							<label 
								class="form-check-label" 
								for="etiqueta_<?= $tag->term_id ?>"
							><?= $tag->name ?></label>
						</div>
			        	<?php
				        endif;
					    endforeach;
						endif; ?>
					</div>
				</div>
				<?php
				$fechado = time() > strtotime( $inicio ) && time() > strtotime( $termino );
				$display = $fechado ? 'display: none;' : 'display: flex;'; 
				$dias = !$fechado ?  ceil( ( strtotime( $termino ) - time() ) / (60 * 60 * 24) ) : 0; 
				?>
				<div class="form-group row" style="<?= $display ?>">
					<label class="col-sm-3 col-form-label pl-4">
						<?= __( 'Encerramento', 'extras4bbpress' ) ?>
					</label>
					<div class="col-sm-9">
						<p>
						<?php
						printf( __( 'Esteja ciente de que, <strong>a geração do relatório encerra a discussão</strong>.<br />Em <strong>%d dias</strong>, ela seria encerrada automaticamente.', 'extras4bbpress' ), $dias );
						?>
						</p>
						<div class="form-check form-check-inline">
							<input 
								class="form-check-input" 
								id="relatorio_encerrar" 
								type="checkbox" 
							>
							<label 
								class="form-check-label" 
								for="relatorio_encerrar"
							>Estou ciente, desejo prosseguir.
							</label>
						</div>
					</div>
				</div>
				<?php
				$display = $fechado ? 'display: flex;' : 'display: none;'; 
				?>
				<div id="gerador" class="form-group row" style="<?= $display ?>">
					<label class="col-sm-3 col-form-label pl-4" for="relatorio_parent">
						<?= __( 'Documento', 'extras4bbpress' ) ?>
					</label>
					<div class="col-sm-9 input-group">
						<select class="form-select border border-primary rounded-left pl-3 pr-3" id="relatorio_parent">
							<option value="0"><?= __( '-- selecione --', 'extras4bbpress' ) ?></option>
							<?php
							if ( $query_docs->have_posts() ) : 
							while ( $query_docs->have_posts() ) :
							$query_docs->the_post(); ?>
							<option value="<?= get_the_ID() ?>"><?= get_the_title() ?></option>
							<?php
							endwhile;
							endif;
							wp_reset_postdata(); ?>
						</select>
						<div class="input-group-append">
							<button id="gerar_relatorio" class="btn btn-primary" type="button" data-save="<?= $url_save ?>">
								<?= __( 'Gerar', 'extras4bbpress' ) ?>
							</button>
						</div>
					</div>
				</div>
				<div class="results">
					<div id="result_success" class="alert alert-success" role="alert"></div>
					<div id="result_danger" class="alert alert-danger" role="alert"></div>
				</div>
			</fieldset>
		</div>
		<?php
	}

	public function bbp_save_relatorio( $post ) {

		$front = array();
		
		$query_respostas = new WP_Query( array(
			'post_type'		=> 'reply',
			'post_status' 	=> array( 'publish' ),
    		'perm'        	=> 'readable',
			'post__in'		=> explode( ',', $post['respostas'] ),
			'nopaging'      => TRUE,
			'posts_per_page'=> -1,
			'order'         => 'DESC',
			'orderby'       => 'ID'
		));
		
		if ( $query_respostas->have_posts() ) {

			$content = '';
			$content .= $post['resumo'];
			$content .= 'Tags: '. $post['topic_tags'] . '<hr />';
			
			while ( $query_respostas->have_posts() ) {

				$query_respostas->the_post();
				$id = get_the_ID();

				$content .= '<strong>'. get_the_author() . '</strong>' . ' em ';
				$content .= '<strong>'. get_the_date() . '</strong>' . ':<br />';
				$content .= get_the_content() . '<hr />';
			}

			$content .= $post['parecer'];
			$front['etiqueta'] = $post['etiqueta'];
			$front['tipo'] = $post['tipo'];
		} else {
			
			return 
			'{"code":"danger","message":"'. __( 'Você não selecionou as respostas.', 'extras4bbpress' ) .'"}';
		}
		
		wp_reset_postdata();
		
		$post_arr = array(
			'post_type'    => 'docs',
			'post_title'   => $post['topic'],
			'post_content' => $content,
			'post_status'  => 'publish',
			'post_author'  => $post['gestor'],
			'post_parent'  => $post['parent']
		);

		$doc_id = wp_insert_post( $post_arr, true );
		$link = get_permalink( $doc_id );

		if ( !is_wp_error( $doc_id ) ) : 
			
			wp_set_object_terms( $doc_id, array( $post['etiqueta'] ), 'doc_tag' );
			$topic = array(
				'ID'           => $post['topic_id'],
				'post_status'  => bbp_get_closed_status_id()
			);
			$err = wp_update_post( $topic );
		endif;

		return 
		is_wp_error( $doc_id ) 
		? '{"code":"danger", "message":"'.__( 'Erro na criação do documento.', 'extras4bbpress' ).'"}'
		: '{"code":"success", "message":"'.__( 'Documento criado com sucesso!', 'extras4bbpress' ).'", "link":"'.$link.'"}';
	}

	public function bbp_add_rest_api_routes() {

		register_rest_route( 'relatorio', '/save', array(
			'methods' => 'POST',
			'callback' => array( $this, 'bbp_save_relatorio' ),
		));
	}

}
