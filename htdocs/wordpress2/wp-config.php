<?php
/**
 * The base configurations of the WordPress.
 *
 * このファイルは、MySQL、テーブル接頭辞、秘密鍵、言語、ABSPATH の設定を含みます。
 * より詳しい情報は {@link http://wpdocs.sourceforge.jp/wp-config.php_%E3%81%AE%E7%B7%A8%E9%9B%86 
 * wp-config.php の編集} を参照してください。MySQL の設定情報はホスティング先より入手できます。
 *
 * このファイルはインストール時に wp-config.php 作成ウィザードが利用します。
 * ウィザードを介さず、このファイルを "wp-config.php" という名前でコピーして直接編集し値を
 * 入力してもかまいません。
 *
 * @package WordPress
 */

// 注意: 
// Windows の "メモ帳" でこのファイルを編集しないでください !
// 問題なく使えるテキストエディタ
// (http://wpdocs.sourceforge.jp/Codex:%E8%AB%87%E8%A9%B1%E5%AE%A4 参照)
// を使用し、必ず UTF-8 の BOM なし (UTF-8N) で保存してください。

// ** MySQL 設定 - こちらの情報はホスティング先から入手してください。 ** //
/** WordPress のためのデータベース名 */
define('DB_NAME', 'wordpress2');

/** MySQL データベースのユーザー名 */
define('DB_USER', 'wordpress2');

/** MySQL データベースのパスワード */
define('DB_PASSWORD', 'wordpress99');

/** MySQL のホスト名 */
define('DB_HOST', 'baycrews.clfdhhapjwey.ap-northeast-1.rds.amazonaws.com');

/** データベースのテーブルを作成する際のデータベースのキャラクターセット */
define('DB_CHARSET', 'utf8');

/** データベースの照合順序 (ほとんどの場合変更する必要はありません) */
define('DB_COLLATE', '');

/** マルチサイト設定 */
define ('WP_ALLOW_MULTISITE', true);
define( 'MULTISITE', true );
define( 'SUBDOMAIN_INSTALL', false );
$base = '/';
define( 'DOMAIN_CURRENT_SITE', 'blog.baycrews.co.jp' );
define( 'PATH_CURRENT_SITE', '/' );
define( 'SITE_ID_CURRENT_SITE', 1 );
define( 'BLOG_ID_CURRENT_SITE', 1 );

/**#@+
 * 認証用ユニークキー
 *
 * それぞれを異なるユニーク (一意) な文字列に変更してください。
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org の秘密鍵サービス} で自動生成することもできます。
 * 後でいつでも変更して、既存のすべての cookie を無効にできます。これにより、すべてのユーザーを強制的に再ログインさせることになります。
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'HD(+=:Sq=bUy6aES7neSzHSOzYDk6+GM/@=w@gn]f}T@THpKgU.eS;H`+=8k|obn');
define('SECURE_AUTH_KEY',  '5:snfn^?N|ZjXCU-tWwYM8`e6N$sgorpYc765-lfB(TntONI&2|@oQ]f|jbN7vQc');
define('LOGGED_IN_KEY',    'eQwo}9gZ/PSUE rJ^(>I9pO`+Uga-*(y,-NMLERk[XCX[|8ly]99@0d|f ,>_]ux');
define('NONCE_KEY',        'GmQz8.^TrCn=~EOE|?Bu+Xq9$I|&Rq4.qzZEE}r0 {A)Zj||)KCP#lk%qR+yckZ&');
define('AUTH_SALT',        'ig{~[R|9rdzKvgX|}VO-BCCp6|wxw}xR*%lVWltbUqsj?%f%5He/r}55+SzX=Y=U');
define('SECURE_AUTH_SALT', 'nQ|b^Z|bMQgov-<|^dLF+6L|hsTC5n}cd1C/ x]yL1hVg~03bS#OmuQCqVY/$XXZ');
define('LOGGED_IN_SALT',   'Q0|UDx9^el7icbsmi{E7!z8B8SM7x}$[5-P/1$j1ro,Z8!3IOI`3MYs-bBX8^K>e');
define('NONCE_SALT',       '`5/%U ZBwb~zzLMlsz%{9kJ|{T8B_3:uL/p-- .C(p5RBY[U.#xv_Pq<u/  cV[i');

/**#@-*/

/**
 * WordPress データベーステーブルの接頭辞
 *
 * それぞれにユニーク (一意) な接頭辞を与えることで一つのデータベースに複数の WordPress を
 * インストールすることができます。半角英数字と下線のみを使用してください。
 */
$table_prefix  = 'wp_';

/**
 * ローカル言語 - このパッケージでは初期値として 'ja' (日本語 UTF-8) が設定されています。
 *
 * WordPress のローカル言語を設定します。設定した言語に対応する MO ファイルが
 * wp-content/languages にインストールされている必要があります。例えば de_DE.mo を
 * wp-content/languages にインストールし WPLANG を 'de_DE' に設定することでドイツ語がサポートされます。
 */
define('WPLANG', 'ja');

/**
 * 開発者へ: WordPress デバッグモード
 *
 * この値を true にすると、開発中に注意 (notice) を表示します。
 * テーマおよびプラグインの開発者には、その開発環境においてこの WP_DEBUG を使用することを強く推奨します。
 */
define('WP_DEBUG', false);
//define('WP_DEBUG', true);

/* 編集が必要なのはここまでです ! WordPress でブログをお楽しみください。 */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** W3 Total Cache plugin **/
define('WP_CACHE', true);

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
