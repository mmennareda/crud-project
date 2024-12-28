<?php
include("initials.php");
session_start();
if (isset($_SESSION['login'])) {

    $page = "All";
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    }
    if ($page == "All") {
        $statment1 = $connect->prepare("SELECT * FROM users");
        $statment1->execute();
        $usercount = $statment1->rowCount();
        $result = $statment1->fetchall();
?>

        <div class="container ">
            <div class="row">
                <div class="col-md-10 m-auto pt-5">
                    <?php
                    if (isset($_SESSION['message'])) {
                        echo "<h4 class='text-center alert alert-success'>" . $_SESSION['message'] . "</h4>";
                        unset($_SESSION['message']);
                        header("Refresh:4;url=users.php");
                    }
                    ?>
                    <h4 class="text-center">Detailes Of Users <span class="badge badge-primary"><?php echo $usercount ?></span>
                        <a class="badge badge-success" href="?page=create">Ceate New User</a>
                    </h4>
                    <table class="table table-dark mt-5 pt-5 text-center">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">NAME</th>
                                <th scope="col">EMAIL</th>
                                <th scope="col">STATUS</th>
                                <th scope="col">ROLE</th>
                                <th scope="col">OPERATION</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($result as $item) {
                            ?>
                                <tr>
                                    <td><?php echo $item['user_id'] ?></td>
                                    <td><?php echo $item['username'] ?></td>
                                    <td><?php echo $item['email'] ?></td>
                                    <td><?php echo $item['status'] ?></td>
                                    <td><?php echo $item['role'] ?></td>
                                    <td><a href="?page=show&user_id=<?php echo $item['user_id'] ?>" class="btn btn-primary"><i class="fa-solid fa-eye"></i></a>
                                        <a href="?page=edit&user_id=<?php echo $item['user_id'] ?>" class="btn btn-success"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <a href="?page=delete&user_id=<?php echo $item['user_id'] ?>" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
                                    </td>
                                </tr>

                            <?php
                            }
                            ?>


                        </tbody>
                    </table>

                </div>
            </div>
        </div>


    <?php
    } else if ($page == "show") {
        if (isset($_GET['user_id'])) {
            $user_id = $_GET['user_id'];
        }

        $statment1 = $connect->prepare("SELECT * FROM users WHERE	user_id =?");
        $statment1->execute(array($user_id));
        $item = $statment1->fetch();
    ?>

        <div class="container ">
            <div class="row">
                <div class="col-md-10 m-auto pt-5">
                    <table class="table table-dark mt-5 pt-5 text-center">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">NAME</th>
                                <th scope="col">EMAIL</th>
                                <th scope="col">PASSWORD</th>
                                <th scope="col">STATUS</th>
                                <th scope="col">ROLE</th>
                                <th scope="col">CREATED_AT</th>
                                <th scope="col">UPDATED_AT</th>
                                <th scope="col">OPERATION</th>

                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td><?php echo $item['user_id'] ?></td>
                                <td><?php echo $item['username'] ?></td>
                                <td><?php echo $item['email'] ?></td>
                                <td><?php echo $item['password'] ?></td>
                                <td><?php echo $item['status'] ?></td>
                                <td><?php echo $item['role'] ?></td>
                                <td><?php echo $item['created_at'] ?></td>
                                <td><?php echo $item['updated_at'] ?></td>
                                <td><a href="users.php" class="btn btn-primary"><i class="fa-solid fa-arrow-right"></i></a></td>
                            </tr>

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    <?php

    } else if ($page == "delete") {
        if (isset($_GET['user_id'])) {
            $user_id = $_GET['user_id'];
        }

        $statment = $connect->prepare("DELETE FROM users WHERE user_id =?");
        $statment->execute(array($user_id));
        $_SESSION['message'] = "Deleted Sucessfully";
        header("Location:users.php");
    } else if ($page == "create") {

    ?>
        <div class="container">
            <div class="row">
                <div class="col-md-10 mt-5">
                    <h4 class="text-center">CREATE USER PAGE</h4>
                    <form action="?page=savenew" method="post">
                        <label>USER_ID</label>
                        <input type="text" name="id" class="form-control mb-3 ">
                        <label>USERNAME</label>
                        <input type="text" name="name" class="form-control mb-3">
                        <label>EMAIL</label>
                        <input type="email" name="email" class="form-control mb-3">
                        <label>PASSWORD</label>
                        <input type="password" name="pass" class="form-control mb-3">
                        <label>STATUS</label>
                        <select name="status" class="form-control mb-3">
                            <option value="1">active</option>
                            <option value="0">block</option>
                        </select>
                        <label>ROLE</label>
                        <select name="role" class="form-control mb-3">
                            <option value="admin">admin</option>
                            <option value="user">user</option>
                        </select>
                        <input type="submit" value="Create New User" class="form-control mt-3 btn btn-success ">

                    </form>
                </div>
            </div>
        </div>

    <?php

    } else if ($page == "savenew") {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $pass = $_POST['pass'];
            $status = $_POST['status'];
            $role = $_POST['role'];

            $statment = $connect->prepare("select * from users where email=?");
            $statment->execute(array($email));
            $count = $statment->rowcount();
            $item = $statment->fetch();
            if ($count > 0) {
                echo "<h4 class='text-center alert alert-danger'>This Account Already Registed</h4>";
                header("Refresh:3;url=users.php?page=create");
            } else {
                try {
                    $statment = $connect->prepare("insert into users 
                    (user_id,username,email,`password`,`status`,`role`,created_at)
                    values(?,?,?,?,?,?,now())");
                    $statment->execute(array($id, $name, $email, $pass, $status, $role));
                    $_SESSION['message'] = "CREATED SUCESSFULLY";
                    header("Location:users.php");
                } catch (PDOException $e) {
                    echo "<h4 class='text-center alert alert-danger'>DUPLICATED IN ID</h4>";
                    header("Refresh:3;url=users.php?page=create");
                }
            }
        }
    } else if ($page == "edit") {
        if (isset($_GET['user_id'])) {
            $user_id = $_GET['user_id'];
        }
        $statment = $connect->prepare("SELECT * FROM users WHERE user_id =?");
        $statment->execute(array($user_id));
        $item = $statment->fetch();

    ?>
        <div class="container">
            <div class="row">
                <div class="col-md-10 mt-5">
                    <h4 class="text-center">UPDATE USER PAGE</h4>
                    <form action="?page=update" method="post">
                        <label>USER_ID</label>
                        <input type="hidden" name="old_id" value="<?php echo $item['user_id']; ?>" class="form-control mb-3 ">
                        <input type="text" name="new_id" value="<?php echo $item['user_id']; ?>" class="form-control mb-3 ">
                        <label>USERNAME</label>
                        <input type="text" readonly name="name" value="<?php echo $item['username']; ?>" class="form-control mb-3">
                        <label>EMAIL</label>
                        <input type="email" readonly name="email" value="<?php echo $item['email']; ?>" class="form-control mb-3">
                        <label>PASSWORD</label>
                        <input type="password" readonly name="pass" value="<?php echo $item['password']; ?>" class="form-control mb-3">
                        <label>STATUS</label>
                        <select name="status" class="form-control mb-3">
                            <?php
                            if ($item['status'] == 1) {
                                echo '<option value="1" selected>active</option>';
                                echo '<option value="0">block</option>';
                            } else {

                                echo '<option value="1" >active</option> ';
                                echo  '<option value="0" selected>block</option>';
                            }
                            ?>
                        </select>
                        <label>ROLE</label>
                        <select name="role" class="form-control mb-3">
                            <?php
                            if ($item['role'] == "admin") {

                                echo '<option value="admin" selected>admin</option> ';
                                echo  '<option value="user">user</option>';
                            } else {
                                echo '<option value="admin">admin</option> ';
                                echo  '<option value="user" selected>user</option>';
                            }
                            ?>
                        </select>
                        <input type="submit" value="Update User" class="form-control mt-3 btn btn-success ">

                    </form>
                </div>
            </div>
        </div>
    <?php
    } else if ($page == "update") {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $old_id = $_POST['old_id'];
            $new_id = $_POST['new_id'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $pass = $_POST['pass'];
            $status = $_POST['status'];
            $role = $_POST['role'];

           
                try {
                    $statment = $connect->prepare("UPDATE USERS SET 
                user_id=?,
                username=?,
                email=?,
               `password`=?,
               `status`=?,
               `role`=?,
                updated_at=now() 
                WHERE user_id=?");
                    $statment->execute(array($new_id, $name, $email, $pass, $status, $role, $old_id));
                    $_SESSION['message'] = "UPDATED SUCESSFULLY";
                    header("Location:users.php");
                } catch (PDOException $e) {
                    echo "<h4 class='text-center alert alert-danger'>DUPLICATED IN ID</h4>";
                    header("Refresh:3;url=users.php?page=edit&user_id=$old_id");
                }
            // }
        }
    }
    ?>
<?php
} else {
    $_SESSION['message'] = "Please Login First";
    header("Location:../login.php");
}
include("Includes/temp/footer.php");
?>