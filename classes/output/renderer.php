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
 * Renderer class for mod_newmodule.
 *
 * @package    mod_newmodule
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_newmodule\output;

defined('MOODLE_INTERNAL') || die;

use plugin_renderer_base;

/**
 * Renderer class.
 *
 * @package    mod_gallery
 * @copyright  2017 SM - CV&A Consulting <mmoriana@cvaconsulting.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class renderer extends plugin_renderer_base {
    /**
     * Defer to template.
     *
     * @param gallery_assignment_page $page
     * @return string html for the page
     */
    public function render_view_page($page) {
        $data = $page->export_for_template($this);

        return parent::render_from_template('mod_newmodule/view_page', $data);
    }
}
