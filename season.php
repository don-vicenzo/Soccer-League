<!-- Header incuded -->
<?php include 'includes/header.php'; ?>
<?php 
    include_once 'config.php';
    $db = new Database();
    $db->db_connect();
    $db->match_count_db();
    

    if(isset($_POST['finish'])){

        # Check if season is finished
        if($db->final_match_count == 30){
            echo "<div class='alert alert-danger'>
            <strong>Can not create new match</strong>
            </div>";

            echo "<div class='alert alert-success'>
            <strong>Season is finished! </strong><a href='table.php' class='alert-link'> View Table</a> to check who is the winner.
            </div>";
        }else{ 
            # Check difference between chosen teams
            if($_POST['team1'] != $_POST['team2']){
                $team1 = $_POST['team1'];
                $team2 = $_POST['team2'];
                $check_home_team = "$team1" . "$team2";

            $home_string = "$check_home_team,";


            # String to array - than check if teams already played match
            # Get the string
                $sql = "SELECT * FROM league.teams WHERE team_id = {$team1}";
                $result = mysqli_query($db::$connection, $sql);

                while ($row = mysqli_fetch_array($result)) {
                    $db_home = $row['home'];
                } 

                # String to array
                $niz_home = explode(',', $db_home);


                # Check if teams already played
                if(in_array($check_home_team, $niz_home)){
                    header ("location:season.php?error=1");
                    
                }else{
                    # Mark to database against which AWAY team HOME team played 
                    $put_string = "UPDATE teams SET home = concat(home, '{$home_string}') WHERE team_id = $team1";
                    $query_put_string = mysqli_query($db::$connection, $put_string);

                    if(!$query_put_string){
                        die("Put string query failed! " . mysqli_error($db::$connection));
                    }

                    # Who is the WINNER 
                    $home_goals = $_POST['home_goals'];
                    $away_goals = $_POST['away_goals'];

                    # if HOME winn - insert points and goals
                    if($home_goals > $away_goals){
                        
                        # HOME team - insert points and goals
                        $query = "UPDATE league.teams SET 
                                team_points = team_points + 3,
                                team_goals = team_goals + {$home_goals},
                                home_count = home_count + 1
                                WHERE team_id = {$team1} ";

                        $create_match_query = mysqli_query($db::$connection, $query);
                    
                        # AWAY team - insert goals
                        $query2 =   "UPDATE league.teams SET 
                        team_goals = team_goals + {$away_goals},
                        away_count = away_count + 1
                        WHERE team_id = {$team2} ";

                        $create_match_query2 = mysqli_query($db::$connection, $query2);

                        # Query check
                        if(!$create_match_query || !$create_match_query2){
                            die("QUERY FAILDED" . mysqli_error($db::$connection));
                        }else{
                            header ("location:season.php?success=1");
                        }

                        # Enter data in 'match' table (match_count increase for 1)
                        $match_count = "UPDATE league.matches SET 
                                match_count = match_count + 1
                                WHERE match_id = 1
                                ";

                        $match_count_query = mysqli_query($db::$connection, $match_count);
                        

                    }elseif ($away_goals > $home_goals) {

                        # if AWAY team win - insert points and goals
                    
                        # AWAY team - insert points and goals
                        $query = "UPDATE league.teams SET 
                                team_points = team_points + 3,
                                team_goals = team_goals + {$away_goals},
                                away_count = away_count + 1
                                WHERE team_id = {$team2} ";

                        $create_match_query = mysqli_query($db::$connection, $query);
                    
                        # HOME team - insert goals
                        $query2 =   "UPDATE league.teams SET 
                        team_goals = team_goals + {$home_goals},
                        home_count = home_count + 1
                        WHERE team_id = {$team1} ";

                        $create_match_query2 = mysqli_query($db::$connection, $query2);

                        # Query check
                        if(!$create_match_query || !$create_match_query2){
                            die("QUERY FAILDED" . mysqli_error($db::$connection));
                        }else{
                            header ("location:season.php?success=1");
                        }

                        # Enter data in 'match' table (match_count increase for 1)
                        $match_count = "UPDATE league.matches SET 
                                match_count = match_count + 1
                                WHERE match_id = 1
                                ";

                        $match_count_query = mysqli_query($db::$connection, $match_count);
                        
                    }else{
                        # if its DRAW

                        # HOME team - insert points and goals
                        $query = "UPDATE league.teams SET 
                                team_points = team_points + 1,
                                team_goals = team_goals + {$home_goals},
                                home_count = home_count + 1
                                WHERE team_id = {$team1} ";

                        $create_match_query = mysqli_query($db::$connection, $query);
                    
                        # AWAY team - insert points and goals
                        $query2 =   "UPDATE league.teams SET 
                                    team_points = team_points + 1,
                                    team_goals = team_goals + {$away_goals},
                                    away_count = away_count + 1
                                    WHERE team_id = {$team2} ";

                        $create_match_query2 = mysqli_query($db::$connection, $query2);

                        # Query check
                        if(!$create_match_query || !$create_match_query2){
                            die("QUERY FAILDED" . mysqli_error($db::$connection));
                        }else{
                            header ("location:season.php?success=1");
                        }

                        # Enter data in 'match' table (match_count increase for 1)
                        $match_count = "UPDATE league.matches SET 
                                match_count = match_count + 1
                                WHERE match_id = 1
                                ";

                        $match_count_query = mysqli_query($db::$connection, $match_count);
                    }
                }
                
            }else{
                echo "<div class='alert alert-danger'>
                <strong>Choose two different teams</strong>
                </div>";
            }
        }
    }


