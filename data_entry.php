<?php
/* SaiKumar Immadi */
// Start the session
session_start();
$server = "localhost";
$username = "myuser";
$password = "mypassword";
$dbname = "pcm";

// creates connection
$conn=mysqli_connect($server,$username,$password);

// checks connection
if(!$conn){
  die("\nConnection Failed : ".mysqli_connect_error()."\r\n\n");
  echo "<br>";
}
echo "\nConnected Successfully using username : ".$username."\r\n";
echo "<br>";

// connects to the database created above
$sql="USE ".$dbname;
if(mysqli_query($conn,$sql)){
  echo "Connected to database : ".$dbname." with user : ".$username."\r\n";
  echo "<br>";
}else{
  echo "Error connecting to database : ".$dbname." with user : ".$username." ".mysqli_error($conn)."\r\n\n";
  echo "<br>";
}
if(_SESSION["admin"]){
  echo "Admin Logged In\r\n";
}else{
  echo "Admin Not Logged In\r\n"
}
?>
<html>
<head>
<style type="text/css">
body
{
  margin: 0;
  padding: 0;
  background-color:#FFFFFF;
  text-align:center;
}
.top-bar
{
  width: 100%;
  height: auto;
  text-align: center;
  background-color:#FFF;
  border-bottom: 1px solid #000;
  margin-bottom: 20px;
}
.inside-top-bar
{
  margin-top: 5px;
  margin-bottom: 5px;
}
.link
{
  font-size: 18px;
  text-decoration: none;
  background-color: #000;
  color: #FFF;
  padding: 5px;
}
.link:hover
{
  background-color: #FCF3F3;
}
</style>

