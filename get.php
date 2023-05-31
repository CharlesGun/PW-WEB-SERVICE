<?php
#Web Service GET MySQL
#Meminta data dengan GET: Uji dengan membuka http://localhost/praktikumweb/api/mahasiswa dan http://localhost/praktikumweb/api.php/mahasiswa/1
# Gunakan aplikasi Postman untuk pengujian API/Web Service | www.getpostman.com
# ws-json-get.php
# Format Data: JSON
header('Content-type: application/json');
# Mendapatkan method yang digunakan: GET/POST/PUT/DELETE
# Cara 1: Menggunakan variabel $_SERVER
// $method = $_SERVER['REQUEST_METHOD'];

# Cara 2: Menggunakan getenv sehingga tidak perlu bekerja dengan variabel $_SERVER
$method = getenv('REQUEST_METHOD');
# This function is useful (compared to $_SERVER, $_ENV) because it searches $varname key in those array case-insensitive manner.


$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1)); //isi dari PATH_INFO = semua parameter yang berada setelah localhost/webservice/get.php contohnya "/mahasiswa/1" 
// di var request path info tadi displit dengan garis miring sehingga var request menjadi array berisi mahasiswa dan 1 
var_dump($request);
// echo "parameter 1 = ", ($request[0]);
// echo "\n", "parameter 2 = ",($request[1]), "\n";
// echo "parameter 3 = ",($request[2]),"\n";

function process_get($param){
    // Check parameter, kalau parameter pertama adalah mahasiswa maka if true
    if ($param[0] == "mahasiswa"){
        // Menggunakan data pada file dbconfig.php
        require_once 'dbconfig.php';
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

            // GET DATA BY ID, cek parameter ke 2 setelah parameter pertama "mahasiswa"
            // Kalau ada parameter kedua berarti if menjadi true
            if (!empty($param[1])) {
                $handle = $conn->prepare(" SELECT id, nama, npm FROM mahasiswa WHERE ID = :id ");
                $handle->bindParam(':id', $param[1], PDO::PARAM_INT);
                $handle->execute();
            } else {
                // GET ALL, kalo parameter ke 2 gak ada berarti else yang berjalan dan nampilin semua data
                $handle = $conn->query("SELECT id, nama, npm from mahasiswa");
            }
            // CHECK DATA IF THERE'S 1 or more, then true.
            if ($handle->rowCount()) {
                $status = 'Berhasil';
                $data = $handle->fetchAll(PDO::FETCH_ASSOC);
                $arr = array('status' => $status, 'data' => $data);
            } else {
                //NO DATA
                $status = "Tidak ada data";
                $arr = array('status' => $status);
            }
            // menampilkan data dengan format JSON
            echo json_encode($arr);
        } catch (PDOException $pe) {
            die(json_encode($pe->getMessage()));
        }
    } else{
        echo "parameter 1 bukan 'mahasiswa' atau tidak ada parameter";
    }
}

switch ($method){
    case 'PUT':
        process_put($request);
        break;
    case 'POST':
        process_post($request);
        break;
    case 'GET':
        process_get($request); //var request menjadi parameter di function process_get()
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
# Meminta data dengan GET: Uji dengan membuka http://localhost/praktikumweb/ws-json-get.php/mahasiswa dan http://localhost/praktikumweb/ws-json-get.php/mahasiswa/1
?>