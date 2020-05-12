<?php
    include_once 'includes/dbh.inc.php';
    include_once 'header.php';

    if(isset($_SESSION['u_id'])){
        echo "<div id = nav ><ul style='list-style-type: none'>";
        echo "<li><a href = index.php>Home</a></li>";
        echo "<li><a href = myPage.php>My Page</a></li>";
        echo "<li><a href = book.php>Book Shuttle</a></li>";
        echo "<li><a id=active href = contact.php>Contact</a></li></ul></div>";
    }
    else{
        echo "<div id = nav><ul style='list-style-type: none'>";
        echo "<li><a href = index.php>Home</a></li>";
        echo "<li><a id= active  href = contact.php>Contact</a></li></ul></div>";
    }
?>
    <div id = "content">
        <h2>Contact Page</h2>
        <p>Created By: Sevil Coskun (s250910) </p>
        <p>You can send an email to administrator</p>
            <a href="mailto:s250910@studenti.polito.it">E-mail: s250910@studenti.polito.it </a>
    </div>

<?php
    include_once 'footer.php';
?>

