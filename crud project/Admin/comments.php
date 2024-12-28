<?php
include("initials.php");
session_start();
if (isset($_SESSION['login'])) {

    $page = "All";
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    }
    if ($page == "All") {
        $statment1 = $connect->prepare("SELECT * FROM comments");
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
                        header("Refresh:3;url=comments.php");
                    }
                    ?>
                    <h4 class="text-center mt-5 ">Detailes Of comments <span class="badge badge-primary"><?php echo $usercount ?></span>
                        <a class="badge badge-success" href="?page=create">Ceate New Comment</a>
                    </h4>
                    <table class="table table-dark text-center mt-4">
                        <thead>
                            <tr>
                                <th scope="col">COMMENT_ID</th>
                                <th scope="col">COMMENT</th>
                                <th scope="col">STATUS</th>
                                <th scope="col">OPERATION</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($result as $item) {
                            ?>
                                <tr>
                                    <td><?php echo $item['comment_id'] ?></td>
                                    <td><?php echo $item['comment'] ?></td>
                                    <td><?php echo $item['status'] ?></td>
                                    <td><a href="?page=show&comment_id=<?php echo $item['comment_id'] ?>" class="btn btn-primary"><i class="fa-solid fa-eye"></i></a>
                                        <a href="?page=edit&comment_id=<?php echo $item['comment_id'] ?>" class="btn btn-success"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <a href="?page=delete&comment_id=<?php echo $item['comment_id'] ?>" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
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
        if (isset($_GET['comment_id'])) {
            $comment_id = $_GET['comment_id'];
        }

        $statment1 = $connect->prepare("SELECT * FROM comments WHERE	comment_id =?");
        $statment1->execute(array($comment_id));
        $item = $statment1->fetch();
    ?>

        <div class="container ">
            <div class="row">
                <div class="col-md-10 m-auto pt-5">
                    <table class="table table-dark mt-5 pt-5 text-center">
                        <thead>
                            <tr>
                                <th scope="col">COMMENT_ID</th>
                                <th scope="col">COMMENT</th>
                                <th scope="col">STATUS</th>
                                <th scope="col">USER_ID</th>
                                <th scope="col">POST_ID</th>
                                <th scope="col">CREATED_AT</th>
                                <th scope="col">UPDATED_AT</th>
                                <th scope="col">OPERATION</th>

                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td><?php echo $item['comment_id'] ?></td>
                                <td><?php echo $item['comment'] ?></td>
                                <td><?php echo $item['status'] ?></td>
                                <td><?php echo $item['user_id'] ?></td>
                                <td><?php echo $item['post_id'] ?></td>
                                <td><?php echo $item['created_at'] ?></td>
                                <td><?php echo $item['updated_at'] ?></td>
                                <td><a href="comments.php" class="btn btn-primary"><i class="fa-solid fa-arrow-right"></i></a></td>
                            </tr>

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    <?php

    } else if ($page == "delete") {
        if (isset($_GET['comment_id'])) {
            $comment_id = $_GET['comment_id'];
        }
        $statment = $connect->prepare("DELETE FROM comments WHERE comment_id =?");
        $statment->execute(array($comment_id));
        $_SESSION['message'] = "Deleted Sucessfully";
        header("Location:comments.php");
    } else if ($page == "create") {
        $statment1 = $connect->prepare("select user_id from users");
        $statment1->execute();
        $result = $statment1->fetchall();

        $statment2 = $connect->prepare("select post_id from posts");
        $statment2->execute();
        $element = $statment2->fetchall();

    ?>
        <div class="container ">
            <div class="row">
                <div class="col-md-10 pt-5 m-auto">
                    <h4 class="text-center">CREATE COMMENT PAGE</h4>
                    <form action="?page=savenew" method="post">
                        <label>COMMENT_ID</label>
                        <input type="text" name="comment_id" class="form-control mb-3 ">
                        <label>COMMENT</label>
                        <input type="text" name="comment" class="form-control mb-3">
                        <label>STATUS</label>
                        <select name="status" class="form-control mb-3">
                            <option value="1">active</option>
                            <option value="0">block</option>
                        </select>
                        <label>USER_ID</label>
                        <select name="user_id" class="form-control mb-4">
                            <?php

                            foreach ($result as $item) {
                            ?>
                                <option>
                                    <?php echo $item['user_id'] ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                        <label>POST_ID</label>
                        <select name="post_id" class="form-control mb-4">
                            <?php

                            foreach ($element as $item) {
                            ?>
                                <option>
                                    <?php echo $item['post_id'] ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                        <input type="submit" value="Create New Comment" class="form-control mt-3 btn btn-success ">

                    </form>
                </div>
            </div>
        </div>

    <?php

    } else if ($page == "savenew") {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $comment_id = $_POST['comment_id'];
            $comment = $_POST['comment'];
            $status = $_POST['status'];
            $user_id = $_POST['user_id'];
            $post_id = $_POST['post_id'];

            try {
                $statment = $connect->prepare("insert into comments 
            (comment_id,comment,`status`,user_id,post_id,created_at)
            values(?,?,?,?,?,now())");
                $statment->execute(array($comment_id, $comment, $status, $user_id, $post_id));
                $_SESSION['message'] = "CREATED SUCESSFULLY";
                header("Location:comments.php");
            } catch (PDOException $e) {
                echo "<h4 class='text-center alert alert-danger'>DUPLICATED IN ID</h4>";
                header("Refresh:3;url=comments.php?page=create");
            }
        }
    } else if ($page == "edit") {
        if (isset($_GET['comment_id'])) {
            $comment_id = $_GET['comment_id'];
        }
        $statment = $connect->prepare("SELECT * FROM comments WHERE comment_id =?");
        $statment->execute(array($comment_id));
        $item = $statment->fetch();

    ?>
        <div class="container">
            <div class="row">
                <div class="col-md-10 mt-5">
                    <h4 class="text-center">UPDATE COMMENT PAGE</h4>
                    <form action="?page=update" method="post">
                        <label>COMMENT_ID</label>
                        <input type="hidden" name="old_id" value="<?php echo $item['comment_id']; ?>" class="form-control mb-3 ">
                        <input type="text" name="new_id" value="<?php echo $item['comment_id']; ?>" class="form-control mb-3 ">
                        <label>COMMENT</label>
                        <input type="text" name="comment" value="<?php echo $item['comment']; ?>" class="form-control mb-3">
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
                        <input type="submit" value="Update User" class="form-control mt-3 btn btn-success ">

                    </form>
                </div>
            </div>
        </div>
    <?php
    } else if ($page == "update") {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $oldid = $_POST['old_id'];
            $newid = $_POST['new_id'];
            $comment = $_POST['comment'];
            $status = $_POST['status'];
        }
        try {
            $statment = $connect->prepare("update comments set 
        comment_id=?,comment=?,`status`=?,updated_at=now()
        where comment_id=?");
            $statment->execute(array($newid, $comment, $status, $oldid));
            $_SESSION['message'] = "UPDATED SUCESSFULLY";
            header("Location:comments.php");
        } catch (PDOException $e) {
            echo "<h4 class='text-center alert alert-danger'>DUPLICATED IN ID</h4>";
            header("Refresh:3;url=comments.php?page=edit&comment_id=$oldid");
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