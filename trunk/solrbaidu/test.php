<?php
   session_start();
   require('./config/common.inc.php');

//����
echo getPinyin::ToPinyin('s���ˣ��ٶ�','gb2312'); //�ڶ���������1�����������ü�Ϊutf8����
?>
