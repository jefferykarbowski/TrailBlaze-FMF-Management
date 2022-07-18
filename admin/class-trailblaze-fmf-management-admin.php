<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://trailblazecreative.com/
 * @since      1.0.0
 *
 * @package    Trailblaze_Fmf_Management
 * @subpackage Trailblaze_Fmf_Management/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Trailblaze_Fmf_Management
 * @subpackage Trailblaze_Fmf_Management/admin
 * @author     TrailBlaze Creative <info@trailblazecreative.com>
 */
class Trailblaze_Fmf_Management_Admin {

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

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/trailblaze-fmf-management-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/trailblaze-fmf-management-admin.js', array( 'jquery' ), $this->version, false );

	}

    // Add a Participant Admin Menu Item
    public function add_admin_menu() {
        add_menu_page(
            'Participant Management',
            'Participant Management',
            'export',
            'manage-participants',
            array( $this, 'manage_participants_page' ),
            'dashicons-groups',
            6
        );
        // add an export Participants page
        add_submenu_page(
            'manage-participants',
            'Export Participants',
            'Export Participants',
            'export',
            'export-participants',
            array( $this, 'export_participants_page' )
        );

        // add an import Participants page
        add_submenu_page(
            'manage-participants',
            'Import Participants',
            'Import Participants',
            'export',
            'import-participants',
            array( $this, 'import_participants_page' )
        );
    }

    // Manage Participants Page
    public function manage_participants_page() {
        // redirect to /users.php?role=participant
         wp_redirect( '/wp-admin/users.php?role=participant' );
            exit;
    }


    // Add the export_participants_page content
    public function export_participants_page() {
        include_once( 'partials/trailblaze-fmf-management-admin-export-participants.php' );

        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];



        $participants = get_users(
            array(
                'role' => 'participant',
                'date_query' => array(
                    array(
                        'after' => $start_date,
                        'before' => $end_date,
                        'inclusive' => true
                    )
                )
            )
        );

        require_once( __DIR__ . '/export-participants.php');

        export_participants($participants);

    }

    // Add the import_participants_page content
    public function import_participants_page() {
        write_log('THIS IS THE START OF MY CUSTOM DEBUG');
        include_once( 'partials/trailblaze-fmf-management-admin-import-participants.php' );
        // require_once( __DIR__ . '/import-participants.php');
    }


    // populate_raffle_ticket
