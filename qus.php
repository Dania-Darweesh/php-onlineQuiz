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
@$question = $_POST["question"];
@$choice1 = $_POST["choice1"];
@$choice2 = $_POST["choice2"];
@$examid  = $_POST["examid"];
@$choice3 = $_POST["choice3"];
@$choice4 = $_POST["choice4"];
@$correct = $_POST["correct"];
//connect
include('connect.php');
include('include/header&aside.php');
$sql = "SELECT * FROM questions";
$result = mysqli_query($conn, $sql);
$questions  = mysqli_fetch_all($result, MYSQLI_ASSOC);
//print_r($questions);

//Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete = "DELETE FROM questions WHERE id = $id";
    $result = mysqli_query($conn, $delete);
    if (mysqli_query($conn, $delete)) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
    redirect("qus.php");
}
//Edit
if (isset($_GET['do'])) {
    $do = $_GET['do'];
    $check = 1;
    if ($do == "edit") {
        $id = $_GET["id"];
        $sql = "SELECT * FROM questions WHERE id =$id";
        $result = mysqli_query($conn, $sql);
        $questions = mysqli_fetch_array($result, MYSQLI_ASSOC);
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if ($question == "") {
                $check = 0;
                $questionError = "The question shouldn't be empty!";
            }

            if ($check == 1) {
                $sql2 = "UPDATE questions SET `question` = '$question', `choice1` ='$choice1',`choice2`='$choice2',`choice3`='$choice3',`choice4`='$choice4',`correct`='$correct',examid ='$examid' WHERE id = '$id'";
                if ($conn->query($sql2) === TRUE) {
                    echo "New record created successfully";
                } else {
                    echo "Error: " . $sql2 . "<br>" . $conn->error;
                }
                $conn->close();
                redirect("qus.php");
            }
        }
    }

    // Add 
    else if ($do == "add") {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if ($question == "") {
                $check = 0;
                $questionError = "The question shouldn't be empty!";
            }
            if ($check == 1) {
                $sql = "INSERT INTO `questions` (`question`,`choice1`,`choice2`,`choice3`,`choice4`,`correct`,`examid`) VALUES ('$question','$choice1','$choice2','$choice3','$choice4','$correct','$examid')";
                if (mysqli_query($conn, $sql)) {
                    echo "New record created successfully";
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                }
                $conn->close();
                redirect("qus.php");
            }
        }
    }
