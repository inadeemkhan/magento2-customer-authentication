<?php
namespace Hyper\Auth\Api;

interface LoginManagementInterface {
    /**
     * GET for Post api
     * @param string $storeid
     * @param string $email
     * @return string
     */
    public function loginGetMethod($storeid, $email);
    /**
     * GET for Post api
     * @param string $storeid
     * @param string $email
     * @param string $city
     * @return string 
     */
    public function loginPostMethod($storeid, $email, $city);
}