<?php
require_once("config.php");
$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or
    die("Could not connect: " . mysql_error());
mysql_select_db(DB_DATABASE, $con);

function rand_string( $length ) {
	$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";	
	
	$size = strlen( $chars );
	for( $i = 0; $i < $length; $i++ ) {
		$str .= $chars[ rand( 0, $size - 1 ) ];
	}

	return $str;
}

$first_names = array('Cliff', 'Del', 'Virgen', 'Carolin', 'Alease', 'Page', 'Briana', 'Anthony', 'Noelia', 'Aurora', 'Merrilee', 'Mona', 'Elease', 'Kassandra', 'Kellie', 'Melodi', 'Socorro', 'Maire', 'Jerald', 'Shirleen', 'Yu', 'Christeen', 'Joline', 'Lieselotte', 'Angelica', 'Johnnie', 'Lora', 'Nisha', 'Min', 'Omer');

$last_names = array('Marez', 'Schlegel', 'Waiters', 'Gardin', 'Alejos', 'Faber', 'Quijas', 'Spanbauer', 'Sliger', 'Wragg', 'Maner', 'Salamone', 'Guest', 'Murray', 'Gwin', 'Geddie', 'Heyer', 'Fortman', 'Lachance', 'Sherrow', 'Frausto', 'Burgo', 'Hurdle', 'Kinman', 'Bone', 'Gilmartin', 'Bolds', 'Kofoed', 'Chagolla', 'Lavallee');

$emails = array('cliff@expensify.com', 'del@expensify.com', 'virgen@expensify.com', 'carolin@expensify.com', 'alease@expensify.com', 'page@expensify.com', 'briana@expensify.com', 'anthony@expensify.com', 'noelia@expensify.com', 'aurora@expensify.com', 'merrilee@expensify.com', 'mona@expensify.com', 'elease@expensify.com', 'kassandra@expensify.com', 'kellie@expensify.com', 'melodi@expensify.com', 'socorro@expensify.com', 'maire@expensify.com', 'jerald@expensify.com', 'shirleen@expensify.com', 'yu@expensify.com', 'christeen@expensify.com', 'joline@expensify.com', 'lieselotte@expensify.com', 'angelica@expensify.com', 'johnnie@expensify.com', 'lora@expensify.com', 'nisha@expensify.com', 'min@expensify.com', 'omer@expensify.com');

$phones = array('8222743624', '8993871069', '8220328313', '8440982420', '8229506857', '8993523112', '8555418643', '8224198495', '8445503184', '8444786909', '8330939198', '8445736594', '8552837416', '8332441977', '8996098072', '8335520473', '8225749914', '8555834505', '8550410235', '8229784421', '8990699741', '8552478421', '8447359707', '8338603823', '8118385797', '8993598703', '8334841827', '8228807046', '8227435415', '8226564699');

$twitters = array('marez', 'schlegel', 'waiters', 'gardin', 'alejos', 'faber', 'quijas', 'spanbauer', 'sliger', 'wragg', 'maner', 'salamone', 'guest', 'murray', 'gwin', 'geddie', 'heyer', 'fortman', 'lachance', 'sherrow', 'frausto', 'burgo', 'hurdle', 'kinman', 'bone', 'gilmartin', 'bolds', 'kofoed', 'chagolla', 'lavallee');

$n = 30;
for ($i = 1; $i <= $n; $i++) {
	$accounts_pk = $i;
	$pgid = rand(100000, 999999);
	$token = rand_string(6);
	$minor = 0;
	if (rand(0, 10) < 2)
		$minor = 1;
	$first_name = $first_names[$i];
	$last_name = $last_names[$i];
	$email = $emails[$i];
	$phone = $phones[$i];
	$twitter = $twitters[$i];
	
	query = "INSERT INTO accounts (accounts_pk, pgid, token, minor, first_name, last_name, email, phone_number, twitter) VALUES ('".$accounts_pk."', '".$pgid."', '".$token."', '".$minor."', '".$first_name."', '".$last_name."', '".$email."', '".$phone."', '".$twitter."')";
	mysql_query(query);
}
?>