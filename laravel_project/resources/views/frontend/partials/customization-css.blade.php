<style>
    /**
     * Start site primary color
     */
    a {
        color:{{ $customization_site_primary_color }};
    }
    a:hover {
        color:#000000;
    }
    .btn-primary {
        background-color:{{ $customization_site_primary_color }};
        border-color:{{ $customization_site_primary_color }} !important;
    }
    .btn-primary:hover {
        background-color:#fff!important;
        color:{{ $customization_site_primary_color }} !important;
        border-color:{{ $customization_site_primary_color }} !important;
    }
    .btn-primary:disabled {
        color:#fff!important;
        background-color:{{ $customization_site_primary_color }} !important;
        border-color:{{ $customization_site_primary_color }} !important;
    }
    .btn-outline-primary {
        color:{{ $customization_site_primary_color }};
        border-color:{{ $customization_site_primary_color }} !important;
    }
    .btn-outline-primary:hover {
        background-color:{{ $customization_site_primary_color }} !important;
        border-color:{{ $customization_site_primary_color }} !important;
        color:#ffffff !important;
    }
    .btn-outline-primary:disabled {
        color:{{ $customization_site_primary_color }} !important;
    }
    .btn-outline-primary.dropdown-toggle {
        background-color:{{ $customization_site_primary_color }} !important;
        border-color:{{ $customization_site_primary_color }} !important;
    }
    .btn-link {
        color:{{ $customization_site_primary_color }} !important;
    }
    .dropdown-item.active,.dropdown-item:active {
        background-color:{{ $customization_site_primary_color }} !important;
    }
    .custom-control-input:checked~.custom-control-label:before {
        background-color:{{ $customization_site_primary_color }} !important;
    }
    .custom-control-input:checked~.custom-control-label:before {
        background-color:{{ $customization_site_primary_color }} !important;
    }
    .custom-control-input:indeterminate~.custom-control-label:before {
        background-color:{{ $customization_site_primary_color }} !important;
    }
    .custom-radio .custom-control-input:checked~.custom-control-label:before {
        background-color:{{ $customization_site_primary_color }} !important;
    }
    .custom-range::-webkit-slider-thumb {
        background-color:{{ $customization_site_primary_color }} !important;
    }
    .custom-range::-moz-range-thumb {
        background-color:{{ $customization_site_primary_color }} !important;
    }
    .custom-range::-ms-thumb {
        background-color:{{ $customization_site_primary_color }} !important;
    }
    .nav-pills .nav-link.active,.nav-pills .show>.nav-link {
        background-color:{{ $customization_site_primary_color }} !important;
    }
    .page-link {
        color:{{ $customization_site_primary_color }} !important;
    }
    .page-item.active .page-link {
        color:#ffffff !important;
        background-color:{{ $customization_site_primary_color }} !important;
        border-color:{{ $customization_site_primary_color }};
    }
    .badge-primary {
        background-color:{{ $customization_site_primary_color }} !important;
    }
    .progress-bar {
        background-color:{{ $customization_site_primary_color }} !important;
    }
    .list-group-item.active {
        background-color:{{ $customization_site_primary_color }} !important;
        border-color:{{ $customization_site_primary_color }} !important;
    }
    .bg-primary {
        background-color:{{ $customization_site_primary_color }} !important;
    }
    .border-primary {
        border-color:{{ $customization_site_primary_color }} !important;
    }
    .text-primary {
        color:{{ $customization_site_primary_color }} !important;
    }
    .pace .pace-progress {
        background:{{ $customization_site_primary_color }} !important;
    }
    .btn.btn-outline-white:hover {
        color:{{ $customization_site_primary_color }} !important;
    }
    .form-control:active, .form-control:focus {
        border-color:{{ $customization_site_primary_color }} !important;
    }
    .site-section-heading:after {
        background:{{ $customization_site_primary_color }} !important;
    }
    .site-section-heading.text-center:after {
        background:{{ $customization_site_primary_color }} !important;
    }
    .ul-check.primary li:before {
        color:{{ $customization_site_primary_color }} !important;
    }
    .site-navbar .site-navigation .site-menu .active > a {
        color:{{ $customization_site_primary_color }} !important;
    }
    .site-navbar .site-navigation .site-menu > li > a:hover {
        color:{{ $customization_site_primary_color }} !important;
    }
    .site-navbar .site-navigation .site-menu .has-children .dropdown {
        border-top: 2px solid {{ $customization_site_primary_color }} !important;
    }
    .site-navbar .site-navigation .site-menu .has-children .dropdown .active > a {
        color:{{ $customization_site_primary_color }} !important;
    }
    .site-navbar .site-navigation .site-menu .has-children:hover > a, .site-navbar .site-navigation .site-menu .has-children:focus > a, .site-navbar .site-navigation .site-menu .has-children:active > a {
        color:{{ $customization_site_primary_color }} !important;
    }
    .site-mobile-menu .site-nav-wrap a:hover {
        color:{{ $customization_site_primary_color }} !important;
    }
    .site-mobile-menu .site-nav-wrap li.active > a {
        color:{{ $customization_site_primary_color }} !important;
    }
    .site-block-tab .nav-item > a:hover, .site-block-tab .nav-item > a.active {
        border-bottom: 2px solid {{ $customization_site_primary_color }} !important;
    }
    .block-13 .owl-nav .owl-prev:hover, .block-13 .owl-nav .owl-next:hover, .slide-one-item .owl-nav .owl-prev:hover, .slide-one-item .owl-nav .owl-next:hover {
        color:{{ $customization_site_primary_color }} !important;
    }
    .slide-one-item .owl-dots .owl-dot.active span {
        background:{{ $customization_site_primary_color }} !important;
    }
    .block-12 .text .text-inner:before {
        background:{{ $customization_site_primary_color }} !important;
    }
    .block-16 figure .play-button {
        color:{{ $customization_site_primary_color }} !important;
    }
    .block-25 ul li a .meta {
        color:{{ $customization_site_primary_color }} !important;
    }
    .player .team-number {
        background:{{ $customization_site_primary_color }} !important;
    }
    .site-block-27 ul li.active a, .site-block-27 ul li.active span {
        background:{{ $customization_site_primary_color }} !important;
    }
    .feature-1, .free-quote, .feature-3 {
        background:{{ $customization_site_primary_color }} !important;
    }
    .border-primary:after {
        background:{{ $customization_site_primary_color }} !important;
    }
    .how-it-work-item .number {
        background:{{ $customization_site_primary_color }} !important;
    }
    .custom-pagination a, .custom-pagination span {
        background:{{ $customization_site_primary_color }} !important;
    }
    .popular-category:hover {
        background:{{ $customization_site_primary_color }} !important;
        -webkit-box-shadow: none !important;
        box-shadow: none !important;
    }
    .listing-item .listing-item-content .category {
        background:{{ $customization_site_primary_color }} !important;
    }
    .accordion-item[aria-expanded="true"] {
        color:{{ $customization_site_primary_color }} !important;
    }
    .rangeslider.rangeslider--horizontal .rangeslider__fill {
        background:{{ $customization_site_primary_color }} !important;
    }
    .rangeslider .rangeslider__handle:after {
        border: 3px solid {{ $customization_site_primary_color }} !important;
    }
    /**
     * End site primary color
     */

    /**
     * Start menu header background color
     */
    .customization-header-background-color {
        background-color:{{ $customization_site_header_background_color }} !important;
    }
    /**
     * End menu header background color
     */

    /**
     * Start menu header font color
     */
    .customization-header-font-color {
        color:{{ $customization_site_header_font_color }} !important;
    }
    .site-navbar .site-navigation .site-menu > li > a {
        color:{{ $customization_site_header_font_color }} !important;
    }
    /**
     * End menu header font color
     */

    /**
     * Start footer background color
     */
    .site-footer {
        background: {{ $customization_site_footer_background_color }} !important;
    }
    /**
     * End footer background color
     */

    /**
     * Start footer font color
     */
    .site-footer .footer-heading {
        color: {{ $customization_site_footer_font_color }} !important;
    }
    .site-footer p {
        color: {{ $customization_site_footer_font_color }} !important;
    }
    .site-footer a {
        color: {{ $customization_site_footer_font_color }} !important;
    }
    .customization-footer-font-color {
        color: {{ $customization_site_footer_font_color }} !important;
    }
    .site-footer .btn-footer-dropdown {
        background-color: transparent;
        color: {{ $customization_site_footer_font_color }};
        border-color: {{ $customization_site_footer_font_color }};
    }
    /**
     * End footer font color
     */

    /**
     * Start my style
     */
    .pace {
        -webkit-pointer-events: none;
        pointer-events: none;

        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
    }

    .pace-inactive {
        display: none;
    }

    .pace .pace-progress {
        background: #30e3ca;
        position: fixed;
        z-index: 2000;
        top: 0;
        right: 100%;
        width: 100%;
        height: 2px;
    }

    address {
        margin-bottom: 0.3rem;
    }

    .review:not(:last-of-type):after {
        content: "•";
        margin: 0px 1px 0px 4px;
    }

    /**
     * Search Box Style
     */
    #search-box-query-icon-div {
        margin-right: 0px !important;
    }

    #search-box-query-icon {
        background-color: #fff !important;
        border-color: #fff !important;
        border-right-color: #e9ecef !important;
    }

    #search_query {
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
    }

    .state-city-invalid-tooltip {
        display: block;
    }
    #multiple-datasets .league-name {
        margin: 0 20px 5px 20px;
        padding: 3px 0;
        border-bottom: 1px solid #ccc;
    }
    #multiple-datasets span.icon {
        z-index: 1000 !important;
    }
    .twitter-typeahead {
        width: 100%;
    }

    .tt-hint {
        color: #999;
    }

    .tt-menu { /* UPDATE: newer versions use tt-menu instead of tt-dropdown-menu */
        text-align: left;
        min-width: 270px;
        margin-top: 12px;
        padding: 8px 0;
        background-color: #fff;
        /*border: 1px solid #ccc;*/
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 8px;
        box-shadow: 0 5px 10px rgba(0,0,0,.2);
        max-height: 230px;
        overflow-y: auto;
        z-index: 1000 !important;
    }

    .tt-suggestion {
        padding: 3px 20px;
        font-size: 1rem;
        line-height: 24px;
    }

    .tt-suggestion.tt-cursor { /* UPDATE: newer versions use .tt-suggestion.tt-cursor */
        color: #fff;
        background-color: #0097cf;
    }

    .tt-suggestion:hover {
        cursor: pointer;
        color: #fff;
        background-color: #0097cf;
    }

    .tt-suggestion p {
        margin: 0;
    }

    /**
     * error message tool tip box
     */
    .invalid-tooltip {
        display: block !important;
    }
    .invalid-tooltip-side-search-query {
        top: auto !important;
    }

    address a {
        color:#4d4d4d;
    }

    .address a {
        color: #ffffff;
    }

    #logout-form {
        display: none;
    }

    .line-height-1-0 {
        line-height: 1.0;
    }

    .line-height-1-2 {
        line-height: 1.2;
    }

    .font-size-13 {
        font-size: 13px;
    }

    .display-none {
        display: none;
    }

    .z-index-1000 {
        z-index: 1000;
    }

    .item-featured-label {
        border-top-left-radius:5px;
        font-size:13px;
        position: relative;
        top: -2%;
        background-color: {{ $customization_site_primary_color }};
    }

    .listing__item_featured_box {
        border: 1px solid {{ $customization_site_primary_color }};
        box-shadow: none;
    }

    .div-clear {
        width:100px;
        clear:both;
    }

    #menu-mobile-div {
        position: relative;
        top: 3px;
    }

    .site-section img {
        max-width: 100%;
    }

    #mapid-item {
        width: 100%;
        height: 400px;
    }

    #mapid-search {
        width: 100%;
        height: 900px;
    }

    #mapid-box {
        width: 100%;
        position: sticky !important;
    }

    .post-body img {
        max-width: 100%;
    }

    /* Comment Style */
    .media-body-comment-body {
        white-space: pre-wrap;
    }

    .item-cover-title-section {
        font-size: 2.6rem !important;
    }
    .item-cover-title-section-sm-xs {
        font-size: 1.8rem !important;
    }

    .item-cover-address-section {
        color: rgba(255, 255, 255, 0.8) !important;
    }
    .item-cover-address-section-sm-xs {
        font-size: 1.0rem !important;
    }

    .item-cover-contact-section h3 {
        color: #fff;
    }

    .item-cover-contact-section p {
        color: rgba(255, 255, 255, 0.9);
        font-size: 2.0rem;
        line-height: 1.5;
    }

    .item-blocks-cover {
        min-height: 600px !important;
    }

    .item-blocks-cover-sm {
        min-height: 580px !important;
    }

    .site-blocks-cover-sm {
        background-size: auto !important;
    }

    #share-modal {
        z-index: 2000;
    }
    #qrcodeModal {
        z-index: 2000;
    }
    #itemLeadModal {
        z-index: 2000;
    }
    #showCategoriesModal {
        z-index: 2000;
    }

    .overlap-category-sm {
        z-index: 20;
        position: relative;
        background: #fff;
        border-radius: 10px;
    }

    .listing .category {
        padding: 5px 10px !important;
        border-radius: 3px !important;
        margin-bottom: 4px !important;
        color: #4d4d4d;
    }

    .listing .category:hover {
        background:{{ $customization_site_primary_color }} !important;
        color: #ffffff;
    }

    .btn-facebook {
        background-color: #3b5998 !important;
        border-color: #3b5998 !important;
    }

    .btn-twitter {
        background-color: #1da1f2 !important;
        border-color: #1da1f2 !important;
    }

    .btn-linkedin {
        background-color: #007bb5 !important;
        border-color: #007bb5 !important;
    }

    .btn-blogger {
        background-color: #fb8f3d !important;
        border-color: #fb8f3d !important;
    }

    .btn-pinterest {
        background-color: #cb2027 !important;
        border-color: #cb2027 !important;
    }

    .btn-evernote {
        background-color: #2dbe60 !important;
        border-color: #2dbe60 !important;
    }

    .btn-reddit {
        background-color: #ff4500 !important;
        border-color: #ff4500 !important;
    }

    .btn-buffer {
        background-color: #232a30 !important;
        border-color: #232a30 !important;
    }

    .btn-wordpress {
        background-color: #0b6086 !important;
        border-color: #0b6086 !important;
    }

    .btn-weibo {
        background-color: #df2029 !important;
        border-color: #df2029 !important;
    }

    .btn-skype {
        background-color: #00aff0 !important;
        border-color: #00aff0 !important;
    }

    .btn-telegram {
        background-color: #0088cc !important;
        border-color: #0088cc !important;
    }

    .btn-viber {
        background-color: #665CAC !important;
        border-color: #665CAC !important;
    }

    .btn-whatsapp {
        background-color: #25d366 !important;
        border-color: #25d366 !important;
    }

    .btn-wechat {
        background-color: #7BB32E !important;
        border-color: #7BB32E !important;
    }

    .btn-line {
        background-color: #00B900 !important;
        border-color: #00B900 !important;
    }

    .btn-google {
        background-color: #ea4335 !important;
        border-color: #ea4335 !important;
    }

    .btn-github {
        background-color: #24292e !important;
        border-color: #24292e !important;
    }

    /**
     * iframe style for youtube background
     */

    /* optional css fade in animation */
    iframe {
        transition: opacity 500ms ease-in-out;
        transition-delay: 250ms;
    }

    /* item rating summary style */
    #review_summary {
        text-align: center;
        padding: 32px 10px;
        -webkit-border-radius: 3px 3px 3px 0;
        -moz-border-radius: 3px 3px 3px 0;
        -ms-border-radius: 3px 3px 3px 0;
        border-radius: 3px 3px 3px 0;
    }
    #review_summary strong {
        font-size: 2.625rem;
        display: block;
        line-height: 1;
    }
    #item-rating-sort-by-form {
        display: inline-block !important;
    }
    /* google map style */
    #mapid-item img {
        max-width: none !important;
    }
    #mapid-box img {
        max-width: none !important;
    }
    #mapid-search img {
        max-width: none !important;
    }
    .gm-style .gm-style-iw-c {
        min-height:310px !important;
        max-width: 260px !important;
    }
    .gm-style-iw-d {
        min-height:300px;
        max-width: 260px;
    }

    .site-footer .dropdown-menu a {
        color: #212529 !important;
    }
    .site-footer .dropdown-menu a:hover {
        color: #999 !important;
    }
    .listings-filter-box {
        box-shadow: 0 2px 20px -2px rgba(0,0,0,.1);
    }
    .bootstrap-select .dropdown-menu {
        z-index: 1021;
    }

    @media (max-width: 400px) {
    .site-footer .dropdown-menu {
        max-height:250px;
        overflow-y:auto;
        }
    }
    @media (min-width: 400px) {
        .site-footer .dropdown-menu {
            max-height:400px;
            overflow-y:auto;
        }
    }

    .item-box-hr {
        margin-top: 10px;
        margin-bottom: 10px;
    }
    .item-box-hour-span-opened {
        font-size: 0.6875rem;
        font-weight: 600;
        color: #28a745;
        border: 1px solid #28a745;
        padding: 2px 8px;
        border-radius: 3px;
        line-height: 1;
    }

    .item-box-hour-span-closed {
        font-size: 0.6875rem;
        font-weight: 600;
        color: #dc3545;
        border: 1px solid #dc3545;
        padding: 2px 8px;
        border-radius: 3px;
        line-height: 1;
    }
    .item-box-user-img-div {
        padding-right: 0px;
    }
    .item-box-user-name-div {
        padding-left: 10px;
    }
    .item-box-index-nearby-hour-span-opened {
        font-size: 0.6875rem;
        font-weight: 600;
        color: #28a745;
        border: 1px solid #28a745;
        background-color: #28a745;
        padding: 2px 8px;
        border-radius: 3px;
        line-height: 1;
    }
    .item-box-index-nearby-hour-span-closed {
        font-size: 0.6875rem;
        font-weight: 600;
        color: #dc3545;
        border: 1px solid #dc3545;
        background-color: #dc3545;
        padding: 2px 8px;
        border-radius: 3px;
        line-height: 1;
    }
    .item-box-index-nearby-hr {
        border-top: 1px solid rgba(255,255,255,.3);
    }
    .image-category {
        padding: 5px 3px;
    }
    .image-category-span {
        margin-bottom: 0px !important;
    }
    .image-category-caption {
        position: relative;
        top: 4px;
    }
    .image-category-img {
        max-height: 113px !important;
    }
    /**
     * End my style
     */
</style>

