<?php
class getSuggestData{
  //����SOLR
     public static function getSuggestResult($wd,&$searchdata){
		 //solr����ȡ��
		 $solr = conn::getConn();

		 //��������  
		$queries = array(
		    $wd
		  );
	    //��������
		$solrdata = self::getSolrData($wd,$solr);
		//var_dump($solrdata);
        //�����ַ���
	    $titles = "[";
		//ѭ������������
        foreach($solrdata as $value){
			$title =  $value ;
			$titles .= "{name:'" . $title ."'}" . ",";
		 }  
		 //ȥ�����ģ�
         $titles = substr($titles,0,-1);
		 $titles .= "]";
		//����������
		 echo $titles;
	 }

	//solr��ȡ��������
	private  function getSolrData($wd,$solr){
		//��������  
		$queries = array(
		    $wd
		  );
		  
	    //ȡ�ü����������
		foreach ( $queries as $query ) { 
			    //��������
			     $response = $solr->suggest($query); 
				// var_dump($response->spellcheck->suggestions->$queries[0]->numFound);
			     //�ж�����������õ���HTTP״̬��
			     if ($response->getHttpStatus() == 200 ) { 
				      if ( $response->spellcheck->suggestions->$queries[0]->numFound > 0) { 
				          //�������
					      $solrdata =$response->spellcheck->suggestions->$queries[0]->suggestion;
				         }
				      else{
				          //�������Ϊ��
				          $solrdata.="";
				      }
				  }else { 
				      //����쳣HTTP������Ϣ
				      $solrdata.=$response->getHttpStatusMessage(); 
				  }
			} 
		 //���ؼ������
		 return $solrdata;
	}
}