<?php
/*
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script>
    function cleanSelection(){
        $("#item1").css("background-color","darkolivegreen");
        $("#item2").css("background-color","darkolivegreen");
        $("#item3").css("background-color","darkolivegreen");
        $("#item4").css("background-color","darkolivegreen");
    }

</script>

<div id = "nav">
    <h1>Pages</h1>
    <ul style="list-style-type: none">
        <div id="item1" onclick="cleanSelection(); makeSelection(item1);"><li><a href = index.php?select=item1>Home</a></li></div>
        <div id="item2" onclick="cleanSelection(); makeSelection(item2);"><li><a href = myPage.php>My Page</a></li></div>
        <div id="item3" onclick="cleanSelection(); makeSelection(item3);"><li><a href = book.php>BookShuttle</a></li></div>
        <div id="item4" onclick="cleanSelection(); makeSelection(item4);"><li><a href = contact.php>Contact</a></li></div>
    </ul>
</div>
*/
?>

<div id = "nav">
    <ul style="list-style-type: none">
       <li><a id="active" href = index.php>Home</a></li>
       <li><a href = myPage.php>My Page</a></li>
       <li><a href = book.php>BookShuttle</a></li>
       <li><a href = contact.php>Contact</a></li>
    </ul>
</div>


