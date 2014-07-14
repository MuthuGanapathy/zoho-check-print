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

    require('lib/zoho.php');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Zoho Check Print Plugin</title>

        <!-- Bootstrap core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom CSS for the 'Heroic Features' Template -->
        <link href="css/bootstrap-theme.min.css" rel="stylesheet">

        <!-- JavaScript -->
        <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </head>

    <body>
        <nav class="navbar navbar-fixed-top navbar-inverse" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.php"><img style="margin-top:-7px;" src="http://hookglobal.com/public/img/logo.png"/></a>
                </div>
            </div>
        </nav>

        <div class="container">
            <div class="jumbotron hero-spacer">
                <h2>
                    <img src="http://2j9zen46cyp13k47i01s551mmxd.wpengine.netdna-cdn.com/wp-content/uploads/2013/07/zoho-logo-200x105.png" style="width:100px;margin-bottom:7px;"/>
                    <b>Check Print</b>
                </h2>
                <p>
                    Welcome to the Zoho Books Check Print plugin! To configure the plugin, please use the config.json provided in
                    the root directory. You must specify your Zoho Books API and at least one organization to begin using the
                    plugin. This is free software provided under the
                    <a href="http://www.apache.org/licenses/LICENSE-2.0.html">
                        Apache 2.0 License
                    </a>.
                    <br/>
                    <p>
                        <a class="btn btn-default" href="https://github.com/hookglobal/zoho-check-print">GitHub</a>
                        <a class="btn btn-success" href="https://books.zoho.com">Zoho Books</a>
                    </p>
                </p>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h3>Organizations</h3>
                </div>
            </div>

            <div class="row text-center">
                <?
                    $config = json_decode(file_get_contents("config.json"), true);
                    $organizations = $config['organizations'];

                    foreach($organizations as $key => $value) {
                ?>
                    <div class="col-lg-3 col-md-6 hero-feature">
                        <div class="thumbnail">
                            <div class="caption">
                                <h3><?=$key?></h3>
                                <h5>Organization ID: <?=$value?></h5>
                                <p><button class="btn btn-primary" data-toggle="modal" data-target="#modal<?=$value?>">Print Check</button>
                                </p>
                            </div>
                        </div>
                    </div>
                <? } ?>
            </div>

            <hr>

            <footer>
                <div class="row">
                    <div class="col-lg-12">
                        <p>Created by <a href="http://hookglobal.com/">Hook Global</a></p>
                    </div>
                </div>
            </footer>
        </div>

        <!-- Print Modal -->
        <? foreach($organizations as $key => $value) { ?>
            <div class="modal fade" id="modal<?=$value?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                            </button>

                            <h4 class="modal-title" id="myModalLabel">Print Check for <?=$key?></h4>
                        </div>

                        <form action="lib/print.php" method="post">
                            <div class="modal-body">
                                <h4>Amount</h4>
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-usd"></span></span>
                                    <input type="text" class="form-control" name="amount<?=$value?>">
                                </div><br/>

                                <h4>Payee</h4>
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                                    <input type="text" class="form-control" name="payee<?=$value?>">
                                </div><br/>

                                <h4>Memo</h4>
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-comment"></span></span>
                                    <input type="text" class="form-control" name="memo<?=$value?>">
                                </div><br/>

                                <?
                                    $templates = array();
                                    foreach(glob("templates/*.json") as $template) {
                                        $tmp = json_decode(file_get_contents($template), true);
                                        array_push($templates, $tmp);
                                    }
                                ?>
                                <h4>Print Template</h4>
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-print"></span></span>
                                    <select class="form-control" name="template<?=$value?>">
                                        <? foreach($templates as $template) { ?>
                                            <option value="<?=$template['template']['name'];?>"><?=$template['template']['name'];?></option>
                                        <? } ?>
                                    </select>
                                </div><br/>

                                <input type="hidden" name="organization" value="<?=$value?>"/>

                                Log Expense to <img style="width:100px;" src="http://blogs.zoho.com/image/13000001065148/zoho-books-logo.png"/>
                                &nbsp;<input type="checkbox" name="expense<?=$value?>" id="expense<?=$value?>"/><br/>

                                <script>
                                    $("#expense<?=$value?>").change(function() {
                                        var bank = $("#bankacc<?=$value?>");
                                        var expense = $("#expenseacc<?=$value?>")

                                        if(bank.attr('disabled')) {
                                            bank.prop('disabled', false);
                                        } else {
                                            bank.prop('disabled', true);
                                        }

                                        if(expense.attr('disabled')) {
                                            expense.prop('disabled', false);
                                        } else {
                                            expense.prop('disabled', true);
                                        }
                                    });
                                </script>

                                <?
                                    $zoho = new ZohoBooks($value, $config['auth']['token']);

                                    $result = $zoho->getBankAccounts();
                                    $banks = json_decode($result, true);
                                    $accounts = $banks['bankaccounts'];
                                ?>

                                <h4>Bank/Cash Account</h4>
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-credit-card"></span></span>
                                    <select class="form-control" name="bankacc<?=$value?>" id="bankacc<?=$value?>" disabled>
                                        <? foreach($accounts as $account) { ?>
                                            <? print_r($account); ?>
                                            <option value="<?=$account['account_id'];?>"><?=$account['account_name'];?> - $<?=$account['balance'];?> (<?=$account['account_type'];?>)</option>
                                        <? } ?>
                                    </select>
                                </div><br/>

                                <?
                                    $result = $zoho->getChartofAccounts("AccountType.Expense");
                                    $chart = json_decode($result, true);
                                    $accounts = $chart['chartofaccounts'];
                                ?>

                                <h4>Expense Account</h4>
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-shopping-cart"></span></span>
                                    <select class="form-control" name="expenseacc<?=$value?>" id="expenseacc<?=$value?>" disabled>
                                        <? foreach($accounts as $account) { ?>
                                            <option value="<?=$account['account_id'];?>"><?=$account['account_name'];?></option>
                                        <? } ?>
                                    </select>
                                </div><br/>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Print Check</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <? } ?>
    </body>
</html>
