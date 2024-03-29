<?php
// Generate Dokumen Dengan Format Json
header('Content-type: application/json');
// Fungsi Post
function process_post($param)
{
    // CHECK PARAM
    if ((count($param) == 1) && ($param[0] == "mahasiswa")) {
        require_once 'dbconfig.php';
        $dataNama = (isset($_POST['nama']) ? $_POST['nama'] : NULL);
        $dataNPM = (isset($_POST['npm']) ? $_POST['npm'] : NULL);
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
            $handle = $conn->prepare("INSERT INTO mahasiswa (nama, npm) VALUES

(:nama, :npm)");

            // mengisi param pada query
            $handle->bindParam(':nama', $dataNama);

            $handle->bindParam(':npm', $dataNPM);
            $handle->execute();
            if ($handle->rowCount()) {
                $status = 'Berhasil';
                $idTerakhir = $conn->lastInsertId();
                $arr = array('status' => $status, 'id' => $idTerakhir);
            } else {
                $status = "Gagal";
                $arr = array('status' => $status);
            }
            echo json_encode($arr);
        } catch (PDOException $pe) {
            die(json_encode($pe->getMessage()));
        }
    } else {
        echo "param kelebihan atau parameter bukan mahasiswa atau tidak ada
parameter";
    }
}
// Fungsi Put
function process_put($param)
{
    if ((count($param) == 2) && ($param[0] == "mahasiswa") &&
        ($_SERVER["CONTENT_TYPE"] == 'application/x-www-form-urlencoded')
    ) {
        require_once 'dbconfig.php';
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
            $handle = $conn->prepare("UPDATE mahasiswa SET nama=:nama,

npm=:npm, tanggal_tercatat=NOW() WHERE ID=:id");

            $dataID = $param[1];
            $handle->bindParam(':id', $dataID, PDO::PARAM_INT);
            $handle->bindParam(':nama', $dataNama);
            $handle->bindParam(':npm', $dataNPM);
            $handle->execute();
            if ($handle->rowCount()) {

                $status = 'Berhasil';
            } else {
                $status = "Gagal";
            }
            $arr = array('status' => $status, 'id' => $dataID, 'nama' =>

            $dataNama, 'npm' => $dataNPM);
            echo json_encode($arr);
        } catch (PDOException $pe) {
            die(json_encode($pe->getMessage()));
        }
    }
}
// Fungsi Get
function process_get($param)
{
    if ($param[0] == "mahasiswa") {
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
            if (!empty($param[1])) {
                $handle = $conn->prepare(" SELECT id, nama, npm FROM mahasiswa

WHERE ID = :id ");

                $handle->bindParam(':id', $param[1], PDO::PARAM_INT);
                $handle->execute();
            } else {
                $handle = $conn->query("SELECT id, nama, npm from mahasiswa");
            }
            if ($handle->rowCount()) {
                $status = 'Berhasil';
                $data = $handle->fetchAll(PDO::FETCH_ASSOC);
                $arr = array('status' => $status, 'data' => $data);
            } else {
                $status = "Tidak ada data";
                $arr = array('status' => $status);
            }
            echo json_encode($arr);
        } catch (PDOException $pe) {
            die(json_encode($pe->getMessage()));
        }
    } else {
        echo "parameter 1 bukan 'mahasiswa' atau tidak ada parameter";
    }
}
function process_delete($param)
{
    if ((count($param) == 2) && ($param[0] == "mahasiswa")) {
        require_once 'dbconfig.php';
        try {
            $conn = new PDO(
                "mysql:host=$host;dbname=$dbname",
                $username,

                $password,

                array(
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_PERSISTENT => false
                )
            );
            $handle = $conn->prepare(" DELETE FROM mahasiswa WHERE ID=:id ");
            $dataID = $param[1];
            $handle->bindParam(':id', $dataID, PDO::PARAM_INT);
            $handle->execute();
            if ($handle->rowCount()) {
                $status = 'Berhasil';
            } else {
                $status = "Gagal";
            }
            $arr = array('status' => $status, 'id' => $dataID);
            echo json_encode($arr);
        } catch (PDOException $pe) {
            die(json_encode($pe->getMessage()));
        }
    }
}
// Fungsi Handle_error
function handle_error($param)
{
    die("Terdapat kesalahan dalam permintaan!");
}
$method = $_SERVER['REQUEST_METHOD'];
$request = explode("/", trim(@$_SERVER['PATH_INFO'], "/"));
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
    case 'DELETE':
        process_delete($request);
        break;
    default:
        handle_error($request);
        break;
}
?>