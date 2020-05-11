<?php

class Database
{
    const DB_HOST = 'localhost';
    const DB_USER = 'root';
    const DB_PASSWORD = '';
    const DB_NAME = 'league';
    public static $connection;
    public $teams, $team_id, $team_goals, $team_name, $team_points, $home, $away, $home_count, $away_count, $position, $match, $match_id, $final_match_count, $match_db_query, $winner_db, $league_winner;

    # DB connect
    public function db_connect(){
        self::$connection = mysqli_connect(self::DB_HOST, self::DB_USER, self::DB_PASSWORD, self::DB_NAME);
    }

    # Show data in table
    public function db_table_show(){
        # If two teams have same points, goals will decide who will be first 
        $query = "SELECT * FROM teams ORDER BY team_points DESC, team_goals DESC";
        $this->teams = mysqli_query(self::$connection, $query);

        while ($row = mysqli_fetch_array($this->teams)) {
            $this->team_id = $row['team_id'];
            $this->team_name = $row['team_name'];
            $this->team_points = $row['team_points'];
            $this->team_goals = $row['team_goals'];
            $this->home_count = $row['home_count'];
            $this->away_count = $row['away_count'];
            $this->home = $row['home'];
            $this->position++;

            echo "<tr>";
            echo "<td>{$this->position}</td>";
            echo "<td>{$this->team_name}</td>";
            echo "<td>{$this->team_points}</td>";
            echo "<td>{$this->team_goals}</td>";
            echo "<td>{$this->home_count}</td>";
            echo "<td>{$this->away_count}</td>";
            echo "</tr>";
        }

        # Check if season is finished 
        # Objasnjenje: 6 timova u ligi, svaki od njih ce odigrati po 5 utakmica
        # Ukupan broj utakmica je 30 (6 * 5 = 30)
        # Call method match_count_db() 
        $this->match_count_db();
        if($this->final_match_count == 30){
            echo "<div class='alert alert-success'>
            <strong>Season is finished. </strong>
            </div>";

            # Check who is the winner
            $winner = "SELECT * FROM teams ORDER BY team_points DESC, team_goals DESC";
            $this->winner_db = mysqli_query(self::$connection, $winner);
            $this->league_winner = ($row = mysqli_fetch_assoc($this->winner_db));

            echo "<div class='alert alert-success'>THE WINNER IS: 
            <strong> {$this->league_winner['team_name']} !</strong>
            </div>";
        }
    }

    # Show teams in <select>
    public function db_select_team(){ 
        $query = "SELECT * FROM teams";
        $this->teams = mysqli_query(self::$connection, $query);

        if(!$this->teams){
            die("Query failed " . mysqli_error(self::$connection));
        }

        while ($row = mysqli_fetch_array($this->teams)) {
            $this->team_id = $row['team_id'];
            $this->team_name = $row['team_name'];
            
            echo "<option value='{$this->team_id}'>{$this->team_name}</option>";
        }
    }

    # Get data from match_count column
    public function match_count_db(){
        $matches_db = "SELECT * FROM matches";
        $this->match_db_query = mysqli_query(self::$connection, $matches_db);

        while($row = mysqli_fetch_array($this->match_db_query)){
            $this->final_match_count = $row['match_count'];
        }
    } 

    # Reset season method
    public function reset_season(){
        # Objasnjenje:
        # Pokusao sam sa 'multiquery' metodom - ali iz nekog (meni nepoznatog) razloga nije radila
        # Pa sam pisao zasebne upite
        $reset_teams1 = "UPDATE teams SET 
        team_points = 0,
        team_goals = 0,
        home_count = 0,
        away_count = 0,
        home = '' 
        WHERE team_id = 1";
        $query_reset_teams1 = mysqli_query(self::$connection, $reset_teams1);

        $reset_teams2 = "UPDATE teams SET 
        team_points = 0,
        team_goals = 0,
        home_count = 0,
        away_count = 0,
        home = '' 
        WHERE team_id = 2";
        $query_reset_teams2 = mysqli_query(self::$connection, $reset_teams2);

        $reset_teams3 = "UPDATE teams SET 
        team_points = 0,
        team_goals = 0,
        home_count = 0,
        away_count = 0,
        home = '' 
        WHERE team_id = 3";
        $query_reset_teams2 = mysqli_query(self::$connection, $reset_teams3);

        $reset_teams4 = "UPDATE teams SET 
        team_points = 0,
        team_goals = 0,
        home_count = 0,
        away_count = 0,
        home = '' 
        WHERE team_id = 4";
        $query_reset_teams2 = mysqli_query(self::$connection, $reset_teams4);

        $reset_teams5 = "UPDATE teams SET 
        team_points = 0,
        team_goals = 0,
        home_count = 0,
        away_count = 0,
        home = '' 
        WHERE team_id = 5";
        $query_reset_teams2 = mysqli_query(self::$connection, $reset_teams5);

        $reset_teams6 = "UPDATE teams SET 
        team_points = 0,
        team_goals = 0,
        home_count = 0,
        away_count = 0,
        home = '' 
        WHERE team_id = 6";
        $query_reset_teams2 = mysqli_query(self::$connection, $reset_teams6);

        $reset_match_count = "UPDATE matches SET match_count = 0 WHERE match_id = 1";
        $query_reset_match_count = mysqli_query(self::$connection, $reset_match_count);
    }
}

?>