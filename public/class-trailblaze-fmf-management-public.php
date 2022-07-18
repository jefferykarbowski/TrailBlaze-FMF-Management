<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://trailblazecreative.com/
 * @since      1.0.0
 *
 * @package    Trailblaze_Fmf_Management
 * @subpackage Trailblaze_Fmf_Management/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Trailblaze_Fmf_Management
 * @subpackage Trailblaze_Fmf_Management/public
 * @author     TrailBlaze Creative <info@trailblazecreative.com>
 */
class Trailblaze_Fmf_Management_Public {

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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/trailblaze-fmf-management-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {


		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/trailblaze-fmf-management-public.js', array( 'jquery' ), $this->version, false );

	}



    // load_pdf_template
    public function load_pdf_template($template) {

        if ( get_query_var( 'raffle_ticket' ) == false || get_query_var( 'raffle_ticket' ) == '' ) {
            return $template;
        }

        // require_once('MPDF');

        // get the email from the url
        $email = get_query_var('raffle_ticket');

        // get the user id from the email
        $user_id = email_exists($email);

        // show error if the user does not exist
        if (!$user_id) {
            echo '<h1>User does not exist</h1>';
            return;
        }


        // get $monthName
        $monthName = date("F");

//            if(empty($user->user_CurrentRaffleTicket)) {
//                echo "Error: User with email <strong>".$email."</strong> has no corresponding raffle ticket.<br>";
//                return;
//            }

        $raffle_ticket = get_field('raffle_ticket_number', 'user_'.$user_id);


        $full_name = get_user_meta($user_id, 'first_name', true) . ' ' .
            get_user_meta($user_id, 'last_name', true);
        // $partner_site_or_school = $user->user_PartnerOrg;

        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A5']);
        /* Old A4 setting */
        //$mpdf = new \Mpdf\Mpdf();
        $mpdf->useSubstitutions=false;
        $mpdf->simpleTables = true;
        /* Important: Had to clear the output buffer first to send via server.*/
        ob_clean();


        $mpdf->WriteHTML('<body style="height: 600px; background-image: url(\'wp-content/uploads/2022/06/ticket-background.jpg\'); background-image-resize:6; background-size: 100% 100%;"><table style="width: 100%;text-align:center;"><tr><td style="height: 384px"></td></tr><tr><td><p style="font-size: 18px;">'.$monthName.' #'.$raffle_ticket.'</p></td><tr><tr><td style="height: 130px"><p style="font-size: 18px;">'.$full_name.'</p></td><tr><tr><td style="height: 38px"><p style="font-size: 18px;"></p></td><tr></table></body>');

        // full name formatted for the file name
        $full_name = str_replace(' ', '-', $full_name);

        return $mpdf->Output($full_name . '-raffle-ticket.pdf', 'I');


    }


    public function get_raffle_ticket_number($value) {

        $ticket_number = date('d-m-') . rand(10000000, 99999999);

        $users = get_users();
        foreach ($users as $user) {
            $user_id = $user->ID;
            $user_ticket_number = get_user_meta($user_id, 'raffle_ticket_number', true);
            if ($user_ticket_number == $ticket_number) {
                $ticket_number = $this->get_raffle_ticket_number();
            }
        }
        return $ticket_number;

    }


    public function custom_confirmation( $confirmation, $form, $entry, $ajax ) {
        if( $form['id'] == '1' ) {

            $confirmation = "<div id=\"pledge_keystone_eligible_success\" style=\"display: none\">
                                    <h2>Great news, your child(ren) may be eligible for $100 from Keystone Scholars.</h2>
                                </div>";
            $confirmation .= "<div id=\"pledge_redirect_notice\" style=\"display: none\">
                                    <p>Redirecting you in <span id=\"pledge_redirect_countdown\">10</span> or <a href=\"\" id=\"pledge_redirect_link\">click here</span>.</p>
                                </div>";
            $confirmation .= "<h2>Congratulations, you’ve taken the pledge!</h2>
Please let us know when you’ve opened up a savings account for your child by emailing us at info@fundmyfuturepgh.org or calling 412-360-8470. We may also be in contact with you to help you establish an account for your child/grandchild.";

        }
        return $confirmation;
    }


    public function gform_after_submission($entry, $form) {

        if( $form['id'] == '1' ) {

            $cell_phone = rgar( $entry, '7' );
            if ($cell_phone == '') {
                $cell_phone = rgar( $entry, '6' );
            }



            $currently_saving = rgar( $entry, '19' );
            if ($currently_saving === 'Yes, they have savings accounts in their name.') {
                update_field('has_a_bank_account', 1, 'user_' . $user_id);



                if ( rgar( $entry, '35.1' ) == 'true' ) {


                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => urlencode("https://app2.simpletexting.com/v1/group/contact/add?token=fedb608aaace69e3704e25715923766a&group=FMF Pledges&phone=".$cell_phone."&firstName=".rgar( $entry, '4.3' )."&lastName=".rgar( $entry, '4.6' )."&email=".rgar( $entry, '3' )."&has_bank_account=Y"),
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_HTTPHEADER => array(
                            "accept: application/json",
                            "content-type: application/x-www-form-urlencoded"
                        ),
                    ));

                    $response = curl_exec($curl);
                    if (curl_errno($curl)) {
                        $error_msg = curl_error($curl);
                    }
                    curl_close($curl);
                    echo $response;



                }

            } else {
                update_field('has_a_bank_account', 0, 'user_' . $user_id);



                if ( rgar( $entry, '36.1' ) == 'true' ) {

                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => urlencode("https://app2.simpletexting.com/v1/group/contact/add?token=fedb608aaace69e3704e25715923766a&group=FMF Pledges&phone=".$cell_phone."&firstName=".rgar( $entry, '4.3' )."&lastName=".rgar( $entry, '4.6' )."&email=".rgar( $entry, '3' )."&has_bank_account=N"),
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_HTTPHEADER => array(
                            "accept: application/json",
                            "content-type: application/x-www-form-urlencoded"
                        ),
                    ));

                    $response = curl_exec($curl);
                    if (curl_errno($curl)) {
                        $error_msg = curl_error($curl);
                    }
                    curl_close($curl);
                    echo $response;



                }

            }





        }

    }



}
