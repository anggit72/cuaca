<?php
	include_once("config.php");
	
	// Wajib, untuk identitas di browser agar ditampilkan dalam format XML
	header('Content-Type: text/xml');
	
	// Cek query string Provinsi
	if(isset($_GET['provinsi'])) {
		$Prov = $mysqli->prepare($_GET['provinsi']);
		$resultProv = $mysqli->prepare("SELECT kabkota, provinsi FROM data_kecamatan WHERE provinsi = '$Prov' GROUP BY kabkota");
        $resultProv->execute();
		// Memulai awal tag data XML
		echo "<?xml version='1.0' encoding='UTF-8' ?>";

		// Memulai ambil data dari query MySQL
		$resApiProv = $resultProv->setFetchMode(PDO::FETCH_ASSOC);

		//Cek baris query MySQL ada
		if(!empty($resApiProv['provinsi'])) {
			echo "<provinsi nama=\"" . $resApiProv['provinsi'] . "\" copyright=\"" . date('Y') . "-BMKG\">";
			while($resApiKab = $resultProv->setFetchMode(PDO::FETCH_ASSOC)) {
				echo "<item";
				echo " kabkota=\"" . $resApiKab['kabkota'] . "\"";
				echo "></item>";
			}
			echo "</provinsi>";
		} else {
			echo "<provinsi>Tidak ditemukan!</provinsi>";
		}
		// Cek query string Kabupaten/Kota
	} else if(isset($_GET['kabkota'])) {
		$KabKota = $mysqli->prepare($_GET['kabkota']);
		// Hanya untuk real-time data cuaca gunakan: AND TIMESTAMPDIFF(HOUR, DATE_ADD(data_cuaca.tanggal, INTERVAL 7 HOUR), NOW()) < 3
		// $resultKabKota = mysqli_query($mysqli, "SELECT kecamatan, data_kecamatan.id_kec, latitude, longitude, kabkota, provinsi, tanggal, weather, t, hu, ws, wd FROM data_kecamatan INNER JOIN data_cuaca ON data_kecamatan.id_kec = data_cuaca.id_kec WHERE data_kecamatan.kabkota = '$KabKota' AND TIMESTAMPDIFF(HOUR, DATE_ADD(data_cuaca.tanggal, INTERVAL 7 HOUR), NOW()) < 3 GROUP BY data_kecamatan.id_kec");
		$resultKabKota = $mysqli->prepare("SELECT kecamatan, data_kecamatan.id_kec, latitude, longitude, kabkota, provinsi, tanggal, weather, t, hu, ws, wd FROM data_kecamatan INNER JOIN data_cuaca ON data_kecamatan.id_kec = data_cuaca.id_kec WHERE data_kecamatan.kabkota = '$KabKota' GROUP BY data_kecamatan.id_kec");
        $resultKabKota->execute();
		// Memulai awal tag data XML
		echo "<?xml version='1.0' encoding='UTF-8' ?>";

		// Memulai ambil data dari query MySQL
		$resApiKabKota = $resultKabKota->setFetchMode(PDO::FETCH_ASSOC);

		//Cek baris query MySQL ada
		if(!empty($resApiKabKota['kabkota'])) {
			echo "<kabkota nama=\"" . $resApiKabKota['kabkota'] . "\" provinsi=\"" . $resApiKabKota['provinsi'] . "\" copyright=\"" . date('Y') . "-BMKG\">";
			while($resApiKec = $resultKabKota->setFetchMode(PDO::FETCH_ASSOC)) {
				echo "<item";
				echo " kecamatan=\"" . $resApiKec['kecamatan'] . "\"";
				echo " id=\"" . $resApiKec['id_kec'] . "\"";
				echo " latitude=\"" . $resApiKec['latitude'] . "\"";
				echo " longitude=\"" . $resApiKec['longitude'] . "\"";
				echo " date=\"" . $resApiKec['tanggal'] . "\"";
				echo " weather=\"" . $resApiKec['weather'] . "\"";
				echo " t=\"" . $resApiKec['t'] . "\"";
				echo " hu=\"" . $resApiKec['hu'] . "\"";
				echo " ws=\"" . $resApiKec['ws'] . "\"";
				echo " wd=\"" . $resApiKec['wd'] . "\"";
				echo "></item>";
			}
			echo "</kabkota>";
		} else {
			echo "<kabkota>Tidak ditemukan!</kabkota>";
		}
		
		// Cek query string ID Kecamatan
	} else if(isset($_GET['id'])) {
		$idKec = $mysqli->prepare($_GET['id']);
		// Hanya untuk real-time data cuaca gunakan: TIMESTAMPDIFF(HOUR, DATE_ADD(data_cuaca.tanggal, INTERVAL 7 HOUR), NOW()) < 6)
		// $resultCuaca = mysqli_query($mysqli, "SELECT data_kecamatan.id_kec, kecamatan, kabkota, provinsi, latitude, longitude, tanggal, t, hu, weather, ws, wd FROM data_kecamatan INNER JOIN data_cuaca ON data_kecamatan.id_kec = data_cuaca.id_kec WHERE data_kecamatan.id_kec = '$idKec' AND TIMESTAMPDIFF(HOUR, DATE_ADD(data_cuaca.tanggal, INTERVAL 7 HOUR), NOW()) < 6");
		$resultCuaca = $mysqli->prepare("SELECT data_kecamatan.id_kec, kecamatan, kabkota, provinsi, latitude, longitude, tanggal, t, hu, weather, ws, wd FROM data_kecamatan INNER JOIN data_cuaca ON data_kecamatan.id_kec = data_cuaca.id_kec WHERE data_kecamatan.id_kec = '$idKec'");
		$resultCuaca->execute();
		// Memulai awal tag data XML
		echo "<?xml version='1.0' encoding='UTF-8' ?>";
		echo "<cuaca>";
		
		// Memulai ambil data dari query MySQL
		$resApiLok = $resultCuaca->setFetchMode(PDO::FETCH_ASSOC);
		
		//Cek baris query MySQL ada
		if(!empty($resApiLok['id_kec'])) {
			echo "<lokasi";
			echo " id=\"" . $resApiLok['id_kec'] . "\"";
			echo " latitude=\"" . $resApiLok['latitude'] . "\"";
			echo " longitude=\"" . $resApiLok['longitude'] . "\"";
			echo " kecamatan=\"" . $resApiLok['kecamatan'] . "\"";
			echo " kabkota=\"" . $resApiLok['kabkota'] . "\"";
			echo " provinsi=\"" . $resApiLok['provinsi'] . "\"";
			echo "></lokasi>";
			echo "<data>";

			while($resApiData = $resultCuaca->setFetchMode(PDO::FETCH_ASSOC)) {
				echo "<item";
				echo " date=\"" . $resApiData['tanggal'] . "\"";
				echo " t=\"" . $resApiData['t'] . "\"";
				echo " hu=\"" . $resApiData['hu'] . "\"";
				echo " weather=\"" . $resApiData['weather'] . "\"";
				echo " ws=\"" . $resApiData['ws'] . "\"";
				if($resApiData['ws'] == 0) {
					echo " wd=\"CALM\"";
				} else {
					echo " wd=\"" . $resApiData['wd'] . "\"";
				}
				echo "></item>";
			}
			echo "</data>";
			echo "<copyright>" . date('Y') . "-BMKG</copyright>";
			
		} else {
			echo "Tidak ditemukan!";
		}
		echo "</cuaca>";
	} else {
		echo "<bmkg>Tidak ditemukan!</bmkg>";
	}
?>