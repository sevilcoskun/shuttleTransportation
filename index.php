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
            echo "<div id = content><h1>Home Page</h1>";
        }
        else{
            echo "<div id = nav><ul style='list-style-type: none'>";
            echo "<li><a id= active href = index.php>Home</a></li>";
            echo "<li><a href = contact.php>Contact</a></li></ul></div>";
            echo "<div id = content><h1>Home Page</h1>";
            echo "<h4>In order to book a new reservation you need to Login</h4>";
            echo "<p>If you do not has an account, before login please Signup</p>";
        }

        include_once 'home.php';
    ?>
        </div>

    </body>
</html>
