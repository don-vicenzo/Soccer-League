<!-- Header incuded -->
<?php include 'includes/header.php'; ?>
<?php include_once 'config.php';
$db = new Database();
$db->db_connect();

?>
<?php

if(isset($_POST['reset'])){
    $db->reset_season();
}
?>
        <main class="tableMain p-2 m-1">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Position</th>
                        <th>Team</th>
                        <th>Points</th>
                        <th>Goal score</th>
                        <th>Home</th>
                        <th>Away</th>
                    </tr>
                </thead>

                <tbody>

                    <?php
                    $db->db_table_show();
                    ?>
                   
                </tbody>
            </table>

            <div class="rightButton">

                <?php 
                #If season is finished - user cannot create new match
                if($db->final_match_count < 30){
                ?>
                    <a type="button" href="season.php" class="btn btn-lg m-1 btn-primary">Create new match</a>
                <?php
                }
                ?>
                
                <form action="" method="post">
                <button type="submit" name="reset" class="btn btn-lg m-1 btn-danger"  onClick="javascript: return confirm('Are you sure want to reset the season?');">Reset season</button>
                </form>
            </div>
        </main>    

<!-- Footer incuded -->
<?php include 'includes/footer.php'; ?>