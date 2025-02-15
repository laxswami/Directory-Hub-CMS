<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.index') }}">
        <div class="sidebar-brand-icon">
            <i class="fas fa-th"></i>
        </div>
        <div class="sidebar-brand-text mx-2">
            {{ empty($site_global_settings->setting_site_name) ? config('app.name', 'Laravel') : $site_global_settings->setting_site_name }}
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('admin.index') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>{{ __('backend.sidebar.dashboard') }}</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        {{ __('backend.sidebar.main-content') }}
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse_category" aria-expanded="true" aria-controls="collapse_category">
            <i class="fas fa-th-large"></i>
            <span>{{ __('backend.sidebar.category') }}</span>
        </a>
        <div id="collapse_category" class="collapse" aria-labelledby="collapse_category" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('admin.categories.index') }}">{{ __('backend.sidebar.category') }}</a>
                <a class="collapse-item" href="{{ route('admin.custom-fields.index') }}">{{ __('backend.sidebar.custom-field') }}</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse_listing" aria-expanded="true" aria-controls="collapse_listing">
            <i class="fas fa-sign"></i>
            <span>{{ __('backend.sidebar.listing') }}</span>
        </a>
        <div id="collapse_listing" class="collapse" aria-labelledby="collapse_listing" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('admin.items.index') }}">{{ __('backend.sidebar.all-listings') }}</a>
                <a class="collapse-item" href="{{ route('admin.items.saved') }}">{{ __('backend.sidebar.saved-listings') }}</a>
                <a class="collapse-item" href="{{ route('admin.items.create') }}">{{ __('backend.sidebar.new-listing') }}</a>
                <a class="collapse-item" href="{{ route('admin.item-claims.index') }}">{{ __('item_claim.sidebar.listing-claims') }}</a>
                <a class="collapse-item" href="{{ route('admin.item-leads.index') }}">{{ __('role_permission.item-leads.item-leads') }}</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse_location" aria-expanded="true" aria-controls="collapse_location">
            <i class="fas fa-map-marked-alt"></i>
            <span>{{ __('backend.sidebar.location') }}</span>
        </a>
        <div id="collapse_location" class="collapse" aria-labelledby="collapse_location" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('admin.countries.index') }}">{{ __('backend.sidebar.country') }}</a>
                <a class="collapse-item" href="{{ route('admin.states.index') }}">{{ __('backend.sidebar.state') }}</a>
                <a class="collapse-item" href="{{ route('admin.cities.index') }}">{{ __('backend.sidebar.city') }}</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse_message" aria-expanded="true" aria-controls="collapse_message">
            <i class="fas fa-comments"></i>
            <span>{{ __('backend.sidebar.messages') }}</span>
        </a>
        <div id="collapse_message" class="collapse" aria-labelledby="collapse_message" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('admin.messages.index') }}">{{ __('backend.sidebar.all-messages') }}</a>
                <a class="collapse-item" href="{{ route('admin.messages.index', ['user_id' => \Illuminate\Support\Facades\Auth::user()->id]) }}">My messages</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse_comment" aria-expanded="true" aria-controls="collapse_comment">
            <i class="fas fa-comment-alt"></i>
            <span>{{ __('backend.sidebar.comments') }}</span>
        </a>
        <div id="collapse_comment" class="collapse" aria-labelledby="collapse_comment" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('admin.comments.index') }}">{{ __('backend.sidebar.all-comments') }}</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse_review" aria-expanded="true" aria-controls="collapse_review">
            <i class="fas fa-star"></i>
            <span>{{ __('review.backend.sidebar.reviews') }}</span>
        </a>
        <div id="collapse_review" class="collapse" aria-labelledby="collapse_review" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('admin.items.reviews.index') }}">{{ __('review.backend.sidebar.all-reviews') }}</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse_product" aria-expanded="true" aria-controls="collapse_product">
            <i class="fas fa-box-open"></i>
            <span>{{ __('product_attributes.sidebar.admin.product') }}</span>
        </a>
        <div id="collapse_product" class="collapse" aria-labelledby="collapse_product" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('admin.products.index') }}">{{ __('product_attributes.sidebar.admin.product') }}</a>
                <a class="collapse-item" href="{{ route('admin.attributes.index') }}">{{ __('product_attributes.sidebar.admin.attribute') }}</a>
{{--                <a class="collapse-item" href="{{ route('admin.product.setting.edit') }}">{{ __('products.sidebar.setting') }}</a>--}}
            </div>
        </div>
    </li>

    <!-- Nav Item - Charts -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('canvas') }}" target="_blank">
            <i class="fas fa-external-link-alt"></i>
            <span>{{ __('backend.sidebar.blog') }}</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        {{ __('backend.sidebar.interface') }}
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse_section" aria-expanded="true" aria-controls="collapse_section">
            <i class="fas fa-stream"></i>
            <span>{{ __('backend.sidebar.sections') }}</span>
        </a>
        <div id="collapse_section" class="collapse" aria-labelledby="collapse_section" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('admin.faqs.index') }}">{{ __('backend.sidebar.faq') }}</a>
                <a class="collapse-item" href="{{ route('admin.social-medias.index') }}">{{ __('backend.sidebar.social-media') }}</a>
                <a class="collapse-item" href="{{ route('admin.testimonials.index') }}">{{ __('backend.sidebar.testimonial') }}</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse_page" aria-expanded="true" aria-controls="collapse_page">
            <i class="fas fa-copy"></i>
            <span>{{ __('backend.sidebar.pages') }}</span>
        </a>
        <div id="collapse_page" class="collapse" aria-labelledby="collapse_page" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('admin.settings.page.about.edit') }}">{{ __('backend.sidebar.about') }}</a>
                <a class="collapse-item" href="{{ route('admin.settings.page.privacy-policy.edit') }}">{{ __('backend.sidebar.privacy-policy') }}</a>
                <a class="collapse-item" href="{{ route('admin.settings.page.terms-service.edit') }}">{{ __('backend.sidebar.terms-of-service') }}</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        {{ __('backend.sidebar.tool') }}
    </div>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.advertisements.index') }}">
            <i class="fas fa-ad"></i>
            <span>{{ __('backend.sidebar.ads') }}</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.social-logins.index') }}">
            <i class="fas fa-share-alt"></i>
            <span>{{ __('backend.sidebar.social-login') }}</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse_lang" aria-expanded="true" aria-controls="collapse_lang">
            <i class="fas fa-language"></i>
            <span>{{ __('backend.setting.language.language') }}</span>
        </a>
        <div id="collapse_lang" class="collapse" aria-labelledby="collapse_lang" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('languages.index') }}" target="_blank">{{ __('trans.edit-lang') }}</a>
                <a class="collapse-item" href="{{ route('admin.lang.sync.index') }}">{{ __('trans.sync-lang') }}</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.settings.recaptcha.edit') }}">
            <i class="fas fa-check"></i>
            <span>{{ __('recaptcha.recaptcha') }}</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.settings.sitemap.edit') }}">
            <i class="fas fa-sitemap"></i>
            <span>{{ __('sitemap.sitemap') }}</span>
        </a>
    </li>


    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse_importer" aria-expanded="true" aria-controls="collapse_importer">
            <i class="fas fa-file-import"></i>
            <span>{{ __('importer_csv.sidebar.importer') }}</span>
        </a>
        <div id="collapse_importer" class="collapse" aria-labelledby="collapse_importer" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('admin.importer.csv.upload.show') }}">{{ __('importer_csv.sidebar.upload-csv') }}</a>
                <a class="collapse-item" href="{{ route('admin.importer.csv.upload.data.index') }}">{{ __('importer_csv.sidebar.upload-history') }}</a>
                <a class="collapse-item" href="{{ route('admin.importer.item.data.index') }}">{{ __('importer_csv.sidebar.listings') }}</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        {{ __('backend.sidebar.settings') }}
    </div>

    <!-- Nav Item - Charts -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse_setting_general" aria-expanded="true" aria-controls="collapse_setting_general">
            <i class="fas fa-cog"></i>
            <span>{{ __('backend.sidebar.general') }}</span>
        </a>
        <div id="collapse_setting_general" class="collapse" aria-labelledby="collapse_setting_general" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('admin.settings.general.edit') }}">{{ __('backend.sidebar.general') }}</a>
                <a class="collapse-item" href="{{ route('admin.settings.cache.edit') }}">{{ __('setting_cache.cache') }}</a>
                <a class="collapse-item" href="{{ route('admin.settings.session.edit') }}">{{ __('setting_session.session') }}</a>
                <a class="collapse-item" href="{{ route('admin.settings.language.edit') }}">{{ __('setting_language.language.sidebar.language') }}</a>
                <a class="collapse-item" href="{{ route('admin.settings.item.edit') }}">{{ __('backend.sidebar.listing') }}</a>
                <a class="collapse-item" href="{{ route('admin.settings.product.edit') }}">{{ __('product_attributes.sidebar.admin.product') }}</a>
                <a class="collapse-item" href="{{ route('admin.settings.maintenance.edit') }}">{{ __('maintenance_mode.sidebar.maintenance') }}</a>
                <a class="collapse-item" href="{{ route('admin.settings.license.edit') }}">{{ __('license_verify.sidebar.license') }}</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse_setting_payments" aria-expanded="true" aria-controls="collapse_setting_payments">
            <i class="far fa-credit-card"></i>
            <span>{{ __('payment.payment') }}</span>
        </a>
        <div id="collapse_setting_payments" class="collapse" aria-labelledby="collapse_setting_payments" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('admin.settings.payment.bank-transfer.index') }}">{{ __('bank_transfer.bank-transfer') }}</a>
                <a class="collapse-item" href="{{ route('admin.settings.payment.paypal.edit') }}">{{ __('payment.paypal') }}</a>
                <a class="collapse-item" href="{{ route('admin.settings.payment.razorpay.edit') }}">{{ __('payment.razorpay') }}</a>
                <a class="collapse-item" href="{{ route('admin.settings.payment.stripe.edit') }}">{{ __('stripe.stripe') }}</a>
                <a class="collapse-item" href="{{ route('admin.settings.payment.payumoney.edit') }}">{{ __('payumoney.payumoney') }}</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse_themes" aria-expanded="true" aria-controls="collapse_themes">
            <i class="fas fa-pencil-ruler"></i>
            <span>{{ __('theme_directory_hub.sidebar.themes') }}</span>
        </a>
        <div id="collapse_themes" class="collapse" aria-labelledby="collapse_themes" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('admin.themes.index') }}">{{ __('theme_directory_hub.sidebar.manage-themes') }}</a>
                <a class="collapse-item" href="{{ route('admin.themes.create') }}">{{ __('theme_directory_hub.sidebar.install-theme') }}</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse_subscription" aria-expanded="true" aria-controls="collapse_subscription">
            <i class="fas fa-tags"></i>
            <span>{{ __('backend.sidebar.subscription') }}</span>
        </a>
        <div id="collapse_subscription" class="collapse" aria-labelledby="collapse_subscription" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('admin.plans.index') }}">{{ __('backend.sidebar.plan') }}</a>
                <a class="collapse-item" href="{{ route('admin.subscriptions.index') }}">{{ __('backend.sidebar.subscription') }}</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Tables -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.users.index') }}">
            <i class="fas fa-user-cog"></i>
            <span>{{ __('backend.sidebar.user') }}</span></a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.users.profile.edit') }}">
            <i class="fas fa-address-card"></i>
            <span>{{ __('backend.sidebar.profile') }}</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
