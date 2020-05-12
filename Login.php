<html>
    <body>
        <?php
            include_once 'includes/dbh.inc.php';
            include_once 'header.php';

            if(isset($_SESSION['u_id'])){
                echo "<div id = nav ><ul style='list-style-type: none'>";
                echo "<li><a id=active href = index.php>Home</a></li>";
                echo "<li><a href = myPage.php>My Page</a></li>";
                echo "<li><a href = book.php>Book Shuttle</a></li>";
                echo "<li><a href = contact.php>Contact</a></li></ul></div>";
            }
            else{
                echo "<div id = nav><ul style='list-style-type: none'>";
                echo "<li><a id= active href = index.php>Home</a></li>";
                echo "<li><a href = contact.php>Contact</a></li></ul></div>";
            }
        ?>
            <div id = "content">
                <h2>Login Page</h2>
                <form action="includes/login.inc.php" method="POST">
                    <p>
                    <div style="width: 80px; float: left">E-mail</div><input type="email" name="u_email" placeholder="E-mail"/><br>
                    <div style="width: 80px; float: left">Password</div><input type="password" name="u_password" placeholder="Password"/>
                    <p></p>
                    <input type = "reset" name="reset" value="Cancel"/>
                    <input type = "submit" name="submit" value="Login"/>
                </form>
            </div>
    </body>
</html>
