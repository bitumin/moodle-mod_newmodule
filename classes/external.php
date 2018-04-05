<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This is the external API for this plugin.
 *
 * @package    mod_newmodule
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_newmodule;

defined('MOODLE_INTERNAL') || die();

use external_api as core_external_api;
use external_function_parameters;
use external_single_structure;
use external_value;
use invalid_parameter_exception;
use mod_newmodule\external\newmodule_submission_exporter;
use mod_newmodule\persistent\newmodule_assignment;

require_once($CFG->libdir . '/externallib.php');
require_once($CFG->dirroot . '/webservice/externallib.php');
require_once(__DIR__ . '/../locallib.php');

/**
 * This is the external API for this plugin.
 *
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class external extends core_external_api {
    public static function update_expected_completion_parameters() {
        return new external_function_parameters(array(
            'assignment' => new external_single_structure(array(
                'id' => new external_value(PARAM_INT),
                'completionexpecteddate' => new external_value(PARAM_ALPHANUMEXT,
                    'Completion expected date with format YYYY-MM-DD or "0" if we want to clear the current date.'),
            )),
        ));
    }

    public static function update_expected_completion($assignment) {
        $params = self::validate_parameters(self::update_expected_completion_parameters(), array('assignment' => $assignment));
        $assignmentid = (int) $params['assignment']['id'];
        $completionexpectedstring = trim($params['assignment']['completionexpecteddate']);
        $timecompletionexpected = $completionexpectedstring === '0' ? 0 : strtotime($completionexpectedstring);

        // Extra param validation.
        if ($assignmentid < 1) {
            throw new invalid_parameter_exception('Unexpected assignment id value: ' . $assignmentid);
        }
        if ($timecompletionexpected === false) {
            throw new invalid_parameter_exception('Unexpected "expected completion" date value: ' . $completionexpectedstring);
        }

        // Context validation.
        $assignment = new newmodule_assignment($assignmentid);
        $newmodule = $assignment->get_newmodule();
        $context = $newmodule->get_context();
        self::validate_context($context);

        // Update assignment expected completion timestamp.
        $assignment->set('timecompletionexpected', $timecompletionexpected);
        $updated = $assignment->update();

        return array(
            'updated' => $updated
        );
    }

    public static function update_expected_completion_returns() {
        return new external_single_structure(array(
            'updated' => new external_value(PARAM_BOOL, 'Assignment expected completion timestamp was successfully updated.'),
        ));
    }

    public static function submit_assignment_parameters() {
        return new external_function_parameters(array(
            'assignment' => new external_single_structure(array(
                'id' => new external_value(PARAM_INT),
            )),
        ));
    }

    public static function submit_assignment($assignment) {
        global $PAGE;

        // Params validation.
        $params = self::validate_parameters(self::submit_assignment_parameters(), array('assignment' => $assignment));
        $assignmentid = (int) $params['assignment']['id'];

        // Extra param validation.
        if ($assignmentid < 1) {
            throw new invalid_parameter_exception('Unexpected assignment id value: ' . $assignmentid);
        }

        // Context validation.
        $assignment = new newmodule_assignment($assignmentid);
        $newmodule = $assignment->get_newmodule();
        $context = $newmodule->get_context();
        self::validate_context($context);

        // Submit assignment.
        $course = $newmodule->get_course_record();
        $cm = $newmodule->get_cm();
        $submissiontimestamp = api::submit_assignment($context, $course, $cm, $assignment);

        // Prepare response.
        $output = $PAGE->get_renderer('core');
        $exporter = new newmodule_submission_exporter(null, ['context' => $context, 'submissiontimestamp' => $submissiontimestamp]);

        return $exporter->export($output);
    }

    public static function submit_assignment_returns() {
        return newmodule_submission_exporter::get_read_structure();
    }
}