# Message to choose another teams
if(isset($_GET['error'])==true) {
    echo "<div class='alert alert-danger'>
            <strong>These two teams have already played, try with another teams.</strong>
            </div>";
}

# Message for successful match created
if(isset($_GET['success'])==true) {
    echo "<div class='alert alert-success'>
            <strong>Match successful created!  </strong><a href='table.php' class='alert-link'> View Table</a>.
        </div>";
}



?>

    
    <!-- Begin of main -->
    <main class="seasonMain">

        <div class="p-2 m-4">
        <!-- Begin of part1 div -->
        <div class="part1">     
            <h2 class="display-4">Choose teams for the first game</h2>

            <form action="" method="post" enctype="multipart/form-data">

                <!-- Home team select -->
                <div class="form-group">
                    <label for="team1" class="lead">Select HOME team:</label>
                    <select class="form-control form-control-lg formCenter" name="team1" id="team1">
                        
                        <?php 
                            $db->db_select_team();
                        ?>

                    </select>
                </div>


                <!-- Away team select -->
                <div class="form-group">
                    <label for="team2" class="lead">Select AWAY team:</label>
                    <select class="form-control form-control-lg formCenter" name="team2" id="team2">
                        
                        <?php 
                            $db->db_select_team();
                        ?>

                    </select>
                </div>

            </div> 
            <!-- End of part1 div -->


            <!-- Begin of part2 div -->
            <div class="part2 mt-4">
                <h2 class="display-4">Enter the result of the match</h2>
                <div class="formCenter"> 
                    <div class="form-row p-2 m-2">
                        <div class="col">
                        <input type="number" name="home_goals" class="form-control" required placeholder="HOME team goals">
                        </div>
                        <div class="col">
                        <input type="number" name="away_goals" class="form-control" required placeholder="AWAY team goals">
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of part2 div -->

            <!-- Submit button -->
            <div class="centerButton">
                <button type="submit" name="finish" class="btn btn-lg m-4 btn-success">Finish match</button>
            </div>

            </form>
        </div>

    </main>
    <!-- End of main -->

<!-- Footer incuded -->
<?php include 'includes/footer.php'; ?>