<?php 

/**
 * SharedController Controller
 * @category  Controller / Model
 */
class SharedController extends BaseController{
	
	/**
     * siswa_kota_id_option_list Model Action
     * @return array
     */
	function siswa_kota_id_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT id AS value,nama AS label FROM kota ORDER BY id";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * user_email_value_exist Model Action
     * @return array
     */
	function user_email_value_exist($val){
		$db = $this->GetModel();
		$db->where("email", $val);
		$exist = $db->has("user");
		return $exist;
	}

	/**
     * getcount_siswa Model Action
     * @return Value
     */
	function getcount_siswa(){
		$db = $this->GetModel();
		$sqltext = "SELECT COUNT(*) AS num FROM siswa";
		$queryparams = null;
		$val = $db->rawQueryValue($sqltext, $queryparams);
		
		if(is_array($val)){
			return $val[0];
		}
		return $val;
	}

	/**
     * getcount_kota Model Action
     * @return Value
     */
	function getcount_kota(){
		$db = $this->GetModel();
		$sqltext = "SELECT COUNT(*) AS num FROM kota";
		$queryparams = null;
		$val = $db->rawQueryValue($sqltext, $queryparams);
		
		if(is_array($val)){
			return $val[0];
		}
		return $val;
	}

	/**
     * getcount_user Model Action
     * @return Value
     */
	function getcount_user(){
		$db = $this->GetModel();
		$sqltext = "SELECT COUNT(*) AS num FROM user";
		$queryparams = null;
		$val = $db->rawQueryValue($sqltext, $queryparams);
		
		if(is_array($val)){
			return $val[0];
		}
		return $val;
	}

	/**
	* piechart_jumlahsiswamenurutjeniskelamin Model Action
	* @return array
	*/
	function piechart_jumlahsiswamenurutjeniskelamin(){
		
		$db = $this->GetModel();
		$chart_data = array(
			"labels"=> array(),
			"datasets"=> array(),
		);
		
		//set query result for dataset 1
		$sqltext = "SELECT CASE
        WHEN jenkel = '1' THEN 'Laki - Laki'
        WHEN jenkel = '2' THEN 'Perempuan'
        ELSE jenkel
    END AS jenis_kelamin,
    COUNT(*) AS total_siswa
FROM siswa
GROUP BY jenkel;"
;
		$queryparams = null;
		$dataset1 = $db->rawQuery($sqltext, $queryparams);
		$dataset_data =  array_column($dataset1, 'total_siswa');
		$dataset_labels =  array_column($dataset1, 'jenis_kelamin');
		$chart_data["labels"] = array_unique(array_merge($chart_data["labels"], $dataset_labels));
		$chart_data["datasets"][] = $dataset_data;

		return $chart_data;
	}

	/**
	* doughnutchart_jumlahsiswaberdasarkanasalkota Model Action
	* @return array
	*/
	function doughnutchart_jumlahsiswaberdasarkanasalkota(){
		
		$db = $this->GetModel();
		$chart_data = array(
			"labels"=> array(),
			"datasets"=> array(),
		);
		
		//set query result for dataset 1
		$sqltext = "SELECT kota.id, kota.nama, COUNT(siswa.id) AS total_siswa
FROM kota
LEFT JOIN siswa ON kota.id = siswa.kota_id
GROUP BY kota.id, kota.nama DESC LIMIT 6;
";
		$queryparams = null;
		$dataset1 = $db->rawQuery($sqltext, $queryparams);
		$dataset_data =  array_column($dataset1, 'id');
		$dataset_labels =  array_column($dataset1, 'nama');
		$chart_data["labels"] = array_unique(array_merge($chart_data["labels"], $dataset_labels));
		$chart_data["datasets"][] = $dataset_data;

		return $chart_data;
	}

	/**
	* barchart_jumlahsiswabytahunlahir Model Action
	* @return array
	*/
	function barchart_jumlahsiswabytahunlahir(){
		
		$db = $this->GetModel();
		$chart_data = array(
			"labels"=> array(),
			"datasets"=> array(),
		);
		
		//set query result for dataset 1
		$sqltext = "SELECT YEAR(s.tgl_lahir), count(s.id) as jmlhSiswa FROM siswa s GROUP BY YEAR(s.tgl_lahir)";
		$queryparams = null;
		$dataset1 = $db->rawQuery($sqltext, $queryparams);
		$dataset_data =  array_column($dataset1, 'jmlhSiswa');
		$dataset_labels =  array_column($dataset1, 'YEAR(s.tgl_lahir)');
		$chart_data["labels"] = array_unique(array_merge($chart_data["labels"], $dataset_labels));
		$chart_data["datasets"][] = $dataset_data;

		return $chart_data;
	}

}
