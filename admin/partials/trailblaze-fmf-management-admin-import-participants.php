<div class="wrap">
    <h1>Import Participants</h1>

    <p>The csv file must be in the following format:</p>
    <p>Parent First Name, Parent Last Name, Parent Email, Phone Number, Cell Phone, Allegheny County Residence, Race/Ethnicity, Gender, People in Household, Household Income, Currently saving, How many children are you saving for?, Where will you save?, Which one?, When will you start?, Youngest Child Birthday?, How much do you pledge to save per month?, How did you first hear about Fund My Future PGH?, Which Bank, What school are you attending?, Name of Event or Organization that hosted event?, Virtual Presentation, Specify, I want to receive raffle tickets and savings reminders, Referrer, Keystone Eligible, Entry Date
    </p>

    <form action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ) ; ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="import_participants">
        <input type="file" name="csv_file">
        <?php submit_button('Import Participants'); ?>
    </form>
</div>



<?php

// If the form has been submitted, process the file and import the data as participants
if (isset($_POST['submit'])) {
    // Get the file name and store it in a variable

    $csv_columns = ['first_name', 'last_name', 'user_email', 'phone', 'cell', 'live_in_allegheny_county', 'race', 'gender', 'how_many_in_household', 'household_income', 'currently_saving', 'how_many_children_are_you_saving_for', 'where_saving', 'savings_bank', 'savings_start_date', 'youngest_child_birthday', 'save_per_month', 'hear_about_fmf', 'hear_about_fmf_bank', 'hear_about_fmf_school', 'hear_about_fmf_event', 'hear_about_fmf_virtual_presentation', 'hear_about_fmf_other', 'receive_raffle_tickets_and_savings_reminders', 'referrer', 'keystone_eligible', 'entry_date'];

    $file = $_FILES['csv_file']['tmp_name'];
    $handle = fopen($file, "r");
    $row = 0;
    $participants = array();
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        if ($row > 0) {
            foreach ($csv_columns as $key => $column) {

                $participants[$row][$column] = $data[$key];
            }
        }
        $row++;
    }


    fclose($handle);

    // for each participant, create a user if they don't exist, and add them to the participant role


    write_log('THIS IS THE START OF MY CUSTOM DEBUG');

    write_log($participants);


    foreach ($participants as $participant) {
        $user_id = username_exists($participant['user_email']);
        if (!$user_id) {
            $user_id = wp_create_user($participant['user_email'], 'password', $participant['user_email']);
            $user = new WP_User($user_id);
            $user->set_role('participant');
        }
        // update the user meta with the data from the csv file

        // update_user_meta($user_id, 'first_name', $participant['first_name']);

        foreach($csv_columns as $meta_field) {
            update_user_meta($user_id, $meta_field, $participant[$meta_field]);
        }
    }



    echo '<p>Participants have been imported.</p>';

    // remove the file from the server
    unlink($file);

}

exit();