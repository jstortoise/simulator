<?php
    include('session.php');
    if ($login_role == 1) {
        header("location:index.php");
        die();
    }

    $result = '';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        foreach ($_POST as $key => $value) {
            //do something
            $sql = "UPDATE fields SET value='$value' WHERE type=0 AND field_id='$key'";
            if (mysqli_query($db, $sql) !== TRUE) {
                echo "An error occured";
            }
        }
        echo "Successfully updated";
    }
    $ses_sql = mysqli_query($db, "select * from fields");
?>
<body>
    <section>
        <a href="./logout.php">Logout (<?php echo $login_user;?>)</a>
    </section>

    <section id="header">
        <div class="container">
            <h1>Admin Panel</h1>
        </div>
    </section>

    <section id="content">
        <div class="container">
            <form action = "" method = "post">
                <div class="row">
                    <?php
                        while ($row = mysqli_fetch_array($ses_sql, MYSQLI_ASSOC)) {
                            if ($row['type'] == 0) {
                    ?>
                    <div class="field">
                        <span class="field-name"><?php echo $row['field_label'];?></span>
                        <input class="field-value" name="<?php echo $row['field_id'];?>" value="<?php echo $row['value'];?>">
                        <span class="unit"><?php echo $row['unit'];?></span>
                    </div>
                    <?php
                            }
                        }
                    ?>
                    <input type = "submit" value = " Submit "/><br />
                </div>
            </form>
        </div>
    </section>
</body>