//    public function after_user_registration($user_id) {
//
//        $user_email = get_user_meta($user_id, 'user_email', true);
//
//        $raffle_ticket_number = substr($user_id . str_shuffle($user_id . hash('md4', $user_email)), 0, 14);
//
//        update_user_meta($user_id, 'raffle_ticket_number', $raffle_ticket_number);
//
//    }




    // setup_pdf_rewrite_rules
    public function setup_pdf_rewrite_rules() {

        add_rewrite_rule(
            'raffle_ticket/([^&]+)',
            'index.php?raffle_ticket=$matches[1]',
            'top'
        );

    }


    // add_query_vars
    public function add_query_vars($vars) {
        $vars[] = 'raffle_ticket';
        return $vars;
    }









    public function acf_add_local_field_groups() {
        acf_add_local_field_group(array(
            'key' => 'group_participant_custom_fields',
            'title' => 'Participant Custom Fields',
            'fields' => array(
                array(
                    'key' => 'field_prize',
                    'label' => 'Prize',
                    'name' => 'prize',
                    'type' => 'text',
                ),
                array(
                    'key' => 'field_drawing_month',
                    'label' => 'Drawing Month',
                    'name' => 'drawing_month',
                    'type' => 'text',
                ),
                array(
                    'key' => 'field_income',
                    'label' => 'Income',
                    'name' => 'income',
                    'type' => 'text',
                ),
                array(
                    'key' => 'field_household_members',
                    'label' => 'Household Members',
                    'name' => 'how_many_in_household',
                    'type' => 'number',
                ),
                array(
                    'key' => 'field_communication_records',
                    'label' => 'Communication Records',
                    'name' => 'communication_records',
                    'type' => 'number',
                ),
                array(
                    'key' => 'field_drawing_month',
                    'label' => 'Drawing Month',
                    'name' => 'drawing_month',
                    'type' => 'text',
                ),
                array(
                    'key' => 'field_surveys',
                    'label' => 'Surveys',
                    'name' => 'drawing_month',
                    'type' => 'true_false',
                ),
                array(
                    'key' => 'field_full',
                    'label' => 'Full?',
                    'name' => 'full',
                    'type' => 'true_false',
                ),
                array(
                    'key' => 'field_ten_dollars',
                    'label' => '$10',
                    'name' => 'ten_dollars',
                    'type' => 'true_false',
                ),
                array(
                    'key' => 'field_paperwork_complete',
                    'label' => 'Paperwork Complete?',
                    'name' => 'paperwork_complete',
                    'type' => 'true_false',
                ),
                array(
                    'key' => 'field_made_deposit_no_withdraws',
                    'label' => 'Made Deposit / No Withdraws',
                    'name' => 'made_deposit_no_withdraws',
                    'type' => 'true_false',
                ),
                array(
                    'key' => 'field_made_deposit_withdraws',
                    'label' => 'Made Deposit / Withdraws',
                    'name' => 'made_deposit_withdraws',
                    'type' => 'true_false',
                ),
                array(
                    'key' => 'field_comments',
                    'label' => 'Comments',
                    'name' => 'comments',
                    'type' => 'text',
                ),
                array(
                    'key' => 'field_propel_students',
                    'label' => 'Propel Students',
                    'name' => 'propel_students',
                    'type' => 'true_false',
                ),
                array(
                    'key' => 'field_prize_distributed',
                    'label' => 'Prize Distributed',
                    'name' => 'prize_distributed',
                    'type' => 'select',
                    'instructions' => '',
                    'choices' => array(
                        'Full' => 'Full',
                        'Partial' => 'Partial',
                    ),
                ),
                array(
                    'key' => 'field_has_a_bank_account',
                    'label' => 'Has a Bank Account',
                    'name' => 'has_a_bank_account',
                    'type' => 'true_false',
                ),
                array(
                    'key' => 'field_currently_saving',
                    'label' => 'Currently Saving',
                    'name' => 'currently_saving',
                    'type' => 'select',
                    'instructions' => '',
                    'choices' => array(
                        'Yes, they have savings accounts in their name.' => 'Yes, they have savings accounts in their name.',
                        'Yes, but they don’t have a savings account(s) in their name. I would like to open an account(s) in their name(s).' => 'Yes, but they don’t have a savings account(s) in their name. I would like to open an account(s) in their name(s).',
                        'No, but I would like to start saving for them.' => 'No, but I would like to start saving for them.',
                    ),
                ),
                array(
                    'key' => 'field_phone',
                    'label' => 'Phone',
                    'name' => 'phone',
                    'type' => 'text',
                ),
                array(
                    'key' => 'field_street_1',
                    'label' => 'Street 1',
                    'name' => 'street_1',
                    'type' => 'text',
                ),
                array(
                    'key' => 'field_street_2',
                    'label' => 'Street 2',
                    'name' => 'street_2',
                    'type' => 'text',
                ),
                array(
                    'key' => 'field_city',
                    'label' => 'City',
                    'name' => 'city',
                    'type' => 'text',
                ),
                array(
                    'key' => 'field_state',
                    'label' => 'State',
                    'name' => 'state',
                    'type' => 'text',
                ),
                array(
                    'key' => 'field_zip',
                    'label' => 'Zip',
                    'name' => 'zip',
                    'type' => 'text',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'user_role',
                        'operator' => '==',
                        'value' => 'participant',
                    ),
                ),
            ),
            'active' => true,
        ));

    }

}



