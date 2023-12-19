<?php
// 初期化関数
function wmd_init_option(){
    //プラグインを初めて有効化した時にデータベースに設定を保存
    //初期化の処理を行う
    if(!get_option('wmd_installed')){
        add_option('wmd_installed', 1);
    }
}
register_activation_hook(__FILE__, 'wmd_init_option'); // インストール・有効化時に初期化


function add_post_modified_column($columns) {
    $columns['post_modified'] = '更新日時';
    return $columns;
}
add_filter('manage_edit-page_columns', 'add_post_modified_column');

// カスタムカラムを追加
function custom_page_columns($column_name, $id) {
    if ($column_name === 'post_modified') {
        echo get_the_modified_date('Y年m月d日 g:i A', $id);
        $revisions = wp_get_post_revisions($id);
        if (!empty($revisions)) {
            $latest_revision = array_shift($revisions);
            $revision_id = $latest_revision->ID;
            $revision_link = esc_url(get_edit_post_link($revision_id));
            echo '<br><a href="' . $revision_link . '">リビジョンを比較</a>';
        }
    }
}
add_action('manage_page_posts_custom_column', 'custom_page_columns', 10, 2);
// 並び順変更を追加
function custom_page_sortable_columns($columns) {
    $columns['post_modified'] = 'post_modified';
    return $columns;
}
add_filter('manage_edit-page_sortable_columns', 'custom_page_sortable_columns');

?>
