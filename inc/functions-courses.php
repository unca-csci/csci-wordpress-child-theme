<?php

add_shortcode('schedule_of_classes', 'shortcode_schedule_of_classes');
function shortcode_schedule_of_classes($atts) {
    $script_ref = '<script src="' . get_stylesheet_directory_uri() . '/assets/js/class-schedule.js"></script>';
    return 
        '<select id="term">
            <option value="2023/fall/">Fall 2023</option>
            <option value="2023/spring/">Spring 2023</option>
            <option value="2022/fall/">Fall 2022</option>
            <option value="2022/summer/">Summer 2022</option>
            <option value="2022/spring/">Spring 2022</option>
            <option value="2021/fall/">Fall 2021</option>
            <option value="2021/summer/">Summer 2021</option>
            <option value="2021/spring/">Spring 2021</option>
        </select>
        <div id="course-list">
        
            Loading...
        
        </div>
        ' .  $script_ref;
}

?>