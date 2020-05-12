<?php
    include_once 'includes/dbh.inc.php';
?>
        <table style="border:solid">
            <thead style="border: solid">
            <tr>
                <th style="border: solid">Departure</th>
                <th style="border: solid">Arrival</th>
                <th style="border: solid">Full Seats</th>
            </tr>
            </thead>
            <tbody style="border: solid">

        <?php
            //take into all places array to all places in ordered
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

            $user_per_seg = array();
            $i = 0;
            while($i < count($places) -1){
                array_push($segments, array('from' => $places[$i], 'to' => $places[$i+1]));
                $passenger_per_seg[$i] = 0;
                $user_per_seg[$i] = "";
                //print_r($segments[$i]);
                //echo '<br>';
                $i++;
            }

            //take into a bookings array to all bookings in format "UserID FROM TO #SEATS"
            $sql = mysqli_query($conn, "SELECT u_name, b_start, b_stop, b_people FROM bookings b, users u WHERE u.u_id = b.u_id ");
            $bookings = array();
            while ($rows = mysqli_fetch_array($sql,MYSQLI_ASSOC)){
                array_push($bookings, $rows);
                //echo "User".$rows['u_id']." --> From:".$rows['b_start']." To: ".$rows['b_stop']." #ofSeat: ".$rows['b_people']."";
                //echo"<br>";
            }

            //Fill each segment couples with number of total passengers with specific userIDs
            foreach($bookings as $booking) {
                //Putting a mark to starting position for segment
                foreach ($places as $place_s) {
                    if ($booking['b_start'] == $place_s) {
                        $b_start_mark = $place_s;
                        break;
                    }
                }
                //putting a mark to ending position for segment
                foreach ($places as $place_e) {
                    if ($booking['b_stop'] == $place_e) {
                        $b_stop_mark = $place_e;
                        break;
                    }
                }

                $x = 0;
                //for each user create an array for keeping each variable
                foreach ($segments as $segment) {
                    if ($b_start_mark < $segment['to'] && $b_stop_mark > $segment['from']) {
                        $passenger_per_seg[$x] += $booking['b_people'];
                        $user_per_seg[$x].= $booking['u_name'].", ";
                    }
                    $x++;
                }

            }
            $x = 0;
            foreach ($segments as $segment){
               // echo "Found".$passenger_per_seg[$x]."segment from: ".$segment['from']."To: ". $segment['to']."Listof users:".$user_per_seg[$x];
                echo "<tr><td style='border: solid'>".$segment['from']."</td>";
                echo "<td style='border: solid'>".$segment['to']."</td>";
                echo "<td style='border: solid'>".$passenger_per_seg[$x]."</td></tr>";
                $x++;
            }

            $conn->close();

            exit();
        ?>
            </tbody>
        </table>