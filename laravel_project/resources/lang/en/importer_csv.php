<?php
/**
 * importer csv translation
 */
return array (
    'seo' => [
        'upload' => "Dashboard - Upload CSV File - :site_name",
        'csv-data-index' => "Dashboard - CSV Upload History - :site_name",
        'csv-data-edit' => "Dashboard - Parse CSV Data - :site_name",
        'item-index' => "Dashboard - Listing Import - :site_name",
        'item-edit' => "Dashboard - Edit Listing Import - :site_name",
    ],
    'alert' => [
        'upload-success' => "File uploaded successfully",
        'upload-empty-file' => "The uploaded file has empty content",
        'fully-parsed' => "The CSV file had fully parsed, it cannot be parsed again",
        'parsed-success' => "CSV file data successful parsed to temporary listing database, please go to Sidebar Menu > Tools > Importer > Listing to start final import",
        'csv-file-deleted' => "CSV file has been deleted from the server file storage",
        'import-item-updated' => "Import listing information updated successfully",
        'import-item-deleted' => "Import listing information deleted successfully",
        'import-process-success' => "Listing information imported to website listing successfully",
        'import-process-error' => "An error encountered while processing import, please check the error log for detail",
        'import-all-process-completed' => "Import all listings process completed",
        'import-item-cannot-edit-success-processed' => "You cannot edit import listing information which had been successful imported",
        'import-process-completed' => "Importing process completed",
        'import-process-no-listing-selected' => "Please select listings before start importing process",
        'import-process-no-categories-selected' => "Please select one or more categories before start importing process",
        'import-listing-process-in-progress' => "In progress, please wait for completion",
        'delete-import-listing-process-no-listing-selected' => "Please select listings before start deleting process",
    ],

    'sidebar' => [
        'importer' => "Importer",
        'upload-csv' => "Upload CSV",
        'upload-history' => "Upload History",
        'listings' => "Listings",
    ],

    'show-upload' => "Upload CSV File",
    'show-upload-desc' => "This page allows you to upload a CSV file, and parse it to raw listing data for import in later steps.",

    'csv-for-model' => "CSV file for",
    'csv-for-model-listing' => "Listing",

    'choose-csv-file' => "Choose File",
    'choose-csv-file-help' => "support file type: csv, txt, maximum size: 10mb",

    'upload' => "Upload",

    'csv-skip-first-row' => "Skip first row",

    'filename' => "Filename",
    'progress' => "Parsed progress",
    'uploaded-at' => "Uploaded at",
    'model-for' => "Model",

    'import-csv-data-index' => "CSV File Upload History",
    'import-csv-data-index-desc' => "This page shows all uploaded CSV files and their parsed progress.",

    'parse' => "Parse",

    'import-csv-data-edit' => "Parse CSV File Data",
    'import-csv-data-edit-desc' => "This page allows you to parse the data of a CSV file you uploaded.",

    'start-parse' => "Start Parse",

    'import-csv-data-parse-error' => "An error occurred, please reload the page to continue to parse the remaining rows.",

    'parsed-percentage' => ":parsed_count of :total_count records parsed",
    'column' => "Column",

    'column-item-title' => "listing title",
    'column-item-slug' => "listing slug",
    'column-item-address' => "listing address",
    'column-item-city' => "listing city",
    'column-item-state' => "listing state",
    'column-item-country' => "listing country",
    'column-item-lat' => "listing lat",
    'column-item-lng' => "listing lng",
    'column-item-postal-code' => "listing postal code",
    'column-item-description' => "listing description",
    'column-item-phone' => "listing phone",
    'column-item-website' => "listing website",
    'column-item-facebook' => "listing facebook",
    'column-item-twitter' => "listing twitter",
    'column-item-linkedin' => "listing linkedin",
    'column-item-youtube-id' => "listing youtube id",

    'delete-file' => "Delete File",
    'csv-file' => "CSV file",

    'import-errors' => [
        'user-not-exist' => "The user does not exist",
        'item-status-not-exist' => "Listing must in submitted, published, or suspend status",
        'item-featured-not-exist' => "Listing featured must be yes, or no",
        'country-not-exist' => "The country does not exist, please add country in Location > Country > Add Country",
        'state-not-exist' => "The state does not exist, please add state in Location > State > Add State",
        'city-not-exist' => "The city does not exist, please add city in Location > City > Add City",
        'item-title-required' => "Listing title is required",
        'item-description-required' => "Listing description is required",
        'item-postal-code-required' => "Listing postal code is required",
        'categories-required' => "Listing must assigned to one or more categories",
        'import-item-cannot-process-success-processed' => "You cannot process import listing information which had been successful imported",
    ],

    'import-listing-index' => "Import Listings",
    'import-listing-index-desc' => "This page shows all parsed listing data from the CSV file. These are raw listing data, which ready to import to website listings.",

    'import-listing-status-not-processed' => "Not Processed",
    'import-listing-status-success' => "Processed with Success",
    'import-listing-status-error' => "Processed with Error",

    'import-listing-order-newest-processed' => "Newest Processed",
    'import-listing-order-oldest-processed' => "Oldest Processed",
    'import-listing-order-newest-parsed' => "Newest Parsed",
    'import-listing-order-oldest-parsed' => "Oldest Parsed",
    'import-listing-order-title-a-z' => "Title (A-Z)",
    'import-listing-order-title-z-a' => "Title (Z-A)",
    'import-listing-order-city-a-z' => "City (A-Z)",
    'import-listing-order-city-z-a' => "City (Z-A)",
    'import-listing-order-state-a-z' => "State (A-Z)",
    'import-listing-order-state-z-a' => "State (Z-A)",
    'import-listing-order-country-a-z' => "Country (A-Z)",
    'import-listing-order-country-z-a' => "Country (Z-A)",

    'select' => "Select",
    'import-listing-title' => "Title",
    'import-listing-city' => "City",
    'import-listing-state' => "State",
    'import-listing-country' => "Country",
    'import-listing-status' => "Status",
    'import-listing-detail' => "Detail",
    'import-listing-slug' => "Slug",
    'import-listing-address' => "Address",
    'import-listing-lat' => "Latitude",
    'import-listing-lng' => "Longitude",
    'import-listing-postal-code' => "Postal code",
    'import-listing-description' => "Description",
    'import-listing-phone' => "Phone",
    'import-listing-website' => "Website",
    'import-listing-facebook' => "Facebook",
    'import-listing-twitter' => "Twitter",
    'import-listing-linkedin' => "LinkedIn",
    'import-listing-youtube-id' => "Youtube Id",
    'import-listing-do-not-parse' => "DO NOT PARSE",
    'import-listing-source' => "Source",
    'import-listing-source-csv' => "CSV File Upload",
    'import-listing-error-log' => "Error log",

    'import-listing-edit' => "Edit Listing Import",
    'import-listing-edit-desc' => "This page allows you to edit the import listing information, as well as process the individual import listing information to the website listing.",

    'import-listing-information' => "Import Listing Information",

    'choose-import-listing-preference' => "Import to Listing",
    'choose-import-listing-categories' => "Choose one or more categories",
    'choose-import-listing-owner' => "Listing Owner",
    'choose-import-listing-status' => "Listing Status",
    'choose-import-listing-featured' => "Listing Featured",

    'import-listing-button' => "Import Now",

    'choose-import-listing-preference-selected' => "Import Selected to Listing",
    'import-listing-selected-button' => "Import Selected",
    'import-listing-selected-modal-title' => "Import Selected Listings",

    'import-listing-selected-total' => "Total to Import",
    'import-listing-selected-success' => "Success",
    'import-listing-selected-error' => "Error",

    'import-listing-per-page-10' => "10 rows",
    'import-listing-per-page-25' => "25 rows",
    'import-listing-per-page-50' => "50 rows",
    'import-listing-per-page-100' => "100 rows",
    'import-listing-per-page-250' => "250 rows",
    'import-listing-per-page-500' => "500 rows",
    'import-listing-per-page-1000' => "1000 rows",

    'import-listing-select-all' => "Select all",
    'import-listing-un-select-all' => "Un-select all",

    'csv-parse-in-progress' => "CSV file parse in progress, please wait for completion",

    'error-notify-modal-close-title' => "Error",
    'error-notify-modal-close' => "Close",

    'csv-file-upload-listing-instruction' => "Instruction",
    'csv-file-upload-listing-instruction-columns' => "Columns for listing: title, slug (option), address (option), city, state, country, latitude (option), longitude (option), postal code, description, phone (option), website (option), facebook (option), twitter (option), linkedin (option), youtube id (option), feature image URL (option), gallery image URLs (option).",
    'csv-file-upload-listing-instruction-tip-1' => "Although the CSV file parse process will try its best to guess, please make sure the name of the city, state, and country matches the location data (Sidebar > Location > Country, State, City) of your website.",
    'csv-file-upload-listing-instruction-tip-2' => "If your website hosts in shared hosting, please try to upload a CSV file with less than 15,000 rows each time to avoid the maximum execution time exceeded error.",
    'csv-file-upload-listing-instruction-tip-3' => "Please group CSV files by categories for convenience. For example, restaurants in one CSV file named restaurant.csv, and hotels in another CSV file named hotel.csv.",

    'import-listing-delete-selected' => "Delete Selected",
    'import-listing-delete-progress' => "Deleting...please wait",
    'import-listing-delete-progress-deleted' => "deleted",
    'import-listing-delete-complete' => "Done",
    'import-listing-delete-error' => "An error occurred, please reload the page to continue deleting the remaining records.",

    'import-listing-import-button-progress' => "Importing...please wait",
    'import-listing-import-button-complete' => "Done",
    'import-listing-import-button-error' => "An error occurred, please reload the page to continue importing the remaining records.",

    'import-listing-markup' => "Markup",
    'import-listing-markup-help' => "Give some words to distinct with other file batches",
    'import-listing-markup-all' => "All markups",

);