?>

    <div class="px-5 grid  grid-cols-3  py-24 mx-auto bg-gray-100 text-gray-900 rounded-lg">
        <form class="col-start-2" enctype="multipart/form-data" method="POST" id="editform">
            <div>
                <span class="uppercase text-sm text-gray-600 font-bold">
                    Question
                </span>
                <input name="question" class="w-full bg-gray-200 text-gray-900 mt-2 p-3 rounded-lg focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-indigo-400" type="text" required />
                <div class="error text-red-500"></div>
            </div>
            <div class="mt-8">
                <span class="uppercase text-sm text-gray-600 font-bold">
                    choice1
                </span>
                <input name="choice1" class="w-full bg-gray-200 text-gray-900 mt-2 p-3 rounded-lg focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-indigo-400" type="text" required />
                <div class="error text-red-500"></div>
            </div>
            <div class="mt-8">
                <span class="uppercase text-sm text-gray-600 font-bold">
                    choice2
                </span>
                <input name="choice2" class="w-full bg-gray-200 text-gray-900 mt-2 p-3 rounded-lg focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-indigo-400" type="text" required />
                <div class="error text-red-500"></div>
            </div>
            <div class="mt-8">
                <span class="uppercase text-sm text-gray-600 font-bold">
                    choice3
                </span>
                <input name="choice3" class="w-full bg-gray-200 text-gray-900 mt-2 p-3 rounded-lg focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-indigo-400" type="text" required />
                <div class="error text-red-500"></div>
            </div>
            <div class="mt-8">
                <span class="uppercase text-sm text-gray-600 font-bold">
                    choice4
                </span>
                <input name="choice4" class="w-full bg-gray-200 text-gray-900 mt-2 p-3 rounded-lg focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-indigo-400" type="text" required />
                <div class="error text-red-500"></div>
            </div>
            <div class="mt-8">
                <span class="uppercase text-sm text-gray-600 font-bold">
                    correct answere
                </span>
                <input name="correct" class="w-full bg-gray-200 text-gray-900 mt-2 p-3 rounded-lg focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-indigo-400" type="text" required />
                <div class="error text-red-500"></div>
            </div>
            <div>
                <select name="examid">
                    <?php
                    $sql = "SELECT * FROM exams";
                    $result = mysqli_query($conn, $sql);
                    $exams  = mysqli_fetch_all($result, MYSQLI_ASSOC);
                    foreach ($exams as $exam) { ?>
                        <option value="<?php echo @$exam['id']  ?>">
                            <?php echo @$exam["name"]; ?>
                        </option>
                    <?php  } ?>
                </select>
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
                        <h3 class="title-5 m-b-35">Questions:</h3>
                        <div class="table-data__tool">
                            <div class="table-data__tool-left">
                                <div class="rs-select2--light rs-select2--md">
                                    <div class="dropDownSelect2"></div>
                                </div>
                                <button class="au-btn-filter">
                                    <i class="zmdi zmdi-filter-list"></i>filters</button>
                            </div>
                            <div class="table-data__tool-right">
                                <button class="au-btn au-btn-icon au-btn--green au-btn--small" style="color: white;">
                                    <a href="qus.php?do=add"><i class="zmdi zmdi-plus" style="text-decoration: none; color:white;"></i>add Question</button></a>
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
                                        <th>question</th>
                                        <th>choice1</th>
                                        <th>choice2</th>
                                        <th>choice3</th>
                                        <th>choice4</th>
                                        <th>correct answere</th>
                                        <th>exam Id</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($questions as $key => $user) { ?>
                                        <tr class="spacer"></tr>
                                        <tr class="tr-shadow">
                                            <td>
                                                <label class="au-checkbox">
                                                    <input type="checkbox">
                                                    <span class="au-checkmark"></span>
                                                </label>
                                            </td>
                                            <td><?php echo isset($user['id']) ? $user['id'] : ''; ?></td>
                                            <td><?php echo isset($user['question']) ? $user['question'] : ''; ?></td>
                                            <td class="desc"><?php echo isset($user['choice1']) ? $user['choice1'] : ''; ?></td>
                                            <td>
                                                <span class="status--process"><?php echo isset($user['choice2']) ? $user['choice2'] : ''; ?></span>
                                            </td>
                                            <td>
                                                <span class="block-description"><?php echo isset($user['choice3']) ? $user['choice3'] : ''; ?></span>
                                            </td>
                                            <td>
                                                <span class="block-description"><?php echo isset($user['choice4']) ? $user['choice4'] : ''; ?></span>
                                            </td>
                                            <td>
                                                <span class="block-description"><?php echo isset($user['correct']) ? $user['correct'] : ''; ?></span>
                                            </td>
                                            <td><?php echo isset($user['examid']) ? $user['examid'] : ''; ?></td>
                                            <td>
                                                <div class="table-data-feature">
                                                    <button class="item" data-toggle="tooltip" data-placement="top" title="Edit">
                                                        <a href="qus.php?do=edit&id=<?php echo $user['id'] ?>"> <i class="zmdi zmdi-edit"></i> </a>
                                                    </button>
                                                    <button class="item" data-toggle="tooltip" data-placement="top" title="Delete">
                                                        <a href="qus.php?delete=<?php echo $user['id'] ?>"> <i class="zmdi zmdi-delete"></i> </a>
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

// header('Location:questions.php');
// exit;

include('include/footer.php');
?>