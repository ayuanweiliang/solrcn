<?php
   session_start();
   require('./config/common.inc.php');
    
   //���ղ���
    if(isset($_REQUEST['q'])){
        $wd = $_REQUEST['q'];
        //����Ϊ�յ������������ҳ��
        if (empty($wd)){
           header("location:./");
        }
     }

	  $solrdata = getSuggestData::getSuggestResult($wd);
     
	 /* test data
      $json="[{ name:'��������'},{ name:'������Ů' },{ name:'��Ů��Ұ��'},{ name:'��ƺ����Ů' }]";  
  
	 */