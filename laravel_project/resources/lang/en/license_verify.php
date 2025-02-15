<?php

return array (
    'seo' => [
        'edit' => "Dashboard - License Setting - :site_name",
    ],

    'alert' => [
        'license-updated' => "License information updated successfully.",
        'domain-verify-token-updated' => "The domain verify token was updated successfully. Please continue on Step 4 to complete the domain verification.",
    ],

    'sidebar' => [
        'license' => "License",
    ],

    'license-setting' => "License Setting",
    'license-setting-desc' => "This page allows you to update and review your license information, as well as manage registered domains that associate with your license.",

    'helper-redirect-no-url' => "We cannot find the website host domain name.",
    'helper-redirect-connect-fail' => "We encountered an internal server error, and cannot fetch license information via license verification API.",
    'helper-redirect-unknown-error' => "We encountered an internal server error, and cannot connect to the license verification API service.",

    'license-information' => "License Information",
    'license-type' => "License type",
    'license-supported-until' => "Support until",
    'license-last-check' => "Last verified",
    'license-last-check-status' => "License status",
    'license-domains' => "Registered domains",

    'license-valid' => "Valid",
    'license-invalid' => "Invalid",

    'license-revoke-domain' => "Revoke",

    'register-purchase-code' => "Register Purchase Code",
    'domain-verify-token' => "Domain Verify Token",
    'domain-verify-token-button' => "Save Token",
    'domain-verify-button' => "Verify Domain",

    'license-revoke-domain-modal-title' => "Revoke registered domain",
    'license-revoke-domain-modal-desc-1' => "You are going to revoke the following domain, after revoke, the domain will no longer associate with your purchase code, which means the website running on the following domain will not fully functional.",
    'license-revoke-domain-modal-desc-2' => "You can re-register the revoked domain by going to your website (on the revoked domain), navigating to Admin > Settings > General > License, and continuing the remaining steps for domain verification.",
    'license-revoke-domain-modal-desc-3' => "Do you want to proceed with the domain revoke?",
    'license-revoke-domain-modal-button-confirm' => "Yes, continue revoke!",

    'verify-step-1' => "Step 1",
    'verify-step-1-desc' => "Please enter your purchase code and CodeCanyon username associated with the purchase code. Click the Save button, and then continue to Step 2.",
    'verify-step-2' => "Step 2",
    'verify-step-2-desc' => "Please click the Get Domain Verify Token button, to generate a random token. Copy and paste the generated token in the next step.",
    'get-domain-verify-token' => "Get Domain Verify Token",
    'verify-step-3' => "Step 3",
    'verify-step-3-desc' => "Enter the generated domain verify token from the previous step, and click the Save Token button.",
    'verify-step-4' => "Step 4",
    'verify-step-4-desc' => "Please click Verify Domain button to complete the domain verification.",

    'domain-verified' => "Verified",
    'domain-unverified' => "Unverified",

    'up-to' => "Up to",
    'up-to-domains' => "domains",

    'license-instruction-terms' => "For information about license terms:",
    'license-terms-link' => "Directory Hub License Terms",
    'license-instruction-guide' => "For purchase code and domain verification:",
    'license-terms-guide-link' => "How to Verify Purchase Code & Domain",
);
