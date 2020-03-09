<?php
namespace Drupal\jn_doi_integration\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Provides route responses for the Example module.
 */
class JnDoiController extends ControllerBase {

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function doiService() {
    global $base_url;

    $req_attr = [];
    $config = jn_doi_integration_get_config();
    $lang = \Drupal::languageManager()->getCurrentLanguage()->getId();

    $data = [
      'req_url' => $base_url . '/oai-script',
      'node_url' => $base_url . '/' . $lang . '/node/',
    ];
    $data += $config;

    $current = \Drupal::time()->getCurrentTime();
    $data['current_date'] = \Drupal::service('date.formatter')->format($current, 'Y-m-d\TH:i:s\Z"');
    $data['earlier_date'] = date("Y-m-d\TH:i:s\Z", strtotime('-1 months'));
    $params = \Drupal::request()->query->all();
	  foreach($params as $key => $val) {
		  $req_attr[] = "$key=\"$val\"";
    }

    $data['req_attr'] = implode(' ', $req_attr);
    $data['content'] = $this->getDoiServiceContent($config['verbs'], $params, $data);

    $service = [
      '#theme' => 'doi_service',
      'data' => $data,
      '#attached' => ['http_header' => ['Content-Type' => 'application/xml; charset=utf-8']]
    ];

	  print $service;
    die();
  }

  public function getDoiServiceContent($verbs, $params, $oai_prefix, $data) {
    $res = $this->validateRequestParams($verbs, $params, $data['oai_prefix']);
    if ($res->error) {
      $ser_content = $res->err_msg;
    } elseif ($params['verb'] == 'Identify') {
      $ser_content = [
        '#theme' => 'doi_service_identify',
        'data' => $data,
      ];
    } elseif ($params['verb'] == 'GetRecord' && isset($res->entity_id)) {
      $nids = [$res->entity_id];
      $ser_content = $this->getDoiServiceRecords($nids, $params['verb'], $data);
    } elseif($params['verb'] == 'ListMetadataFormats' && ( !isset($params['identifier'])) || isset($res->entity_id)) {
      $ser_content = [
        '#theme' => 'doi_service_metadataformat',
        'data' => $data,
      ];
    } else if($params['verb'] == 'ListRecords' || $params['verb'] == 'ListIdentifiers') {
      $nids = jn_doi_integration_get_nodes_by_from_until('article', $params['from'], $params['until']);
			if (count($nids)) {
        $ser_content = $this->getDoiServiceRecords($nids, $params['verb'], $data);
			} else {
				$ser_content = '<error code="noRecordsMatch">No records found to your request</error>';	
			}
    }

    return $ser_content;
  }

  public function getDoiServiceRecords($nids, $verb, $data) {
    $data = [];
    $data['verb'] = $verb;
    
    $nodes = \Drupal::entityTypeManager()->getStorage('node')->loadMultiple($nids);
    foreach ($nodes as $node) {
      $node_meta = $this->getMetadataFromNode($node);
      $node_meta['oai_prefix'] = $data['oai_prefix'];
      $node_meta['setspec'] = 'DOI';
      $node_meta['node_url'] = $data['node_url'] . $node_meta['nid'];
      $data['records'][] = $node_meta;
    }

    return [
      '#theme' => 'doi_service_record',
      'data' => $data,
    ];
  }

