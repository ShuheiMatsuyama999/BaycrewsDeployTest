<?php
/*
静的HTML洗い替え用ディレクトリ削除
*/

$deletedDir = '/home/blog.baycrews.co.jp/htdocs/sp/cache/tmp';
system("rm -rf {$deletedDir}");

exit;