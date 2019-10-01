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
	if ($do == 'manage') {
		$sort = "ASC";
		$sort_array = array("ASC","DESC");
		if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array) ) {
			$sort = $_GET['sort'];
		}

		$stmt = $con->prepare("SELECT * FROM categories ORDER BY ordering $sort");
		$stmt->execute();
		$categ = $stmt->fetchAll();?>
        
		<h1 class="text-center mt-3">Manage Categories</h1>
		<div class="container-fluid categories">
			<h2>Manage Categories</h2>
				<div class="pull-right">
					Sort :-
					<a href="?sort=ASC" class="<?php if($sort == 'ASC') { echo "active";} ?>">Ascending</a> |
					<a href="?sort=DESC" class="<?php if($sort == 'DESC') { echo "active";} ?>">Descending</a>
				</div>
				<br>
			<?php
			foreach ($categ as $cat ) {
				echo "<div class='btn-show'>";
				echo "<div class='pull-right hide-btn'>";
				echo "<a href='categories.php?do=edit&catid=" . $cat['ID'] . "' class='btn m-1 btn-primary'><i class='fa  mr-2 fa-edit'>Edit</i></a><br>";
				echo "<a href='categories.php?do=delete&catid=" . $cat['ID'] . "' class='delete btn btn-danger'><i class='fa  fa-close'>Delete</i></a><br>";
				echo "</div>";
				echo "<p>Category Name is :-  " ."<b>" . $cat['Name'] . "</b></p>";
				echo "<div class='hideAndShow'>";
                    echo "Description is :- "; if ($cat['Description'] == '') {
                        echo "this Category is Empty !!" . "<br>";
                    }else{
                        echo $cat['Description'] . "<br>";
                    };

                    echo "ordering Number is :- " ."<b>" . $cat['Ordering'] . "</b>". "<br>";
                    if ($cat['Visibility'] == 1) {
                        echo "<span class='visibility'>Hidden</span>";
                    }
                    if ($cat['Allow_Comment'] == 1) {
                        echo "<span class='Allow_Comment'>Comment Disabled</span>";
                    }
                    if ($cat['Allow_Ads'] == 1) {
                        echo "<span class='Allow_Ads'>Ads Disabled</span>";
                    }
                echo "</div>";
                echo "</div>";
				echo "<hr>";
					}
			?>
		</div>


		<?php
        echo "<a href='categories.php?do=add' class='btn btn-primary  mt-1 mb-3 w-100'><h3><i class='fa fa-plus '>Add New Category</i></h3></a>";
    }
