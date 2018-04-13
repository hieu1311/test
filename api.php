<?php
$content = file_get_contents('php://input');
$POST_DATA = json_decode($content, TRUE);
function deposit($agentID,$balance,$date,$note){
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
	curl_setopt($ch,CURLOPT_URL,'http://xuatve.maybay.net/Login.aspx');
	curl_setopt($ch,CURLOPT_COOKIEFILE,'cookie.txt');
	curl_setopt($ch,CURLOPT_COOKIEJAR,'cookie.txt');

	$field = array(
		'__VIEWSTATE' => 'acaVAow8ebtlQp76oKhfjgc9qp7J+55Y2CNPLI8em66URykE+wz625NyNU/cP0t1XzE3nC6zlUF284Pnpwe7mQlatjWuj/jntcqCIzvGlwa1J1ntKQ+xwi52+/iscAqKoqeMpAhsqblz9HMLxiSqD4Synwe46XEvBnkSz5NXNiB8OnldbYK1yleo0lDla73pbCWyWSyUuGusgbGpdIGd++0sJ1PhPoxxidfAG2bErJQ=',
		'__VIEWSTATEGENERATOR' => 'C2EE9ABB',
		'__EVENTVALIDATION' => '2AUvLL6eOZkhrJrqY1Eajlr2uN07CB2rVtAwpkpldBQENKSBPe51wnR253gTNt9UoW7ZIUaeRBOcNXla4zQdwmJYH2V8G1h6pkpywXa0JXn/m7CNh1e9jYbDuOPDRpOaDUxm8uYUfr2e345AgRs1YAsb5vP6A+wdCFL0nOQmbjrVwZ97prdOH8zFbSyf4+U5',
		'txtAgentCode' => 'tazk',
		'txtUsername' => 'admin',
		'txtPassword' => 'QUAN.0981615083',
		'btnLogin' => 'Đăng nhập'
	);
	$data= http_build_query($field);
	curl_setopt($ch,CURLOPT_POST,true);
	curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
	curl_exec($ch);

	curl_setopt($ch,CURLOPT_URL,'http://xuatve.maybay.net/Page/NewDeposit.aspx');
	curl_exec($ch);
	curl_setopt($ch,CURLOPT_POST,true);
	$field = array(
		'__VIEWSTATE' => 'N6mxjHaUf02I0L0+BjBfqT89tRLNp4rTF7fLZjYGLzFegBM2DxF1W0/h2NuAVzSiUV4wh8gDMGmaSiRkX+ongvaxDxlAGsAXKJAx9Y2M+uSoVHqIpfDJzk1wji6JyUP/L6WVALlPvqtxfNOZVGEIZA/NByvZM775wR0cPWsHi2d3mNj7Mwu0tCYvCYMge+W6qBS4A54fRBJZ4fGp8rltdLYpxTXTiI88SktEppJgNT135rr/sFRnFig4oGQLoiGChE3eGpOBBj/xtRVAdYnx1ZwrCaoNinozRFN8ATap0cE=',
		'__VIEWSTATEGENERATOR' => 'E2CCCBEF',
		'__EVENTVALIDATION' => '8pP0KDEJKD3Jp0GjSgNO1/PVgiPGUFWA45+K5aM5QAngT3Sz6EuMSduughL/JwjSCkKgJtm8gT+YgEs1RQfYIgzgUr2U4WdtRHn+dPGCfYCUyOkJoUnuClOxBavk+38RFQGFLnkKv/ipnW3UwfG3d/7U470Oc8H2bumkIwt3Lywiq1Lolg6OGu0arYpgsXoXbKnHuBZPLPDL+jG8KCpSOpYbrcKbMsG3RXG/Fsx3TjI=',
		'ctl00$ContentPlaceHolder1$hdfAgentID' => $agentID,
		'ctl00$ContentPlaceHolder1$txtRefCode' => '',
		'ctl00$ContentPlaceHolder1$txtTransactTime' => $date,
		'ctl00$ContentPlaceHolder1$txtValue' => $balance,
		'ctl00$ContentPlaceHolder1$txtDescription' => $note,
		'ctl00$ContentPlaceHolder1$btnLuu' => 'Lưu lại'
	);
	$data = http_build_query($field);
	curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
	curl_exec($ch);
	curl_close($ch);
}

function prString($str){
	$begin_point = strpos($str, '+');
	$end_point = strpos($str, 'VND');
	$balance = substr($str, ($begin_point+1), ($end_point-$begin_point-1));
	$begin_point = strpos($str, 'luc');
	$date = substr($str, ($begin_point+4), 10);
	$date = str_replace('-','/',$date);
	$begin_point = strpos($str, 'TVN');
	$note = substr($str, $begin_point, 8);
	$agentID = substr($note,3,5);
	$DATA = Array(
		'balance' => $balance,
		'date' => $date,
		'note' => $note,
		'agentID' => $agentID
	);
	return $DATA;
}

$str = $POST_DATA['MESS'];
if(strpos($str,'TVN')!=false){
	$data = prString($str);
	$agentID = $data['agentID'];
	$balance = $data['balance'];
	$date = $data['date'];
	$note = $data['note'];
	deposit($agentID,$balance,$date,$note);
}


?>
