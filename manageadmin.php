<?php
function redirect($url)
{
    if (!headers_sent()) {
        header('Location: ' . $url);
        exit;
    } else {
        echo '<script type="text/javascript">';
        echo 'window.location.href="' . $url . '";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url=' . $url . '" />';
        echo '</noscript>';
        exit;
    }
}
//connect
include('connect.php');
include('include/header&aside.php');
$sql = "SELECT * FROM users WHERE type = 1";
$result = mysqli_query($conn, $sql);
$users  = mysqli_fetch_all($result, MYSQLI_ASSOC);
//print_r($users);

//Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete = "DELETE FROM users WHERE id = $id";
    $result = mysqli_query($conn, $delete);
    if (mysqli_query($conn, $delete)) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
redirect("manageadmin.php");
}
//Edit
if (isset($_GET['do'])) {
    $do = $_GET["do"];
    if ($do == "edit") {
        $id = $_GET["id"];
        $sql = "SELECT * FROM users WHERE id =$id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        print_r($row);
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $check = 1;
            $name = ($_POST["name"]);
            $email = strtolower($_POST["email"]);
            $mobile = ($_POST["mobile"]);
            if (isset($_POST["mobile"])) {
                $mobile = $_POST["mobile"];
                if (empty($_POST["mobile"])) {
                    $mobileErr = "mobile is required";
                    $check = 0;
                } elseif (!preg_match('/(?:[0-9]{14})+/s', $mobile)) {
                    $mobileErr = "mobile is not valid";
                    $check = 0;
                }
            }
            if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
                $nameError = "Only letters and white space allowed";
                $check = 0;
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailError = ("$email is not a valid email address");
                $check = 0;
            }

            if ($name == "") {
                $check = 0;
                $nameError = "The name shouldn't be empty!";
            }
            if ($email == "") {
                $check = 0;
                $emailError = "The email shouldn't be empty!";
            }
            if ($check == 1) {
                $sql2 = "UPDATE users SET username = '$name', email='$email', mobile = '$mobile' WHERE id = '$id'";
                if ($conn->query($sql2) === TRUE) {
                    echo "New record created successfully";
                } else {
                    echo "Error: " . $sql2 . "<br>" . $conn->error;
                }
                $conn->close();
                redirect("manageadmin.php");
            }
        }
    } else if ($do == "add") {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $check = 1;
            $name = ($_POST["name"]);
            $email = strtolower($_POST["email"]);
            $mobile = ($_POST["mobile"]);
            if (isset($_POST["mobile"])) {
                $mobile = $_POST["mobile"];
                if (empty($_POST["mobile"])) {
                    $mobileErr = "mobile is required";
                    $check = 0;
                } elseif (!preg_match('/(?:[0-9]{14})+/s', $mobile)) {
                    $mobileErr = "mobile is not valid";
                    $check = 0;
                }
            }
            if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
                $nameError = "Only letters and white space allowed";
                $check = 0;
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailError = ("$email is not a valid email address");
                $check = 0;
            }

            if ($name == "") {
                $check = 0;
                $nameError = "The name shouldn't be empty!";
            }
            if ($email == "") {
                $check = 0;
                $emailError = "The email shouldn't be empty!";
            }
            $pass = $_POST['password'];
            if ($check == 1) {
                $sql = "INSERT INTO `users` (`username`,`email`,`password`,`mobile`, `type`) VALUES ('$name','$email','$password','$mobile',1)";
                if (mysqli_query($conn, $sql)) {
                    echo "New record created successfully";
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                }
                $conn->close();
                redirect("manageadmin.php");
            }
        }
    }
