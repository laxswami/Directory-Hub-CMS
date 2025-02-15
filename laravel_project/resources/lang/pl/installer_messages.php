<?php 
return array (
  'back' => 'Poprzedni',
  'environment' => 
  array (
    'classic' => 
    array (
      'back' => 'Użyj kreatora formularzy',
      'install' => 'Zapisz i zainstaluj',
      'save' => 'Zapisz .env',
      'templateTitle' => 'Krok 3 | Ustawienia środowiska | Classic Editor',
      'title' => 'Klasyczny edytor środowiska',
    ),
    'errors' => 'Nie można zapisać pliku .env. Utwórz go ręcznie.',
    'menu' => 
    array (
      'classic-button' => 'Klasyczny edytor tekstu',
      'desc' => 'Wybierz, jak chcesz skonfigurować plik apps <code>.env</code> .',
      'templateTitle' => 'Krok 3 | Ustawienia środowiska',
      'title' => 'Ustawienia środowiska',
      'wizard-button' => 'Konfiguracja kreatora formularzy',
    ),
    'success' => 'Twoje ustawienia pliku .env zostały zapisane.',
    'wizard' => 
    array (
      'form' => 
      array (
        'app_debug_label' => 'Debugowanie aplikacji',
        'app_debug_label_false' => 'Fałszywy',
        'app_debug_label_true' => 'Prawdziwe',
        'app_environment_label' => 'Środowisko aplikacji',
        'app_environment_label_developement' => 'Rozwój',
        'app_environment_label_local' => 'Lokalny',
        'app_environment_label_other' => 'Inny',
        'app_environment_label_production' => 'Produkcja',
        'app_environment_label_qa' => 'Qa',
        'app_environment_placeholder_other' => 'Wejdź do swojego środowiska ...',
        'app_log_level_label' => 'Poziom dziennika aplikacji',
        'app_log_level_label_alert' => 'alarm',
        'app_log_level_label_critical' => 'krytyczny',
        'app_log_level_label_debug' => 'odpluskwić',
        'app_log_level_label_emergency' => 'nagły wypadek',
        'app_log_level_label_error' => 'błąd',
        'app_log_level_label_info' => 'info',
        'app_log_level_label_notice' => 'ogłoszenie',
        'app_log_level_label_warning' => 'ostrzeżenie',
        'app_name_label' => 'Nazwa aplikacji',
        'app_name_placeholder' => 'Nazwa aplikacji',
        'app_tabs' => 
        array (
          'broadcasting_label' => 'Broadcast Driver',
          'broadcasting_placeholder' => 'Broadcast Driver',
          'broadcasting_title' => 'Nadawanie, buforowanie, sesja i kolejka',
          'cache_label' => 'Sterownik pamięci podręcznej',
          'cache_placeholder' => 'Sterownik pamięci podręcznej',
          'mail_driver_label' => 'Mail Driver',
          'mail_driver_placeholder' => 'Mail Driver',
          'mail_encryption_label' => 'Szyfrowanie poczty',
          'mail_encryption_placeholder' => 'Szyfrowanie poczty',
          'mail_host_label' => 'Host poczty',
          'mail_host_placeholder' => 'Host poczty',
          'mail_label' => 'Poczta',
          'mail_password_label' => 'Hasło poczty',
          'mail_password_placeholder' => 'Hasło poczty',
          'mail_port_label' => 'Port poczty',
          'mail_port_placeholder' => 'Port poczty',
          'mail_username_label' => 'Nazwa użytkownika poczty',
          'mail_username_placeholder' => 'Nazwa użytkownika poczty',
          'more_info' => 'Więcej informacji',
          'pusher_app_id_label' => 'Identyfikator aplikacji Pusher',
          'pusher_app_id_palceholder' => 'Identyfikator aplikacji Pusher',
          'pusher_app_key_label' => 'Klucz aplikacji Pusher',
          'pusher_app_key_palceholder' => 'Klucz aplikacji Pusher',
          'pusher_app_secret_label' => 'Sekret aplikacji Pusher',
          'pusher_app_secret_palceholder' => 'Sekret aplikacji Pusher',
          'pusher_label' => 'Popychacz',
          'queue_label' => 'Sterownik kolejki',
          'queue_placeholder' => 'Sterownik kolejki',
          'redis_host' => 'Host Redis',
          'redis_label' => 'Sterownik Redis',
          'redis_password' => 'Hasło Redis',
          'redis_port' => 'Port Redis',
          'session_label' => 'Sterownik sesji',
          'session_placeholder' => 'Sterownik sesji',
        ),
        'app_url_label' => 'Adres URL aplikacji',
        'app_url_placeholder' => 'Adres URL aplikacji',
        'buttons' => 
        array (
          'install' => 'zainstalować',
          'setup_application' => 'Konfiguracja aplikacji',
          'setup_database' => 'Baza danych konfiguracji',
        ),
        'db_connection_failed' => 'Nie można połączyć z bazą danych.',
        'db_connection_label' => 'Połączenie z bazą danych',
        'db_connection_label_mysql' => 'mysql',
        'db_connection_label_pgsql' => 'pgsql',
        'db_connection_label_sqlite' => 'sqlite',
        'db_connection_label_sqlsrv' => 'sqlsrv',
        'db_host_label' => 'Host bazy danych',
        'db_host_placeholder' => 'Host bazy danych',
        'db_name_label' => 'Nazwa bazy danych',
        'db_name_placeholder' => 'Nazwa bazy danych',
        'db_password_label' => 'Hasło bazy danych',
        'db_password_placeholder' => 'Hasło bazy danych',
        'db_port_label' => 'Port bazy danych',
        'db_port_placeholder' => 'Port bazy danych',
        'db_username_label' => 'Nazwa użytkownika bazy danych',
        'db_username_placeholder' => 'Nazwa użytkownika bazy danych',
        'name_required' => 'Wymagana jest nazwa środowiska.',
      ),
      'tabs' => 
      array (
        'application' => 'Podanie',
        'database' => 'Baza danych',
        'environment' => 'Środowisko',
      ),
      'templateTitle' => 'Krok 3 | Ustawienia środowiska | Kreator z przewodnikiem',
      'title' => 'Kreator z przewodnikiem <code>.env</code>',
    ),
  ),
  'final' => 
  array (
    'console' => 'Dane wyjściowe konsoli aplikacji:',
    'env' => 'Ostateczny plik .env:',
    'exit' => 'Kliknij tutaj, aby wyjść',
    'finished' => 'Aplikacja została pomyślnie zainstalowana.',
    'log' => 'Wpis dziennika instalacji:',
    'migration' => 'Dane wyjściowe konsoli migracji i nasion:',
    'templateTitle' => 'Instalacja zakończona',
    'title' => 'Instalacja zakończona',
  ),
  'finish' => 'zainstalować',
  'forms' => 
  array (
    'errorTitle' => 'Wystąpiły następujące błędy:',
  ),
  'install' => 'zainstalować',
  'installed' => 
  array (
    'success_log_message' => 'Instalator Laravel został pomyślnie ZAINSTALOWANY na',
  ),
  'next' => 'Następny krok',
  'permissions' => 
  array (
    'next' => 'Skonfiguruj środowisko',
    'templateTitle' => 'Krok 2 | Uprawnienia',
    'title' => 'Uprawnienia',
  ),
  'requirements' => 
  array (
    'next' => 'Sprawdź uprawnienia',
    'templateTitle' => 'Krok 1 | Wymagania serwera',
    'title' => 'Wymagania serwera',
  ),
  'title' => 'Instalator Laravel',
  'updater' => 
  array (
    'final' => 
    array (
      'exit' => 'Kliknij tutaj, aby wyjść',
      'finished' => 'Baza danych aplikacji została pomyślnie zaktualizowana.',
      'title' => 'Skończone',
    ),
    'log' => 
    array (
      'success_message' => 'Instalator Laravel został pomyślnie zaktualizowany',
    ),
    'overview' => 
    array (
      'install_updates' => 'Zainstaluj aktualizacje',
      'message' => 'Jest 1 aktualizacja. | Dostępnych jest :number aktualizacji.',
      'title' => 'Przegląd',
    ),
    'title' => 'Laravel Updater',
    'welcome' => 
    array (
      'message' => 'Witamy w kreatorze aktualizacji.',
      'title' => 'Witamy w aktualizacji',
    ),
  ),
  'welcome' => 
  array (
    'message' => 'Prosty kreator instalacji i konfiguracji.',
    'next' => 'Sprawdź wymagania',
    'templateTitle' => 'Witamy',
    'title' => 'Instalator Laravel',
  ),
);