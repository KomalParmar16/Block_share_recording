<?php

defined('MOODLE_INTERNAL') || die();

$plugin->version = 2020090300;  // YYYYMMDDHH (year, month, day, 24-hr time)
$plugin->requires = 2010112400; // YYYYMMDDHH (This is the release version for Moodle 2.0)
$plugin->component = 'block_share_recording';
$plugin->dependencies = [
    'mod_congrea' => ANY_VERSION
];