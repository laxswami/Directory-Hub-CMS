<!-- Footer -->
<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>{{ __('backend.footer.copyright') }} &copy; {{ empty($site_global_settings->setting_site_name) ? config('app.name', 'Laravel') : $site_global_settings->setting_site_name }} <script>document.write(new Date().getFullYear());</script></span>
        </div>
    </div>
</footer>
<!-- End of Footer -->