?>

    <div class="px-5 grid  grid-cols-3  py-24 mx-auto bg-gray-100 text-gray-900 rounded-lg">
        <form class="col-start-2" enctype="multipart/form-data" method="POST" onsubmit="function1()" id="editform">
            <div>
                <span class="uppercase text-sm text-gray-600 font-bold">
                    Admin Name
                </span>
                <input name="name" class="w-full bg-gray-200 text-gray-900 mt-2 p-3 rounded-lg focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-indigo-400" type="text" required />
                <div class="error text-red-500"><?php echo @$nameError ?></div>
            </div>
            <div class="mt-8">
                <span class="uppercase text-sm text-gray-600 font-bold">
                    Email
                </span>
                <input name="email" class="w-full bg-gray-200 text-gray-900 mt-2 p-3 rounded-lg focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-indigo-400" type="text" required />
                <div class="error text-red-500"><?php echo @$emailError ?></div>
            </div>
            <div class="mt-8">
                <span class="uppercase text-sm text-gray-600 font-bold">
                    password
                </span>
                <input name="password" class="w-full bg-gray-200 text-gray-900 mt-2 p-3 rounded-lg focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-indigo-400" type="text" required />
                <div class="error text-red-500"></div>
            </div>
            <div class="mt-8">
                <span class="uppercase text-sm text-gray-600 font-bold">
                    Mobile
                </span>
                <input name="mobile" class="w-full bg-gray-200 text-gray-900 mt-2 p-3 rounded-lg focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-indigo-400" type="number" required />
                <div class="error text-red-500"><?php echo @$mobileErr ?></div>
            </div>
            <div class="mt-8">
                <button class="uppercase text-sm font-bold tracking-wide bg-indigo-500 text-gray-100 p-3 rounded-lg w-full focus:outline-none focus:shadow-outline hover:bg-indigo-700" type="submit">
                    Save
                </button>
            </div>
        </form>
    </div>
<?php
}

$conn->close();
if (!isset($_GET['do'])) {
?>
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <!-- DATA TABLE -->
                        <h3 class="title-5 m-b-35">Admins</h3>
                        <div class="table-data__tool">
                            <div class="table-data__tool-left">
                                <div class="rs-select2--light rs-select2--md">
                                    <select class="js-select2" name="property">
                                        <option selected="selected">All Properties</option>
                                        <option value="">Option 1</option>
                                        <option value="">Option 2</option>
                                    </select>
                                    <div class="dropDownSelect2"></div>
                                </div>
                                <button class="au-btn-filter">
                                    <i class="zmdi zmdi-filter-list"></i>filters</button>
                            </div>
                            <div class="table-data__tool-right">
                                <button class="au-btn au-btn-icon au-btn--green au-btn--small">
                                    <a href="manageadmin.php?do=add"><i class="zmdi zmdi-plus"></i>add admin</button></a>
                                <div class="rs-select2--dark rs-select2--sm rs-select2--dark2">
                                    <select class="js-select2" name="type">
                                        <option selected="selected">Export</option>
                                        <option value="">Option 1</option>
                                        <option value="">Option 2</option>
                                    </select>
                                    <div class="dropDownSelect2"></div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive table-responsive-data2">
                            <table class="table table-data2">
                                <thead>
                                    <tr>
                                        <th>
                                            <label class="au-checkbox">
                                                <input type="checkbox">
                                                <span class="au-checkmark"></span>
                                            </label>
                                        </th>
                                        <th>id</th>
                                        <th>username</th>
                                        <th>email</th>
                                        <th>mobile</th>
                                        <th>firstsignup</th>
                                        <th>lastsign</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $key => $user) { ?>
                                        <tr class="spacer"></tr>
                                        <tr class="tr-shadow">
                                            <td>
                                                <label class="au-checkbox">
                                                    <input type="checkbox">
                                                    <span class="au-checkmark"></span>
                                                </label>
                                            </td>
                                            <td><?php echo isset($user['id']) ? $user['id'] : ''; ?></td>
                                            <td><?php echo isset($user['username']) ? $user['username'] : ''; ?></td>
                                            <td>
                                                <span class="block-email"><?php echo isset($user['email']) ? $user['email'] : ''; ?></span>
                                            </td>
                                            <td class="desc"><?php echo isset($user['mobile']) ? $user['mobile'] : ''; ?></td>
                                            <td>
                                                <span class="status--process"><?php echo isset($user['firstsignup']) ? $user['firstsignup'] : ''; ?></span>
                                            </td>
                                            <td><?php echo isset($user['lastlogin']) ? $user['lastlogin'] : ''; ?></td>
                                            <td>
                                                <div class="table-data-feature">
                                                    <button class="item" data-toggle="tooltip" data-placement="top" title="Edit">
                                                        <a href="manageadmin.php?do=edit&id=<?php echo $user['id'] ?>"> <i class="zmdi zmdi-edit"></i> </a>
                                                    </button>
                                                    <button class="item" data-toggle="tooltip" data-placement="top" title="Delete">
                                                        <a href="manageadmin.php?delete=<?php echo $user['id'] ?>"> <i class="zmdi zmdi-delete"></i> </a>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php }  ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- END DATA TABLE -->
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}

// header('Location:manageadmin.php');
// exit;

include('include/footer.php');
?>