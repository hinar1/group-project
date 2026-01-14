<?php
session_start();
include("../config/db.php");

$student_id = $_SESSION['uid'];

// Fetch all applications and outcomes for this student
$sql = "
SELECT ip.title, io.final_status, io.rating_by_company, io.certificate_url
FROM internship_outcome io
JOIN application a ON io.application_id = a.application_id
JOIN internship_posting ip ON a.posting_id = ip.posting_id
WHERE a.student_id = $student_id
";

$result = mysqli_query($conn, $sql);

if(!$result){
    die("SQL Error: " . mysqli_error($conn));
}

if(mysqli_num_rows($result) > 0){
    echo "<table border='1'>
            <tr>
                <th>Internship</th>
                <th>Final Status</th>
                <th>Rating</th>
                <th>Certificate</th>
            </tr>";
    while($row = mysqli_fetch_assoc($result)){
        $cert = $row['certificate_url'] ? "<a href='../".$row['certificate_url']."'>View</a>" : "-";
        echo "<tr>
                <td>{$row['title']}</td>
                <td>{$row['final_status']}</td>
                <td>{$row['rating_by_company']}</td>
                <td>$cert</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No outcomes available yet.";
}
?>
