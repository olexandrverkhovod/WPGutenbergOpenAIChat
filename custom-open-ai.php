<?php
/**
 * Plugin Name: Custom Open AI Block
 * Description: A Wordpress Plugin for inset Open AI into Gutenberg.
 * Version: 1.0.0
 * Author: Aleks Verkhovod
 * Author URI: 
 * Text Domain: custom-open-ai
 */

class Custom_Open_AI {

    public function __construct()
    {
        register_deactivation_hook( __FILE__, array($this, 'custom_open_ai_deactivate'));
        add_action('admin_menu', array( $this, 'custom_open_ai_settings_page' ));
        if(is_admin()):
			$this->check_for_settings();
		endif;
        add_action( 'enqueue_block_editor_assets', array($this, 'block_enqueue_scripts'));
        add_action( 'wp_enqueue_scripts', array($this, 'enqueue_front_scripts_styles'));
		add_action ('wp_ajax_nopriv_ai_send', array($this, 'open_ai_event_handler'));
    }

    /**
	* Enques block javascript
	*/
	public function block_enqueue_scripts() {
        wp_enqueue_script( 'custom-open-ai-block', plugins_url( '/js/block-scripts.js', __FILE__ ), array('wp-blocks','wp-i18n','wp-editor'),true,false);
        wp_enqueue_style('custom-open-ai-block-styles', plugins_url( '/css/block-styles.css', __FILE__ ));

	}
    
    /**
	* Enques front javascript and styles    
	*/
	public function enqueue_front_scripts_styles() {
	    wp_enqueue_style( 'custom-open-ai-front', plugins_url('/css/styles.css', __FILE__) );
        wp_enqueue_script( 'custom-open-ai-ajax-script', plugins_url( '/js/scripts.js', __FILE__ ), array('jquery') );
			wp_localize_script( 'custom-open-ai-ajax-script', 'ajaxObject', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )) );
    }
    
    /**
     *  Registers Plugin Settings
     */
    public function register_open_ai_settings() {
        register_setting( 'custom-open-ai-settings', 'api_key' );
    }

    /**
    *  Adds Settings page, and fields
    */
    public function custom_open_ai_settings_page() {
        add_options_page( 'Open IA Settings', 'Open IA Settings', 'manage_options', 'custom-open-ai', array($this, 'custom_open_ai_options') );
        $this->register_open_ai_settings();
    }

    /**
     *  Settings Page
     */
    public function custom_open_ai_options() {
        if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }
        ?>
        <div class="wrap">
        <h2>Open IA Settings</h2>
        <form method="post" action="options.php">
        <?php settings_fields( 'custom-open-ai-settings' ); ?>
        <?php do_settings_sections( 'custom-open-ai-settings' ); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">API Key</th>
                    <td><input type="text" name="api_key" value="<?php echo get_option('api_key'); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>

        </form>
        <?php
    }


    /**
	* Checks to make sure settings are set, displays error if not, run parent constructor if they are
	*/
	public function check_for_settings() {
		$api_key = get_option('api_key');

		if(empty($api_key)) {
			add_action( 'admin_notices', array($this, 'settings_missing_message') );
		}
	}

    /**
	*  Displays Error Message
	*/
	public function settings_missing_message() {
	    ?>
	    <div class="error">
	        <p><?php _e( '<b>Open IA Gutenberg:</b> Empty API key field! Please insert it on <a href="'.admin_url().'options-general.php?page=custom-open-ai">settings page</a>', 'custom-open-ai' ); ?></p>
	    </div>
	    <?php
	}

    /**
     * Send message event handler
     */
    public function open_ai_event_handler() {
    $message = $_POST['message'];
    if(substr($message, -1)!='.' || substr($message, -1)!='?' || substr($message, -1)!='!'):
        $message = $message.'.';
    endif;
    $chat_body = $_POST['chat_body'];
    $prompt = $chat_body.'\n<b>Human:</b> '.$message;

    $api_key = get_option('api_key');
    $url = 'https://api.openai.com/v1/completions';
    $data = array(
    "model"=>"text-davinci-003",
    "prompt"=>$prompt,
    "temperature"=>0.9,
    "max_tokens"=>150,
    "top_p"=>1,
    "frequency_penalty"=>0,
    "presence_penalty"=>0.6,
    "stop"=>[" Human:", " AI:"]
    );
    $headers = array(
        'Content-Type: application/json',
        'Authorization: Bearer '.$api_key
    );
    $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        $response = curl_exec($curl);
        curl_close($curl);
        
        echo json_encode(array('data' => $response));
	    die();
    }

    /**
     * Plugin deactivation
     */
    public static function custom_open_ai_deactivate() {
        unregister_setting( 'custom-open-ai-settings', 'api_key' );
        delete_option('api_key');
    }
    
}

$pinpoint = new Custom_Open_AI();