//	end of manage page
	// start of add page
	if ($do == 'add') {
		?>
		<h1 class="text-center mt-5 ">Add New Categorie</h1>
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
					<form class="mt-5" action="?do=insert" method="POST" >
						<div class=" form-group">
							<label for="user"><b><h3>Name</h3></b></label>
							<input type="text" id="user" name="name"  class="w-75 ml-5" placeholder="Name Of The Category" required>
						</div>
						<div class=" form-group">
							<label for="pass"><b><h3>Description </h3></b></label>
							
							<input type="text" id="pass" name="description"  class=" w-75"  placeholder="Descripe The Category">
						</div>
						<div class=" form-group">
							<label for="ordering"><b><h3>Ordering</h3></b></label>
							<input type="text" id="ordering" name="ordering" class="w-75 ml-5"  placeholder="Arrenge Categories">
						</div>
						<div class=" form-group">
							<label><h3>Visibility</h3></label>
								<label for="vis-yes">Yes</label>
								<input type="radio" id="vis-yes" name="visibility" value="0" checked>
								<label for="vis-no">No</label>
								<input type="radio" id="vis-no" name="visibility" value="1" >
						</div>
						<div class=" form-group">
							<label><h3>Allow Commenting</h3></label>
								<label for="com-yes">Yes</label>
								<input type="radio" id="com-yes" name="commenting" value="0" checked>
								<label for="com-no">No</label>
								<input type="radio" id="com-no" name="commenting" value="1" >
						</div>
						<div class=" form-group">
							<label><h3>Allow Ads</h3></label>
								<label for="ads-yes">Yes</label>
								<input type="radio" id="ads-yes" name="ads" value="0" checked>
								<label for="ads-no">No</label>
								<input type="radio" id="ads-no" name="ads" value="1" >
						</div>
						<div class="">
							<input type="submit" value="Add New Category"  class="btn btn-primary btn-lg w-100 text-center">
						</div>

					</form>
				</div>
			</div>
				</div>
<?php

	}
	// end of add page

	if ($do == 'insert') {
		if ($_SERVER['REQUEST_METHOD'] == "POST") {
		 $name = $_POST['name'];
		 $desc = ($_POST['description']);
		 $order = $_POST['ordering'];
		 $visibil = $_POST['visibility'];
		 $comment = $_POST['commenting'];
		 $ads = $_POST['ads'];
		 $errors = [];
		 //start of Validation
		$errors = [];
		 if(empty($name)){
		 	$errors[] = "Name field cant be empty";
		 }
		 if(strlen($name) < 4){
		 	$errors[] = "Name field must be more than 4 letters";
		 }
		 
		 foreach ($errors as $error) {
		 	echo "<div class='alert alert-danger'>" . $error . "<br>" . "</div>";
		 	// echo $error . "<br>";
		 }
		
			
		if(empty($errors)){
			// check if category exist in database
			$check = checkIteam("Name","categories",$name);
			if ($check == 1) {
				$errorMsg =  "This Category is Exist";
				redirectHome($errorMsg,'back');
			}else{
				($checkUserType = getData("*" , "users" , "UserName" , $_SESSION['username']));
				$regStatus = 0;
				if(count($checkUserType)> 0){
					if($checkUserType[0]['GroupID']  == "1"){
						$regStatus = 1;
					}
				}
				// insert new Category into Database from admin 
			$stmt = $con->prepare("INSERT INTO categories(Name, Description, Ordering, Visibility,Allow_Comment,Allow_Ads)
			VALUES(:name, :description, :order, :visi,:comment , :ads)
			");
			$stmt->execute(array(
				'name' 	=> $name,
				'description' 	=> $desc,
				'order'	=> $order,
				'visi'	=> $visibil,
				'comment' => $comment,
				'ads' 		=> $ads
			));
			echo  "<div class='alert alert-success'>Member has been inserted</div>";
			
			}
		}

		}else{
			$errorMsg = "Sorry You Cant Brows This Page Directly";
			redirectHome($errorMsg ,'members.php',10);
		}
	}

	// start of edit page

 if ($do == 'edit') { 
		$catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0 ;
		$stmt = $con->prepare("SELECT * FROM categories WHERE ID = ? ");
		$stmt->execute(array($catid));
		$cat = $stmt->fetch();
		$count = $stmt->rowCount();
	
	if ($count > 0) {
		?>
	<h1 class="text-center mt-5 ">Edit Categorie</h1>
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
					<form class="mt-5" action="?do=update" method="POST" >
						<div class=" form-group">
							<label for="user"><b><h3>Name</h3></b></label>
							<input type="text" id="user" name="name"  class="w-75 ml-5" placeholder="Name Of The Category" required value="<?php echo $cat['Name']?>">
							<input type="hidden" name="catid" value="<?php echo $catid ?>">

						</div>
						<div class=" form-group">
							<label for="pass"><b><h3>Description </h3></b></label>
							
							<input type="text" id="pass" name="description"  class=" w-75"  placeholder="Descripe The Category" value="<?php echo $cat['Description']?>">
						</div>
						<div class=" form-group">
							<label for="ordering"><b><h3>Ordering</h3></b></label>
							<input type="text" id="ordering" name="ordering" class="w-75 ml-5"  placeholder="Arrenge Categories" value="<?php echo $cat['Ordering']?>">
						</div>
						<div class=" form-group">
							<label><h3>Visibility</h3></label>
								<label for="vis-yes">Yes</label>
								<input type="radio" id="vis-yes" name="visibility" value="0" <?php if($cat['Visibility'] == 0) {
									echo 'Checked';
								}?> 
								<label for="vis-no">No</label>
								<input type="radio" id="vis-no" name="visibility" value="1" <?php if($cat['Visibility'] == 1 ){
									echo 'Checked';
								}?>  >
						</div>
						<div class=" form-group">
							<label><h3>Allow Commenting</h3></label>
								<label for="com-yes">Yes</label>
								<input type="radio" id="com-yes" name="commenting" value="0" <?php if($cat['Allow_Comment'] == 0) {
									echo 'Checked';
								}?> >
								<label for="com-no">No</label>
								<input type="radio" id="com-no" name="commenting" value="1" <?php if($cat['Allow_Comment'] == 1) {
									echo 'Checked';
								}?> >
						</div>
						<div class=" form-group">
							<label><h3>Allow Ads</h3></label>
								<label for="ads-yes">Yes</label>
								<input type="radio" id="ads-yes" name="ads" value="0" <?php if($cat['Allow_Ads'] == 0) {
									echo 'Checked';
								}?> >
								<label for="ads-no">No</label>
								<input type="radio" id="ads-no" name="ads" value="1" <?php if($cat['Allow_Ads'] == 1) {
									echo 'Checked';
								}?> >
						</div>
						<div class="">
							<input type="submit" value="Save member"  class="btn btn-primary btn-lg w-100 text-center">
						</div>

					</form>
				</div>
			</div>
				</div>		

		<?php
	}else{
		$errorMsg = "This ID isnt exits";
		
			redirectHome($errorMsg,'back' );
	}
} 
// end of edit page

// start of update page
if( $do == 'update'){
	echo "<h1 class='text-center'>Update Member </h1>";
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$name = $_POST['name'];
		$id = $_POST['catid'];
		$desc = $_POST['description'];
		$order = $_POST['ordering'];
		$visi = $_POST['visibility'];
		$comment = $_POST['commenting'];
		$ads = $_POST['ads'];

		// var_dump($_POST);
			$stmt = $con->prepare("UPDATE categories SET Name = ? , Description = ? , Ordering = ? , Visibility = ? , Allow_Comment = ? , Allow_Ads = ? WHERE ID = ?");
		// die();
			$stmt->execute(array($name, $desc, $order, $visi, $comment, $ads, $id));
			$errorMsg =   $stmt->rowCount() . "record updated" ;
			redirectHome($errorMsg);

	}else{
		$errorMsg =  "you cant brows here !!!";
		redirectHome($errorMsg,'back');
	}
}
// end of update page

// start of Delete page
    if ($do == 'delete') {
        echo "<h1 class='text-center'>delete Page </h1>";
        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0 ;
        $check = checkIteam('ID','categories',$catid);
        if ($check > 0) {
            $stmt = $con->prepare("DELETE FROM categories WHERE ID = :Id");
            $stmt->bindParam(':Id',$catid);
            $stmt ->execute();
            $errorMsg =  $stmt->rowCount() . "record Deleted" ;
            redirectHome($errorMsg,'categories.php' );
        }else{
            $errorMsg = "this user isnt exist";
            redirectHome($errorMsg, 'back' );
        }
    }


// end of Delete Page



include $tpl . "footer.php";
}else{

}