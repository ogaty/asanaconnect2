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


add_action('wp_dashboard_setup', 'asana_dashboard_widgets');
function asana_dashboard_widgets() {
	wp_add_dashboard_widget('asana_theme_options_widget', 'Asana My Tasks', 'asana_widgets_callback');
}

function asana_widgets_callback() {
	$token = get_option('asana_personal_token');
	$headers = [
		'Accept: application/json',
		'Authorization: Bearer ' . $token,
	];

        $curl = curl_init();
        curl_setopt_array($curl, [
          CURLOPT_URL => 'https://app.asana.com/api/1.0/user_task_lists/1126733236832578/tasks?completed_since=' . date('Y-m-d'),
          CURLOPT_SSL_VERIFYPEER => true,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_HTTPHEADER => $headers,
        ]);
        $response = curl_exec($curl);
        curl_close($curl);
	$tasks = json_decode($response, true);

	echo '<table>';

	foreach ($tasks['data'] as $task) {
            echo '<tr>';
	    echo '<td><a href="https://app.asana.com/0/1126733236832578/'. $task['gid'] .'" target="_blank">' . $task['name'] . '</a></td>';
	    echo '</tr>';
	}
	echo '</table>';
}
