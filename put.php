<?php
# Web Service PUT MySQL
# Mengubah data dengan PUT: Uji dengan membuka http://localhost/praktikumweb/api/mahasiswa/1 menggunakan metode PUT. Data nama dan npm harus disertakan
# Gunakan aplikasi Postman untuk pengujian API/Web Service | www.getpostman.com
# ws-json-put.php
# Format Data: JSON
header('Content-type: application/json');
# Mendapatkan method yang digunakan: GET/POST/PUT/DELETE
# Cara 1: Menggunakan variabel $_SERVER
# $method = $_SERVER['REQUEST_METHOD'];

# Cara 2: Menggunakan getenv sehingga tidak perlu bekerja dengan variabel $_SERVER
$method = getenv('REQUEST_METHOD');
# This function is useful (compared to $_SERVER, $_ENV) because it searches $varname key in those array case-insensitive manner.


$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));
function process_put($param){
    if ((count($param) == 2) and $param[0] == "mahasiswa" and
        $_SERVER["CONTENT_TYPE"] == 'application/x-www-form-urlencoded'
    ) {
        require_once 'dbconfig.php';
        # Mendapatkan nilai yang disematkan pada body PUT, bisa juga untuk POST

        # Data harus dikirimkan dalam body dengan format x-www-form-urlencoded tidak boleh form-data

        // get data from form
        parse_str(file_get_contents('php://input'), $data);
        $dataNama = $data['nama'];
        $dataNPM = $data['npm'];
        try {
            $conn = new PDO(
                "mysql:host=$host;dbname=$dbname",
                $username,
                $password,
                array(
                    \PDO::ATTR_ERRMODE =>
                    \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_PERSISTENT => false
                )
            );
            $handle = $conn->prepare("UPDATE mahasiswa SET nama=:nama, npm=:npm, tanggal_tercatat=NOW() WHERE ID=:id");
            $dataID = $param[1]; //parameter ke 2 menjadi id untuk kondisi WHERE ID = :id di query
            // mengisi param pada query
            $handle->bindParam(':id', $dataID, PDO::PARAM_INT);
            $handle->bindParam(':nama', $dataNama);
            $handle->bindParam(':npm', $dataNPM);
            $handle->execute();
            if ($handle->rowCount()) {
                $status = 'Berhasil';
            } else {
                $status = "Gagal";
            }
            $arr = array('status' => $status, 'id' => $dataID, 'nama' =>$dataNama, 'npm' => $dataNPM);
            echo json_encode($arr);
        } catch (PDOException $pe) {
            die(json_encode($pe->getMessage()));
        }
    }
}
switch ($method) {
    case 'PUT':
        process_put($request);
        break;
    case 'POST':
        process_post($request);
        break;
    case 'GET':
        process_get($request);
        break;
    case 'HEAD':
        process_head($request);
        break;
    case 'DELETE':
        process_delete($request);
        break;
    case 'OPTIONS':
        process_options($request);
        break;
    default:
        handle_error($request);
        break;
}
    # Gunakan aplikasi Postman untuk pengujian API/Web Service | www.getpostman.com
    # Mengubah data dengan PUT: Uji dengan membuka http://localhost/praktikumweb/ws-json-put.php/mahasiswa/1 menggunakan metode PUT. Data nama dan npm harus disertakan
?>