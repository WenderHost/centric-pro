<?php

namespace CaseAntiques\gravityforms;

function stripe_charge_authorization_only( $authorization_only, $feed ) {
    $feed_name  = rgars( $feed, 'meta/feedName' );
    if ( $feed_name == 'Bidder Authorization' ) {
        return true;
    }

    return $authorization_only;
}
add_filter( 'gform_stripe_charge_authorization_only', __NAMESPACE__ . '\\stripe_charge_authorization_only', 10, 2 );