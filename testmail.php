<?php
// Link libary https://github.com/PHPMailer/PHPMailer
// Download về và giải nén ra, sau đó import vào như dòng 5,6,7(Lưu ý patch để thư viện ở đâu thì patch vào đó) thì hàm mới hoạt động nhé
// Truyền các tham số cho hàm để set Mail Tự động(Lưu ý đã cấp quyền truy cập từ cài đặt google thì mới hoạt động)
// Link bài viết chi tiết: https://longnv.name.vn/lap-trinh-php-co-ban/gui-mail-voi-phpmailer
require "../PHPMailer-master/src/PHPMailer.php"; 
require "../PHPMailer-master/src/SMTP.php"; 
require '../PHPMailer-master/src/Exception.php'; 
function GuiMail( $emailNhan,$tenNguoiGui, $tenNguoiNhan, $tieuDe, $noiDung){   
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);//true:enables exceptions
    try {
        $mail->SMTPDebug = 0; //0,1,2: chế độ debug. khi chạy ngon thì chỉnh lại 0 nhé
        $mail->isSMTP();  
        $mail->CharSet  = "utf-8";
        $mail->Host = 'smtp.gmail.com';  //SMTP servers
        $mail->SMTPAuth = true; // Enable authentication
        $mail->Username = 'mail hỗ trợ gửi tin'; // SMTP username
        $mail->Password = 'mật khẩu mail';   // SMTP password 
        $mail->SMTPSecure = 'ssl';  // encryption TLS/SSL 
        $mail->Port = 465;  // port to connect to                
        $mail->setFrom('Mail hỗ trợ gửi tin(giống line 17)', $tenNguoiGui ); 
        $mail->addAddress($emailNhan, $tenNguoiNhan);
        $mail->isHTML(true);  // Set email format to HTML
        $mail->Subject = $tieuDe;
        $noidungthu = $noiDung; 
        $mail->Body = $noidungthu;
        $mail->smtpConnect( array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
                "allow_self_signed" => true
            )
        ));
        $mail->send();
        // echo "<script>alert('Gửi Mail Thành Công!!!!!') </script>";
    } catch (Exception $e) {
        echo 'Mail không gửi được. Lỗi: ', $mail->ErrorInfo;
    }
 }//function GuiMail //mail và tên người nhận  

$servername = "";
$username = "chuong";
$password = "123456";
$dbname = "baith101";
 
// tạo connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// kiểm tra connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
 
$sql = "SELECT sinhvien.`TenSV`, sinhvien.`Email`, dmsach.`TenSach`, muonsach.`NgayMuon`, muonsach.`NgayTra`, DATEDIFF( CURDATE(), muonsach.`NgayTra` ) AS 'HanTra' FROM `muonsach`, `sinhvien`, `dmsach` WHERE sinhvien.`MaSV` = muonsach.`MaSV` AND dmsach.`MaSach` = muonsach.`MaSach`;";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thư Viện</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
      body {
        margin-top: 20px;
        padding: 0 20px;
        background: #454d55;
      }

      .custom {
        box-shadow: 0 8px 32px 0 rgba( 255, 255, 255, 0.37 );
    }

    .mt-20 {
        margin-top: 20px;
    }
    button {
        margin: auto
    }
    </style>
</head>
<body>
    <table class="table custom table-dark">
    <thead>
      <tr>
        <th scope="col">Tên Sinh Viên</th>
        <th scope="col">Email Sinh Viên</th>
        <th scope="col">Tên Sách</th>
        <th scope="col">Ngày Mượn</th>
        <th scope="col">Ngày Trả</th>
        <th scope="col">Hạn Trả</th>
      </tr>
    </thead>
    <tbody>
      <?php 
            if (mysqli_num_rows($result) > 0) {
                // hiển thị dữ liệu trên trang
            while($row = mysqli_fetch_assoc($result)) {
        ?>
        <tr>
            <th scope="row"><?php echo $row['TenSV'] ?></th>
            <td><?php echo $row['Email']?></td>
            <td><?php echo $row['TenSach']?></td>
            <td><?php echo $row['NgayMuon']?></td>
            <td><?php echo $row['NgayTra']?></td>
            <td><?php echo $row['HanTra']?></td>
        </tr>
      <?php
                }
            }
        ?>
    </tbody>
  </table>
  <form action="" method="post">
    <button type = "submit" name = "send_mail" type="button" class="btn btn-warning">Nhấp vào để gửi mail sinh viên quá hạn</button>
  </form>
</body>
</html>

<?php 

if (isset($_POST['send_mail'])) {
        $sql = "SELECT sinhvien.`TenSV`, sinhvien.`Email`, dmsach.`TenSach`, muonsach.`NgayMuon`, muonsach.`NgayTra`, DATEDIFF( CURDATE(), muonsach.`NgayTra` ) AS 'HanTra' FROM `muonsach`, `sinhvien`, `dmsach` WHERE sinhvien.`MaSV` = muonsach.`MaSV` AND dmsach.`MaSach` = muonsach.`MaSach`;";

        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $noidung = '
            <i><b>Xin Chào!!! Đây là thư viện tự động</b></i></br>
            <p>Bạn <b><i>'.$row['TenSV'].'</i></b> có thuê từ thư viện quyển sách '.$row['TenSach'].'</p></br>'
            .'<p>Ngày mượn: '.$row['NgayMuon'].'<p/></br>'.
            '<p>Ngày trả: '.$row['NgayTra'].'<p/></br>'.
            'Thời gian quá hạn: '.$row['HanTra'].' ngày.'
            .
            '<p>Nay đã quá hạn vui lòng liên hệ thư viện để trả lại sách</p><br/>
            <p>Đây là Mail tự động vui lòng không trả lời!!!</p><br/>
            ';
            $tieude = 'Về việc trả sách đã quá hạn cho thư viện';
            $nguoiGui = 'Chương Leo';
            if($row['HanTra'] > 0) {
                GuiMail($row['Email'], $nguoiGui, $row['TenSV'], $tieude, $noidung);
            } 
        }
        echo "<script>alert('Gửi Mail Thành Công!!!!!') </script>";
    }
}

?>