  public function getMetadataFromNode($node) {
    $authors = [];

    $created = $node->getCreatedTime();
    $created_date = \Drupal::service('date.formatter')->format($created, 'Y-m-d\TH:i:s\Z');
    $cat = $node->get('field_journal_category')->value;
    $pub_year = date("Y-m", strtotime($node->get('field_year_of_publication')->value));

    $title = $node->getTitle();
    if (!empty($node->get('field_sub_title')->value)) {
      $title .= ' ' . $node->get('field_sub_title')->value;
    }

    if ($node->hasField('field_authors')) {
      /** @var \Drupal\Core\Field\Plugin\Field\FieldType\EntityReferenceItem $referenceItem */
      foreach ($node->get('field_authors')->referencedEntities() as $author) {
        if (!empty($author->get('field_first_name')->value)) {
          $name = $author->get('field_first_name')->value;
        }

        if (!empty($author->get('field_last_name')->value)) {
          $name .= ' ' . $author->get('field_last_name')->value;
        }

        $authors[] = $name;
      }
    }

    return [
      'title' => $title,
      'nid' => $node->id(),
      'created' => $created,
      'created_date' => $created_date,
      'type' => $cat == 'book review' ? 'Book Review' : 'Journal Article',
      'abstract_en' => $node->get('body')->value,
      'abstract_de' => $node->get('field_de_abstract')->value,
      'pub_year' => $pub_year,
      'volume' => $node->get('field_volume_number')->value,
      'start' => $node->get('field_starting_page')->value,
      'end' => $node->get('field_ending_page')->value,
      'oai' => $node->get('field_oai_identifier')->value,
      'doi' => $node->get('field_doi_number')->value,
      'lang' => $node->get('field_language')->value,
      'authors' => $authors,
      'publisher' => 'Naturwissenschaftliche Sektion am Goetheanum, Elemente der Naturwissenschaft',
      'ispartof'=> 'urn:ISSN:0422-9630',
      'rights' => 'Naturwissenschaftliche Sektion am Goetheanum'
    ];
  }

  public function validateRequestParams($verbs, $params, $oai_prefix) {
    $res = new \StdClass();
    $error = FALSE;
    $err_msg = '';

    if (!isset($params['verb'])) {
      $error = TRUE;
      $err_msg = '<error code="badArgument"/>';
    } elseif (!in_array($params['verb'], $verbs)) {
      $error = TRUE;
      $err_msg = '<error code="badVerb">Illegal OAI verb</error>';
    } elseif (($params['verb'] == 'ListRecords' || $params['verb'] == 'ListIdentifiers' || $params['verb'] == 'GetRecord') && !isset($params['metadataPrefix'])) {
      $error = TRUE;
      $err_msg = '<error code="badVerb">Illegal OAI verb</error>';
    } elseif (($params['verb'] == 'ListRecords' || $params['verb'] == 'ListIdentifiers' || $params['verb'] == 'GetRecord') && ( $params['metadataPrefix'] != 'oai_dc')) {
      $error = TRUE;
      $err_msg = '<error code="badArgument">No valid metadataPrefix</error>';
    } elseif (($params['verb'] == 'ListRecords' || $params['verb'] == 'ListIdentifiers') && $params['metadataPrefix'] == 'oai_dc' && (isset($params['from']) || isset($params['until']))) {
      if (isset($params['from']) && !preg_match("/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})Z$/", $params['from'])){
        $error = TRUE;
        $err_msg = '<error  code="badArgument">no valid granularity</error>';
      }
  
      if (isset($params['until']) && !preg_match("/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})Z$/", $params['until'])){
        $error = TRUE;
        $err_msg = '<error  code="badArgument">no valid granularity</error>';
      }
    }	elseif (($params['verb'] == 'ListRecords' || $params['verb'] == 'ListIdentifiers') && isset( $params['set'])) {
      $error = TRUE;
      $err_msg = '<error  code="noSetHierarchy">This repository does not support sets</error>';	
    }	elseif (($params['verb'] == 'ListMetadataFormats' || $params['verb'] == 'GetRecord') && isset($params['identifier'])) {
      $oai_identifier = str_replace($oai_prefix, '', $params['identifier']);
      if ($entity_id = jn_doi_integration_get_nid_by_oai($oai_identifier)) {
        $res->entity_id = $entity_id;
      } else {
        $error = TRUE;
        $err_msg = '<error  code="idDoesNotExist">No record found for this identifier</error>';	
      }
    } elseif ($params['verb'] == 'GetRecord' && !isset($params['identifier']) ) {
      $error = TRUE;
      $err_msg = '<error code="badArgument"/>';
    } elseif (($params['verb'] == 'ListRecords' || $params['verb'] == 'ListIdentifiers') && isset($params['resumptionToken']) ) {
      $error = TRUE;
      $err_msg = '<error code="badResumptionToken">No valid ResumptionToken</error>';	
    } elseif ($params['verb'] == 'ListSets' ) {
      $error = TRUE;
      $err_msg = '<error code="noSetHierarchy">This repository does not support sets</error>';	
    }
  
    $res->error = $error;
    if ($error) {
      $res->err_msg = $err_msg;
    }
  
    return $res;
  }

}
