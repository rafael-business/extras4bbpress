<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://rafael.business
 * @since      1.0.0
 *
 * @package    Extras4bbpress
 * @subpackage Extras4bbpress/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Extras4bbpress
 * @subpackage Extras4bbpress/admin
 * @author     Codash <rafael@codash.com.br>
 */
class Extras4bbpress_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->post = isset($_GET['post']) ? $_GET['post'] : null;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/extras4bbpress-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/extras4bbpress-admin.js', array( 'jquery' ), $this->version, false );

	}


	public function bbp_extra_fields_admin() {

		$value = get_post_meta( $this->post, 'bbp_extra_dt_inicio', true );
		?>
		<hr />
		<h3>Controle</h3>
		<p>Esta sessão permite o gerenciamento da linha de discussão.</p>
		<p>
			<strong class="label"><?php esc_html_e( 'Início', 'extras4bbpress' ); ?></strong>
			<label class="screen-reader-text" for="bbp_extra_dt_inicio"><?php esc_html_e( 'Início', 'extras4bbpress' ); ?></label>
			<input type="datetime-local" name="bbp_extra_dt_inicio" value="<?= $value ?>">
		</p>
		<?php

		$value = get_post_meta( $this->post, 'bbp_extra_dt_termino', true );
		?>
		<p>
			<strong class="label"><?php esc_html_e( 'Término', 'extras4bbpress' ); ?></strong>
			<label class="screen-reader-text" for="bbp_extra_dt_termino"><?php esc_html_e( 'Término', 'extras4bbpress' ); ?></label>
			<input type="datetime-local" name="bbp_extra_dt_termino" value="<?= $value ?>">
		</p>
		<?php

		$value = intval( get_post_meta( $this->post, 'bbp_extra_limitado', true ) );
		?>
		<p>
			<div>
				<strong><?php esc_html_e( 'Acesso', 'extras4bbpress' ); ?></strong>
				<label class="screen-reader-text" for="bbp_extra_limitado"><?php esc_html_e( 'Acesso', 'extras4bbpress' ); ?></label>
			</div>
			<label style="margin-right: 10px;">
				<input type="radio" name="bbp_extra_limitado" value="1" <?= 1 === $value ? 'checked' : '' ?>> Limitado
			</label>
			<label>
				<input type="radio" name="bbp_extra_limitado" value="2" <?= 2 === $value ? 'checked' : '' ?>> Livre
			</label>
		</p>
		<?php

		$value = get_post_meta( $this->post, 'bbp_extra_limite', true );
		?>
		<p>
			<div>
				<strong><?php esc_html_e( 'Qtd. de Vozes', 'extras4bbpress' ); ?></strong>
				<label class="screen-reader-text" for="bbp_extra_limite"><?php esc_html_e( 'Qtd. de Vozes', 'extras4bbpress' ); ?></label>
			</div>
			<input type="number" name="bbp_extra_limite" value="<?= $value ?>" min="2">
			<br />
			<small>Atenção: Atente-se para o fato de que o criador do tópico é automaticamente uma voz.</small>
		</p>
		<?php
		$groups = $this->get_bp_groups();
		?>
		<p>
			<div>
				<strong><?php esc_html_e( 'Restringir a determinados grupos', 'extras4bbpress' ); ?></strong>
				<label class="screen-reader-text" for="bbp_extra_groups"><?php esc_html_e( 'Restringir a determinados grupos', 'extras4bbpress' ); ?></label>
			</div>
			<?php 
			foreach( $groups as $group ) : 
				$values = get_post_meta( $this->post, 'bbp_extra_groups', true );
				$values_unserialized = maybe_unserialize( $values );
			?>
			<label style="display: block;">
				<input 
					type="checkbox" 
					name="bbp_extra_groups[<?= $group['value'] ?>]" 
					value="<?= $group['value'] ?>" 
					<?= in_array( $group['value'], $values_unserialized ) ? 'checked' : '' ?>
				> <?= $group['label'] ?>
			</label>
			<?php 
			endforeach; ?>
		</p>
		<?php
	}

	public function get_bp_groups() {
	
		
		$url = get_rest_url( null, 'buddypress/v1/groups' );
		$args = '';
		$groups = wp_remote_get( $url, $args );
		if ( is_array( $groups ) && ! is_wp_error( $groups ) ) {

		    $headers 		= $groups['headers'];
		    $groups_body    = json_decode( $groups['body'] );
		}
		$groups_formatted = array();
		foreach ( $groups_body as $group ) {
			
			array_push( $groups_formatted, ['value' => $group->id, 'label' => $group->name] );
		}

		return $groups_formatted;
	}

	public function add_meta_meta_box() {

	    add_meta_box('meta-meta-box-id', 'Meta', array( $this, 'meta_meta_box' ), 'topic', 'normal', 'high');
	}

	public function meta_meta_box( $post ) {

		$voice_count = bbp_get_topic_voice_count( $this->post, true );

	    echo '<pre>';
	    echo $voice_count;
	    print_r( get_post_meta($post->ID) );
	    echo '</pre>';
	}

	public function bbp_save_topic_extra_fields( $topic_id = null, $post = null ) {

		$topic_id = $topic_id && null === $topic_id ? $this->post : $topic_id;

	    if ( isset($_POST) && $_POST['bbp_extra_dt_inicio']!='' )
    		update_post_meta( $topic_id, 'bbp_extra_dt_inicio', $_POST['bbp_extra_dt_inicio'] );

    	if ( isset($_POST) && $_POST['bbp_extra_dt_termino']!='' )
    		update_post_meta( $topic_id, 'bbp_extra_dt_termino', $_POST['bbp_extra_dt_termino'] );

    	if ( isset($_POST) && $_POST['bbp_extra_limitado']!='' )
    		update_post_meta( $topic_id, 'bbp_extra_limitado', $_POST['bbp_extra_limitado'] );

    	if ( isset($_POST) && $_POST['bbp_extra_limite']!='' )
    		update_post_meta( $topic_id, 'bbp_extra_limite', $_POST['bbp_extra_limite'] );

    	$groups = $this->get_bp_groups();
		foreach( $groups as $group ) : 

			if ( isset($_POST) && $_POST["bbp_extra_groups"]!='' )
    			update_post_meta( $topic_id, "bbp_extra_groups", $_POST["bbp_extra_groups"] );
		endforeach;
	}


	

}
