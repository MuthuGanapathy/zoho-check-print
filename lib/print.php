<?
    /*
     * Copyright 2014 Hook Global, LLC
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

    require('helpers.php');
    require('zoho.php');

    $token = "";
    $org = "";
    $template = array();

    $config = json_decode(file_get_contents("../config.json"), true);
    $auth = $config['auth'];

    if($_POST["organization"]) {
        $org = $_POST['organization'];
    }

    if($_POST["template$org"]) {
        $template = json_decode(file_get_contents("../templates/" . $_POST["template$org"] . ".json"), true);
    }
?>

<html>
    <head>
        <style>
            body {
                font-family:<?=$template['check']['font-family']?>;
                font-size:<?=$template['check']['font-size']?>;
            }

            .check {
                width:100%;
                padding:<?=$template['check']['padding']?>;
            }

            .amount {
                left:<?=$template['fields']['amount']['left']?>;
                top:<?=$template['fields']['amount']['top']?>;
                <? if($template['fields']['amount']['font-size']) { ?>
                    font-size:<?=$template['fields']['amount']['font-size']?>;
                <? } ?>
                <? if($template['fields']['amount']['css']) { ?>
                    <?=$template['fields']['amount']['css']?>;
                <? } ?>
                position:absolute;
            }

            .written_amount {
                top:<?=$template['fields']['written-amount']['top']?>;
                left:<?=$template['fields']['written-amount']['left']?>;
                <? if($template['fields']['written-amount']['font-size']) { ?>
                    font-size:<?=$template['fields']['written-amount']['font-size']?>;
                <? } ?>
                <? if($template['fields']['written-amount']['css']) { ?>
                    <?=$template['fields']['written-amount']['css']?>;
                <? } ?>
                position:absolute;
            }

            .date {
                left:<?=$template['fields']['date']['left']?>;
                top:<?=$template['fields']['date']['top']?>;
                <? if($template['fields']['date']['font-size']) { ?>
                    font-size:<?=$template['fields']['date']['font-size']?>;
                <? } ?>
                <? if($template['fields']['date']['css']) { ?>
                    <?=$template['fields']['date']['css']?>;
                <? } ?>
                position:absolute;
            }

            .memo {
                top:<?=$template['fields']['memo']['top']?>;
                left:<?=$template['fields']['memo']['left']?>;
                <? if($template['fields']['memo']['font-size']) { ?>
                    font-size:<?=$template['fields']['memo']['font-size']?>;
                <? } ?>
                <? if($template['fields']['memo']['css']) { ?>
                    <?=$template['fields']['memo']['css']?>;
                <? } ?>
                position:absolute;
            }

            .payee {
                top:<?=$template['fields']['payee']['top']?>;
                left:<?=$template['fields']['payee']['left']?>;
                <? if($template['fields']['payee']['font-size']) { ?>
                    font-size:<?=$template['fields']['payee']['font-size']?>;
                <? } ?>
                <? if($template['fields']['payee']['css']) { ?>
                    <?=$template['fields']['payee']['css']?>;
                <? } ?>
                position:absolute;
            }
        </style>
    </head>

    <?
        if(isset($_POST["expense$org"])) {
            $zoho = new ZohoBooks($org, $auth['token']);

            $bank_account = $_POST["bankacc$org"];
            $expense_account = $_POST["expenseacc$org"];

            $data = array(
                "account_id" => $expense_account,
                "paid_through_account_id" => $bank_account,
                "date" => date("Y-m-d"),
                "amount" => floatval($_POST["amount$org"]),
                "description" => "Generated Expense from Zoho Check Print Plugin - " . $_POST["memo$org"],
                "is_billable" => false
            );

            $request = array(
                'authtoken'=>$auth['token'],
                'JSONString'=>json_encode($data),
                'organization_id'=>$org
            );

            $zoho->createExpense($request);
        }
    ?>

    <body>
        <div class="check">
            <div class="amount">
                <?=$_POST["amount$org"];?>
            </div>

            <div class="written_amount">
                <?=strtoupper(convert_number_to_words($_POST["amount$org"]));?> *****
            </div>

            <div class="date">
                <?=date($template['fields']['date']['format']);?>
            </div>

            <div class="memo">
                <?=$_POST["memo$org"];?>
            </div>

            <div class="payee">
                <?=$_POST["payee$org"];?>
            </div>
        </div>
    </body>
</html>