</head>
<body>
  <div class="top-bar">
    <div class="inside-top-bar">Data Entry into Database<br><br>
    </div>
  </div>
  <div style="text-align:left; border:1px solid #333333; width:450px; margin:2px auto; padding:10px;">
    <h4>Bulk Loading</h4>
    <form name="import_csv" method="post" enctype="multipart/form-data">
      CSV File: <input type="file" name="file" required/><br />
      <input type="submit" name="submitb" value="Submit" />
    </form>
    <?php
    // function for inserting each row of data into database
    function row_entry( $conn, $dbname, $rank, $previous_rank, $first_appearance, $first_rank, $machine, $computer, $site, $manufacturer, $country, $year, $segment, $total_cores, $accelerator_cores, $rmax, $rpeak, $nmax, $nhalf, $power, $power_source, $mflops_per_watt, $architecture, $processor, $processor_technology, $processor_speed, $operating_system, $operating_system_family, $accelerator, $cores_per_socket, $processor_generation, $system_model, $system_family, $interconnect_family, $interconnect, $region, $continent) {
      // insert into ranks table
      if(strlen($previous_rank)==0){
        $sql = "INSERT INTO ranks (rank,first_appearance,first_rank) VALUES ('".$rank."','".$first_appearance."','".$first_rank."')";
      }else{
        $sql = "INSERT INTO ranks (rank,previous_rank,first_appearance,first_rank) VALUES ('".$rank."','".$previous_rank."','".$first_appearance."','".$first_rank."')";
      }
      if(mysqli_query($conn,$sql)){
      }else{
        echo "Error inserting rows into database ".$dbname." ".mysqli_error($conn)."\r\n\n";
      }
      // retrieve green_rank from ranks table
      $sql="SELECT green_rank FROM ranks WHERE rank='".$rank."'";
      $result=mysqli_query($conn,$sql);
      if(mysqli_num_rows($result)==1){
        $row = mysqli_fetch_assoc($result);
        $green_rank=$row["green_rank"];
      }else{
        echo "Error inserting rows into database ".$dbname."\r\n\n";
      }
      // insert into locations table
      $sql="INSERT INTO locations (country,region,continent) VALUES ('".$country."','".$region."','".$continent."')";
      mysqli_query($conn,$sql);
      // retrieve location_id from locations table
      $sql="SELECT location_id FROM locations WHERE country='".$country."'";
      $result=mysqli_query($conn,$sql);
      if(mysqli_num_rows($result)==1){
        $row = mysqli_fetch_assoc($result);
        $location_id=$row["location_id"];
      }else{
        echo "Error inserting rows into database ".$dbname."\r\n\n";
      }
      // insert into details table
      if(strlen($machine)==0){
        $sql="INSERT INTO details (green_rank,computer,site,manufacturer,location_id,year,segment,power_source) VALUES ('".$green_rank."','".$computer."','".$site."','".$manufacturer."','".$location_id."','".$year."','".$segment."','".$power_source."')";
      }else{
        $sql="INSERT INTO details (green_rank,machine,computer,site,manufacturer,location_id,year,segment,power_source) VALUES ('".$green_rank."','".$machine."','".$computer."','".$site."','".$manufacturer."','".$location_id."','".$year."','".$segment."','".$power_source."')";
      }
      if(mysqli_query($conn,$sql)){
      }else{
        echo "Error inserting rows into database ".$dbname." ".mysqli_error($conn)."\r\n\n";
      }
      // insert into numbers table
      $sql="INSERT INTO numbers (green_rank,total_cores,accelerator_cores,rmax,rpeak,nmax,nhalf,power,mflops_per_watt) VALUES ('".$green_rank."','".$total_cores."','".$accelerator_cores."','".$rmax."','".$rpeak."','".$nmax."','".$nhalf."','".$power."','".$mflops_per_watt."')";
      if(mysqli_query($conn,$sql)){
      }else{
        echo "Error inserting rows into database ".$dbname." ".mysqli_error($conn)."\r\n\n";
      }
      // insert into geeky_details table
      if(strlen($accelerator)==0){
        $sql="INSERT INTO geeky_details (green_rank,architecture,processor,processor_technology,processor_speed,operating_system,operating_system_family,cores_per_socket,processor_generation,system_model,system_family,interconnect,interconnect_family) VALUES ('".$green_rank."','".$architecture."','".$processor."','".$processor_technology."','".$processor_speed."','".$operating_system."','".$operating_system_family."','".$cores_per_socket."','".$processor_generation."','".$system_model."','".$system_family."','".$interconnect."','".$interconnect_family."')";
      }else{
        $sql="INSERT INTO geeky_details (green_rank,architecture,processor,processor_technology,processor_speed,operating_system,operating_system_family,accelerator,cores_per_socket,processor_generation,system_model,system_family,interconnect,interconnect_family) VALUES ('".$green_rank."','".$architecture."','".$processor."','".$processor_technology."','".$processor_speed."','".$operating_system."','".$operating_system_family."','".$accelerator."','".$cores_per_socket."','".$processor_generation."','".$system_model."','".$system_family."','".$interconnect."','".$interconnect_family."')";
      }
      if(mysqli_query($conn,$sql)){
      }else{
        echo "Error inserting rows into database ".$dbname." ".mysqli_error($conn)."\r\n\n";
      }
    }

    if(isset($_POST["submitb"])){//ajax post request for bulk loading
      if(_SESSION["admin"]){
        $file = $_FILES['file']['tmp_name'];//uploaded file is temporarily stored
        $handle = fopen($file, "r");
        $c = 0;
        while(($filesop = fgetcsv($handle, ",")) !== false){
          // breaking down each row from csv file
          // $green_rank = $filesop[0] is autogenerated
          $rank = $filesop[1];
          $previous_rank = $filesop[2];
          $first_appearance= $filesop[3];
          $first_rank = $filesop[4];
          $machine = $filesop[5];
          $computer = $filesop[6];
          $site = $filesop[7];
          $manufacturer = $filesop[8];
          $country = $filesop[9];
          $year = $filesop[10];
          $segment = $filesop[11];
          $total_cores = $filesop[12];
          $accelerator_cores = $filesop[13];
          $rmax = $filesop[14];
          $rpeak = $filesop[15];
          $nmax = $filesop[16];
          $nhalf = $filesop[17];
          $power = $filesop[18];
          $power_source = $filesop[19];
          $mflops_per_watt = $filesop[20];
          $architecture = $filesop[21];
          $processor = $filesop[22];
          $processor_technology = $filesop[23];
          $processor_speed = $filesop[24];
          $operating_system = $filesop[25];
          $operating_system_family = $filesop[26];
          $accelerator = $filesop[27];
          $cores_per_socket = $filesop[28];
          $processor_generation = $filesop[29];
          $system_model = $filesop[30];
          $system_family = $filesop[31];
          $interconnect_family = $filesop[32];
          $interconnect = $filesop[33];
          $region = $filesop[34];
          $continent = $filesop[35];
          // calling the function for entering each row into database
          row_entry( $conn, $dbname, $rank, $previous_rank, $first_appearance, $first_rank, $machine, $computer, $site, $manufacturer, $country, $year, $segment, $total_cores, $accelerator_cores, $rmax, $rpeak, $nmax, $nhalf, $power, $power_source, $mflops_per_watt, $architecture, $processor, $processor_technology, $processor_speed, $operating_system, $operating_system_family, $accelerator, $cores_per_socket, $processor_generation, $system_model, $system_family, $interconnect_family, $interconnect, $region, $continent);
          $c = $c + 1;
        }
        echo "You database has been imported successfully. You have inserted ". $c ." records";
      }else{
        echo "Admin Not Logged In\r\n";
      }
    }
    ?>
  </div>
  <div style="text-align:left; border:1px solid #333333; width:450px; margin:2px auto; padding:10px;">
    <h4>Row Entry</h4>
    <form name="row_entry" method="post" enctype="multipart/form-data">
      Rank: <input type="text" name="rank" required/><br/>
      Previous Rank: <input type="text" name="previous_rank"/><br/>
      First Appearance: <input type="text" name="first_appearance" required/><br/>
      First Rank: <input type="text" name="first_rank" required/><br/>
      Machine: <input type="text" name="machine"/><br/>
      Computer: <input type="text" name="computer" required/><br/>
      Site: <input type="text" name="site" required/><br/>
      Manufacturer: <input type="text" name="manufacturer" required/><br/>
      Country: <input type="text" name="country" required/><br/>
      Year: <input type="text" name="year" required/><br/>
      Segment: <input type="text" name="segment" required/><br/>
      Total Cores: <input type="text" name="total_cores" required/><br/>
      Accelerator / Co-Processor Cores: <input type="text" name="accelerator_cores" required/><br/>
      Rmax: <input type="text" name="rmax" required/><br/>
      Rpeak: <input type="text" name="rpeak" required/><br/>
      Nmax: <input type="text" name="nmax" required/><br/>
      Nhalf: <input type="text" name="nhalf" required/><br/>
      Power: <input type="text" name="power" required/><br/>
      Power Source: <input type="text" name="power_source" required/><br/>
      Mflops/Watt: <input type="text" name="mflops_per_watt" required/><br/>
      Architecture: <input type="text" name="architecture" required/><br/>
      Processor: <input type="text" name="processor" required/><br/>
      Processor Technology: <input type="text" name="processor_technology" required/><br/>
      Processor Speed: <input type="text" name="processor_speed" required/><br/>
      Operating System: <input type="text" name="operating_system" required/><br/>
      Operating System Family: <input type="text" name="operating_system_family" required/><br/>
      Accelerator / Co-Processor: <input type="text" name="accelerator"/><br/>
      Cores Per Socket: <input type="text" name="cores_per_socket" required/><br/>
      Processor Generation: <input type="text" name="processor_generation" required/><br/>
      System Model: <input type="text" name="system_model" required/><br/>
      System Family: <input type="text" name="system_family" required/><br/>
      Interconnect: <input type="text" name="interconnect" required/><br/>
      Interconnect Family: <input type="text" name="interconnect_family" required/><br/>
      Region: <input type="text" name="region" required/><br/>
      Continent: <input type="text" name="continent" required/><br/>
      <input type="submit" name="submitr" value="Submit" />
    </form>
    <?php
    if(isset($_POST["submitr"])){
      if(_SESSION["admin"]){
        $rank = $_POST["rank"];
        $previous_rank = $_POST["previous_rank"];
        $first_appearance= $_POST["first_appearance"];
        $first_rank = $_POST["first_rank"];
        $machine = $_POST["machine"];
        $computer = $_POST["computer"];
        $site = $_POST["site"];
        $manufacturer = $_POST["manufacturer"];
        $country = $_POST["country"];
        $year = $_POST["year"];
        $segment = $_POST["segment"];
        $total_cores = $_POST["total_cores"];
        $accelerator_cores = $_POST["accelerator_cores"];
        $rmax = $_POST["rmax"];
        $rpeak = $_POST["rpeak"];
        $nmax = $_POST["nmax"];
        $nhalf = $_POST["nhalf"];
        $power = $_POST["power"];
        $power_source = $_POST["power_source"];
        $mflops_per_watt = $_POST["mflops_per_watt"];
        $architecture = $_POST["architecture"];
        $processor = $_POST["processor"];
        $processor_technology = $_POST["processor_technology"];
        $processor_speed = $_POST["processor_speed"];
        $operating_system = $_POST["operating_system"];
        $operating_system_family = $_POST["operating_system_family"];
        $accelerator = $_POST["accelerator"];
        $cores_per_socket = $_POST["cores_per_socket"];
        $processor_generation = $_POST["processor_generation"];
        $system_model = $_POST["system_model"];
        $system_family = $_POST["system_family"];
        $interconnect_family = $_POST["interconnect_family"];
        $interconnect = $_POST["interconnect"];
        $region = $_POST["region"];
        $continent = $_POST["continent"];
        row_entry( $conn, $dbname, $rank, $previous_rank, $first_appearance, $first_rank, $machine, $computer, $site, $manufacturer, $country, $year, $segment, $total_cores, $accelerator_cores, $rmax, $rpeak, $nmax, $nhalf, $power, $power_source, $mflops_per_watt, $architecture, $processor, $processor_technology, $processor_speed, $operating_system, $operating_system_family, $accelerator, $cores_per_socket, $processor_generation, $system_model, $system_family, $interconnect_family, $interconnect, $region, $continent);
        echo "Record inserted successfully";
      }else{
        echo "Admin Not Logged In\r\n";
      }
    }


    ?>
  </div>
</body>
</html>
