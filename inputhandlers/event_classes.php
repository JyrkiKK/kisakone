<?php
/*
 * Suomen Frisbeeliitto Kisakone
 * Copyright 2009-2010 Kisakone projektiryhm§
 *
 * Changing classes of signups for an event
 * 
 * --
 *
 * This file is part of Kisakone.
 * Kisakone is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Kisakone is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with Kisakone.  If not, see <http://www.gnu.org/licenses/>.
 * */

/**
 * Processes the login form
 * @return Nothing or Error object on error
 */
function processForm() {
    $event = GetEventDetails($_GET['id']);
    
    if ($event->resultsLocked) return Error::AccessDenied();
    
    if (!IsAdmin() && $event->management != 'td') {
        return Error::AccessDenied('eventclasses');
    }
    if (@$_POST['cancel']) {
        header("Location: " . url_smarty(array('page' => 'manageevent', 'id' => $_GET['id']), $_GET));
        die();
    }
    $failures = false;
    
    foreach ($_POST as $key => $value) {
        if (substr($key, 0, 6) == 'class_') {
            $pid = substr($key, 6);
            $init = $_POST["init_$pid"];
            
            if ($value == "removeplayer") {
                if (!RemovePlayerFromEvent($event->id, $pid)) {
                    $failures = true;
                }
            }
            
            if ($init != $value)  {
                SetParticipantClass($event->id, $pid, $value);
            }
            
            
        } 
    }
    
    if ($failures) return true;

    require_once('inputhandlers/support/event_edit_notification.php');
    return input_EditNotification();
    
    
   
}

?>