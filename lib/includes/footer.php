<?php

//* Footer Copyright
add_filter('genesis_footer_creds_text', 'caseanti_footer_creds_filter');
function caseanti_footer_creds_filter( $creds ) {
  $creds = '[footer_copyright] ' . get_bloginfo( 'title' ) . '. All rights reserved.<br />Auctions in Association with:<br />Case Antiques, Inc. - TNGL#5157, TNGBL#5387 - 4310 Papermill Drive, Knoxville, TN 37909 - (865) 558-3033';
  return $creds;
}