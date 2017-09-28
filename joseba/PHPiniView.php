<?php
function return_bytes($val) {
    $val = trim($val);
    $last = strtolower($val[strlen($val)-1]);
    switch($last) {
        // The 'G' modifier is available since PHP 5.1.0
        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }

    return $val . " bytes";
}

echo "<hr>";
echo 'display_errors = ' . ini_get('display_errors') . "<br />";
echo "<hr>";
echo "Language Options" . "<br />";
echo "short_open_tag = ".ini_get('short_open_tag') . "<br />";
echo "asp_tags = ".ini_get('asp_tags') . "<br />";
echo "precision = ".ini_get('precision') . "<br />";
echo "serialize_precision = ".ini_get('serialize_precision') . "<br />";
echo "y2k_compliance = ".ini_get('y2k_compliance') . "<br />";
echo "allow_call_time_pass_reference = ".ini_get('allow_call_time_pass_reference') . "<br />";
echo "disable_functions = ".ini_get('disable_functions') . "<br />";
echo "disable_classes = ".ini_get('disable_classes') . "<br />";
echo "exit_on_timeout = ".ini_get('exit_on_timeout') . "<br />";
echo "expose_php = ".ini_get('expose_php') . "<br />";
echo "zend.multibyte = ".ini_get('zend.multibyte') . "<br />";
echo "zend.script_encoding = ".ini_get('zend.script_encoding') . "<br />";
echo "zend.signal_check = ".ini_get('zend.signal_check') . "<br />";
echo "zend.ze1_compatibility_mode = ".ini_get('zend.ze1_compatibility_mode') . "<br />";
echo "detect_unicode = ".ini_get('detect_unicode') . "<br />";
echo "<hr>";
echo "Resource Limits" . "<br />";
echo "memory_limit = ".ini_get('memory_limit') . "<br />";
echo "<hr>";
echo "Resource Limits" . "<br />";
echo "short_open_tag = ".ini_get('short_open_tag') . "<br />";
echo "short_open_tag = ".ini_get('short_open_tag') . "<br />";
echo "<hr>";
echo "Performance Tuning" . "<br />";
echo "realpath_cache_size = ".ini_get('realpath_cache_size') . "<br />";
echo "realpath_cache_ttl = ".ini_get('realpath_cache_ttl') . "<br />";
echo "<hr>";
echo "Data Handling" . "<br />";
echo "track_vars = ".ini_get('track_vars') . "<br />";
echo "arg_separator.output = ".ini_get('arg_separator.output') . "<br />";
echo "arg_separator.input = ".ini_get('arg_separator.input') . "<br />";
echo "variables_order = ".ini_get('variables_order') . "<br />";
echo "request_order = ".ini_get('request_order') . "<br />";
echo "auto_globals_jit = ".ini_get('auto_globals_jit') . "<br />";
echo "register_globals = ".ini_get('register_globals') . "<br />";
echo "register_argc_argv = ".ini_get('register_argc_argv') . "<br />";
echo "register_long_arrays = ".ini_get('register_long_arrays') . "<br />";
echo "post_max_size = ".ini_get('post_max_size') . " = " . return_bytes(ini_get('post_max_size')) . "<br />";
echo "gpc_order = ".ini_get('gpc_order') . "<br />";
echo "auto_prepend_file = ".ini_get('auto_prepend_file') . "<br />";
echo "auto_append_file = ".ini_get('auto_append_file') . "<br />";
echo "default_mimetype = ".ini_get('default_mimetype') . "<br />";
echo "default_charset = ".ini_get('default_charset') . "<br />";
echo "always_populate_raw_post_data = ".ini_get('always_populate_raw_post_data') . "<br />";
echo "allow_webdav_methods = ".ini_get('allow_webdav_methods') . "<br />";
echo "<hr>";
echo "Paths and Directories" . "<br />";
echo "include_path = ".ini_get('include_path') . "<br />";
echo "open_basedir = ".ini_get('open_basedir') . "<br />";
echo "doc_root = ".ini_get('doc_root') . "<br />";
echo "user_dir = ".ini_get('user_dir') . "<br />";
echo "extension_dir = ".ini_get('extension_dir') . "<br />";
echo "extension = ".ini_get('extension') . "<br />";
echo "zend_extension = ".ini_get('zend_extension') . "<br />";
echo "zend_extension_debug = ".ini_get('zend_extension_debug') . "<br />";
echo "zend_extension_debug_ts = ".ini_get('zend_extension_debug_ts') . "<br />";
echo "zend_extension_ts = ".ini_get('zend_extension_ts') . "<br />";
echo "cgi.check_shebang_line = ".ini_get('cgi.check_shebang_line') . "<br />";
echo "cgi.fix_pathinfo = ".ini_get('cgi.fix_pathinfo') . "<br />";
echo "cgi.force_redirect = ".ini_get('cgi.force_redirect') . "<br />";
echo "cgi.redirect_status_env = ".ini_get('cgi.redirect_status_env') . "<br />";
echo "cgi.rfc2616_headers = ".ini_get('cgi.rfc2616_headers') . "<br />";
echo "fastcgi.impersonate = ".ini_get('fastcgi.impersonate') . "<br />";
echo "fastcgi.logging = ".ini_get('fastcgi.logging') . "<br />";
echo "<hr>";
echo "File Uploads" . "<br />";
echo "file_uploads = ".ini_get('file_uploads') . "<br />";
echo "upload_tmp_dir = ".ini_get('upload_tmp_dir') . "<br />";
echo "max_input_nesting_level = ".ini_get('max_input_nesting_level') . "<br />";
echo "max_input_vars = ".ini_get('max_input_vars') . "<br />";
echo "upload_max_filesize = ".ini_get('upload_max_filesize') . " = " . return_bytes(ini_get('upload_max_filesize')) . "<br />";
echo "max_file_uploads = ".ini_get('max_file_uploads') . "<br />";
echo "<hr>";
echo "General SQL" . "<br />";
echo "sql.safe_mode = ".ini_get('sql.safe_mode') . "<br />";
echo "<hr>";
echo "Windows Specific" . "<br />";
echo "windows_show_crt_warning = ".ini_get('windows_show_crt_warning') . "<br />";
echo "<hr>";

phpinfo();

echo "<hr>";

print_r(ini_get_all());

?>