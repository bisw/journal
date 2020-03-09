<?php

namespace Drupal\journal\Controller;

use FPDF;
use FPDI;
use Drupal\node\NodeInterface;
use Drupal\Core\Controller\ControllerBase;

/**
 * Provides route responses for the Example module.
 */
class JournalDownload extends ControllerBase {

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function getDownload(NodeInterface $node, $type) {
    $valid_types = ['ptf', 'btf', 'ris', 'pre_pdf', 'pdf'];
    $type = strtolower($type);
    $func = 'downloadArticle' . $type;
   // print_r($type);die;

    if (in_array($type, $valid_types) && method_exists($this, $func)) {
      $this->{$func}($node);
	  } else {
	    echo t('Invalid Text Format, An error occurred and processing did not complete.');
	    die();
	  }
  }

  private function downloadArticleptf($node) {
    $lang = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $data = $this->getDataFromNode($node);
  
    $et_al = '';
    $authors_name = [];
    foreach ($data['authors'] as $key => $author) {
      if ($key == 9) {
        $et_al = $et_al = 'et al.';
        break;
      }
  
      $authors_name[] = $author['last_name'] . ', ' . $author['first_name'];
    }
  
    $plainTextOp = implode('; ', $authors_name) . $et_al;
    $plainTextOp .= ' (' . date('Y', strtotime($data['pub_year'])) . '): ';
    $plainTextOp .= $data['title'];
  
    if (isset($data['sub_title'])) {
      $plainTextOp .= '.' . $data['sub_title'];
    }
  
    $plainTextOp .= '. Elemente der Naturwissenschaft ' . $data['volume'] . ',';
    if($lang == 'en') {
      $plainTextOp .= ' P. ' . $data['start'] . ' - ' . $data['end'];
    } else {
      $plainTextOp .= ' S. ' . $data['start'] . ' - ' . $data['end'];
    }
  
    $plainTextOp .= '. doi:' . $data['doi'];
    
    header("Content-Type: text/plain");
    print $plainTextOp;
    exit();
  }

  private function downloadArticlebtf($node) {
    $lang = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $data = $this->getDataFromNode($node);
  
    $langa = array('German'=>'de','English'=>'en','French'=>'fr','Spanish'=>'es','Other'=>'');
    $bib_to_latex_codes = journal_bib_to_latex_codes();
  
    $btf = '@article{' . $data['doi'] . ",\n\t";
    $title = $data['title'];
    if (!empty($data['sub_title'])) {
      $title .= '. ' . $data['sub_title'];
    }
  
    $title_in_latex = strtr($title, $bib_to_latex_codes);
    $btf .= 'title = {{' . $title_in_latex . "}},\n\t";
    $btf .= 'shorttitle = {{' . strtr($data['title'], $bib_to_latex_codes) . "}},\n\t";
  
    $authors_name = [];
    $first_auth_last_name = '';
    foreach ($data['authors'] as $key => $author) {
      $authors_name[] = $author['last_name'] . ', ' . $author['first_name'];
      if ($key == 0) {
        $first_auth_last_name = $author['first_name'];
      }
    }
  
    $pub_year = date('Y', strtotime($data['pub_year']));
    $btf .= 'author = {' . strtr(implode(' and ', $authors_name), $bib_to_latex_codes) . "},\n\t";	
    $btf .= 'journal = {' . t('Elemente der Naturwissenschaft') . "},\n\t";
    $btf .= 'year = {' . $pub_year . "},\n\t";
    $btf .= 'volume = {' . $data['volume'] . "},\n\t";
    $btf .= 'pages = {' . $data['start'] . '--' . $data['end'] . "},\n\t";
    $btf .= 'url = {https://dx.doi.org/' . $data['doi'] . "},\n\t";
    $btf .= 'doi = {' . $data['doi'] . "},\n\t";
    $btf .= "issn = {p-ISSN 0422-9630},\n\t";
    $btf .= 'language = {' . $lang . "},\n\t";	
    $btf .= "abstract = {" . strtr($data['abstract_de'], $bib_to_latex_codes) . "},\n\t";
    $btf .= "annote = {" . strtr($data['abstract_en'], $bib_to_latex_codes) . "}\n";
    $btf .= "}";
    $filename = $first_auth_last_name . t('elemente') . $pub_year . $data['nid'] . '.bib';
  
    header('Content-type: text/plain');
    header('Content-Length: ' . strlen($btf));
    header("Content-Disposition: attachment; filename=$filename");
    print $btf;
    exit;
  }

