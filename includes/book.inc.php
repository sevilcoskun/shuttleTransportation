<?php
    session_start();

    if(isset($_POST['submit'])) {
        include_once 'dbh.inc.php';
        $u_id = $_SESSION['u_id'];
        $u_name = $_SESSION['u_name'];

        //Check that user has any other reservation too
        $sql_check = "SELECT u_id FROM bookings WHERE u_id ='$u_id'";
        $result_check = mysqli_query($conn, $sql_check);
        $resultCheck_check = mysqli_num_rows($result_check);
        if ($resultCheck_check > 0) {
            header("refresh:5;url=../index.php?book=error");
            echo 'ERROR! No more than 1 booking is permitted.';
            echo '<br>';
            echo 'You are redirecting to the home page...';
            exit();
        }
        else{
            $d_start = $_POST['d_start'];
            $t_start = mysqli_real_escape_string($conn, $_POST['t_start']);
            $d_stop = $_POST['d_stop'];
            $t_stop = mysqli_real_escape_string($conn, $_POST['t_stop']);
            $people = $_POST['people'];

            //Check is there any empty field or no
            if(empty($d_start) && empty($t_start)) {
                header("refresh:5;url=../book.php?book=empty");
                echo 'ERROR! Please fill necessary fields';
                echo '<br>';
                echo 'You are redirecting to the same page...';
                exit();
            }
            if(empty($d_stop) && empty($t_stop)){
                header("refresh:5;url=../book.php?book=empty");
                echo 'ERROR! Please fill necessary fields';
                echo '<br>';
                echo 'You are redirecting to the same page...';
                exit();
            }
            if(empty($people)){
                header("refresh:5;url=../book.php?book=empty");
                echo 'ERROR! Please fill necessary fields';
                echo '<br>';
                echo 'You are redirecting to the same page...';
                exit();
            }

            if(!empty($d_start) && !empty($t_start)){
                header("refresh:5;url=../book.php?book=empty");
                echo 'ERROR! Please enter only one decision for departure';
                echo '<br>';
                echo 'You are redirecting to the same page...';
                exit();
            }

            if(!empty($d_stop) && !empty($t_stop)){
                header("refresh:5;url=../book.php?book=empty");
                echo 'ERROR! Please enter only one decision for arrival';
                echo '<br>';
                echo 'You are redirecting to the same page...';
                exit();
            }

            $db_flag_start = 0;
            $db_flag_stop = 0;
            //Take only ONE selected starting and ending positions from fields
            if (empty($d_start) && empty($d_stop)) {
                $b_start_mark = $t_start;
                $b_stop_mark = $t_stop;
                $db_flag_start = 1;
                $db_flag_stop = 1;
            } else if (empty($t_start) && empty($d_stop)) {
                $b_start_mark = $d_start;
                $b_stop_mark = $t_stop;
                $db_flag_stop = 1;
            } else if (empty($d_start) && empty($t_stop)) {
                $b_start_mark = $t_start;
                $b_stop_mark = $d_stop;
                $db_flag_start = 1;
            } else {
                $b_start_mark = $d_start;
                $b_stop_mark = $d_stop;
            }

            //echo "Departure position: $b_start_mark Arrival Position: $b_stop_mark";
            //echo "<br>";

            ///////////////////////////////////////////////
            $sql_p = mysqli_query($conn, "SELECT DISTINCT p_stop FROM places, bookings WHERE b_start = p_stop OR b_stop = p_stop ORDER BY p_stop ASC");
            $places = array();
            $in = 0;
            while ($row = mysqli_fetch_array($sql_p,MYSQLI_NUM)){
                $places[$in] = $row[0];
                //echo "".$places[$in]." ";
                $in++;
            }

            //Create segment couples for keeping user id and total number of seats
            $segments = $GLOBALS['seg'];
            $passenger_per_seg = $GLOBALS['pass'];
            $i = 0;
            while($i < count($places) -1){
                array_push($segments, array('from' => $places[$i], 'to' => $places[$i+1]));
                $passenger_per_seg[$i] = 0;
                $user_per_seg[$i] = "";
                $i++;
            }

            //take into a bookings array to all bookings in format "UserID FROM TO #SEATS"
            $sql = mysqli_query($conn, "SELECT u_name, b_start, b_stop, b_people FROM bookings b, users u WHERE u.u_id = b.u_id ");
            $bookings = array();
            while ($rows = mysqli_fetch_array($sql,MYSQLI_ASSOC)){
                array_push($bookings, $rows);
            }

            //Fill each segment couples with number of total passengers with specific userIDs
            foreach($bookings as $booking) {
                //Putting a mark to starting position for segment
                foreach ($places as $place_s) {
                    if ($booking['b_start'] == $place_s) {
                        $u_start_mark = $place_s;
                        break;
                    }
                }
                //putting a mark to ending position for segment
                foreach ($places as $place_e) {
                    if ($booking['b_stop'] == $place_e) {
                        $u_stop_mark = $place_e;
                        break;
                    }
                }

                $x = 0;
                //for each user create an array for keeping each variable
                foreach ($segments as $segment) {
                    if ($u_start_mark < $segment['to'] && $u_stop_mark > $segment['from']) {
                        $passenger_per_seg[$x] += $booking['b_people'];
                    }
                    $x++;
                }
            }

            ///////////////////////////////////////////
            /*//Putting a mark to starting position for segment
            $segments =  $GLOBALS['seg'];
            $passenger_per_seg =  $GLOBALS['pass'];*/
            $bus_capacity = $GLOBALS['capacity'];
            //print_r($bus_capacity);
            //print_r($segments);
            //print_r($passenger_per_seg);

            $x = 0;
            $available = 0;

            //Visit for each segments for checking is there any available capacity for each segment
            foreach ($segments as $segment) {
                $a = $segment['from'];
                $b = $segment['to'];
                $c = $passenger_per_seg[$x];
                //echo "Segment--> $x ";
                //echo "Segment[from]: $a Segment[to]: $b #ofSeat: $c ";
                //echo "<br>";
                if ($b_start_mark < $segment['to'] && $b_stop_mark > $segment['from']) {
                    $available = $bus_capacity - $c;
                    //echo "Available seat: $available --> Wanted seat: $people";
                    if ($available < $people) {
                        header("refresh:5;url=../book.php");
                        echo "ERROR! Bus capacity is not enough please chance segment or try later.";
                        echo '<br>';
                        echo 'You are redirecting to the same page...';
                        exit();
                    }
                }
                $x++;
            }

            //Check that starting position is bigger than ending position
            if($b_start_mark > $b_stop_mark){
                header("refresh:5;url=../book.php?book=wrongposition");
                echo 'ERROR! From position should be lower than To position';
                echo '<br>';
                echo 'You are redirecting to the same page...';
                exit();
            }

            //CHECK FROM PLACE IS IN THE PLACES DATABASE
            if($db_flag_start == 1) {
                $sql_start = "SELECT * FROM places WHERE p_stop ='$b_start_mark'";
                $result_start = mysqli_query($conn, $sql_start);
                $resultCheck_start = mysqli_num_rows($result_start);
                if ($resultCheck_start > 0) {
                    //not insert
                    header("refresh:5;url=../book.php");
                    echo "ERROR! Written value has already in db";
                    echo '<br>';
                    echo 'You are redirecting to the same page...';
                    exit();
                } else {
                    //insert into places
                    $sql_insert = "INSERT INTO places(p_stop) VALUES('$b_start_mark')";
                    $result_insert = mysqli_query($conn, $sql_insert);
                }
            }

            //CHECK FROM PLACE IS IN THE PLACES DATABASE
            if($db_flag_stop == 1) {
                $sql_stop = "SELECT * FROM places WHERE p_stop ='$b_stop_mark'";
                $result_stop = mysqli_query($conn, $sql_stop);
                $resultCheck_stop = mysqli_num_rows($result_stop);
                if ($resultCheck_stop > 0) {
                    //not insert
                    header("refresh:5;url=../book.php");
                    echo "ERROR! Written value has already in db";
                    echo '<br>';
                    echo 'You are redirecting to the same page...';
                    exit();
                } else {
                    //insert into places
                    $sql_insert = "INSERT INTO places(p_stop) VALUES('$b_stop_mark')";
                    $result_insert = mysqli_query($conn, $sql_insert);
                }
            }

            $sql_booking = "INSERT INTO bookings(u_id, b_start, b_stop, b_people) VALUES('$u_id','$b_start_mark','$b_stop_mark','$people')";
            $result_booking = mysqli_query($conn, $sql_booking);

            header("refresh:5;url=../myPage.php");
            echo 'Your booking has been completed successfully';
            echo '<br>';
            echo 'You are redirecting to your personal page...';
            exit();
        }
    }
    else{
        header("refresh:5;url=../book.php");
        exit();
    }

