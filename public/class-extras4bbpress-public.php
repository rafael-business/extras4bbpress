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
	   echo '<label for="bbp_extra_dt_inicio">Data de Início</label><br>';
	   echo "<input type='datetime-local' name='bbp_extra_dt_inicio' value='".$value."'>";

	   $value = get_post_meta( bbp_get_topic_id(), 'bbp_extra_dt_termino', true);
	   echo '<label for="bbp_extra_dt_termino">Data de Término</label><br>';
	   echo "<input type='datetime-local' name='bbp_extra_dt_termino' value='".$value."'>";
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
                    Discussão com término em <strong><?= $_termino ?></strong>.
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
	}

}
