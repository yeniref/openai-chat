<?php

class WP_Openai_Class
{

    public function __construct()
    {
        add_shortcode('openai-chat', array($this, 'shortcode_callback'));
        add_action('wp_footer', array($this, 'footer_openai_form_fonk'));
        add_action('wp_ajax_openai_form_ajax', array($this, 'openai_form_veriler'));
        add_action('wp_ajax_nopriv_openai_form_ajax', array($this, 'openai_form_veriler'));
    }


    public static function shortcode_callback()
    {
        ob_start();
    ?>
        <div id="chat-log">
        <p class="bot-mesaj"><img src="<?php echo plugin_dir_url( __FILE__  ).'images/bot.png'; ?>"> Merhaba nasıl yardımcı olabilirim ?</p>
        </div>

        <form id="chat-form">
            <input type="text" class="mesaj-input" name="message" placeholder="Mesajınızı buraya yazın...">
            <?php $rand = rand(0, 1000000); ?>
            <input type="hidden" name="kontrol" value="<?php echo $rand; ?>" style="display: none; visibility: hidden; opacity: 0;">
            <input type="hidden" name="action" value="openai_form_ajax" style="display: none; visibility: hidden; opacity: 0;">
            <input type="hidden" id="guvenlik" name="guvenlik" value="<?php echo wp_create_nonce($rand); ?>" style="display: none; visibility: hidden; opacity: 0;">
            <input type="hidden" id="base" name="base" value="<?php echo base64_encode(base64_encode(admin_url("admin-ajax.php"))); ?>" style="display: none; visibility: hidden; opacity: 0;">
            <button type="submit" class="mesaj-button">Gönder</button>
        </form>
        <?php
        return ob_get_clean();
    }

    public function footer_openai_form_fonk()
    {
        global $post;
        if (has_shortcode($post->post_content, 'openai-chat')) {
        ?>
            <link href="<?php echo plugin_dir_url( __FILE__  ).'css/style.css'; ?>" rel="stylesheet">
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="<?php echo plugin_dir_url( __FILE__  ).'js/form.js'; ?>"></script>

<?php
        }
    }


    public function openai_form_veriler()
    {

        $kontrol = $_POST['kontrol'];
        check_ajax_referer($kontrol, 'guvenlik');
        $nonce = $_POST['guvenlik'];


        if (!empty($nonce) && wp_verify_nonce($nonce, $kontrol)) {

            $api_key = get_option( 'openai_key', '' );
            $model_engine = 'text-davinci-003';

            $message = $_POST['message'];

            $url = "https://api.openai.com/v1/engines/$model_engine/completions";
            $data = array(
                "prompt" => $message,
                "max_tokens" => (int)get_option( 'openai_limit', '' ),
                "temperature" => 0.7,
            );

            $headers = array(
                'Content-Type: application/json',
                "Authorization: Bearer $api_key",
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                echo curl_error($ch);
            } else {
                $decoded_response = json_decode($response, true)['choices'][0]['text'];
                echo $decoded_response;
            }

            die;
        } else {
            die('Güvenlik Hatası ...');
        }
    }
}

new WP_Openai_Class();
