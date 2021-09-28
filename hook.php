<?php

function to_asana($post_ID) {
	$post = get_post($post_ID);
	$token = get_option('asana_personal_token');
	$gid = get_option('asana_project_gid');

	if (empty($token) || empty($gid)) {
		return;
	}

	$data = [
		'data' => [
			'name' => $post->post_title . 'が公開されました',
			'projects' => '1200940239792522',
		]
        ];
	$headers = [
		'Content-Type: application/json',
		'Accept: application/json',
		'Authorization: Bearer ' . $token,
	];

        $curl = curl_init();
        curl_setopt_array($curl, [
          CURLOPT_URL => 'https://app.asana.com/api/1.0/tasks',
          CURLOPT_SSL_VERIFYPEER => true,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_HTTPHEADER => $headers,
          CURLOPT_POST => true,
          CURLOPT_POSTFIELDS => json_encode($data),
        ]);
        $response = curl_exec($curl);
        curl_close($curl);
}


add_action( 'publish_post',  'to_asana');
