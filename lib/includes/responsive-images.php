<?php

//* Disable responsive images
add_filter( 'max_srcset_image_width', create_function( '', 'return 1;' ) );