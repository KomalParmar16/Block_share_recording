<?php 
require_once('../../config.php');
 
$courseid = required_param('courseid', PARAM_INT);
$id = optional_param('id', 0, PARAM_INT);
$confirm = optional_param('confirm', 0, PARAM_INT);

if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('invalidcourse', 'block_share_recording', $courseid);
}

require_login($course);
require_capability('block/sharerecording:managepages', context_course::instance($courseid));
if (! $sharerecordingpage = $DB->get_record('block_share_recording', array('id' => $id))) {
    print_error('nopage', 'block_simplehtml', '', $id);
}
 
$site = get_site();
$PAGE->set_url('/course/view.php', array('id' => $id, 'courseid' => $courseid));
echo $OUTPUT->header();
if (!$confirm) {
    $optionsno = new moodle_url('/course/view.php', array('id' => $courseid));
    $optionsyes = new moodle_url('/blocks/share_recording/delete.php', array('id' => $id,
    'courseid' => $courseid, 'confirm' => 1, 'sesskey' => sesskey()));
    echo $OUTPUT->confirm(get_string('deletepage', 'block_share_recording',
    $sharerecordingpage->recordingname), $optionsyes, $optionsno);
} else {
    if (confirm_sesskey()) {
        if (!$DB->delete_records('block_share_recording', array('id' => $id))) {
            print_error('deleteerror', 'block_share_recording');
        }
    } else {
        print_error('sessionerror', 'block_share_recording');
    }
    $url = new moodle_url('/course/view.php', array('id' => $courseid));
    redirect($url);
}
echo $OUTPUT->footer();