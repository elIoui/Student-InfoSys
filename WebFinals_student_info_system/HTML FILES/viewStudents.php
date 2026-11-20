<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);




// CONNECT TO DATABASE
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "student_info_system"; // <-- Make sure this is your actual database name

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
// echo "Connected successfully!"; // Optional: Remove this after testing

// FETCH all students
// $sql = "SELECT * FROM students ORDER BY last_name ASC";
// $result = $conn->query($sql);


        $search = "";

        if (isset($_GET['search'])) {
            $search = $_GET['search'];
        }

        if ($search != "") {
            $sql = "SELECT * FROM students 
                    WHERE student_id LIKE '%$search%' 
                    OR first_name LIKE '%$search%' 
                    OR middle_name LIKE '%$search%' 
                    OR last_name LIKE '%$search%' 
                    OR course LIKE '%$search%' 
                    OR section LIKE '%$search%'";
        } else {
            $sql = "SELECT * FROM students";
        }

        $result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>View Students</title>

    <!-- Google Icons and Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- External CSS -->
    <link rel="stylesheet" href="../CSS_FILES/headerFooterNavMain.css">
    <link rel="stylesheet" href="../CSS_FILES/viewStudents.css">
</head>

<body>
        <?php
        if (isset($_GET['deleted']) && $_GET['deleted'] === 'success') {
            echo "<div class='successMessage'>Student deleted successfully!</div>";
        }
        ?>

<!-- HEADER -->
<header>
    <div class="headerContent">
        <img src="../MEDIAS/formifyLogo.png" alt="Logo" class="logo">
        <span>Formify: Student Information System</span>
    </div>
</header>

<div class="container">

    <!-- SIDEBAR -->
    <nav class="sidebar">
        <div class="navTop">
            <ul>
                <li><a href="dashboard.html"><span class="icon">home</span> Dashboard</a></li>
                <li><a href="addStudent.html"><span class="icon">person_add</span> Add Student</a></li>
                <li><a href="viewStudents.php" class="activeNav"><span class="icon">groups</span> View Student</a></li>
                <li><a href="updateInfo.html"><span class="icon">edit</span> Update Info</a></li>
                <li><a href="settings.html"><span class="icon">settings</span> Settings</a></li>
            </ul>
        </div>

        <div class="navBottom">
            <ul>
                <hr>
                <li><a href="index.html"><span class="icon">logout</span> Log Out</a></li>
            </ul>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <div class="main">
        <h3>View Students</h3>
        <p>List of all registered students. Use the search bar below to quickly find a student.</p>

        <!-- Search Bar -->
        <div class="searchContainer">
            <form method="GET" action="viewStudents.php" class="searchForm">
                <input type="text" 
                    name="search"
                    class="searchInput" 
                    placeholder="Search student id, name, course, section..." 
                    value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">

                <button class="searchBtn" type="submit">Search</button>
            </form>
        </div>





        <!-- STUDENT TABLE -->
        <div class="viewTableContainer">
            <table class="studentTable">
                <thead>
                <tr>
                    <th>Photo</th>
                    <th>Student ID</th>
                    <th>Full Name</th>
                    <th>Course</th>
                    <th>Year</th>
                    <th>Section</th>
                    <th>Status</th>
                    <th>Phone</th>
                    <th>Personal Email</th>
                    <th>School Email</th>
                    <th>Actions</th>
                </tr>
                </thead>

                <tbody>
                <?php
                if ($result && $result->num_rows > 0) {

                    while ($row = $result->fetch_assoc()) {
                        $fullName = $row['last_name'] . ", " . $row['first_name'] . " " . $row['middle_name'];
                        echo "<tr>";
                        echo "<td><img src='../uploads/" . $row['photo'] . "' width='50' height='50' style='border-radius:5px;'></td>";
                        echo "<td>" . $row['student_id'] . "</td>";
                        echo "<td>" . $fullName . "</td>";
                        echo "<td>" . $row['course'] . "</td>";
                        echo "<td>" . $row['year_level'] . "</td>";
                        echo "<td>" . $row['section'] . "</td>";
                        echo "<td>" . $row['student_status'] . "</td>";
                        echo "<td>" . $row['phone_number'] . "</td>";
                        echo "<td>" . $row['personal_email'] . "</td>";
                        echo "<td>" . $row['school_email'] . "</td>";

                        echo "<td>
                            <div class='actionContainer'>
                                <a href='updateInfo.html?id=" . $row['id'] . "' class='btnEdit'>
                                    <span class='material-symbols-outlined'>edit</span>
                                </a>

                                <a href='../php/deleteStudent.php?id=" . $row['id'] . "' 
                                class='btnDelete'
                                onclick='return confirm(\"Are you sure you want to delete this student?\")'>
                                    <span class='material-symbols-outlined'>delete</span>
                                </a>
                            </div>
                        </td>";



                        echo "</tr>";
                    }

                } else {
                    echo "<tr><td colspan='11' style='text-align:center;'>No students found.</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<footer class="footer">
    © 2025 Formify. All rights reserved. | SIS v1.0 | <a href="aboutUs.html">About Us</a>
</footer>

<script src="../JS FILES/activeNav.js"></script>

<script>
function searchStudent() {
    const searchValue = document.getElementById("searchInput").value.toLowerCase();
    const rows = document.querySelectorAll(".studentTable tbody tr");

    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(searchValue) ? "" : "none";
    });
}
</script>

</body>
</html>
