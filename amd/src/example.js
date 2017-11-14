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

// Define dependencies: other amd modules, from the Moodle core or any other module made by you
define([
    'core/notification'
], function(notification) {

    // Now you can use the injected dependency objects as you wish.

    // Finally, return an object with methods. If your module needs some sort of initialization logic, create an init method
    // within the returned object. Like so:
    return {
        // We will call this init method from the template were this amd module is loaded.
        // Take a look at templates/view_page.mustache.
        init: function() {
            notification.alert('Hello world!');
        }
    };
});
