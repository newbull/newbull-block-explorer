<?php
if (!defined('IN_SCRIPT')) {die('Invalid attempt!');}
$config = array(
    "name" => "NewBull", // Coin name/title
    "symbol" => "NB", // Coin symbol
    "description" => "You could browse the NewBull block detail and transaction detail with NewBull Block Explorer.",
    "homepage" => "https://newbull.org/",
    "root_path" => "/", //start with '/', end with '/'
    "copy_name" => "newbull.org",
    "start_year" => 2016,
    "explorer_name" => "NewBull Block Explorer",
    // "explorer_path" => "explorer-0.14.2.18/", //do not start with '/',  but end with '/', if root write ""
    "theme" => "theme2",
    "url_rewrite" => true,
    "rpc_host" => "127.0.0.1", // Host/IP for the daemon
    "rpc_port" => 10102, // RPC port for the daemon
    "rpc_user" => "newbullrpc", // 'rpcuser' from the coin's .conf
    "rpc_password" => "kMewATKm0S8J7uG76ZZQ10TiaD2X5kRPmV0uQ2O8klbR", // 'rpcpassword' from the coin's .conf
    "proofof" => "pow", //pow,pos
    "total_amount" => 2100000000000,
    "genesis_block_timestamp" => 1466861400,
    "nTargetTimespan" => 1209600, //14 * 24 * 60 * 60
    "nTargetSpacing" => 180, //3 * 60
    "retarget_diff_since" => 0,
    "halving_reward_since" => 480000,
    "halving_reward_per_blocks" => 160000,
    "blocks_per_page" => 10,
    "date_format" => "Y-m-d H:i:s",
    "refresh_interval" => 180, //seconds
);
