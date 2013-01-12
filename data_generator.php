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

$phones = array('(822) 274-3624', '(899) 387-1069', '(822) 032-8313', '(844) 098-2420', '(822) 950-6857', '(899) 352-3112', '(855) 541-8643', '(822) 419-8495', '(844) 550-3184', '(844) 478-6909', '(833) 093-9198', '(844) 573-6594', '(855) 283-7416', '(833) 244-1977', '(899) 609-8072', '(833) 552-0473', '(822) 574-9914', '(855) 583-4505', '(855) 041-0235', '(822) 978-4421', '(899) 069-9741', '(855) 247-8421', '(844) 735-9707', '(833) 860-3823', '(811) 838-5797', '(899) 359-8703', '(833) 484-1827', '(822) 880-7046', '(822) 743-5415', '(822) 656-4699');

$twitters = array('@marez', '@schlegel', '@waiters', '@gardin', '@alejos', '@faber', '@quijas', '@spanbauer', '@sliger', '@wragg', '@maner', '@salamone', '@guest', '@murray', '@gwin', '@geddie', '@heyer', '@fortman', '@lachance', '@sherrow', '@frausto', '@burgo', '@hurdle', '@kinman', '@bone', '@gilmartin', '@bolds', '@kofoed', '@chagolla', '@lavallee');

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