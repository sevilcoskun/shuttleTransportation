<html>
    <body>
        <?php
            include_once 'includes/dbh.inc.php';
            include_once 'header.php';

            if(isset($_SESSION['u_id'])){
                $u_id = $_SESSION['u_id'];
                $u_name = $_SESSION['u_name'];
                echo "<div id = nav ><ul style='list-style-type: none'>";
                echo "<li><a href = index.php>Home</a></li>";
                echo "<li><a id=active href = myPage.php>My Page</a></li>";
                echo "<li><a href = book.php>Book Shuttle</a></li>";
                echo "<li><a href = contact.php>Contact</a></li></ul></div>";
            }
            else{
                header("Location: index.php");
                exit(0);
            }
        ?>

        <div id = "content">
            <h2> Hello <?= $u_name ?></h2>
            <h4>This is your personal page</h4>
            <table style="border:solid">
                <thead style="border: solid">
                <tr>
                    <th style="border: solid">Departure</th>
                    <th style="border: solid">Arrival</th>
                    <th style="border: solid">Full Seats</th>
                    <th style="border: solid">Users</th>
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
                            $user_per_seg[$x].= "[".$booking['u_name']."]";
                        }
                        $x++;
                    }
                }
                $x = 0;
                foreach ($segments as $segment){
                    if(strpos($user_per_seg[$x], $u_name) !== false){
                        echo "<tr><td style='border: solid; background-color: #c43a3a' >".$segment['from']."</td>";
                        echo "<td style='border: solid; background-color: #c43a3a'>".$segment['to']."</td>";
                        echo "<td style='border: solid'>".$passenger_per_seg[$x]."</td>";
                        echo "<td style='border: solid'>".$user_per_seg[$x]."</td></tr>";
                        $x++;
                    }
                    else{
                        // echo "Found".$passenger_per_seg[$x]."segment from: ".$segment['from']."To: ". $segment['to']."Listof users:".$user_per_seg[$x];
                        echo "<tr><td style='border: solid'>".$segment['from']."</td>";
                        echo "<td style='border: solid'>".$segment['to']."</td>";
                        echo "<td style='border: solid'>".$passenger_per_seg[$x]."</td>";
                        echo "<td style='border: solid'>".$user_per_seg[$x]."</td></tr>";
                        $x++;
                    }
                }

                ?>

                </tbody>
            </table>
            <br>
            <section style="border: solid #4d5c65 ;">
                <p>Your Booking Ticket</p>
                <?php

                $sql = "SELECT * FROM bookings WHERE u_id = '$u_id'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    echo '<table style="border:solid">';
                    echo '<thead>';
                    echo '<tr><th style="border: solid">Departure</th>';
                    echo '<th style="border: solid">Arrival</th>';
                    echo '<th style="border: solid">People</th></tr></thead>';
                    echo '<tbody style="border: solid">';
                    // output data of each row
                    while($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
                        echo '<tr><td style="border: solid">'.$row[2].'</td>';
                        echo '<td style="border: solid">'.$row[3].'</td>';
                        echo '<td style="border: solid">'.$row[4].'</td></tr>';
                    }
                    echo '</tbody></table>';
                } else {
                    echo "You do not have any reservation";
                }
                ?>

                <form action="myPage.php" method="POST">
                    <br>
                    <input style="background-color: #4d5c65; height: 25px; color: white" type = "submit" name="delete" value="Delete Booking"/>
                </form>

                <?php
                if (isset($_POST['delete'])) {
                    // get value of id that sent from address bar
                    // Delete data in mysql from row that has this id
                    $sql_req="SELECT * FROM bookings WHERE u_id='$u_id'";
                    $result_req=$conn->query($sql_req);
                    $res = $result_req->fetch_assoc();

                    if($res === null){
                        echo "You do not send DELETE request! Because you do not have any reservation";
                        goto end;
                    }
                    $sql_delete="DELETE FROM bookings WHERE u_id='$u_id'";
                    $result_delete = $conn->query($sql_delete);

                    // if successfully deleted
                    if ($result){
                        echo "Your reservation is deleted Successfully";
                        header("Location: myPage.php");

                    } else {
                        echo "Delete ERROR";
                    }
                }
                end:
                $conn->close();
                ?>
            </section>
        </div>

    </body>
</html>





