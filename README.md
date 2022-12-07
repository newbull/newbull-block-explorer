# NewBull Block Explorer

[NewBull Block Explorer](https://github.com/newbull/newbull-block-explorer) is a simple and lightweight block explorer written in PHP(no database required) with `easybitcoin.php` ([get it here](https://github.com/aceat64/EasyBitcoin-PHP)).

NewBull Block Explorer work with [NewBull Core](https://newbull.org) fine. It's should work with others pow block chain, such as Bitcoin.

## Requirements

- Linux or Windows server of running NewBull Core
- NewBull Core v0.14.2
- Apache 2.4.x
- PHP 5.6.x with CURL and JSON support enabled

## Installation

Installation is quite simple, and just complete the following steps:

- Upload the contents of the archive to your server.
- Modify the conf/config.php file with your own info and NewBull RPC info.
- That's it! Open your any Modern Browsers to the install URL, and the block explorer should come up.

Note:

1. If you do not know about apache url_rewrite, please turn off the url_rewrite configuration in conf/config.php file;

## Usage

Below shows the URLs available:

- / = Home page, showig recent blocks list.
- /block/HEIGHT = displays all detail and txs within a block.
- /blockhash/HASH = displays all detail and txs within a block.
- /tx/TXID = View a single tx

## Theme / Template Modifications

- Content css, js, img, pages, header and footer files are in /themes/theme2/ directory.
- CSS uses Bootstrap 5.2.3 as requested.

## Donations

NB: NbUSBit9Q8mrDYPMwv6fW17rykThe3X735

ETH: 0xdA667f1921A2e454A1cD3E9D90c75a7c0EE94193

BTC:

LTC:

## License

MIT

---

## Changelog

2022-12-02 0.14.2.18

change version from 1.8.0 to 0.14.2.18, This means that the current version is compatible with newbull/bitcoin core 0.14.2, and this is 18th update;

add theme2 with bootstrap-5.2.3;

add auto detection about root path;

add highlight about navbar active item;

add Reward and Next Reward Blocks to Overview page;

add halving_reward_since, halving_reward_per_blocks to config;

remove explorer_path from config;

remove Home from navbar;

remove logo link from navbar;

change Explorer to Explorer Overview;

fix some known issues;

1.7.0 2021-11-27

add stats page;

make the block hash display shorter;

1.6.0 2021-05-31

add chaintips page;

1.5.2 2020-10-06

add retarget_diff_since;

1.5.1 2020-09-30

format weight same as size;

1.5.0 2020-09-25

add: block weight and version hex;

1.4.0 2020-05-18

remove: no longer used code;

fix: some bugs;

1.3.0 2020-03-28

add: next difficulty estimate;

1.2.0 2019-12-27

add: memory pool list;

1.1.0 2019-12-13

add: root_path in conf/config.php file;

fix: explorer_path in conf/config.php file;

1.0.0 2019-05-09

refactor;

0.9.0 2019-02-21

fix some bugs;

0.8.0 2018-06-14

refactor;

0.7.0 2018-01-04

fix some bugs;

0.6.0 2017-11-27

add theme system;

0.5.0 2017-09-06

fix some bugs;

0.4.0 2017-09-05

add some features;

0.3.0 2017-04-30

fix some bugs;

0.2.0 2017-04-29

add some features;

0.1.0 2017-04-23

first release;
