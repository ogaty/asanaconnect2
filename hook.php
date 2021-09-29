<?php

// 記事投稿
function to_asana($post_ID)
{
    $post = get_post($post_ID);
    $token = get_option('asana_personal_token');
    $gid = get_option('asana_project_gid');

    if (empty($token) || empty($gid)) {
        return;
    }

    $data = [
        'data' => [
            'name'     => $post->post_title . 'が公開されました',
            'projects' => $gid,
        ]
    ];
    $headers = [
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . $token,
    ];

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL            => 'https://app.asana.com/api/1.0/tasks',
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($data),
    ]);
    $response = curl_exec($curl);
    curl_close($curl);
}


add_action('publish_post', 'to_asana');


// dashboard
add_action('wp_dashboard_setup', 'asana_dashboard_widgets');
function asana_dashboard_widgets()
{
    wp_add_dashboard_widget('asana_theme_options_widget', 'Asana My Tasks', 'asana_widgets_callback');
}

function asana_widgets_callback()
{
    $token = get_option('asana_personal_token');
    $user_task_lists = get_option('asana_user_task_lists');
    $user_task_list_gids = explode(',', $user_task_lists);

    foreach ($user_task_list_gids as $user_task_list_gid) {
        $workspace_url = 'https://app.asana.com/api/1.0/user_task_lists/' . $user_task_list_gid;
        $response = curl_get($workspace_url, $token);
        $workspaceNameJson = json_decode($response, true);
        $workspaceName = $workspaceNameJson['data']['name'];

        $user_task_lists_url = 'https://app.asana.com/api/1.0/user_task_lists/' . $user_task_list_gid . '/tasks?opt_fields=gid,name,due_on&completed_since=' . date('Y-m-d');
        $response = curl_get($user_task_lists_url, $token);
        $tasks = json_decode($response, true);

        echo '<h2>' . $workspaceName . '</h2>';
        echo '<table>';

        foreach ($tasks['data'] as $task) {
            echo '<tr>';
            echo '<td><a href="https://app.asana.com/0/' . $user_task_list_gid . '/' . $task['gid'] . '" target="_blank">' . $task['name'] . '</a></td>';
            echo '<td>' . $task['due_on'] . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    }
}

function curl_get($url, $token) {
    $headers = [
        'Accept: application/json',
        'Authorization: Bearer ' . $token,
    ];

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL            => $url,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => $headers,
    ]);
    $response = curl_exec($curl);
    curl_close($curl);

    return $response;
}
