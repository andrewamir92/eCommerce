

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
          <a class="navbar-brand" href="dashboard.php"><?php echo lang('home') ?> </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item ">
                <a class="nav-link" href="categories.php"><?php echo lang('categories')?></a>
      </li>
      <li class="nav-item">
                <a class="nav-link" href="items.php"><?php echo lang('ITEMS')?></a>
      </li>
      <li class="nav-item">
                <a class="nav-link" href="members.php"><?php echo lang('MEMBERS')?></a>
              </li>
               <li class="nav-item">
                <a class="nav-link" href="#"><?php echo lang('STATISTICS')?></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#"><?php echo lang('LOGS')?></a>
              </li>
     
    </ul>
    <ul class="navbar-nav">
     <li class="nav-item dropdown test">
                <a class="nav-link dropdown-toggle" href="#" id="nav-bar" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <?php echo $_SESSION['username'] ?>
                </a>
                <div class="dropdown-menu" aria-labelledby="nav-bar">
                  <a class="dropdown-item" href="members.php?do=edit&userid=<?php echo $_SESSION['ID'] ?>">Edit</a>
                  <a class="dropdown-item" href="#">Setting</a>
                  <a class="dropdown-item" href="logout.php">LogOut</a>
                </div>
              </li>
            </ul>
  </div>
</nav>