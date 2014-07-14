<?
    /*
     * Copyright 2013 Cart Designers, LLC
     *
     * Original Author: Ransom Carroll [github.com/ransomcarroll]
     * Modified by Hook Global, LLC for Zoho Check Print Plugin
     *
     * Licensed under the Apache License, Version 2.0 (the "License");
     * you may not use this file except in compliance with the License.
     * You may obtain a copy of the License at
     *
     *   http://www.apache.org/licenses/LICENSE-2.0
     *
     * Unless required by applicable law or agreed to in writing, software
     * distributed under the License is distributed on an "AS IS" BASIS,
     * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
     * See the License for the specific language governing permissions and
     * limitations under the License.
     */

    define("METHOD_POST", 1);
    define("METHOD_PUT", 2);
    define("METHOD_GET", 3);

    class ZohoBooks {
        private $timeout = 10;
        private $debug = false;
        private $advDebug = false; // Note that enabling advanced debug will include debugging information in the response possibly breaking up your code
        private $zohoBooksApiVersion = "3";
        public $responseCode;

        private $endPointUrl;
        private $apiKey;
        private $expensesUrl;
        private $bankAccountsUrl;
        private $chartOfAccountsUrl;

        public function __construct($organization_id, $api_token){
            $this->apiKey = $api_token;
            $this->organizationId = $organization_id;

            $this->endPointUrl               = "https://books.zoho.com/api/v{$this->zohoBooksApiVersion}/";
            $this->expensesUrl               = $this->endPointUrl."expenses";
            $this->bankAccountsUrl           = $this->endPointUrl."bankaccounts";
            $this->chartOfAccountsUrl        = $this->endPointUrl."chartofaccounts";
        }

        public function createExpense($data) {
            $url = $this->expensesUrl;
            $curl = curl_init($url);

            curl_setopt_array($curl, array(
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_RETURNTRANSFER => true
            ));

            $result = curl_exec($curl);

            return json_decode($result, true);
        }

        public function getChartofAccounts($filter = "AccountType.All", $sort = "account_name") {
            $params = array(
                "filter_by" => $filter,
                "sort_column" => $sort
            );

            $call = $this->callZohoBooks($this->chartOfAccountsUrl,null,METHOD_GET,1,$params);

            return $call;
        }

        public function getBankAccounts($filter = "Status.Active", $sort = "account_name") {
            $params = array(
                "filter_by" => $filter,
                "sort_coulmn" => $sort
            );

            $call = $this->callZohoBooks($this->bankAccountsUrl,null,METHOD_GET,1,$params);

            return $call;
        }

        /*
         * This function communicates with Zoho Books REST API.
         * You don't need to call this function directly. It's only for inner class working.
         *
         * @param string $url
         * @param string $data Must be a json string
         * @param int $method See constants defined at the beginning of the class
         * @param int $page equates to page number for paginating
         * @return string JSON or null
         */
        private function callZohoBooks($url, $data = null, $method = METHOD_GET, $page = 1, $params){
            $curl = curl_init();
            if($params != ''){
                $filter = '';
                foreach($params as $key => $value){
                    $filter = $filter.'&'.$key.'='.$value;
                }
            }
            curl_setopt($curl, CURLOPT_URL, $url.'?authtoken='.$this->apiKey.'&organization_id='.$this->organizationId.'&page='.$page.$filter);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Don't print the result
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->timeout);
            curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
            curl_setopt($curl, CURLOPT_FAILONERROR, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // Don't verify SSL connection
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); //
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json")); // Send as JSON
            if($this->advDebug){
                curl_setopt($curl, CURLOPT_HEADER, true); // Display headers
                curl_setopt($curl, CURLOPT_VERBOSE, true); // Display communication with server
            }
            if($method == METHOD_POST){
                curl_setopt($curl, CURLOPT_POST, true);
            } else if($method == METHOD_PUT){
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            }
            if(!is_null($data) && ($method == METHOD_POST || $method == METHOD_PUT)){
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            }

            try {
                $return = curl_exec($curl);
                $this->responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

                if($this->debug || $this->advDebug){
                    echo "<pre>"; print_r(curl_getinfo($curl)); echo "</pre>";
                }
            } catch(Exception $ex){
                if($this->debug || $this->advDebug){
                    echo "<br>cURL error num: ".curl_errno($curl);
                    echo "<br>cURL error: ".curl_error($curl);
                }
                echo "Error on cURL";
                $return = null;
            }

            curl_close($curl);

            return $return;
        }
    }