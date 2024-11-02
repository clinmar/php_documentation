//prcess
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $lname = $_POST['lname'];
    $number = $_POST['number'];
    $email = $_POST['email'];
    $pwd = $_POST['pwd'];

    // Handle the image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image'];
        $imageName = $image['name'];
        $imageTemp = $image['tmp_name'];
        $imagePath = '../uploads/' . basename($imageName);

        // Move the file to the uploads directory

        if (!move_uploaded_file($imageTemp, $imagePath)) {
            die("Failed to upload image.");
        }
    } else {
        die("Image file not uploaded.");
    }

    try {
        // Connection to the database
        require_once '../connection/conn.php';
        require_once '../Controller/user_controller.php';
        require_once '../Model/user_model.php';

        // Create account
        user_add($pdo, $imagePath, $fname, $mname, $lname, $number, $email, $pwd);

        header("Location: add.php");
        exit;
    } catch (PDOException $e) {
        die("Failed to add user: " . $e->getMessage());
    }
} else {
    header("Location: user.php");
    exit;
}
?>


//controller
function user(object $pdo, string $image, string $fname, string $mname, string $lname, string $number, string $email, string $pwd)
{

    user_add($pdo, $image, $fname, $mname, $lname, $number, $email, $pwd);

}




// model 


function user_add(object $pdo, string $image, string $fname, string $mname, string $lname, string $number, string $email, string $pwd)
{
    $query = "INSERT INTO users(image, fname, mname, lname, number, email, pwd) VALUES (:image, :fname, :mname, :lname, :number, :email, :pwd)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":image", $image);
    $stmt->bindParam(":fname", $fname);
    $stmt->bindParam(":mname", $mname);
    $stmt->bindParam(":lname", $lname);
    $stmt->bindParam(":number", $number);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":pwd", $pwd);
    $stmt->execute();
}
