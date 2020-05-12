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
                echo "<li><a href = myPage.php>My Page</a></li>";
                echo "<li><a id=active href = book.php>Book Shuttle</a></li>";
                echo "<li><a href = contact.php>Contact</a></li></ul></div>";
            }
            else{
                header("Location: index.php");
                exit(0);
            }
        ?>

        <div id = "content">
            <h2>Booking a Shuttle Page</h2>
            <form action="includes/book.inc.php" method="POST">
                <h3>Where do you want to go?</h3>
                <div>
                    <select name = "d_start">
                        <?php
                        $sql = mysqli_query($conn, "SELECT p_stop FROM places ORDER BY p_stop ASC");
                        echo'<option value="">----</option>';
                        while ($row = $sql->fetch_assoc()){
                            echo '<option value"'.$row['p_stop'].'">'.$row['p_stop'].'</option>';
                        }
                        ?>
                    </select>

                    <div style="width: 100px; float: left">From:</div><input type="text" name="t_start" placeholder="Starting Destination"/><br>

                    <select name = "d_stop">
                        <?php
                        $sql = mysqli_query($conn, "SELECT p_stop FROM places ORDER BY p_stop ASC");
                        echo'<option value="">----</option>';
                        while ($row = $sql->fetch_assoc()){
                            echo "<option>" . $row['p_stop'] . "</option>";
                        }
                        ?>
                    </select>

                    <div style="width: 100px; float: left">To:</div><input type="text" name="t_stop" placeholder="Stop Destination"/>

                    <br>

                    <div style="width: 100px; float: left"># of people:</div>

                    <select name="people">
                        <option value="">----</option>
                        <?php
                        $bus_capacity = $GLOBALS['capacity'];
                        for ($i = 1; $i <= $bus_capacity; $i++) : ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <br>
                <input  style="background-color: #4d5c65; height: 25px; color: white" type = "reset" name="reset"/>
                <input  style="background-color: #4d5c65; height: 25px; color: white" type = "submit" name="submit" value="Complete Book"/>
            </form>
        </div>

    </body>
</html>
