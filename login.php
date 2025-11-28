<?php
error_reporting(0);
date_default_timezone_set('Asia/Ho_Chi_Minh');
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Origin, X-Requested-With, Accept');

/*==================================================
= 🔥 FIREBASE CONFIG (ĐÃ GẮN CỦA BẠN)              =
==================================================*/
$FIREBASE_DB_URL = "https://zubu-site-default-rtdb.asia-southeast1.firebasedatabase.app";
$FIREBASE_SECRET = "erN1DttMyAvvjKfgxlvHbfzZc3tCbsSZ2ilWQ93i";
/*==================================================*/

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit(json_encode(["status"=>"error","message"=>"Có Cái Chim, Thách M Xem Được, Xem Được T Kệ =))"]));
}

if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) 
    || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
    header("HTTP/1.1 403 Forbidden");
    exit("Truy cập bị từ chối.");
}

$ua = $_SERVER['HTTP_USER_AGENT'];
$blocked = ["python-requests","curl","Wget","libwww-perl"];
foreach ($blocked as $a) {
    if (stripos($ua, $a) !== false) {
        header("HTTP/1.1 403 Forbidden");
        exit("Truy cập bị từ chối.");
    }
}

$data = json_decode(file_get_contents("php://input"), true);
$user = trim($data["user"] ?? "");
$pass = trim($data["pass"] ?? "");

if ($user == "" || $pass == "") {
    exit(json_encode(["status"=>"error","message"=>"Thiếu tài khoản hoặc mật khẩu."]));
}

$firebaseUrl = "$FIREBASE_DB_URL/users/$user.json?auth=$FIREBASE_SECRET";

$res = file_get_contents($firebaseUrl);
if ($res === "null") {
    exit(json_encode(["status"=>"fail","message"=>"Sai tài khoản hoặc mật khẩu."]));
}

$info = json_decode($res, true);

if ($info["password"] !== $pass) {
    exit(json_encode(["status"=>"fail","message"=>"Sai mật khẩu."]));
}

echo json_encode(["status"=>"success","message"=>"Đăng nhập thành công! Đợi Trang Một Xíu"]);
