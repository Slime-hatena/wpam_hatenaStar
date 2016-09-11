<?php
/*
Plugin Name:はてなスター for Wordpress
Plugin URI: (プラグインの説明と更新を示すページの URI)
Description:Wordpressにはてなスターの機能を追加します。
Version:1.0
Author:Slime_hatena
Author URI:https://twitter.com/Slime_hatena
License:MIT
*/

//--------------------------------------------------------------------------
//
//  管理画面>設定>プラグインページを追加
//
//--------------------------------------------------------------------------

// ヘッダーに出力するフック
add_action('wp_head','wpam_hatenaStar');

// 管理メニューのアクションフック
add_action('admin_menu', 'wpam_hatenaStar_setting');
add_action( 'admin_init', 'register_wpam_hatenaStar_setting' );

// アクションフックのコールバッック関数
function wpam_hatenaStar_setting () {
    // 設定メニュー下にサブメニューを追加:
    add_options_page('はてなスター設定', 'はてなスター設定', 'manage_options', 'wpam_hatenaStar', 'wpam_hatenaStar_settingPage');
}

function register_wpam_hatenaStar_setting() {
    register_setting('wpam_hatenaStar' , 'wpam_hatenaStar_token');
    register_setting('wpam_hatenaStar' , 'wpam_hatenaStar_topPage');
    register_setting('wpam_hatenaStar' , 'wpam_hatenaStar_singlePage');
    register_setting('wpam_hatenaStar' , 'wpam_hatenaStar_fixedPage');
    register_setting('wpam_hatenaStar' , 'wpam_hatenaStar_categoryPage');
    register_setting('wpam_hatenaStar' , 'wpam_hatenaStar_monthPage');
    register_setting('wpam_hatenaStar' , 'wpam_hatenaStar_otherPage');
}



// プラグインページのコンテンツを表示
function wpam_hatenaStar_settingPage () {
    // 設定変更画面を表示する
    ?>

  <div class="wrap">
    <h2>はてなスター設定</h2> wpam_hatenaStar_
    <form method="post" action="options.php">
      <?php
    
    var_dump(get_option('wpam_hatenaStar_setting'));
    
    settings_fields( 'wpam_hatenaStar' );
    do_settings_sections( 'default' );
    ?>

        <h3>サイト設定</h3>

        <table class="form-table">
          <tr valign="top">
            <th scope="row">はてなスター トークン(*)</th>
            <td>
              <input type="text" name="wpam_hatenaStar_token" value="<?php echo get_option('wpam_hatenaStar_token'); ?>" />
            </td>
          </tr>
        </table>

        <?php
    // ループのセッティングする
    $menu_index = array(
    "topPage" => "トップページ",
    "singlePage" => "シングルページ",
    "fixedPage" => "固定ページ",
    "categoryPage" => "カテゴリページ",
    "monthPage" => "月別ページ",
    "otherPage" => "その他のページ"
    );
    
    echo '<table class="form-table">';
    
    foreach ($menu_index as $key => $value) {
        
        $headding_selected = array("","","","","","","");
        
        
        echo "wpam_hatenaStar_" . $key . "→" . get_option('wpam_hatenaStar_' . $key) . "<br>";
        
        
        switch (get_option('wpam_hatenaStar_' . $key)) {
            case 'h1':
                $headding_selected[0] = "selected";
                break;
            case 'h2':
                $headding_selected[1] = "selected";
                break;
            case 'h3':
                $headding_selected[2] = "selected";
                break;
            case 'h4':
                $headding_selected[3] = "selected";
                break;
            case 'h5':
                $headding_selected[4] = "selected";
                break;
            case 'h6':
                $headding_selected[5] = "selected";
                break;
            case 'none':
                $headding_selected[6] = "selected";
                break;
    }
    
    echo '<tr valign="top"><th scope="row">' . $value . 'の見出しタグ</th><td>';
    
    echo '
    <select name="' . 'wpam_hatenaStar_' . $key .  '" value="' . 'wpam_hatenaStar_' . $key . '">
    <option value="h1" ' . $headding_selected[0] . '>h1</option>
    <option value="h2" ' . $headding_selected[1] . '>h2</option>
    <option value="h3" ' . $headding_selected[2] . '>h3</option>
    <option value="h4" ' . $headding_selected[3] . '>h4</option>
    <option value="h5" ' . $headding_selected[4] . '>h5</option>
    <option value="h6" ' . $headding_selected[5] . '>h6</option>
    <option value="none" ' . $headding_selected[6] . '>非表示</option>
    </select>';
    
    echo '</td></tr>';
}

?>
          </table>

          <?php

$wpam_page_options = "wpam_hatenaStar_token";
foreach ($menu_index as $key => $value){
    $wpam_page_options  = $wpam_page_options .  '<br>wpam_hatenaStar_' . $key;
}

// echo '<input type="hidden" name="page_options" value="' . $wpam_page_options . '"/>';
echo $wpam_page_options;

?>

            <p class="submit">
              <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
            </p>
    </form>
  </div>
  <?php
}

//--------------------------------------------------------------------------
//
//  プラグイン削除の際に行うオプションの削除
//
//--------------------------------------------------------------------------
if ( function_exists('wpam_hatenaStar_uninstall') ) {
    register_uninstall_hook('wpam_hatenaStar', 'wpam_hatenaStar_uninstall');
}
function wpam_hatenaStar_uninstall () {
    delete_option('sa');
}

//--------------------------------------------------------------------------
//
//  実際にはてなスターを表示するところ
//      (ヘッダー部分に出力する)
//--------------------------------------------------------------------------


function wpam_hatenaStar() {
    if(is_home()){
        // トップページの処理
        $wpam_view_headding =  get_option('wpam_hatenaStar_topPage');
    }elseif(is_single()){
        //シングルページ
        $wpam_view_headding =  get_option('wpam_hatenaStar_singlePage');
    }elseif(is_page()){
        //個別ページ
        $wpam_view_headding =  get_option('wpam_hatenaStar_fixedPage');
    }elseif(is_category()){
        //カテゴリページ
        $wpam_view_headding =  get_option('wpam_hatenaStar_categoryPage');
    }elseif(is_month()){
        //月別アーカイブページ
        $wpam_view_headding =  get_option('wpam_hatenaStar_monthPage');
    }else{
        $wpam_view_headding =  get_option('wpam_hatenaStar_otherPage');
    }
    
    if (get_option('wpam_hatenaStar_token') != ""){
        
        $wpam_view_appToken = '<script type="text/javascript">
        Hatena.Star.Token = "' . get_option('wpam_hatenaStar_token') .  '";
        </script>';
    }
    
    if (!($wpam_view_headding == "none")){
        echo "
        <script type='text/javascript' src='http://s.hatena.ne.jp/js/HatenaStar.js'></script>" .
        $wpam_view_appToken . "
        <script src='http://s.hatena.com/js/Hatena/Star/EntryLoader/WordPress.js' type='text/javascript'></script>
        <script type='text/javascript'>
        Hatena.Star.SiteConfig = {
            entryNodes: {
                'div.section': {
                    uri: '" . $wpam_view_headding .  "',
                    title: '" . $wpam_view_headding .  "',
                    container: '" . $wpam_view_headding .  "'
                }
            }
        };
        </script>
        ";
    }
}



?>