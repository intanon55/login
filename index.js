liff.init(
    {
      liffId: "1654195194-732m4xvP",
    },
    () => {
      if (liff.isLoggedIn()) {
        runApp();
      } else {
        liff.login();
      }
    },
    (err) => console.error(err.code, error.message)
  );
  
  function runApp() {
    liff
      .getProfile()
      .then((profile) => {
        id = profile.userId;
        console.log(id);
        getprofile(id);
      })
      .catch((err) => {
        console.log("error", err);
      });
  }
  function getprofile(id) {
    console.log(id);
  
    $.ajax({
      url: "index.php",
      type: "POST",
      data: {
        userId: id,
      },
      success: function (result) {
        if (result != 1) {
          var res = result.split(",");
          swal({
            title: "สวัสดีครับ",
            text: "ยินดีต้อนรับ " + res[0],
            icon: "success",
            button: "OK!",
          }).then((value) => {
            liff.closeWindow();
          });
        } else {
          swal({
            title: "คุณยังไม่ได้ลงทะเบียน",
            text: "กรุณาลงทะเบียนใช้งานก่อนครับ",
            icon: "error",
            button: "OK!",
          }).then((value) => {
            window.location.assign("https://liff.line.me/1654195194-732m4xvP");
          });
        }
      },
    });
  }
  