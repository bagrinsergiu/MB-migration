<?php
namespace Brizy;

use Brizy\Config;
use Brizy\Helper;

class MinistryBrands{

    public function getOrganizationMB (): string
    {
        
        /**
         * 
         * 
         */
        return "{ \"data\": [ { \"id\": \"497f6eca-6276-4993-bfeb-53cbbbba6f08\", \"name\": \"Erich Hook\", \"avatar_url\": \"https:\/\/cdn.ministryone.com\/assets\/users\/497f6eca-6276-4993-bfeb-53cbbbba6f08\/avatar.jpg\", \"email\": \"user@example.com\", \"phone\": \"+1 (555) 555-5555\", \"organization_owner\": false, \"universal_admin\": true, \"status\": { \"current\": \"active\", \"updated_at\": \"2019-08-25T08:04:43Z\", \"history\": [ { \"status\": \"invited\", \"updated_at\": \"2019-08-24T14:15:22Z\" }, { \"status\": \"active\", \"updated_at\": \"2019-08-25T08:04:43Z\" } ] }, \"created_at\": \"2019-08-24T14:15:22Z\", \"updated_at\": \"2019-08-25T08:04:43Z\" } ], \"links\": { \"first\": \"http:\/\/example.com?page=1\", \"last\": \"http:\/\/example.com?page=3\", \"prev\": null, \"next\": \"http:\/\/example.com?page=2\" }, \"meta\": { \"current_page\": 2, \"from\": 11, \"last_page\": 3, \"links\": [ { \"url\": \"http:\/\/example.com?page=1\", \"label\": \"Previous\", \"active\": false }, { \"url\": \"http:\/\/example.com?page=1\", \"label\": \"1\", \"active\": false }, { \"url\": \"http:\/\/example.com?page=2\", \"label\": \"2\", \"active\": true }, { \"url\": \"http:\/\/example.com?page=3\", \"label\": \"3\", \"active\": false }, { \"url\": \"http:\/\/example.com?page=3\", \"label\": \"Next\", \"active\": false } ], \"path\": \"http:\/\/example.com\", \"per_page\": 10, \"to\": 20, \"total\": 30 } }";
    }




}