<?php
include("initials.php");
session_start();
if (isset($_SESSION['login'])) {

    $page = "All";
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    }
    if ($page == "All") {
        $statment = $connect->prepare("select * from posts");
        $statment->execute();
        $usercount = $statment->rowcount();
        $result = $statment->fetchall();

?>

        <div class="container mt-5 ">
            <div class="row">
                <div class="col-md-10 m-auto pt-5">
                    <div>
                        <?php
                        if (isset($_SESSION['message'])) {
                            echo "<h4 class='text-center alert alert-success'>" . $_SESSION['message'] . "</h4>";
                            unset($_SESSION['message']);
                            header("refresh:3;url=posts.php");
                        }
                        ?>
                        <h4 class="text-center mb-4">NUMBER OF POSTS
                            <span class="badge badge-primary"><?php echo $usercount ?></span>
                            <a href="?page=create" class="btn btn-success">create new post</a>
                        </h4>
                        <table class="table table-striped table-dark ">
                            <thead>
                                <tr>
                                    <th scope="col">Post_id</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Operation</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($result as $item) {
                                ?>
                                    <tr>
                                        <th scope="row"><?php echo $item['post_id'] ?></th>
                                        <td><?php echo $item['title'] ?></td>
                                        <td><?php echo $item['description'] ?></td>
                                        <td><?php echo $item['image'] ?></td>
                                        <td><?php echo $item['status'] ?></td>
                                        <td>
                                            <a href="?page=show&post_id=<?php echo $item['post_id'] ?>" class="btn btn-primary"><i class="fa-solid fa-eye"></i></a>
                                            <a href="?page=edit&post_id=<?php echo $item['post_id'] ?>" class="btn btn-success"><i class="fa-solid fa-pen-to-square"></i></a>
                                            <a href="?page=delete&post_id=<?php echo $item['post_id'] ?>" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
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
        </div>

    <?php

    } else if ($page == "show") {
        if (isset($_GET['post_id'])) {
            $post_id = $_GET['post_id'];
        }
        $statment = $connect->prepare("select * from posts where post_id =?");
        $statment->execute(array($post_id));
        $item = $statment->fetch();
    ?>

        <div class="container mt-5 pt-5">
            <div class="row">
                <div class="col-md-10 m-auto">
                    <table class="table table-striped table-dark">
                        <thead>
                            <tr>
                                <th scope="col">post_id</th>
                                <th scope="col">Title</th>
                                <th scope="col">Description</th>
                                <th scope="col">Image</th>
                                <th scope="col">Status</th>
                                <th scope="col">User_id</th>
                                <th scope="col">Category_id</th>
                                <th scope="col">Created_at</th>
                                <th scope="col">Updated_at</th>
                                <th scope="col">Operation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row"><?php echo $item['post_id'] ?></th>
                                <td><?php echo $item['title'] ?></td>
                                <td><?php echo $item['description'] ?></td>
                                <td><?php echo $item['image'] ?></td>
                                <td><?php echo $item['status'] ?></td>
                                <td><?php echo $item['user_id'] ?></td>
                                <td><?php echo $item['category_id'] ?></td>
                                <td><?php echo $item['created_at'] ?></td>
                                <td><?php echo $item['updated_at'] ?></td>
                                <td>
                                    <a href="posts.php" class="btn btn-success"><i class="fa-solid fa-house"></i></a>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>




    <?php
    } else if ($page == "delete") {
        if (isset($_GET['post_id'])) {
            $post_id = $_GET['post_id'];
        }
        $statment = $connect->prepare("delete from posts where  post_id=?");
        $statment->execute(array($post_id));
        $_SESSION['message'] = "deleted sucessfully";
        header("Location:posts.php");
    } else if ($page == "create") {
        $statment1 = $connect->prepare("select user_id from users");
        $statment1->execute();
        $result = $statment1->fetchall();

        $statment2 = $connect->prepare("select category_id from categories");
        $statment2->execute();
        $element = $statment2->fetchall();

    ?>
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-10 m-auto">
                    <h4 class="text-center mt-4">CREATE POST PAGE</h4>
                    <form action="?page=savenew" method="post">
                        <label>POST_ID</label>
                        <input type="text" name="post_id" class="form-control mb-4">
                        <label>TITLE</label>
                        <input type="text" name="title" class="form-control mb-4">
                        <label>DESCRIPTION</label>
                        <input type="text" name="desc" class="form-control mb-4">
                        <label>IMAGE</label>
                        <input type="text" name="image" class="form-control mb-4">
                        <label>STATUS</label>
                        <select name="status" class="form-control mb-4">
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
                        <label>CATEGORY_ID</label>
                        <select name="category_id" class="form-control mb-4">
                            <?php

                            foreach ($element as $item) {
                            ?>
                                <option>
                                    <?php echo $item['category_id'] ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                        <input type="submit" class="form-control  btn btn-success" value="CREATE NEW POST">
                    </form>
                </div>
            </div>
        </div>


    <?php
    } else if ($page == "savenew") {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $post_id = $_POST['post_id'];
            $title = $_POST['title'];
            $desc = $_POST['desc'];
            $image = $_POST['image'];
            $status = $_POST['status'];
            $userid = $_POST['user_id'];
            $cateid = $_POST['category_id'];
        }
        try {
            $statment = $connect->prepare("insert into posts 
        (post_id,title,description,image,`status`,user_id,category_id,created_at)
        values
     (?,?,?,?,?,?,?,now())");
            $statment->execute(array($post_id, $title, $desc, $image, $status, $userid, $cateid));
            $_SESSION['message'] = "created sucessfully";
            header("Location:posts.php");
        } catch (PDOException $e) {
            echo "<h4 class='text-center alert alert-danger'>DUPLICATED IN VALUES</h4>";
            header("Refresh:3;url=posts.php?page=create");
        }
    } else if ($page == "edit") {
        if (isset($_GET['post_id'])) {
            $post_id = $_GET['post_id'];
        }
        $statment = $connect->prepare("select * from posts where post_id=?");
        $statment->execute(array($post_id));
        $result = $statment->fetch();
    ?>
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-10 m-auto">
                    <h4 class="text-center mt-4">UPDATE POST PAGE</h4>
                    <form action="?page=saveupdate" method="post">
                        <input type="hidden" name="oldid" value="<?php echo $result['post_id'] ?>" class="form-control mb-4">
                        <label>POST_ID</label>
                        <input type="text" name="new_id" value="<?php echo $result['post_id'] ?>" class="form-control mb-4">
                        <label>TITLE</label>
                        <input type="text" name="title" value="<?php echo $result['title'] ?>" class="form-control mb-4">
                        <label>DESCRIPTION</label>
                        <input type="text" name="desc" value="<?php echo $result['description'] ?>" class="form-control mb-4">
                        <label>IMAGE</label>
                        <input type="text" name="image" value="<?php echo $result['image'] ?>" class="form-control mb-4">
                        <label>STATUS</label>
                        <select name="status" class="form-control mb-4">
                            <?php
                            if ($result['status'] == "1") {
                                echo '<option value="1" selected>active</option>';
                                echo  '<option value="0">block</option>';
                            } else {
                                echo '<option value="1" >active</option>';
                                echo  '<option value="0" selected>block</option>';
                            }
                            ?>
                        </select>
                        <input type="submit" class="form-control  btn btn-success" value="UPDATE POST">
                    </form>
                </div>
            </div>
        </div>
    <?php
    } else if ($page == "saveupdate") {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $oldid = $_POST['oldid'];
            $new_id = $_POST['new_id'];
            $title = $_POST['title'];
            $desc = $_POST['desc'];
            $image = $_POST['image'];
            $status = $_POST['status'];
        }
        try {
            $statment = $connect->prepare("UPDATE posts
        SET post_id=?,title=?,description=?,image=?,`status`=?,updated_at=now()
        WHERE post_id=?");
            $statment->execute(array($new_id, $title, $desc, $image, $status, $oldid));
            $_SESSION['message'] = "UPDATED SUCESSFULLY";
            header("Location:posts.php");
        } catch (PDOException $e) {
            echo "<h4 class='text-center alert alert-danger'>DUPLICATED IN VALUES</h4>";
            header("Refresh:3;url=posts.php?page=edit&post_id=$oldid");
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