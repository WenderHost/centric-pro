<?php

//* Disable responsive images
add_filter( 'max_srcset_image_width', function(){
  return 1;
} );