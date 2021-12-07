<?php 
	require_once "./header.php"; 
	require_once "./config.php";

	$sql_select = "SELECT P.name product_name, P.release_dt " . 
		  " FROM 	products P "; 

	$result = mysqli_query($conn, $sql_select);

    $result_array = Array();

    while($row = mysqli_fetch_array($result)){
         $result_array[] = array('date' => $row['release_dt'], 'name' => $row['product_name']);
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

	<p> Welcome to Hype Radar! So, what are you waiting for?</p>

    <div class="contents">
        <div id="myCalendar" style="max-width: 1000px;">
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
    	event.from = arrayProductObjects[index].date.replace(/-/g, '\/');
    	event.to   = arrayProductObjects[index].date.replace(/-/g, '\/');
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

