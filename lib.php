<?php 
/**
 * Create form and send sumbitted value to
 * given url
 *
 * @param string $sharesessionid
 * @param string $sharesessionname
 * @param string $recordinglink 
 * @param int $courseid
 * @param int $congreaid
 * @return string
 * */
defined('MOODLE_INTERNAL') || die();
function sharerecordingdetail($url, $sharesessionid, $sharesessionname, $recordinglink, $courseid, $instanceid) {
    global $DB;
    $link = $url.'?'.$recordinglink;
    $sharedbdata = new stdClass();
    $sharedbdata->recordingname = $sharesessionname;
    $sharedbdata->sessionid = $sharesessionid;
    $sharedbdata->recordinglink = $link;
    $sharedbdata->courseid = $courseid;
    $sharedbdata->instanceid = $instanceid;
    $sharedbdata->timecreated = time();
    $sharedbdata->timemodified = $sharedbdata->timecreated;
    $DB->insert_record('block_share_recording', $sharedbdata);
}
?>