<?php 
	require_once "./header.php"; 
	require_once "./config.php";

	$sql_select = "SELECT E.name event_name, E.event_from_datetime, E.event_to_datetime " . 
		  " FROM 	events E "; 

	$result = mysqli_query($conn, $sql_select);

    $result_array = Array();

    while($row = mysqli_fetch_array($result)){
         $result_array[] = array('from_date' => $row['event_from_datetime'], 'to_date' => $row['event_to_datetime'],'name' => $row['event_name']);
    }
    
    $json_array = json_encode($result_array);
?>	

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

	<link rel="stylesheet" href="src/calendarjs.css" />

    <script src="src/calendarjs.js"></script>

    <title>Hype Radar!</title>
</head>

<body>
    <div style="width:1000px; margin:0 auto;">
        <h2> Welcome to Hype Radar! So, what are you waiting for?</h2>
    </div>

    <div class="wrapper">
        <div class="container-fluid" id="Top Metrics" style="max-width: 1000px;">

                <?php
                    $sql =  "SELECT COUNT(S.id) count, name " .
                            "  FROM events E, subscriptions S " .
                            " WHERE E.id = S.event_id " .
                            " GROUP BY S.id " . 
                            " ORDER BY 1 DESC"; 

                    if($result = mysqli_query($conn, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table style="max-width:350px" class="table table-bordered table-striped d-inline-block align-top">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Top Subscribed Events</th>";
                                        echo "<th>Count</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['name']  . "</td>";
                                        echo "<td>" . $row['count'] . "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                ?>

                <?php
                    $sql =  "SELECT COUNT(E.id) count, name " .
                            "  FROM events E, notifications N " .
                            " WHERE E.id = N.event_id " .
                            " GROUP BY E.id" . 
                            " ORDER BY 1 DESC"; 

                    if($result = mysqli_query($conn, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table style="max-width:350px" class="table table-bordered table-striped d-inline-block align-top">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Top Notified Events</th>";
                                        echo "<th>Count</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['name']  . "</td>";
                                        echo "<td>" . $row['count'] . "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                ?>

                <?php
                    $sql =  "SELECT COUNT(U.id) count, username " .
                            "  FROM users U, notifications N " .
                            " WHERE U.id = N.creator_user_id " .
                            " GROUP BY U.username " .
                            " ORDER BY 1 DESC"; 

                    if($result = mysqli_query($conn, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table style="max-width:350px" class="table table-bordered table-striped d-inline-block align-top">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Top Notification Creators</th>";
                                        echo "<th>Count</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['username']  . "</td>";
                                        echo "<td>" . $row['count']     . "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                ?>

                <?php
                    $sql =  "SELECT COUNT(U.id) count, username " .
                            "  FROM users U, events E " .
                            " WHERE U.id = E.creator_user_id " .
                            " GROUP BY U.username " .
                            " ORDER BY 1 DESC"; 

                    if($result = mysqli_query($conn, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table style="max-width:350px" class="table table-bordered table-striped d-inline-block align-top">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Top Event Creators</th>";
                                        echo "<th>Count</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['username']  . "</td>";
                                        echo "<td>" . $row['count']     . "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                ?>
 
         </div>
    </div>

    <div class="contents">
        <div id="myCalendar" style="max-width: 1000px; margin:0 auto;">
            <p>The JavaScript Calendar goes here...</p>
		</div>
    </div>
</body>

<script>
    var calendarInstance = new calendarJs( "myCalendar", { 
        exportEventsEnabled: true, 
        manualEditingEnabled: true, 
        showTimesInMainCalendarEvents: false,
        minimumDayHeight: 0,
        manualEditingEnabled: true,
        organizerName: "Your Name",
        organizerEmailAddress: "your@email.address",
        visibleDays: [ 0, 1, 2, 3, 4, 5, 6 ]
    } );

    document.title += " v" + calendarInstance.getVersion();
// TODO: why does the following line not work?
//        document.getElementById( "header" ).innerText += " v" + calendarInstance.getVersion();

    calendarInstance.addEvents( getEvents() );

    function turnOnEventNotifications() {
        calendarInstance.setOptions( {
            eventNotificationsEnabled: true
        } );
    }

    function setEvents() {
        calendarInstance.setEvents( getEvents() );
    }

    function removeEvent() {
        calendarInstance.removeEvent( new Date(), "Test Title 2" );
    }

    function daysInMonth( year, month ) {
        return new Date( year, month + 1, 0 ).getDate();
    }

    function setOptions() {
        calendarInstance.setOptions( {
            minimumDayHeight: 70,
            manualEditingEnabled: false,
            exportEventsEnabled: false,
            showDayNumberOrdinals: false,
            fullScreenModeEnabled: false,
            maximumEventsPerDayDisplay: 0,
            showTimelineArrowOnFullDayView: false,
            maximumEventTitleLength: 10,
            maximumEventDescriptionLength: 10,
            maximumEventLocationLength: 10,
            maximumEventGroupLength: 10,
            showDayNamesInMainDisplay: false,
            tooltipsEnabled: false,
            visibleDays: [ 0, 1, 2, 3, 4, 5, 6 ],
            allowEventScrollingOnMainDisplay: true,
            showExtraMainDisplayToolbarButtons: false,
            hideEventsWithoutGroupAssigned: true
        } );
    }

    function setSearchOptions() {
        calendarInstance.setSearchOptions( {
            left: 10,
            top: 10
        } );
    }

    function onlyDotsDisplay() {
        calendarInstance.setOptions( {
            useOnlyDotEventsForMainDisplay: true
        } );
    }

    function setCurrentDisplayDate() {
        var newDate = new Date();
        newDate.setMonth( newDate.getMonth() + 3 );

        calendarInstance.setCurrentDisplayDate( newDate );
    }

    function getEvents() {
    var arrayProductObjects = <?php echo $json_array; ?>;
    var returnEventsArray = [];
    var index = 0;
    
    while ( index < arrayProductObjects.length) {
	        var event =  {};
	// Changing the date format so the event shows on the correct day on the calendar
    	event.from = arrayProductObjects[index].from_date.replace(/-/g, '\/');
    	event.to   = arrayProductObjects[index].to_date.replace(/-/g, '\/');
	event.title = arrayProductObjects[index].name;
	returnEventsArray.push(event);
    	index++;
    }; 
        return returnEventsArray;

    }

    function getCopiedEvent() {
        var today = new Date(),
            todayPlus1Hour = new Date();

        todayPlus1Hour.setHours( today.getHours() + 1 );

        return {
            from: today,
            to: todayPlus1Hour,
            title: "Copied Event",
            description: "This is a another description of the event that has been added, so it can be shown in the pop-up dialog.",
            group: "Group 1"
        }
    }

    function addNewHoliday() {
        var today = new Date();

        var holiday1 = {
            day: today.getDate(),
            month: today.getMonth() + 1,
            title: "A New Holiday",
        };

        var holiday2 = {
            day: today.getDate(),
            month: today.getMonth() + 1,
            title: "Another Holiday",
        };
        
        calendarInstance.addHolidays( [ holiday1, holiday2 ] );
    }
</script>


<?php require_once("./footer.php"); ?>	

