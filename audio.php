<?php
function suara($text)
{
	$text = strtoupper($text);

	$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/';
	$audio_url = $base_url . 'audio/';

	$number_list = [
		0 => $audio_url . 'angka/WAVE_NOL.wav',
		1 => $audio_url . 'angka/WAVE_SATU.wav',
		2 => $audio_url . 'angka/WAVE_DUA.wav',
		3 => $audio_url . 'angka/WAVE_TIGA.wav',
		4 => $audio_url . 'angka/WAVE_EMPAT.wav',
		5 => $audio_url . 'angka/WAVE_LIMA.wav',
		6 => $audio_url . 'angka/WAVE_ENAM.wav',
		7 => $audio_url . 'angka/WAVE_TUJUH.wav',
		8 => $audio_url . 'angka/WAVE_DELAPAN.wav',
		9 => $audio_url . 'angka/WAVE_SEMBILAN.wav',
		10 => $audio_url . 'angka/WAVE_SEPULUH.wav',
		11 => $audio_url . 'angka/WAVE_SEBELAS.wav'
	];

	if (preg_match('/^\d+$/', $text)) {
		$number = abs((int)$text);
		if ($number < 12)
			return in_array($number, array_keys($number_list)) ? [$number_list[$number]] : [];
		else if ($number < 20)
			return array_merge(suara($number % 10), [$audio_url . 'hitung/WAVE_BELAS.wav']);
		else if ($number < 100)
			return array_merge(suara(floor($number / 10)), [$audio_url . 'hitung/WAVE_PULUH.wav'], suara($number % 10));
		else if ($number < 200)
			return array_merge([$audio_url . 'angka/WAVE_SERATUS.wav'], suara($number % 100));
		else if ($number < 1000)
			return array_merge(suara(floor($number / 100)), [$audio_url . 'hitung/WAVE_RATUS.wav'], suara($number % 100));
		else if ($number < 2000)
			return array_merge([$audio_url . 'angka/WAVE_SERIBU.wav'], suara($number % 1000));
		else if ($number < 1000000)
			return array_merge(suara(floor($number / 1000)), [$audio_url . 'hitung/WAVE_RIBU.wav'], suara($number % 1000));
	} else {
		$return = [];
		foreach (str_split($text) as $key => $value)
			if ($value >= 'A' && $value <= 'Z') $return[] = $audio_url . 'huruf/WAVE_' . $value . '.wav';
			else if(preg_match('/^\d+$/', $value)) $return = array_merge($return, suara($value));
		return $return;
	}
}

echo json_encode(suara($_POST['input']));