  private function downloadArticleris($node) {
    $lang = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $data = $this->getDataFromNode($node);
  
    $langa = array('German'=>'de','English'=>'en','French'=>'fr','Spanish'=>'es','Other'=>'');
  
    $title = $data['title'];
    if (!empty($data['sub_title'])) {
      $title .= '. ' . $data['sub_title'];
    }
  
    $ris = 'TY - JOUR'."\n";
    $ris .= 'T1 - ' . $title . "\n";
    
    $authors_name = [];
    $first_auth_last_name = '';
    foreach ($data['authors'] as $key => $author) {
      $authors_name[] = $author['last_name'] . ', ' . $author['first_name'];
      if ($key == 0) {
        $first_auth_last_name = $author['first_name'];
      }
    }
  
    $pub_year = date('Y', strtotime($data['pub_year']));
    $ris .= implode("\n", $authors_name) . "\n";  
    $ris .= 'JA - Elem. d. Naturw.'."\n";	 
    $ris .= 'JF - Elemente der Naturwissenschaft' . "\n";
    $ris .= 'PY - ' . $pub_year . "\n";
    $ris .= 'VL - ' . $data['volume'] . "\n";
    $ris .= 'SP - ' . $data['start'] . "\n";
    $ris .= 'EP - ' . $data['end'] . "\n"; 
    $ris .= 'DO - ' . $data['doi'] . "\n";
    $ris .= 'SN - p-ISSN 0422-9630' . "\n";
    $ris .= 'LA - ' . $lang . "\n";
  
    $abstract = $lang == 'en' ? $data['abstract_en'] : $data['abstract_de'];
    $ris .= 'N2 - ' . $data['abstract_de'] . "\n";
    $ris .= 'N1 - ' . $data['abstract_en'] . "\n";
    $ris .= 'AB - ' . $abstract . "\n";
    $ris .= 'ST - ' . $data['title'] . "\n";
    $ris .= 'UR - https://dx.doi.org/' . $data['doi'] . "\n";
    $ris .= 'Y2 - ' . date("Y-m-d h:i:s") . "\n";
    $ris .= 'ER - ' . "\n";
  
    $filename = $first_auth_last_name . t('journal') . $pub_year . $data['nid'] . '.ris';
    header("Content-type: text/plain");
    header("Content-Disposition: attachment; filename=$filename");
    print $ris;
    exit();
  }

  private function downloadArticleSplitPdf($filename, $end_dir = FALSE) {
    module_load_include('php', 'journal', 'vendor/fpdf/fpdf');
    module_load_include('php', 'journal', 'vendor/fpdi/fpdi');
  
    $end_dir = $end_dir ? $end_dir : '';
    $new_path = preg_replace('/[\/]+/', '/', $end_dir.'/'.substr($filename, 0, strrpos($filename, '/')));
  
    if (!is_dir($new_path)) {
      mkdir($new_path, 0777, TRUE);
    }
  
    $pdf = new FPDI();
    $pdf->AddPage();
    echo "filename:$filename, ";
    $pagecount = @$pdf->setSourceFile($filename); // How many pages?
  
    if ($pagecount > 1) {
      $pdf3 = new FPDI();
      $pdf3->setSourceFile($filename);
      $tplIdx = $pdf3->ImportPage(1);
      $s = $pdf3->getTemplatesize($tplIdx);
      $pdf3->AddPage($s['w'] > $s['h'] ? 'L' : 'P', array($s['w'], $s['h']));
      $pdf3->useTemplate($tplIdx);
      $pdf3->setSourceFile($filename);
      $tplIdx = $pdf3->ImportPage(2);
      $s = $pdf3->getTemplatesize($tplIdx);
      $pdf3->AddPage($s['w'] > $s['h'] ? 'L' : 'P', array($s['w'], $s['h']));
      $pdf3->useTemplate($tplIdx);
      $pdf3->Output();
    } else if($pagecount > 0) {
      $pdf1 = new FPDI();
      $pdf1->AddPage();
      $pdf1->setSourceFile($filename);
      $pdf1->useTemplate($pdf1->importPage(1));
      $pdf1->Output();
    } else {
      die('No PDF attached.');
    }
  }

  private function downloadArticlepre_pdf($node, $show_pages = 2) {
    $uri = $node->field_attach_pdf_article->entity->getFileUri();
    if ($pdf_url = \Drupal::service('file_system')->realpath($uri)) {
      $this->downloadArticleSplitPdf($pdf_url);
    }
  }

  public function getDataFromNode($node) {
    $authors = [];
    if ($node->hasField('field_authors')) {
      foreach ($node->get('field_authors')->referencedEntities() as $author) {
        $authorName = [];
        if (!empty($author->get('field_first_name')->value)) {
          $authorName['first_name'] = $author->get('field_first_name')->value;
        }
  
        if (!empty($author->get('field_last_name')->value)) {
          $authorName['last_name'] = $author->get('field_last_name')->value;
        }
  
        $authors[] = $authorName;
      }
    }
  
    return [
      'title' => $node->getTitle(),
      'sub_title' => $node->get('field_sub_title')->value,
      'nid' => $node->id(),
      'created' => $node->getCreatedTime(),
      'cat' => $node->get('field_journal_category')->value,
      'abstract_en' => $node->get('body')->value,
      'abstract_de' => $node->get('field_abstract')->value,
      'pub_year' => $node->get('field_year_of_publication')->value,
      'volume' => $node->get('field_volume_number')->value,
      'start' => $node->get('field_starting_page')->value,
      'end' => $node->get('field_ending_page')->value,
      'oai' => $node->get('field_oai_identifier')->value,
      'doi' => $node->get('field_doi_number')->value,
      'lang' => $node->get('field_language')->value,
      'authors' => $authors,
    ];
  }

}
