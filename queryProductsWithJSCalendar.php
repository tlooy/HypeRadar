<?php
	require_once "./header.php"; 
	require_once "./config.php";
	
	$selected_product = $selected_genre = $selected_franchise = "--";
	
	//sql used to get drop list values
	$sqlGenres 	= "SELECT * FROM genres";
	$all_genres 	= mysqli_query($conn,$sqlGenres);
	$sqlFranchises  = "SELECT * FROM franchises";
	$all_franchises = mysqli_query($conn,$sqlFranchises);
	$sqlProducts  = "SELECT * FROM products";
	$all_products = mysqli_query($conn,$sqlProducts);

	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if (isset($_POST["QueryAll"])) {
			$sql_select_list = "SELECT 	P.id, P.name product_name, P.description product_description, P.genre_id, " . 
							" P.franchise_id, G.name genre_name, F.name franchise_name, P.release_dt, P.release_dt_status, P.price " . 
					  " FROM 	products P, genres G, franchises F " . 
					  " WHERE 	P.genre_id = G.id " . 
					  " AND 	P.franchise_id = F.id ";
		}    	

		elseif (isset($_POST["QueryMine"])) {
			$sql_select_list = "SELECT 	P.id, P.name product_name, P.description product_description, P.genre_id, " . 
							" P.franchise_id, G.name genre_name, F.name franchise_name, P.release_dt, P.release_dt_status, P.price " . 
					  " FROM 	products P, genres G, franchises F, subscriptions S " . 
					  " WHERE 	P.genre_id = G.id " . 
					  " AND 	P.franchise_id = F.id " .
					  " AND 	S.product_id = P.id " .
					  " AND 	S.user_id = " . $_SESSION["id"];

		}	
		$selected_product   = $_POST["selected_product"]; 
		$selected_genre     = $_POST["selected_genre"]; 
		$selected_franchise = $_POST["selected_franchise"]; 

		if ($selected_product != "--") {
			$sql_select = $sql_select_list . " AND P.name = '" .  $selected_product . "' order by P.name";		
		} elseif ($selected_genre != "--" && $selected_franchise != "--") {
			$sql_select = $sql_select_list . " AND P.genre_id = " .  $selected_genre . " AND P.franchise_id = " . $selected_franchise . " order by P.name";		
		} elseif ($_POST["selected_genre"] != "--") {
			$sql_select = $sql_select_list . " AND P.genre_id = " .  $selected_genre . " order by P.name";		
		} elseif ($_POST["selected_franchise"] != "--") {
			$sql_select = $sql_select_list . " AND P.franchise_id = " .  $selected_franchise . " order by P.name";		
		} else {
			$sql_select = $sql_select_list;		
		}
		$result = mysqli_query($conn, $sql_select);
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Query Product</title>

	<link rel="stylesheet" href="src/calendarjs.css" />

	<script src="src/calendarjs.js"></script>
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Query Products</h2>
                    <p>Please fill this form and query to find Product records in the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                        <div class="form-group">
                            <label>Select a Product</label>
				<select name="selected_product">
				
		                	<option value="<?php echo "--"; ?>">
		                	<?php echo "--"; ?>

					<?php while ($product = mysqli_fetch_array($all_products,MYSQLI_ASSOC)):; ?>
			                	<option value="<?php echo $product["name"]; ?>">
			                	<?php echo $product["name"]; ?>
			                	</option>
					<?php endwhile; ?>
				</select>
                            <label>-- OR --</label>

                            <label>Select a Genre</label>
				<select name="selected_genre">
				
		                	<option value="<?php echo "--"; ?>">
		                	<?php echo "--"; ?>

					<?php while ($genre = mysqli_fetch_array($all_genres,MYSQLI_ASSOC)):; ?>
			                	<option value="<?php echo $genre["id"]; ?>">
			                	<?php echo $genre["name"]; ?>
			                	</option>
					<?php endwhile; ?>
				</select>

                            <label>Select a Franchise</label>
				<select name="selected_franchise">
				
		                	<option value="<?php echo "--"; ?>">
		                	<?php echo "--"; ?>

					<?php while ($franchise = mysqli_fetch_array($all_franchises,MYSQLI_ASSOC)):; ?>
			                	<option value="<?php echo $franchise["id"]; ?>">
			                	<?php echo $franchise["name"]; ?>
			                	</option>
					<?php endwhile; ?>
				</select>
                        </div>
                        <input type="submit" class="btn btn-primary" name="QueryMine" value="Query Mine">
                        <input type="submit" class="btn btn-primary" name="QueryAll"  value="Query All">
                        <a href="./index.php" class="btn btn-secondary ml-2">Cancel</a>

		        <div class="contents">
				<div id="myCalendar" style="min-width: 1000px;">
			                <p>The JavaScript Calendar goes here...</p>
				</div>
			</div>

	                <div class="row">
                	     <div class="col-md-12">
                                 <div class="mt-5 mb-3 clearfix">
                                      <h2 class="pull-left">Product List</h2>
                                 </div>
                                 <?php
                                           if(isset($result)){ // if we have results in $result then process them
                                                echo '<table class="table table-bordered table-striped">';
                                                echo "<thead>";
                                                echo "<tr>";
                                                echo "<th>Product Name</th>";
                                                echo "<th>Description</th>";
                                                echo "<th>Genre</th>";
                                                echo "<th>Franchise</th>";
                                                echo "<th>Release Date</th>";
                                                echo "<th>Release Date Status</th>";
                                                echo "<th>Price</th>";
                                                echo "<th>Comments</th>";
                                                echo "</tr>";
                                                echo "</thead>";
                                                echo "<tbody>";
                                                $result_array = Array();
                                                while($row = mysqli_fetch_array($result)){
                                                     echo "<tr>";
                                                     echo "<td>" . $row['product_name'] 		. "</td>";
                                                     echo "<td>" . $row['product_description'] 	. "</td>";
                                                     echo "<td>" . $row['genre_name'] 		. "</td>";
                                                     echo "<td>" . $row['franchise_name'] 		. "</td>";
                                                     echo "<td>" . $row['release_dt'] 		. "</td>";
                                                     echo "<td>" . $row['release_dt_status'] 	. "</td>";
                                                     echo "<td>" . $row['price'] 			. "</td>";
		                                     echo "<td>";
                                                         echo '<a href="./readProductComments.php?id=' . $row['id'] .'" class="mr-3" title="View Comments" data-toggle="tooltip"><span class="fa fa-book"></span></a>';
	                                             echo "</td>";
                                                     echo "</tr>";
						      $result_array[] = array('date' => $row['release_dt'], 'name' => $row['product_name']);
                                                }
						 $json_array = json_encode($result_array);
                                                echo "</tbody>";                            
                                                echo "</table>";
                                                // Free result set
                                                mysqli_free_result($result);
                                          } else{
                                                echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                                          }

                                ?>
                           </div>
                      </div>
                    </form>
                </div>
            </div>        
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
</html>

