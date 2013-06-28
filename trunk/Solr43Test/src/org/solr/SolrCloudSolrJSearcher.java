package org.solr;

import java.net.MalformedURLException;

import org.apache.solr.client.solrj.SolrServerException;
import org.apache.solr.client.solrj.impl.CloudSolrServer;
import org.apache.solr.client.solrj.response.QueryResponse;
import org.apache.solr.common.SolrDocumentList;
import org.apache.solr.common.params.ModifiableSolrParams;

public class SolrCloudSolrJSearcher {
	public static void main(String[] args) throws MalformedURLException, SolrServerException {
		String zkHost = "localhost:2181";
		String defaultCollection = "collection1";
		CloudSolrServer solr = new CloudSolrServer(zkHost);
		solr.setDefaultCollection(defaultCollection);

		ModifiableSolrParams params = new ModifiableSolrParams();
		params.set("q", "cat:electronics");
		params.set("defType", "edismax");
		params.set("start", "0");

		QueryResponse response = solr.query(params);
		SolrDocumentList results = response.getResults();
		for (int i = 0; i < results.size(); ++i) {
			System.out.println(results.get(i));
		}
	}
}