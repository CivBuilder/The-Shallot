<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="adminStyle.css">
    <title>Admin Portal</title>
</head>
<body>
    <h1>Admin Portal</h1>
    <button>Manage Users (WIP)</button>
    <button>Manage Articles (WIP)</button>
    <br>
    <a href="home.php">Click to go to homepage</a>

    <br><br><br>
    <?php
    // get table of users
    $user  = 'root';                // server username
    $pass  = '';                    // server password
    $db    = 'newsApp';             // database name

    // connect to db
    $conn = mysqli_connect('localhost', $user, $pass, $db);

    // check connection
    if (!$conn) {
        echo 'Connection failed' . mysqli_connect_error();
    }


    
    session_start();
    // $_SESSION['type'] = "admin";    // debug, manually set to admin :)
    $disabled = 'disabled';
    if (isset($_SESSION['type'])) {
        if ($_SESSION['type'] == "admin") {
            $disabled = '';
        }
    }

    // print out changes messages
    if (isset($_GET['changes'])) {
        echo '<p style="color: red;">',$_GET['changes'],'</p>';
    }
    
    ?>

    <!-- MANAGE USERS -->
    <table>
    <caption>Manage Users</caption>
    <th>User ID</th>
    <th>Username</th>
    <th>User Type</th>
    <th>Remove User</th>
    <?php
    $sql = "SELECT * FROM `usertable` ORDER BY `usertable`.`type` ASC";
    $sqlResult = mysqli_query($conn, $sql);
    $allUsers = mysqli_fetch_all($sqlResult);
    
    foreach($allUsers as $row) {
        echo "<tr><td>", $row[3], "</td>";             // print user ID
        echo "<td>", $row[0], "</td>";      // print user name

        // print user type
        if ($row[2] == "user") {                // user type is set
            echo 
            '<td>
            <form method="post">
                <select ',$disabled,' name="userTypeName',$row[3],'" onchange="this.form.submit();">
                    <option value="user">user</option>
                    <option value="mod">mod</option>
                </select>
            </form>
            </td>';
        } else if ($row[2] == "mod") {      // mod type is set
            echo 
            '<td> 
            <form name="typeForm" method="post">
                <select ',$disabled,' name="userTypeName',$row[3],'" onchange="this.form.submit();">
                    <option value="mod">mod</option>
                    <option value="user">user</option>
                </select> 
            </form>
            </td>';
        } else if ($row[2] == "admin") {      // onl type is set
            echo 
            '<td>
            <select disabled name="userTypeName',$row[3],'">
                <option value="admin">admin</option>
            </select>
            </td>';
        }

        if ($row[2] != "admin") {       // can only remove non-admin
        echo '<td>
        <form method="post">
            <input type="submit" name="deleteUser',$row[3],'" value="Remove">
        </form></td>';        // press to delete
        }
        echo "</tr>";
    }
    ?>
    </table>
    <?php

    // make any changes to user types
    foreach($allUsers as $row) {
        if (isset($_POST["userTypeName$row[3]"])) {     // change user type
            // echo "typeform set";
            // sleep(2);
            $newType = $_POST["userTypeName$row[3]"];
            $sql = "UPDATE `usertable` SET `type`='$newType' WHERE `id` = $row[3]";
            mysqli_query($conn, $sql);            
            echo "<meta http-equiv='refresh' content='0'>"; // reload the page
        } 

        if (isset($_POST["deleteUser$row[3]"])) {       // delete user
            // echo "User Removed: ", $row[1];
            $sql = "DELETE FROM `usertable` WHERE `id` = $row[3]";
            mysqli_query($conn, $sql);
            // header("Location: admin.php?changes=User Removed: $row[0]" );
            echo "<meta http-equiv='refresh' content='0'>";// reload the page 
        }   
    }
    ?>

    <!-- MANAGE ARTICLES -->

    <!-- to do:
    add functions to change status and delete articles 
    add for loop to check -->

    <br><br><br>
    <table>
        <caption>Manage Articles</caption>
        <th>Article ID</th>
        <th>Title</th>
        <th>Author</th>
        <th>Category</th>
        <th>Status</th>
        <th>Decline / Remove</th>

    <?php

$disabled = '';

    $sql = "SELECT `title`,`category`,`username`,`status`,`id` FROM `newstable` ORDER BY `newstable`.`id` DESC";
    $sqlResult = mysqli_query($conn, $sql);
    $allArticles = mysqli_fetch_all($sqlResult);

    foreach($allArticles as $row) {
        echo "<tr><td>", $row[4], "</td>";   // print article ID
        echo "<td>", $row[0], "</td>";       // title
        echo "<td>", $row[2], "</td>";       // author
        echo "<td>", $row[1], "</td>";       // category
        $deleteButton = "Remove";
       
        // print user type
        if ($row[3] == "pending") {                // pending article
            echo 
            '<td>
            <form method="post">
                <select ',$disabled,' name="statusName',$row[4],'" onchange="this.form.submit();">
                    <option value="pending">pending</option>
                    <option value="approved">approved</option>
                </select>
            </form>
            </td>';
            $deleteButton = "Decline";
        } else {                                // approved article
            echo 
            '<td> 
            <form name="typeForm" method="post">
                <select disabled name="statusName',$row[4],'" onchange="this.form.submit();">
                    <option value="approved">approved</option>
                </select> 
            </form>
            </td>';
        }
        

        // press to decline
        echo '<td>                           
        <form method="post">
            <input type="submit" name="deleteArticle',$row[4],'" value="',$deleteButton,'">
        </form></td>';   
        echo "</tr>";
    }
    ?>
    </table>

    <?php
   
    // approve or delete articles
    foreach($allArticles as $row) {
        if (isset($_POST["statusName$row[4]"])) {     // approve article
            $sql = "UPDATE `newstable` SET `status`='approved' WHERE `id` = $row[4]";
            mysqli_query($conn, $sql);            
            echo "<meta http-equiv='refresh' content='0'>"; // reload the page
        } 

        if (isset($_POST["deleteArticle$row[4]"])) {       // decline or remove article
            echo "remove";
            $sql = "DELETE FROM `newstable` WHERE `id` = $row[4]";
            mysqli_query($conn, $sql);
            // header("Location: admin.php?changes=User Removed: $row[0]" );
            echo "<meta http-equiv='refresh' content='0'>";// reload the page 
        }   
    }
    ?>

</body>
</html>