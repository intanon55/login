<?php
session_start();
require_once('class.user.php');
$user = new USER();
if ($user->is_loggedin() != "") {
    $user->redirect('home.php');
}
if (isset($_POST['btn-signup'])) {
    $username = strip_tags($_POST['txt_uname']);
    $email = strip_tags($_POST['txt_email']);
    $password = strip_tags($_POST['txt_upass']);

    if ($username == "") {
        $error[] = "โปรดใส่ชื่อผู้ใช้ !";
    } else if ($email == "") {
        $error[] = "โปรดใส่อีเมล!";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error[] = 'กรุณาใส่อีเมล์ที่ถูกต้อง !';
    } else if ($password == "") {
        $error[] = "โปรดใส่รหัสผ่าน !";
    } else if (strlen($password) < 6) {
        $error[] = "รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร";
    } else {
        try {
            $stmt = $user->runQuery("SELECT username, email FROM users WHERE username=:username OR email=:email");
            $stmt->execute(array(':username' => $username, ':email' => $email));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row['username'] == $username) {
                $error[] = "ขออภัยชื่อผู้ใช้ถูกใช้ไปแล้ว !";
            } else if ($row['email'] == $email) {
                $error[] = "ขออภัยอีเมล์ถูกใช้ไปแล้ว !";
            } else {
                if ($user->register($username, $email, $password)) {
                    $user->redirect('sign-up.php?joined');
                }
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <title>SAVE.IN.TH</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <form method="post">
                    <h2>สมัครสมาชิก</h2>
                    <hr />
                    <?php
                    if (isset($error)) {
                        foreach ($error as $error) {
                            ?>
                            <?php echo "<script> Swal.fire(
						'ผิดพลาด!',
						'$error',
						'error'
					  ); </script>"; ?>
                        <?php
                    }
                } else if (isset($_GET['joined'])) {
                    ?>

                        <?php echo "<script> Swal.fire(
						'สำเร็จ!',
						'สมัครสมาชิกเรียบร้อยแล้วสามารถเข้าใช้งานได้เลย',
						'success'
					  ); </script>"; ?>
                    <?php
                }
                ?>
                    <div class="form-group">
                        <input type="text" class="form-control" name="txt_uname" placeholder="Enter Username" value="<?php if (isset($error)) {
                                                                                                                            echo $username;
                                                                                                                        } ?>" />
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="txt_email" placeholder="Enter E-Mail ID" value="<?php if (isset($error)) {
                                                                                                                            echo $email;
                                                                                                                        } ?>" />
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="txt_upass" placeholder="Enter Password" />
                    </div>
                    <div class="clearfix"></div>
                    <hr />
                    <div class="form-group">
                        <button type="submit" class="btn btn-block btn-primary" name="btn-signup">
                            <i class="glyphicon glyphicon-open-file"></i>&nbsp;ยืนยัน
                        </button>
                    </div>
                    <br />
                    <label>เป็นสมาชิกอยู่แล้ว! <a href="index.php">เข้าสู่ระบบ</a></label>
                </form>
            </div>
        </div>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>