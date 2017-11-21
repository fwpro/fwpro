<?php

if (!$link = mysql_connect('localhost', 'root', '')) {
    echo 'Could not connect to mysql';
    exit;
}

if (!mysql_select_db('meteor_cmspro', $link)) {
    echo 'Could not select database';
    exit;
}

$sql    = 'SELECT * FROM tbl_program';
$result = mysql_query($sql, $link);

if (!$result) {
    echo "DB Error, could not query the database\n";
    echo 'MySQL Error: ' . mysql_error();
    exit;
}

while ($row = mysql_fetch_assoc($result)) {
	//print_r($row);
	echo $row['ProgramName']."<br>";
	$sql2    = 'SELECT * FROM tbl_programmajoring where idProgram='.$row["IdProgram"];
	//echo $sql2;
	$result2 = mysql_query($sql2, $link);
	$common=false;
	if (!$result2) {
		echo "<hr>";
	}else{
		
		while ($row2 = mysql_fetch_assoc($result2)) {
			echo "-".$row2["EnglishDescription"]."<br>";
			if(strtolower($row2["EnglishDescription"])=="common"){
				$common=true;
			}
		}
		if(!$common){
			echo "<b>kena buat common</b><br>";
			$sql3="INSERT INTO `tbl_programmajoring` 
			( `IdMajor`, `idProgram`, 
			`EnglishDescription`, `BahasaDescription`) VALUES
			('Common', ".$row["IdProgram"].", 'Common', 'Bersama')";
			$result3 = mysql_query($sql3, $link);
		}
		echo "<hr>";
	}
    
}

mysql_free_result($result);
mysql_free_result($result2);
//mysql_free_result($result3);

?>
