<?php
$servername = "localhost";
$username = "vlog_user";
$password = "root";
$dbname = "vlogging_site";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle different requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if action is set and execute corresponding function
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                createBlog($conn);
                break;
            case 'update':
                updateBlog($conn);
                break;
            case 'delete':
                deleteBlog($conn);
                break;
            default:
                echo "Invalid action.";
                break;
        }
    } else {
        echo "Action parameter is missing.";
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if action is read and call readBlogs function
    if (isset($_GET['action']) && $_GET['action'] === 'read') {
        readBlogs($conn);
    } else {
        serveFrontend();
    }
} else {
    echo "Unsupported request method.";
}

// Function to create a new blog
function createBlog($conn) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    // SQL injection vulnerability fix: Use prepared statements
    $stmt = $conn->prepare("INSERT INTO blogs (title, content) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $content);

    if ($stmt->execute()) {
        echo "New blog created successfully";
    } else {
        echo "Error: " . $conn->error;
    }
    $stmt->close();
}

// Function to read all blogs
function readBlogs($conn) {
    $sql = "SELECT * FROM blogs";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $blogs = array();
        while ($row = $result->fetch_assoc()) {
            $blogs[] = $row;
        }
        echo json_encode($blogs);
    } else {
        echo "No blogs found.";
    }
}

// Function to update an existing blog
function updateBlog($conn) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    // SQL injection vulnerability fix: Use prepared statements
    $stmt = $conn->prepare("UPDATE blogs SET title=?, content=? WHERE id=?");
    $stmt->bind_param("ssi", $title, $content, $id);

    if ($stmt->execute()) {
        echo "Blog updated successfully";
    } else {
        echo "Error: " . $conn->error;
    }
    $stmt->close();
}

// Function to delete a blog
function deleteBlog($conn) {
    $id = $_POST['id'];

    // SQL injection vulnerability fix: Use prepared statements
    $stmt = $conn->prepare("DELETE FROM blogs WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Blog deleted successfully";
    } else {
        echo "Error: " . $conn->error;
    }
    $stmt->close();
}

// Function to serve the frontend HTML file
function serveFrontend() {
    $filePath = __DIR__ . '/frontend/index.html';
    if (file_exists($filePath)) {
        readfile($filePath);
    } else {
        echo "Frontend file not found.";
    }
}

$conn->close();
?>
