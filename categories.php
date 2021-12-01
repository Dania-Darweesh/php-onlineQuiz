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
$sql = "SELECT * FROM categories ";
$result = mysqli_query($conn, $sql);
$categories  = mysqli_fetch_all($result, MYSQLI_ASSOC);
//print_r($categories);

//Delete
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $delete = "DELETE FROM categories WHERE id = $id";
  $result = mysqli_query($conn, $delete);
  if (mysqli_query($conn, $delete)) {
    echo "Record deleted successfully";
  } else {
    echo "Error deleting record: " . mysqli_error($conn);
  }
  redirect("categories.php");
}
//Edit
$name = "";
$description = "";
$image      = "";
$imageError = "";
if (isset($_GET['do'])) {
  $do = $_GET["do"];
  if ($do == "edit") {
    $id = $_GET["id"];
    $sql = "SELECT * FROM categories WHERE id =$id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $check = 1;
      $name = $_POST["name"];
      $description = $_POST["description"];
      $image = $_FILES["image"];
      // Check file size
      if ($image["size"] > 500000 || $image["size"] == 0) {
        $imageError = "Sorry, your file is too large.";
        $check     = 0;
      } // Check if image file is a actual image or fake image
      $check_if_image = getimagesize($image["tmp_name"]);
      if ($check_if_image == false) {
        $imageError = "File is not an image.";
        $check = 0;
      }
      if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
        $nameError = "Only letters and white space allowed";
        $check = 0;
      }
      if ($name == "") {
        $check = 0;
        $nameError = "The name shouldn't be empty!";
      }
      if ($description == "") {
        $check = 0;
        $descriptionError = "The description shouldn't be empty!";
      }
      if ($check == 1) {
        $image_folder = "uploads/";
        $target_file = $image_folder . uniqid() . basename($image["name"]);
        move_uploaded_file($image["tmp_name"], $target_file);
        $sql2 = "UPDATE categories SET name = '$name', description='$description',categorie_image='$target_file' WHERE id = '$id'";
        if ($conn->query($sql2) === TRUE) {
          echo "New record created successfully";
        } else {
          echo "Error: " . $sql2 . "<br>" . $conn->error;
        }
        $conn->close();
        redirect("categories.php");
      }
    }
  }
  // Add
  else if ($do == "add") {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $check = 1;
      $name = ($_POST["name"]);
      $description = $_POST["description"];
      $image = $_FILES["image"];
      // Check file size
      if ($image["size"] > 500000 || $image["size"] == 0) {
        $imageError = "Sorry, your file is too large.";
        $check     = 0;
      } // Check if image file is a actual image or fake image
      $check_if_image = getimagesize($image["tmp_name"]);
      if ($check_if_image == false) {
        $imageError = "File is not an image.";
        $check = 0;
      }
      if ($name == "") {
        $check = 0;
        $nameError = "The name shouldn't be empty!";
      }
      if ($description == "") {
        $check = 0;
        $descriptionError = "The description shouldn't be empty!";
      }
      if ($check == 1) {
        $image_folder = "uploads/";
        $target_file = $image_folder . uniqid() . basename($image["name"]);
        move_uploaded_file($image["tmp_name"], $target_file);
        $sql = "INSERT INTO `categories` (`name`,`description`,`categorie_image`) VALUES ('$name','$description','$target_file')";
        if (mysqli_query($conn, $sql)) {
          echo "New record created successfully";
        } else {
          echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        $conn->close();
        redirect("categories.php");
      }
    }
  }

?>

  <div class="px-5 grid  grid-cols-3  py-24 mx-auto bg-gray-100 text-gray-900 rounded-lg">
    <form class="col-start-2" enctype="multipart/form-data" method="POST" onsubmit="function1()" id="editform">
      <div>
        <span class="uppercase text-sm text-gray-600 font-bold">
          Name
        </span>
        <input name="name" class="w-full bg-gray-200 text-gray-900 mt-2 p-3 rounded-lg focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-indigo-400" type="text" required />
        <div class="error text-red-500"><?php echo @$nameError ?></div>
      </div>
      <div class="mt-8">
        <span class="uppercase text-sm text-gray-600 font-bold">
          description
        </span>
        <input name="description" class="w-full bg-gray-200 text-gray-900 mt-2 p-3 rounded-lg focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-indigo-400" type="text" required />
        <div class="error text-red-500"><?php echo @$descriptionError ?></div>
      </div>
      <div>
        <span class="uppercase text-sm text-gray-600 font-bold">
          choose image
        </span>
        <input name="image" class="w-full bg-gray-200 text-gray-900 mt-2 p-3 rounded-lg focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-indigo-400" type="file" required />
        <div class="error text-red-500"><?php echo @$imageError ?></div>
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
            <h3 class="title-5 m-b-35">categories:</h3>
            <div class="table-data__tool">
              <div class="table-data__tool-left">
                <div class="rs-select2--light rs-select2--md">
                  <div class="dropDownSelect2"></div>
                </div>
                <button class="au-btn-filter">
                  <i class="zmdi zmdi-filter-list"></i>filters</button>
              </div>
              <div class="table-data__tool-right">
                <button class="au-btn au-btn-icon au-btn--green au-btn--small">
                  <a href="categories.php?do=add"><i class="zmdi zmdi-plus"></i>add user</button></a>
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
                    <th>Image</th>
                    <th>name</th>
                    <th>description</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($categories as $key => $user) { ?>
                    <tr class="spacer"></tr>
                    <tr class="tr-shadow">
                      <td>
                        <label class="au-checkbox">
                          <input type="checkbox">
                          <span class="au-checkmark"></span>
                        </label>
                      </td>
                      <td><?php echo isset($user['id']) ? $user['id'] : ''; ?></td>
                      <td><?php echo "<img src='{$user['categorie_image']}' style='width:20px height:20px;border-radius:50%;'>"; ?></td>
                      <td><?php echo isset($user['name']) ? $user['name'] : ''; ?></td>
                      <td>
                        <span class="block-description"><?php echo isset($user['description']) ? $user['description'] : ''; ?></span>
                      </td>
                      <td>
                        <div class="table-data-feature">
                          <button class="item" data-toggle="tooltip" data-placement="top" title="Edit">
                            <a href="categories.php?do=edit&id=<?php echo $user['id'] ?>"> <i class="zmdi zmdi-edit"></i> </a>
                          </button>
                          <button class="item" data-toggle="tooltip" data-placement="top" title="Delete">
                            <a href="categories.php?delete=<?php echo $user['id'] ?>"> <i class="zmdi zmdi-delete"></i> </a>
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

// header('Location:categories.php');
// exit;

include('include/footer.php');
?>