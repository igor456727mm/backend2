<?php
//header('Content-Encoding: gzip');
//header_remove('content-type');
//header_remove('transfer-encoding');
//echo gzencode(trim($message));
//header('content-type: application/json');
//header('Content-Length: '.(strlen($message)+1));
echo trim($message)."
";
?>