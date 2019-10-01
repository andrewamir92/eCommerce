<?php
session_start();
if (isset($_SESSION['username'])) {
    $pageTitle = 'members';
    include 'init.php';
    $do = '';

    if(isset($_GET['do'])){
        $do = $_GET['do'];
    }else{
        $do = 'manage';
    }
    // start managa page
    if($do == 'manage') {
    echo "Welcome to items page";
    }
//    end of Manage page

    // start of add page
    if($do == 'add'){
        ?>
        <h1 class="text-center mt-5 ">Add New item</h1>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form class="mt-5" action="?do=insert" method="POST" >
                        <div class=" form-group">
                            <label for="name"><b><h3>Name</h3></b></label>
                            <input type="text" id="name" name="name"  class="w-75" placeholder="Insert name of the item" required>
                        </div>
                        <div class=" form-group">
                            <label for="desc"><b><h3>Description</h3></b></label>
                            <input type="text" id="desc" name="description"  class="w-75" placeholder="Description of the item" >
                        </div>
                        <div class=" form-group">
                            <label for="price"><b><h3>Price</h3></b></label>
                            <input type="text" id="price" name="price"  class="w-75" placeholder="Insert price of the item" >
                        </div>
                        <div class=" form-group">
                            <label for="country"><b><h3>Country</h3></b></label>
                            <input type="text" id="country" name="country"  class="w-75" placeholder="Insert Country of Made" >
                        </div>
                        <div class=" form-group">
                            <label for="user"><b><h3>Status</h3></b></label>
                            <select class="w-75" name="status">
                                <option value="0">Choose</option>
                                <option value="1">New</option>
                                <option value="2">Like New</option>
                                <option value="3">Used</option>
                            </select>
                        </div>
                       <div class="">
                            <input type="submit" value="Add Item"  class="btn btn-primary btn-lg w-100 text-center">
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <?php

    }
//start of insert
    if ($do == 'insert') {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $name = $_POST['name'];
            $desc =($_POST['description']);
            $price = $_POST['price'];
            $country = $_POST['country'];
            $status = $_POST['status'];
            $errors = [];
            //start of Validation
            $errors = [];
            if(empty($name)){
                $errors[] = "Name feild cant be empty";
            }
            if (strlen($name) < 4 ) {
                $errors[] = " Name Feild cant be less than 4 chars";
            }
            if (strlen($name) > 20 ) {
                $errors[] = " Name Feild cant be more than 20 chars";
            }
            if (empty($desc)) {
                $errors[] = "Description Feild cant be empty";
            }
            if (empty($price)) {
                $errors[] = "Price Feild cant be empty";
            }
            if (empty($country)) {
                $errors[] = "Country Feild cant be empty";
            }

            foreach ($errors as $error) {
                echo "<div class='alert alert-danger'>" . $error . "<br>" . "</div>";
                // echo $error . "<br>";
            }


            if(empty($errors)){
                // check if user exist in database
                $check = checkIteam("Name","items",$name);
                if ($check == 1) {
                    echo "This User is Exist";
                }else{
                    // insert new member into Database from admin
                    $stmt = $con->prepare("INSERT INTO items(Name, Description, Price, Country_Made,Status,Add_Date)
			VALUES(:name, :desc, :price, :country,:status , now())
			");
                    $stmt->execute(array(
                        'name' 	=> $name,
                        'desc' 	=> $desc,
                        'price'	=> $price,
                        'country'	=> $country,
                        'status' => $status
                    ));
                    echo  "<div class='alert alert-success'>Member has been inserted</div>";

                }
            }

        }else{
            $errorMsg = "Sorry You Cant Brows This Page Directly";
            redirectHome($errorMsg ,'members.php',10);
        }
    }
//end of insert
    // end of add page

    // start of edit page

// end of edit page

// start of update page

// end of update page

// start of Delete page

// end of Delete Page

    // start of activate page

    // end of activate page

    include $tpl . "footer.php";
}else{

}